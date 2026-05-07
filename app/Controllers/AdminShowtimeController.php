<?php
require_once ROOT . '/core/Controller.php';

class AdminShowtimeController extends Controller {
    public function __construct() {
        $this->middlewareAdmin();
    }

    /**
     * Trang danh sách Suất chiếu (Có Search, Filter và Bulk Delete)
     */
    public function index() {
        $showtimeModel = $this->model('Showtime');
        $keyword = trim($_GET['q'] ?? '');
        $dateFilter = trim($_GET['date'] ?? '');
        $sort = $_GET['sort'] ?? 'newest';

        // Logic Phân trang
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $totalRows = $showtimeModel->countShowtimes($keyword, $dateFilter);
        $totalPages = ceil($totalRows / $limit);

        $showtimes = $showtimeModel->searchShowtimes($keyword, $dateFilter, $sort, $limit, $offset);

        $this->adminView('admin/showtimes/index', 'showtime', [
            'showtimes' => $showtimes,
            'keyword' => $keyword,
            'dateFilter' => $dateFilter,
            'sort' => $sort,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function create() {
        $movies = $this->model('Movie')->getAllMovies();
        $rooms = $this->model('Room')->getAllRooms();

        $this->adminView('admin/showtimes/create', 'showtime', [
            'title' => 'Thêm Suất Chiếu Mới',
            'movies' => $movies,
            'rooms' => $rooms
        ]);
    }

    // public function store() {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $startTime = $_POST['start_time'];
    //         // Tạm tính tự động cộng 2 tiếng cho thời gian kết thúc
    //         $endTime = date('Y-m-d H:i:s', strtotime($startTime . ' + 2 hours')); 

    //         $data = [
    //             ':movie_id' => $_POST['movie_id'],
    //             ':room_id' => $_POST['room_id'],
    //             ':start_time' => $startTime,
    //             ':end_time' => $endTime,
    //             ':base_price' => $_POST['base_price']
    //         ];

    //         $this->model('Showtime')->createShowtime($data);
    //         $this->redirect('admin/showtime/index');
    //     }
    // }
    /**
     * Xử lý lưu suất chiếu mới vào CSDL
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $movieId   = (int)$_POST['movie_id'];
            $roomId    = (int)$_POST['room_id'];
            $startTime = $_POST['start_time'];
            $basePrice = (float)$_POST['base_price'];

            // Tính toán thời lượng và giờ kết thúc
            $movie = $this->model('Movie')->getMovieById($movieId);
            $duration = isset($movie['duration_min']) ? (int)$movie['duration_min'] : 120;
            $endTime = date('Y-m-d H:i:s', strtotime($startTime . " + {$duration} minutes"));

            $showtimeModel = $this->model('Showtime');

            // Kiểm tra Lớp 1: Phòng chiếu có trống không?
            if (!$showtimeModel->isRoomAvailable($roomId, $startTime, $endTime)) {
                $_SESSION['error'] = "Phòng chiếu đã có lịch bận vào thời gian này (bao gồm 10 phút dọn dẹp).";
                $this->redirect('admin/showtime/create');
                return;
            }

            // Kiểm tra Lớp 2: Bộ phim có đang chiếu ở phòng khác không?
            if (!$showtimeModel->isMovieAvailable($movieId, $startTime, $endTime)) {
                $_SESSION['error'] = "Bộ phim này đang được chiếu ở một phòng khác trong cùng khung giờ. Mỗi phim chỉ chiếu 1 suất tại 1 thời điểm!";
                $this->redirect('admin/showtime/create');
                return;
            }

            // Fix lỗi 500: Các key bắt buộc phải có dấu ':' để bind param trong SQL[cite: 10]
            $data = [
                ':movie_id'   => $movieId,
                ':room_id'    => $roomId,
                ':start_time' => $startTime,
                ':end_time'   => $endTime,
                ':base_price' => $basePrice,
            ];

            // Trong hàm createShowtime cũ không xử lý :status, nên ta không truyền vào đây để tránh lỗi PDO[cite: 10]
            if ($showtimeModel->createShowtime($data)) {
                $_SESSION['success'] = "Thêm suất chiếu thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi thêm suất chiếu!";
            }
            $this->redirect('admin/showtime/index');
        }
    }
    public function edit($id = null) {
        if (!$id) {
            $this->redirect('admin/showtime/index');
            return;
        }

        $showtime = $this->model('Showtime')->getShowtimeById($id);
        
        if (!$showtime) {
            $this->redirect('admin/showtime/index');
            return;
        }

        $movies = $this->model('Movie')->getAllMovies();
        $rooms = $this->model('Room')->getAllRooms();

        $this->adminView('admin/showtimes/edit', 'showtime', [
            'title' => 'Sửa Suất Chiếu',
            'showtime' => $showtime,
            'movies' => $movies,
            'rooms' => $rooms
        ]);
    }

    // Xử lý lưu dữ liệu cập nhật
    // public function update($id = null) {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    //         $startTime = $_POST['start_time'];
    //         $endTime = date('Y-m-d H:i:s', strtotime($startTime . ' + 2 hours'));

    //         $data = [
    //             ':movie_id' => $_POST['movie_id'],
    //             ':room_id' => $_POST['room_id'],
    //             ':start_time' => $startTime,
    //             ':end_time' => $endTime,
    //             ':base_price' => $_POST['base_price']
    //         ];

    //         $this->model('Showtime')->updateShowtime($id, $data);
    //         $this->redirect('admin/showtime/index');
    //     }
    // }
    /**
     * Xử lý cập nhật suất chiếu
     */
    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $movieId   = (int)$_POST['movie_id'];
            $roomId    = (int)$_POST['room_id'];
            $startTime = $_POST['start_time'];
            $basePrice = (float)$_POST['base_price'];

            $movie = $this->model('Movie')->getMovieById($movieId);
            $duration = isset($movie['duration_min']) ? (int)$movie['duration_min'] : 120;
            $endTime = date('Y-m-d H:i:s', strtotime($startTime . " + {$duration} minutes"));

            $showtimeModel = $this->model('Showtime');

            // Kiểm tra Lớp 1 (Truyền ID để loại trừ suất chiếu đang sửa)
            if (!$showtimeModel->isRoomAvailable($roomId, $startTime, $endTime, $id)) {
                $_SESSION['error'] = "Phòng chiếu bị trùng lịch với một phim khác. Vui lòng đổi giờ!";
                $this->redirect('admin/showtime/edit/' . $id);
                return;
            }

            // Kiểm tra Lớp 2 (Truyền ID để loại trừ suất chiếu đang sửa)
            if (!$showtimeModel->isMovieAvailable($movieId, $startTime, $endTime, $id)) {
                $_SESSION['error'] = "Bộ phim này đang được chiếu ở một phòng khác trong cùng khung giờ!";
                $this->redirect('admin/showtime/edit/' . $id);
                return;
            }

            // Fix lỗi 500: Đảm bảo có dấu ':'[cite: 10]
            $data = [
                ':movie_id'   => $movieId,
                ':room_id'    => $roomId,
                ':start_time' => $startTime,
                ':end_time'   => $endTime,
                ':base_price' => $basePrice,
            ];

            if ($showtimeModel->updateShowtime($id, $data)) {
                $_SESSION['success'] = "Cập nhật suất chiếu thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật!";
            }
            $this->redirect('admin/showtime/index');
        }
    }
    public function delete($id = null) {
        // Kiểm tra nếu có ID được truyền lên
        if ($id) {
            $this->model('Showtime')->deleteShowtime($id);
        }
        // Xóa xong thì tự động quay về trang danh sách suất chiếu
        $this->redirect('admin/showtime/index');
    }
}