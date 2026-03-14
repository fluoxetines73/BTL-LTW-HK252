<?php
require_once ROOT . '/core/Controller.php';

class ProductController extends Controller {
	private ?Product $productModel = null;

	public function __construct() {
		require_once APPROOT . '/Models/Model.php';
		require_once APPROOT . '/Models/Product.php';

		try {
			$this->productModel = new Product();
		} catch (Throwable $e) {
			$this->productModel = null;
		}
	}

	public function index(): void {
		$products = $this->productModel ? $this->productModel->findAll() : $this->seedProducts();

		$this->view('layouts/main', [
			'title' => 'San pham',
			'content' => 'product/index',
			'products' => $products,
		]);
	}

	public function detail(int $id = 0): void {
		$product = $this->productModel ? $this->productModel->findById($id) : $this->findSeedById($id);

		if (!$product) {
			http_response_code(404);
			$this->view('layouts/main', [
				'title' => 'Khong tim thay san pham',
				'content' => 'home/not_found',
			]);
			return;
		}

		$this->view('layouts/main', [
			'title' => 'Chi tiet san pham',
			'content' => 'product/detail',
			'product' => $product,
		]);
	}

	public function admin(): void {
		$this->view('layouts/main', [
			'title' => 'Quan ly san pham',
			'content' => 'product/index',
			'products' => $this->seedProducts(),
		]);
	}

	private function seedProducts(): array {
		return [
			['id' => 1, 'name' => 'Goi Website Co Ban', 'price' => 4900000, 'description' => 'Landing page + responsive.'],
			['id' => 2, 'name' => 'Goi Website Ban Hang', 'price' => 9900000, 'description' => 'Co gio hang va quan ly don hang.'],
			['id' => 3, 'name' => 'Goi Website Doanh Nghiep', 'price' => 15900000, 'description' => 'Tin tuc, SEO co ban, dashboard.'],
		];
	}

	private function findSeedById(int $id): array|false {
		foreach ($this->seedProducts() as $item) {
			if ((int)$item['id'] === $id) {
				return $item;
			}
		}
		return false;
	}
}
