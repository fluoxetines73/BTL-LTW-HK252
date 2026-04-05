<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Phim - Admin</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; text-align: left; }
        .btn { padding: 5px 10px; text-decoration: none; color: #fff; border-radius: 3px; }
        .btn-add { background-color: #28a745; display: inline-block; }
        .btn-edit { background-color: #007bff; }
        .btn-delete { background-color: #dc3545; }
    </style>
</head>
<body style="padding: 20px; font-family: sans-serif;">

    <h2>Danh Sách Phim</h2>
    
    <a href="<?= BASE_URL ?>admin/movie/create" class="btn btn-add">+ Thêm Phim Mới</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Phim</th>
                <th>Đạo diễn</th>
                <th>Thời lượng</th>
                <th>Ngày chiếu</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($movies)): ?>
                <?php foreach ($movies as $movie): ?>
                <tr>
                    <td><?= $movie['id'] ?></td>
                    <td><strong><?= htmlspecialchars($movie['title']) ?></strong></td>
                    <td><?= htmlspecialchars($movie['director']) ?></td>
                    <td><?= $movie['duration_min'] ?> phút</td>
                    <td><?= $movie['release_date'] ?></td>
                    <td>
                        <?php 
                            if($movie['status'] == 'now_showing') echo 'Đang chiếu';
                            elseif($movie['status'] == 'coming_soon') echo 'Sắp chiếu';
                            else echo 'Đã kết thúc';
                        ?>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>admin/movie/edit/<?= $movie['id'] ?>" class="btn btn-edit">Sửa</a>
                        <a href="<?= BASE_URL ?>admin/movie/delete/<?= $movie['id'] ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa phim này?');">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Chưa có bộ phim nào trong cơ sở dữ liệu.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>