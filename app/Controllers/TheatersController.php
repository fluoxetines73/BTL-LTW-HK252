<?php
require_once ROOT . '/core/Controller.php';

class TheatersController extends Controller {

	public function index(): void {
		$theaters = $this->seedTheaters();

		$this->view('layouts/main', [
			'title' => 'Danh sach rap CGV',
			'content' => 'theaters/index',
			'theaters' => $theaters,
		]);
	}

	public function all(): void {
		$theaters = $this->seedAllTheaters();

		$this->view('layouts/main', [
			'title' => 'Tat ca rap CGV',
			'content' => 'theaters/all',
			'theaters' => $theaters,
		]);
	}

	public function special(): void {
		$theaters = $this->seedSpecialTheaters();

		$this->view('layouts/main', [
			'title' => 'Rap dac biet',
			'content' => 'theaters/special',
			'theaters' => $theaters,
		]);
	}

	public function threeD(): void {
		$theaters = $this->seedThreeDTheaters();

		$this->view('layouts/main', [
			'title' => 'Rap 3D',
			'content' => 'theaters/threeD',
			'theaters' => $theaters,
		]);
	}

	private function seedTheaters(): array {
		return [
			[
				'id' => 1,
				'name' => 'CGV Crescent Mall',
				'location' => 'Q7, Ho Chi Minh City',
				'screens' => 8,
				'amenities' => ['2D', '3D', 'IMAX'],
				'image' => 'https://via.placeholder.com/400x300?text=CGV+Crescent+Mall',
			],
			[
				'id' => 2,
				'name' => 'CGV Landmark 81',
				'location' => 'Binh Thanh District',
				'screens' => 10,
				'amenities' => ['2D', '3D', '4DX'],
				'image' => 'https://via.placeholder.com/400x300?text=CGV+Landmark',
			],
			[
				'id' => 3,
				'name' => 'CGV Aeon Mall Binh Duong',
				'location' => 'Binh Duong Province',
				'screens' => 7,
				'amenities' => ['2D', '3D'],
				'image' => 'https://via.placeholder.com/400x300?text=CGV+Aeon+Binh+Duong',
			],
			[
				'id' => 4,
				'name' => 'CGV Vincom Da Nang',
				'location' => 'Da Nang City',
				'screens' => 6,
				'amenities' => ['2D', '3D', 'VIP'],
				'image' => 'https://via.placeholder.com/400x300?text=CGV+Vincom+Da+Nang',
			],
		];
	}

	private function seedAllTheaters(): array {
		return $this->seedTheaters();
	}

	private function seedSpecialTheaters(): array {
		return [
			[
				'id' => 2,
				'name' => 'CGV Landmark 81',
				'location' => 'Binh Thanh District',
				'screens' => 10,
				'amenities' => ['2D', '3D', '4DX'],
				'image' => 'https://via.placeholder.com/400x300?text=CGV+Landmark',
				'special_feature' => '4DX Technology - Motion Seats & Environmental Effects',
			],
			[
				'id' => 1,
				'name' => 'CGV Crescent Mall',
				'location' => 'Q7, Ho Chi Minh City',
				'screens' => 8,
				'amenities' => ['2D', '3D', 'IMAX'],
				'image' => 'https://via.placeholder.com/400x300?text=CGV+Crescent+Mall',
				'special_feature' => 'IMAX Screen - Premium Large Format',
			],
		];
	}

	private function seedThreeDTheaters(): array {
		return [
			[
				'id' => 1,
				'name' => 'CGV Crescent Mall',
				'location' => 'Q7, Ho Chi Minh City',
				'screens' => 3,
				'amenities' => ['3D', 'IMAX'],
				'image' => 'https://via.placeholder.com/400x300?text=CGV+Crescent+Mall',
				'screen_count_3d' => 3,
			],
			[
				'id' => 2,
				'name' => 'CGV Landmark 81',
				'location' => 'Binh Thanh District',
				'screens' => 4,
				'amenities' => ['3D', '4DX'],
				'image' => 'https://via.placeholder.com/400x300?text=CGV+Landmark',
				'screen_count_3d' => 4,
			],
			[
				'id' => 3,
				'name' => 'CGV Aeon Mall Binh Duong',
				'location' => 'Binh Duong Province',
				'screens' => 2,
				'amenities' => ['3D'],
				'image' => 'https://via.placeholder.com/400x300?text=CGV+Aeon+Binh+Duong',
				'screen_count_3d' => 2,
			],
			[
				'id' => 4,
				'name' => 'CGV Vincom Da Nang',
				'location' => 'Da Nang City',
				'screens' => 2,
				'amenities' => ['3D'],
				'image' => 'https://via.placeholder.com/400x300?text=CGV+Vincom+Da+Nang',
				'screen_count_3d' => 2,
			],
		];
	}
}
