<?php
require_once ROOT . '/core/Controller.php';

class AdminShowtimeController extends Controller {
    public function __construct() {
        $this->middlewareAdmin();
    }

    public function index() {
        $showtimes = $this->model('Showtime')->getAllShowtimes();

        $this->adminView('admin/showtimes/index', 'showtime', [
            'title' => 'Quản lý Suất chiếu',
            'showtimes' => $showtimes
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

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $startTime = $_POST['start_time'];
            // Tạm tính tự động cộng 2 tiếng cho thời gian kết thúc
            $endTime = date('Y-m-d H:i:s', strtotime($startTime . ' + 2 hours')); 

            $data = [
                ':movie_id' => $_POST['movie_id'],
                ':room_id' => $_POST['room_id'],
                ':start_time' => $startTime,
                ':end_time' => $endTime,
                ':base_price' => $_POST['base_price']
            ];

            $this->model('Showtime')->createShowtime($data);
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
    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $startTime = $_POST['start_time'];
            $endTime = date('Y-m-d H:i:s', strtotime($startTime . ' + 2 hours'));

            $data = [
                ':movie_id' => $_POST['movie_id'],
                ':room_id' => $_POST['room_id'],
                ':start_time' => $startTime,
                ':end_time' => $endTime,
                ':base_price' => $_POST['base_price']
            ];

            $this->model('Showtime')->updateShowtime($id, $data);
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