<?php
/**
 * TMDB API v3 Client (cURL-free — works with stock PHP)
 *
 * Uses file_get_contents with a stream context so no curl extension is required.
 */
class TMDBClient {
    private string $apiKey;
    private string $baseUrl;
    private string $imageBaseUrl;
    private string $youtubeBaseUrl;

    public function __construct(array $config) {
        $this->apiKey = $config['tmdb_api_key'];
        $this->baseUrl = rtrim($config['tmdb_base_url'], '/');
        $this->imageBaseUrl = rtrim($config['image_base_url'], '/');
        $this->youtubeBaseUrl = rtrim($config['youtube_base_url'], '/');
    }

    /**
     * Discover movies by release date range and region.
     */
    public function discoverMovies(string $from, string $to, string $region, int $page = 1): array {
        $url = sprintf(
            '%s/discover/movie?%s',
            $this->baseUrl,
            http_build_query([
                'api_key' => $this->apiKey,
                'include_adult' => 'false',
                'include_video' => 'false',
                'language' => 'vi-VN',
                'region' => $region,
                'release_date.gte' => $from,
                'release_date.lte' => $to,
                'with_release_type' => '2|3',
                'with_runtime.gte' => '60',
                'sort_by' => 'release_date.asc',
                'page' => $page,
            ])
        );
        return $this->fetch($url);
    }

    /**
     * Get full movie details with credits, videos and release dates.
     */
    public function getMovieDetails(int $movieId): array {
        $url = sprintf(
            '%s/movie/%d?%s',
            $this->baseUrl,
            $movieId,
            http_build_query([
                'api_key' => $this->apiKey,
                'language' => 'vi-VN',
                'append_to_response' => 'credits,videos,release_dates',
            ])
        );
        return $this->fetch($url);
    }

    /**
     * Fetch URL via file_get_contents and decode JSON.
     */
    private function fetch(string $url): array {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'Accept: application/json',
                ],
                'timeout' => 30,
                'follow_location' => 1,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            throw new RuntimeException("Failed to fetch URL: $url");
        }

        // Parse HTTP status from response headers
        $status = 200;
        $headers = function_exists('http_get_last_response_headers')
            ? http_get_last_response_headers()
            : ($http_response_header ?? []);
        if (!empty($headers)) {
            preg_match('#HTTP/\d+(?:\.\d+)?\s+(\d+)#', $headers[0], $m);
            $status = (int) ($m[1] ?? 200);
        }

        if ($status === 429) {
            throw new RuntimeException("TMDB rate limit exceeded (HTTP 429). Please wait a moment and retry.");
        }

        if ($status !== 200) {
            throw new RuntimeException("TMDB API request failed: HTTP $status, URL: $url, Response: $response");
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("JSON decode error: " . json_last_error_msg());
        }

        return $data;
    }

    /**
     * Download image from TMDB and save locally.
     * Returns just the filename for database storage.
     */
    public function downloadImage(?string $path, string $type = 'poster', string $saveDir = __DIR__ . '/../public/uploads/movies'): ?string {
        if (empty($path)) {
            return null;
        }

        // Ensure directory exists
        if (!is_dir($saveDir)) {
            mkdir($saveDir, 0755, true);
        }

        // Choose size based on type
        $size = $type === 'banner' ? 'w1280' : 'w500';
        $url = "https://image.tmdb.org/t/p/{$size}{$path}";

        // Generate unique filename: tmdb_{type}_{hash}.jpg
        $ext = pathinfo($path, PATHINFO_EXTENSION) ?: 'jpg';
        $filename = sprintf('tmdb_%s_%s.%s', $type, md5($path), $ext);
        $filepath = $saveDir . '/' . $filename;

        // Skip if already exists
        if (file_exists($filepath)) {
            return $filename;
        }

        // Download image
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 30,
                'header' => [
                    'Accept: image/webp,image/apng,image/*,*/*;q=0.8',
                ],
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        $imageData = @file_get_contents($url, false, $context);

        if ($imageData === false) {
            echo "  Warning: Failed to download image: $url\n";
            return null;
        }

        // Save file (skip MIME validation to avoid dependency on fileinfo extension)
        if (file_put_contents($filepath, $imageData) === false) {
            echo "  Warning: Failed to save image to: $filepath\n";
            return null;
        }

        return $filename;
    }

    public function getTrailerUrl(array $videos): ?string {
        foreach ($videos['results'] ?? [] as $video) {
            if (($video['type'] ?? '') === 'Trailer' && ($video['site'] ?? '') === 'YouTube') {
                return $this->youtubeBaseUrl . $video['key'];
            }
        }
        return null;
    }
}
