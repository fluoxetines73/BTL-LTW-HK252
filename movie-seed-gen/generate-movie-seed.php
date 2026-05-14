<?php
/**
 * TMDB Movie Seed Generator
 *
 * Scans TMDB for movies releasing in Vietnam during May-June 2026
 * and generates an SQL seed file matching database/schema.sql.
 *
 * Usage:
 *   export TMDB_API_KEY=your_key_here
 *   php movie-seed-gen/generate-movie-seed.php
 */

$config = require __DIR__ . '/config.php';
require __DIR__ . '/TMDBClient.php';
require __DIR__ . '/../configs/database.php';

class MovieSeedGenerator {
    private array $config;
    private TMDBClient $client;
    private array $movies = [];
    private array $movieGenres = [];
    private int $nextMovieId;
    private array $usedSlugs = [];

    /** @var array<string, array{id:int, slug:string}> */
    private array $genreMap = [
        'Action' => ['id' => 1, 'slug' => 'hanh-dong'],
        'Adventure' => ['id' => 8, 'slug' => 'phieu-luu'],
        'Animation' => ['id' => 5, 'slug' => 'hoat-hinh'],
        'Comedy' => ['id' => 3, 'slug' => 'hai'],
        'Crime' => ['id' => 7, 'slug' => 'tam-ly'],
        'Documentary' => ['id' => 9, 'slug' => 'tai-lieu'],
        'Drama' => ['id' => 7, 'slug' => 'tam-ly'],
        'Family' => ['id' => 10, 'slug' => 'gia-dinh'],
        'Fantasy' => ['id' => 6, 'slug' => 'khoa-hoc-vien-tuong'],
        'History' => ['id' => 9, 'slug' => 'tai-lieu'],
        'Horror' => ['id' => 2, 'slug' => 'kinh-di'],
        'Music' => null,
        'Mystery' => ['id' => 7, 'slug' => 'tam-ly'],
        'Romance' => ['id' => 4, 'slug' => 'lang-man'],
        'Science Fiction' => ['id' => 6, 'slug' => 'khoa-hoc-vien-tuong'],
        'TV Movie' => null,
        'Thriller' => ['id' => 7, 'slug' => 'tam-ly'],
        'War' => ['id' => 1, 'slug' => 'hanh-dong'],
        'Western' => ['id' => 1, 'slug' => 'hanh-dong'],
    ];

    public function __construct(array $config) {
        $this->config = $config;
        $this->client = new TMDBClient($config);
        $this->nextMovieId = $this->detectNextMovieId();
    }

    /**
     * Detect the next available movie ID from the database.
     * Falls back to config value or 7 if DB connection fails.
     */
    private function detectNextMovieId(): int {
        $fallbackId = (int) ($this->config['starting_movie_id'] ?? 7);

        try {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME);
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            $stmt = $pdo->query('SELECT MAX(id) as max_id FROM movies');
            $result = $stmt->fetch();
            $maxId = (int) ($result['max_id'] ?? 0);

            $nextId = $maxId + 1;
            echo "Auto-detected next movie ID: $nextId (current max: $maxId)\n\n";

            return $nextId;
        } catch (PDOException $e) {
            echo "Warning: Could not connect to database to detect next ID. Using fallback: $fallbackId\n";
            echo "Error: " . $e->getMessage() . "\n\n";
            return $fallbackId;
        }
    }

    /**
     * Reset movie IDs to start from 1 and update related genre mappings.
     */
    private function resetMovieIds(): void {
        $newId = 1;
        $idMap = [];

        // Reassign movie IDs starting from 1
        foreach ($this->movies as $index => $movie) {
            $oldId = $movie['id'];
            $idMap[$oldId] = $newId;
            $this->movies[$index]['id'] = $newId;
            $newId++;
        }

        // Update movie_genres to use new IDs
        foreach ($this->movieGenres as $index => $mg) {
            if (isset($idMap[$mg['movie_id']])) {
                $this->movieGenres[$index]['movie_id'] = $idMap[$mg['movie_id']];
            }
        }

        echo "Reset movie IDs to start from 1 (total: " . count($this->movies) . ")\n";
    }

    public function run(): void {
        if (empty($this->config['tmdb_api_key'])) {
            fwrite(STDERR, "ERROR: TMDB_API_KEY is not set.\n");
            fwrite(STDERR, "Please set it in your environment before running:\n");
            fwrite(STDERR, "  export TMDB_API_KEY=your_api_key_here\n");
            fwrite(STDERR, "  php movie-seed-gen/generate-movie-seed.php\n");
            exit(1);
        }

        echo "=== TMDB Movie Seed Generator ===\n";
        echo "Region: {$this->config['region']}\n";
        echo "Date range: {$this->config['date_from']} to {$this->config['date_to']}\n";
        echo "\n";

        $this->fetchMovies();
        $this->generateSql();

        echo "\nDone! Generated " . count($this->movies) . " movie(s).\n";
        echo "Output: {$this->config['output_file']}\n";
    }

    private function fetchMovies(): void {
        $page = 1;
        $totalPages = 1;

        do {
            echo "Fetching discover page $page / $totalPages...\n";

            try {
                $result = $this->client->discoverMovies(
                    $this->config['date_from'],
                    $this->config['date_to'],
                    $this->config['region'],
                    $page
                );
            } catch (RuntimeException $e) {
                fwrite(STDERR, "Discover error on page $page: " . $e->getMessage() . "\n");
                break;
            }

            $totalPages = min((int) ($result['total_pages'] ?? 1), 500);
            $results = $result['results'] ?? [];

            foreach ($results as $summary) {
                $movieId = (int) ($summary['id'] ?? 0);
                if ($movieId <= 0) continue;

                try {
                    $this->processMovie($movieId);
                    // Rate limiting: ~4 requests/sec to stay well under TMDB limits
                    usleep(250000);
                } catch (RuntimeException $e) {
                    fwrite(STDERR, "  Error processing movie ID $movieId: " . $e->getMessage() . "\n");
                }
            }

            $page++;
        } while ($page <= $totalPages);
    }

    private function processMovie(int $movieId): void {
        $details = $this->client->getMovieDetails($movieId);

        $title = trim($details['title'] ?? $details['original_title'] ?? 'Unknown');
        if ($title === '' || $title === 'Unknown') {
            return;
        }

        // Prefer Vietnam release date if available, else primary
        $releaseDate = $this->extractVietnamReleaseDate($details['release_dates'] ?? []);
        if ($releaseDate === null) {
            $releaseDate = $details['release_date'] ?? $this->config['date_from'];
        }

        // Only keep movies whose Vietnam release falls inside the window
        if ($releaseDate < $this->config['date_from'] || $releaseDate > $this->config['date_to']) {
            return;
        }

        $slug = $this->uniqueSlug($title);

        // Director & cast from credits
        $director = '';
        $castList = [];
        $credits = $details['credits'] ?? [];

        foreach ($credits['crew'] ?? [] as $crew) {
            if (($crew['job'] ?? '') === 'Director') {
                $director = $crew['name'] ?? '';
                break;
            }
        }

        foreach (array_slice($credits['cast'] ?? [], 0, 5) as $actor) {
            $name = trim($actor['name'] ?? '');
            if ($name !== '') $castList[] = $name;
        }

        // Certification → age_rating
        $cert = $this->getCertification($details['release_dates'] ?? []);
        $ageRating = $this->mapCertification($cert);

        // Language / subtitle
        $langInfo = $this->mapLanguage($details['original_language'] ?? 'en');

        // Country
        $country = $this->mapCountry($details['production_countries'] ?? []);

        // Status
        $status = $releaseDate <= date('Y-m-d') ? 'now_showing' : 'coming_soon';

        // Runtime (default 0 if unknown – schema is NOT NULL)
        $duration = (int) ($details['runtime'] ?? 0);
        if ($duration <= 0) {
            $duration = 90;
        }

        // Download images locally and get just the filename
        echo "  Downloading images...\n";
        $posterFilename = $this->client->downloadImage($details['poster_path'] ?? null, 'poster');
        $bannerFilename = $this->client->downloadImage($details['backdrop_path'] ?? null, 'banner');

        $movie = [
            'id' => $this->nextMovieId,
            'title' => $title,
            'slug' => $slug,
            'description' => $details['overview'] ?? null,
            'director' => $director ?: null,
            'cast' => implode(', ', $castList) ?: null,
            'duration_min' => $duration,
            'release_date' => $releaseDate,
            'end_date' => null,
            'poster' => $posterFilename,
            'banner' => $bannerFilename,
            'trailer_url' => $this->client->getTrailerUrl($details['videos'] ?? []),
            'age_rating' => $ageRating,
            'status' => $status,
            'language' => $langInfo['language'],
            'subtitle' => $langInfo['subtitle'],
            'country' => $country,
        ];

        $this->movies[] = $movie;

        // Genre mapping
        foreach ($details['genres'] ?? [] as $genre) {
            $genreName = $genre['name'] ?? '';
            $mapped = $this->genreMap[$genreName] ?? null;
            if ($mapped !== null) {
                $this->movieGenres[] = [
                    'movie_id' => $this->nextMovieId,
                    'genre_id' => $mapped['id'],
                ];
            }
        }

        echo "  [{$movie['id']}] {$title} ({$releaseDate})\n";
        $this->nextMovieId++;
    }

    /**
     * Try to extract the Vietnam theatrical release date.
     */
    private function extractVietnamReleaseDate(array $releaseDates): ?string {
        foreach ($releaseDates['results'] ?? [] as $country) {
            if (($country['iso_3166_1'] ?? '') !== 'VN') continue;
            foreach ($country['release_dates'] ?? [] as $rd) {
                $type = (int) ($rd['type'] ?? 0);
                // 2 = theatrical limited, 3 = theatrical
                if (in_array($type, [2, 3], true)) {
                    $date = $rd['release_date'] ?? null;
                    if ($date) {
                        return substr($date, 0, 10);
                    }
                }
            }
        }
        return null;
    }

    /**
     * Look for VN certification first, then US, then any.
     */
    private function getCertification(array $releaseDates): ?string {
        foreach ($releaseDates['results'] ?? [] as $country) {
            if (($country['iso_3166_1'] ?? '') === 'VN') {
                foreach ($country['release_dates'] ?? [] as $rd) {
                    $cert = trim($rd['certification'] ?? '');
                    if ($cert !== '') return $cert;
                }
            }
        }
        foreach ($releaseDates['results'] ?? [] as $country) {
            if (($country['iso_3166_1'] ?? '') === 'US') {
                foreach ($country['release_dates'] ?? [] as $rd) {
                    $cert = trim($rd['certification'] ?? '');
                    if ($cert !== '') return $cert;
                }
            }
        }
        return null;
    }

    private function mapCertification(?string $cert): string {
        $cert = strtoupper($cert ?? '');
        if ($cert === '') return 'C13';

        $p = ['G', 'PG', 'TV-Y', 'TV-G', 'TV-Y7', 'TV-Y7-FV', 'P'];
        $c13 = ['PG-13', 'TV-PG', 'TV-14', 'M', 'C13', 'K', 'T13'];
        $c16 = ['R', 'TV-MA', '15', '16', 'C16', 'T16'];
        $c18 = ['NC-17', 'X', '18', 'R18', 'C18', 'T18'];

        if (in_array($cert, $p, true)) return 'P';
        if (in_array($cert, $c13, true)) return 'C13';
        if (in_array($cert, $c16, true)) return 'C16';
        if (in_array($cert, $c18, true)) return 'C18';

        return 'C13';
    }

    private function mapLanguage(string $code): array {
        return match ($code) {
            'vi' => ['language' => 'Tieng Viet', 'subtitle' => null],
            'en' => ['language' => 'Tieng Anh', 'subtitle' => 'Phu de Viet'],
            'ja' => ['language' => 'Tieng Nhat', 'subtitle' => 'Phu de Viet'],
            'ko' => ['language' => 'Tieng Han', 'subtitle' => 'Phu de Viet'],
            'zh' => ['language' => 'Tieng Trung', 'subtitle' => 'Phu de Viet'],
            'fr' => ['language' => 'Tieng Phap', 'subtitle' => 'Phu de Viet'],
            'es' => ['language' => 'Tieng Tay Ban Nha', 'subtitle' => 'Phu de Viet'],
            'de' => ['language' => 'Tieng Duc', 'subtitle' => 'Phu de Viet'],
            'th' => ['language' => 'Tieng Thai', 'subtitle' => 'Phu de Viet'],
            'hi' => ['language' => 'Tieng Hindi', 'subtitle' => 'Phu de Viet'],
            'ru' => ['language' => 'Tieng Nga', 'subtitle' => 'Phu de Viet'],
            default => ['language' => 'Tieng Anh', 'subtitle' => 'Phu de Viet'],
        };
    }

    private function mapCountry(array $productionCountries): ?string {
        if (empty($productionCountries)) return null;
        $code = $productionCountries[0]['iso_3166_1'] ?? '';
        return match ($code) {
            'VN' => 'Viet Nam',
            'US' => 'My',
            'GB' => 'Anh',
            'JP' => 'Nhat Ban',
            'KR' => 'Han Quoc',
            'CN' => 'Trung Quoc',
            'FR' => 'Phap',
            'DE' => 'Duc',
            'TH' => 'Thai Lan',
            'IN' => 'An Do',
            'RU' => 'Nga',
            'AU' => 'Uc',
            'CA' => 'Canada',
            'IT' => 'Y',
            'ES' => 'Tay Ban Nha',
            'ID' => 'Indonesia',
            'MY' => 'Malaysia',
            'PH' => 'Philippines',
            'SG' => 'Singapore',
            default => $productionCountries[0]['name'] ?? null,
        };
    }

    private function uniqueSlug(string $title): string {
        $base = $this->vietnameseSlug($title);
        $slug = $base;
        $suffix = 1;
        while (in_array($slug, $this->usedSlugs, true)) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }
        $this->usedSlugs[] = $slug;
        return $slug;
    }

    private function vietnameseSlug(string $text): string {
        $map = [
            'à'=>'a','á'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ầ'=>'a','ấ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a','ă'=>'a','ằ'=>'a','ắ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a',
            'è'=>'e','é'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ề'=>'e','ế'=>'e','ệ'=>'e','ể'=>'e','ễ'=>'e',
            'ì'=>'i','í'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i',
            'ò'=>'o','ó'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ồ'=>'o','ố'=>'o','ộ'=>'o','ổ'=>'o','ỗ'=>'o','ơ'=>'o','ờ'=>'o','ớ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o',
            'ù'=>'u','ú'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u','ự'=>'u','ử'=>'u','ữ'=>'u',
            'ỳ'=>'y','ý'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y',
            'đ'=>'d',
            'À'=>'A','Á'=>'A','Ạ'=>'A','Ả'=>'A','Ã'=>'A','Â'=>'A','Ầ'=>'A','Ấ'=>'A','Ậ'=>'A','Ẩ'=>'A','Ẫ'=>'A','Ă'=>'A','Ằ'=>'A','Ắ'=>'A','Ặ'=>'A','Ẳ'=>'A','Ẵ'=>'A',
            'È'=>'E','É'=>'E','Ẹ'=>'E','Ẻ'=>'E','Ẽ'=>'E','Ê'=>'E','Ề'=>'E','Ế'=>'E','Ệ'=>'E','Ể'=>'E','Ễ'=>'E',
            'Ì'=>'I','Í'=>'I','Ị'=>'I','Ỉ'=>'I','Ĩ'=>'I',
            'Ò'=>'O','Ó'=>'O','Ọ'=>'O','Ỏ'=>'O','Õ'=>'O','Ô'=>'O','Ồ'=>'O','Ố'=>'O','Ộ'=>'O','Ổ'=>'O','Ỗ'=>'O','Ơ'=>'O','Ờ'=>'O','Ớ'=>'O','Ợ'=>'O','Ở'=>'O','Ỡ'=>'O',
            'Ù'=>'U','Ú'=>'U','Ụ'=>'U','Ủ'=>'U','Ũ'=>'U','Ư'=>'U','Ừ'=>'U','Ứ'=>'U','Ự'=>'U','Ử'=>'U','Ữ'=>'U',
            'Ỳ'=>'Y','Ý'=>'Y','Ỵ'=>'Y','Ỷ'=>'Y','Ỹ'=>'Y',
            'Đ'=>'D'
        ];
        $text = strtr($text, $map);
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return trim($text, '-');
    }

    private function generateSql(): void {
        $lines = [];
        $lines[] = '-- Cinema Booking - Movie Seed for May & June 2026 (Vietnam)';
        $lines[] = '-- Generated from TMDB API on ' . date('Y-m-d H:i:s');
        $lines[] = '';
        $lines[] = 'SET NAMES utf8mb4;';
        $lines[] = 'SET FOREIGN_KEY_CHECKS=0;';
        $lines[] = '';
        $lines[] = "-- Clear all existing movies and reset IDs";
        $lines[] = "DELETE FROM `movie_genres`;";
        $lines[] = "DELETE FROM `movies`;";
        $lines[] = "ALTER TABLE `movies` AUTO_INCREMENT = 1;";
        $lines[] = '';

        // Reset movie IDs to start from 1 since we're clearing everything
        $this->resetMovieIds();

        if (empty($this->movies)) {
            $lines[] = '-- No movies found for the given criteria.';
        } else {
            $lines[] = 'INSERT INTO `movies` (`id`, `title`, `slug`, `description`, `director`, `cast`, `duration_min`, `release_date`, `end_date`, `poster`, `banner`, `trailer_url`, `age_rating`, `status`, `language`, `subtitle`, `country`) VALUES';

            $values = [];
            foreach ($this->movies as $m) {
                $values[] = sprintf(
                    "(%d, %s, %s, %s, %s, %s, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                    $m['id'],
                    $this->quote($m['title']),
                    $this->quote($m['slug']),
                    $this->quote($m['description']),
                    $this->quote($m['director']),
                    $this->quote($m['cast']),
                    $m['duration_min'],
                    $this->quote($m['release_date']),
                    'NULL',
                    $this->quote($m['poster']),
                    $this->quote($m['banner']),
                    $this->quote($m['trailer_url']),
                    $this->quote($m['age_rating']),
                    $this->quote($m['status']),
                    $this->quote($m['language']),
                    $this->quote($m['subtitle']),
                    $this->quote($m['country'])
                );
            }
            $lines[] = implode(",\n", $values) . ';';
            $lines[] = '';

            if (!empty($this->movieGenres)) {
                $lines[] = 'INSERT INTO `movie_genres` (`movie_id`, `genre_id`) VALUES';
                $mgValues = [];
                foreach ($this->movieGenres as $mg) {
                    $mgValues[] = "({$mg['movie_id']}, {$mg['genre_id']})";
                }
                $lines[] = implode(",\n", $mgValues) . ';';
            }
        }

        $lines[] = '';
        $lines[] = 'SET FOREIGN_KEY_CHECKS=1;';

        $dir = dirname($this->config['output_file']);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($this->config['output_file'], implode("\n", $lines));
    }

    private function quote(?string $value): string {
        if ($value === null) return 'NULL';
        return "'" . str_replace("'", "\\'", $value) . "'";
    }
}

// ------------------------------------------------------------------
// Entry point
// ------------------------------------------------------------------
$generator = new MovieSeedGenerator($config);
$generator->run();
