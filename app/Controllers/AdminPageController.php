<?php
require_once ROOT . '/core/Controller.php';

class AdminPageController extends Controller {
    public function __construct() {
        $this->middlewareAdmin();
    }

    public function index() {
        $pageModel = $this->model('Page');
        $pages = $pageModel->getAllPages();

        $this->view('layouts/admin', [
            'title' => 'Quản lý Trang',
            'content' => 'admin/page/index',
            'activeSection' => 'page',
            'pages' => $pages
        ]);
    }

    public function create() {
        $this->view('layouts/admin', [
            'title' => 'Thêm Trang Mới',
            'content' => 'admin/page/create',
            'activeSection' => 'page',
            'extraScripts' => $this->slugScript()
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $status = $_POST['status'] ?? 'draft';

            // Validation
            if ($title === '' || $slug === '' || $content === '') {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc.';
                $this->redirect('admin/page/create');
                return;
            }

            $data = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'status' => $status
            ];

            $pageModel = $this->model('Page');
            if ($pageModel->createPage($data)) {
                $_SESSION['success'] = 'Tạo trang thành công.';
                $this->redirect('admin/page/index');
            } else {
                $_SESSION['error'] = 'Lỗi khi tạo trang. Có thể slug đã tồn tại.';
                $this->redirect('admin/page/create');
            }
        }
    }

    public function edit($id = null) {
        if (!$id) {
            $this->redirect('admin/page/index');
            return;
        }

        $pageModel = $this->model('Page');
        $page = $pageModel->getPageById($id);

        if (!$page) {
            $_SESSION['error'] = 'Không tìm thấy trang.';
            $this->redirect('admin/page/index');
            return;
        }

        $this->view('layouts/admin', [
            'title' => 'Sửa Trang',
            'content' => 'admin/page/edit',
            'activeSection' => 'page',
            'page' => $page,
            'extraScripts' => $this->slugScript()
        ]);
    }

    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $status = $_POST['status'] ?? 'draft';

            // Validation
            if ($title === '' || $slug === '' || $content === '') {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc.';
                $this->redirect('admin/page/edit/' . $id);
                return;
            }

            $data = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'status' => $status
            ];

            $pageModel = $this->model('Page');
            if ($pageModel->updatePage($id, $data)) {
                $_SESSION['success'] = 'Cập nhật trang thành công.';
                $this->redirect('admin/page/index');
            } else {
                $_SESSION['error'] = 'Lỗi khi cập nhật trang.';
                $this->redirect('admin/page/edit/' . $id);
            }
        }
    }

    public function delete($id = null) {
        if ($id) {
            $pageModel = $this->model('Page');
            if ($pageModel->deletePage($id)) {
                $_SESSION['success'] = 'Xóa trang thành công.';
            } else {
                $_SESSION['error'] = 'Lỗi khi xóa trang.';
            }
        }
        $this->redirect('admin/page/index');
    }

    private function slugScript(): string {
        return <<<'SCRIPT'
<script>
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = title.toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
    document.getElementById('slug').value = slug;
});
</script>
SCRIPT;
    }
}
