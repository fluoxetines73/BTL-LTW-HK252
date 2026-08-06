<?php
require_once ROOT . '/core/Controller.php';

class AdminFaqController extends Controller {
    private const DEFAULT_CATEGORIES = [
        'Vé & Đặt chỗ',
        'Thành viên & Rewards',
        'Thông tin Rạp',
        'Chính sách & Quy định',
        'Bắp & Đồ ăn',
        'Công nghệ & Định dạng',
        'Sự kiện & Chương trình đặc biệt',
        'Chung'
    ];

    public function __construct() {
        $this->middlewareAdmin();
    }

    private function getAllCategories(array $dbCategories): array {
        return array_unique(array_merge(self::DEFAULT_CATEGORIES, $dbCategories));
    }

    private function parseIds($raw): array {
        if (is_array($raw)) {
            $ids = array_map('intval', array_map('trim', $raw));
        } elseif (is_string($raw) && $raw !== '') {
            $ids = array_map('intval', array_map('trim', explode(',', $raw)));
        } else {
            $ids = [];
        }
        return array_values(array_filter($ids, static fn($id) => $id > 0));
    }

    public function index($sortBy = null, $sortOrder = 'asc') {
        $faqModel = $this->model('Faq');

        $sortBy = $sortBy ?? ($_GET['sort'] ?? 'id');
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'asc';

        $keyword = trim((string)($_GET['q'] ?? ''));
        $categoryFilter = trim((string)($_GET['category'] ?? ''));
        $statusFilter = trim((string)($_GET['status'] ?? ''));

        if (!in_array($statusFilter, ['active', 'inactive'], true)) {
            $statusFilter = '';
        }

        $faqs = $faqModel->searchFaqs(
            $keyword ?: null,
            $categoryFilter ?: null,
            $statusFilter ?: null,
            $sortBy,
            $sortOrder
        );
        $categories = $faqModel->findAllCategories();

        $this->adminView('admin/faq/index', 'faq', [
            'title' => 'Quản lý FAQ',
            'faqs' => $faqs,
            'categories' => $categories,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'keyword' => $keyword,
            'categoryFilter' => $categoryFilter,
            'statusFilter' => $statusFilter
        ]);
    }

    public function create() {
        $faqModel = $this->model('Faq');
        $categories = $faqModel->findAllCategories();

        $this->adminView('admin/faq/create', 'faq', [
            'title' => 'Thêm FAQ Mới',
            'categories' => $this->getAllCategories($categories)
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $question = trim($_POST['question'] ?? '');
            $answer = trim($_POST['answer'] ?? '');
            $category = trim($_POST['category'] ?? 'Chung');
            $sortOrder = (int)($_POST['sort_order'] ?? 0);
            $status = $_POST['status'] ?? 'active';

            if ($question === '' || $answer === '') {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ câu hỏi và câu trả lời.';
                $this->redirect('admin/faq/create');
                return;
            }

            $faqModel = $this->model('Faq');
            if ($faqModel->createFaq([
                'question' => $question,
                'answer' => $answer,
                'category' => $category,
                'sort_order' => $sortOrder,
                'status' => $status
            ])) {
                $_SESSION['success'] = 'Tạo câu hỏi thành công.';
                $this->redirect('admin/faq/index');
            } else {
                $_SESSION['error'] = 'Lỗi khi tạo câu hỏi.';
                $this->redirect('admin/faq/create');
            }
        }
    }

    public function edit($id = null) {
        if (!$id) {
            $this->redirect('admin/faq/index');
            return;
        }

        $faqModel = $this->model('Faq');
        $faq = $faqModel->getFaqById($id);

        if (!$faq) {
            $_SESSION['error'] = 'Không tìm thấy câu hỏi.';
            $this->redirect('admin/faq/index');
            return;
        }

        $categories = $faqModel->findAllCategories();

        $this->adminView('admin/faq/edit', 'faq', [
            'title' => 'Sửa FAQ',
            'faq' => $faq,
            'categories' => $this->getAllCategories($categories)
        ]);
    }

    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $question = trim($_POST['question'] ?? '');
            $answer = trim($_POST['answer'] ?? '');
            $category = trim($_POST['category'] ?? 'Chung');
            $sortOrder = (int)($_POST['sort_order'] ?? 0);
            $status = $_POST['status'] ?? 'active';

            if ($question === '' || $answer === '') {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ câu hỏi và câu trả lời.';
                $this->redirect('admin/faq/edit/' . $id);
                return;
            }

            $faqModel = $this->model('Faq');
            if ($faqModel->updateFaq($id, [
                'question' => $question,
                'answer' => $answer,
                'category' => $category,
                'sort_order' => $sortOrder,
                'status' => $status
            ])) {
                $_SESSION['success'] = 'Cập nhật câu hỏi thành công.';
                $this->redirect('admin/faq/index');
            } else {
                $_SESSION['error'] = 'Lỗi khi cập nhật câu hỏi.';
                $this->redirect('admin/faq/edit/' . $id);
            }
        }
    }

    public function delete($id = null) {
        if ($id) {
            $faqModel = $this->model('Faq');
            if ($faqModel->deleteFaq($id)) {
                $_SESSION['success'] = 'Xóa câu hỏi thành công.';
            } else {
                $_SESSION['error'] = 'Lỗi khi xóa câu hỏi.';
            }
        }
        $this->redirect('admin/faq/index');
    }

    public function bulkDelete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/faq/index');
            return;
        }

        $ids = $this->parseIds($_POST['selected_ids'] ?? '');

        if (empty($ids)) {
            $_SESSION['error'] = 'Vui lòng chọn ít nhất một câu hỏi để xóa.';
            $this->redirect('admin/faq/index');
            return;
        }

        $faqModel = $this->model('Faq');
        if ($faqModel->deleteMultiple($ids)) {
            $_SESSION['success'] = 'Đã xóa ' . count($ids) . ' câu hỏi.';
        } else {
            $_SESSION['error'] = 'Lỗi khi xóa các câu hỏi đã chọn.';
        }
        $this->redirect('admin/faq/index');
    }

    public function bulkUpdateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/faq/index');
            return;
        }

        $ids = $this->parseIds($_POST['selected_ids'] ?? '');
        $status = $_POST['status'] ?? '';

        if (!in_array($status, ['active', 'inactive'], true)) {
            $_SESSION['error'] = 'Trạng thái không hợp lệ.';
            $this->redirect('admin/faq/index');
            return;
        }

        if (empty($ids)) {
            $_SESSION['error'] = 'Vui lòng chọn ít nhất một câu hỏi để cập nhật.';
            $this->redirect('admin/faq/index');
            return;
        }

        $faqModel = $this->model('Faq');
        if ($faqModel->updateStatusMultiple($ids, $status)) {
            $statusLabel = $status === 'active' ? 'Kích hoạt' : 'Vô hiệu hóa';
            $_SESSION['success'] = 'Đã ' . $statusLabel . ' ' . count($ids) . ' câu hỏi.';
        } else {
            $_SESSION['error'] = 'Lỗi khi cập nhật trạng thái.';
        }
        $this->redirect('admin/faq/index');
    }
}
