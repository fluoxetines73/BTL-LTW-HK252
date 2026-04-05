<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Phim Mới - Admin</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="number"], input[type="date"], select, textarea { 
            width: 100%; padding: 8px; box-sizing: border-box; 
        }
        .btn-submit { background-color: #28a745; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        .btn-back { background-color: #6c757d; color: white; padding: 10px 15px; text-decoration: none; display: inline-block; margin-right: 10px;}
    </style>
</head>
<body>

    <h2>Thêm Phim Mới</h2>
    
    <form action="<?= BASE_URL ?>admin/movie/store" method="POST">
        <div class="form-group">
            <label>Tên Phim (*):</label>
            <input type="text" name="title" required placeholder="Nhập tên phim">
        </div>

        <div class="form-group">
            <label>Đường dẫn tĩnh (Slug) (*):</label>
            <input type="text" name="slug" required placeholder="vd: avengers-endgame">
        </div>

        <div class="form-group">
            <label>Đạo diễn:</label>
            <input type="text" name="director">
        </div>

        <div class="form-group">
            <label>Diễn viên:</label>
            <input type="text" name="cast" placeholder="vd: Robert Downey Jr., Chris Evans">
        </div>

        <div class="form-group">
            <label>Thời lượng (phút) (*):</label>
            <input type="number" name="duration_min" required value="120">
        </div>

        <div class="form-group">
            <label>Ngày phát hành (*):</label>
            <input type="date" name="release_date" required>
        </div>

        <div class="form-group">
            <label>Độ tuổi cho phép (*):</label>
            <select name="age_rating" required>
                <option value="P">P - Mọi lứa tuổi</option>
                <option value="C13">C13 - Từ 13 tuổi trở lên</option>
                <option value="C16">C16 - Từ 16 tuổi trở lên</option>
                <option value="C18">C18 - Từ 18 tuổi trở lên</option>
            </select>
        </div>

        <div class="form-group">
            <label>Trạng thái chiếu (*):</label>
            <select name="status" required>
                <option value="coming_soon">Sắp chiếu</option>
                <option value="now_showing">Đang chiếu</option>
                <option value="ended">Đã kết thúc</option>
            </select>
        </div>

        <div class="form-group">
            <label>Mô tả nội dung:</label>
            <textarea name="description" rows="4"></textarea>
        </div>

        <a href="<?= BASE_URL ?>admin/movie/index" class="btn-back">Quay lại</a>
        <button type="submit" class="btn-submit">Lưu Phim CSDL</button>
    </form>

</body>
</html>