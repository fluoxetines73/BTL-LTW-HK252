<?php
require_once ROOT . '/core/Controller.php';

class ApiController extends Controller {
    public function getShowtimes() {
        header('Content-Type: application/json');
        $movieId = $_GET['movie_id'] ?? 0;
        $date = $_GET['date'] ?? '';

        if ($movieId && $date) {
            $showtimes = $this->model('Showtime')->getShowtimesByMovieAndDate($movieId, $date);
            echo json_encode(['success' => true, 'data' => $showtimes]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Thiếu tham số']);
        }
    }

    public function getOccupiedSeats() {
        header('Content-Type: application/json');
        $showtimeId = $_GET['showtime_id'] ?? 0;

        if ($showtimeId) {
            $occupiedSeats = $this->model('Booking')->getOccupiedSeats($showtimeId);
            echo json_encode(['success' => true, 'data' => $occupiedSeats]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Thiếu ID suất chiếu']);
        }
    }
}