<?php
require_once ROOT . '/core/Controller.php';

class MoviesController extends Controller {

	public function index(): void {
		$movies = $this->seedMovies();

		$this->view('layouts/main', [
			'title' => 'Danh sach phim',
			'content' => 'movies/index',
			'movies' => $movies,
		]);
	}

	public function current(): void {
		$movies = $this->seedCurrentMovies();

		$this->view('layouts/main', [
			'title' => 'Phim dang chieu',
			'content' => 'movies/current',
			'movies' => $movies,
		]);
	}

	public function coming(): void {
		$movies = $this->seedComingMovies();

		$this->view('layouts/main', [
			'title' => 'Phim sap chieu',
			'content' => 'movies/coming',
			'movies' => $movies,
		]);
	}

	private function seedMovies(): array {
		return [
			[
				'id' => 1,
				'title' => 'Deadpool & Wolverine',
				'poster' => 'https://via.placeholder.com/300x450?text=Deadpool+Wolverine',
				'release_date' => '2024-07-26',
				'director' => 'Shawn Levy',
				'genre' => 'Action/Comedy',
				'duration' => 128,
				'rating' => 'T16',
			],
			[
				'id' => 2,
				'title' => 'Inside Out 2',
				'poster' => 'https://via.placeholder.com/300x450?text=Inside+Out+2',
				'release_date' => '2024-06-14',
				'director' => 'Kelsey Mann',
				'genre' => 'Animation/Comedy',
				'duration' => 96,
				'rating' => 'P',
			],
			[
				'id' => 3,
				'title' => 'Dune: Part Two',
				'poster' => 'https://via.placeholder.com/300x450?text=Dune+2',
				'release_date' => '2024-02-29',
				'director' => 'Denis Villeneuve',
				'genre' => 'Sci-Fi/Drama',
				'duration' => 166,
				'rating' => 'T13',
			],
			[
				'id' => 4,
				'title' => 'Godzilla x Kong',
				'poster' => 'https://via.placeholder.com/300x450?text=Godzilla+Kong',
				'release_date' => '2024-03-29',
				'director' => 'Adam Wingard',
				'genre' => 'Action/Adventure',
				'duration' => 145,
				'rating' => 'T13',
			],
			[
				'id' => 5,
				'title' => 'The Brutalist',
				'poster' => 'https://via.placeholder.com/300x450?text=The+Brutalist',
				'release_date' => '2023-12-06',
				'director' => 'Brady Corbet',
				'genre' => 'Drama',
				'duration' => 215,
				'rating' => 'T16',
			],
			[
				'id' => 6,
				'title' => 'Barbie',
				'poster' => 'https://via.placeholder.com/300x450?text=Barbie',
				'release_date' => '2023-07-21',
				'director' => 'Greta Gerwig',
				'genre' => 'Comedy/Fantasy',
				'duration' => 114,
				'rating' => 'P',
			],
		];
	}

	private function seedCurrentMovies(): array {
		return array_slice($this->seedMovies(), 0, 4);
	}

	private function seedComingMovies(): array {
		return array_slice($this->seedMovies(), 4, 2);
	}
}
