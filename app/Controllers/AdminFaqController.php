<?php
require_once ROOT . '/core/Controller.php';

class AdminFaqController extends Controller {
    public function __construct() {
        $this->middlewareAdmin();
    }

    public function index($sortBy = null, $sortOrder = 'asc') {
        $faqModel = $this->model('Faq');
        
        // Get sort parameters from URL if not provided as method arguments
        $sortBy = $sortBy ?? ($_GET['sort'] ?? 'id');
        $sortOrder = $sortOrder ?? ($_GET['order'] ?? 'asc');
        
        // Validate sort order
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'asc';
        
        $faqs = $faqModel->getAllFaqs($sortBy, $sortOrder);
        $categories = $faqModel->findAllCategories();
        
        $this->adminView('admin/faq/index', 'faq', [
            'title' => 'Quản lý FAQ',
            'faqs' => $faqs, 
            'categories' => $categories,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder
        ]);
    }

    public function create() {
        $faqModel = $this->model('Faq');
        $categories = $faqModel->findAllCategories();
        
        // Predefined categories matching current hardcoded data
        $defaultCategories = [
            'Vé & Đặt chỗ',
            'Thành viên & Rewards',
            'Thông tin Rạp',
            'Chính sách & Quy định',
            'Bắp & Đồ ăn',
            'Công nghệ & Định dạng',
            'Sự kiện & Chương trình đặc biệt',
            'Chung'
        ];
        
        // Merge existing categories with defaults
        $allCategories = array_unique(array_merge($defaultCategories, $categories));
        
        $this->adminView('admin/faq/create', 'faq', [
            'title' => 'Thêm FAQ Mới',
            'categories' => $allCategories
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $question = trim($_POST['question'] ?? '');
            $answer = trim($_POST['answer'] ?? '');
            $category = trim($_POST['category'] ?? 'Chung');
            $sortOrder = (int)($_POST['sort_order'] ?? 0);
            $status = $_POST['status'] ?? 'active';

            // Validation
            if ($question === '' || $answer === '') {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ câu hỏi và câu trả lờii.';
                $this->redirect('admin/faq/create');
                return;
            }

            $data = [
                'question' => $question,
                'answer' => $answer,
                'category' => $category,
                'sort_order' => $sortOrder,
                'status' => $status
            ];

            $faqModel = $this->model('Faq');
            if ($faqModel->createFaq($data)) {
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
        
        // Predefined categories
        $defaultCategories = [
            'Vé & Đặt chỗ',
            'Thành viên & Rewards',
            'Thông tin Rạp',
            'Chính sách & Quy định',
            'Bắp & Đồ ăn',
            'Công nghệ & Định dạng',
            'Sự kiện & Chương trình đặc biệt',
            'Chung'
        ];
        
        $allCategories = array_unique(array_merge($defaultCategories, $categories));

        $this->adminView('admin/faq/edit', 'faq', [
            'title' => 'Sửa FAQ',
            'faq' => $faq,
            'categories' => $allCategories
        ]);
    }

    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $question = trim($_POST['question'] ?? '');
            $answer = trim($_POST['answer'] ?? '');
            $category = trim($_POST['category'] ?? 'Chung');
            $sortOrder = (int)($_POST['sort_order'] ?? 0);
            $status = $_POST['status'] ?? 'active';

            // Validation
            if ($question === '' || $answer === '') {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ câu hỏi và câu trả lờii.';
                $this->redirect('admin/faq/edit/' . $id);
                return;
            }

            $data = [
                'question' => $question,
                'answer' => $answer,
                'category' => $category,
                'sort_order' => $sortOrder,
                'status' => $status
            ];

            $faqModel = $this->model('Faq');
            if ($faqModel->updateFaq($id, $data)) {
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
}
