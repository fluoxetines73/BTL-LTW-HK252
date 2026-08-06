<?php

class Upload {
    private const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private const MAX_SIZE = 5242880;
    private string $error = '';

    public function handle(array $file, string $subDir = 'avatars', string $oldFile = ''): ?string {
        if (!isset($file['tmp_name']) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE || empty($file['tmp_name'])) {
            return null;
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            $this->error = 'Upload failed.';
            return null;
        }

        if (($file['size'] ?? 0) > self::MAX_SIZE) {
            $this->error = 'File quá lớn. Tối đa 5MB.';
            return null;
        }

        // Detect MIME type using getimagesize (works without fileinfo extension)
        $imgInfo = @getimagesize($file['tmp_name']);
        if ($imgInfo === false) {
            $this->error = 'File không phải là ảnh hợp lệ.';
            return null;
        }
        $mime = $imgInfo['mime'] ?? '';

        if (!in_array($mime, self::ALLOWED_TYPES, true)) {
            $this->error = 'Chỉ chấp nhận ảnh JPEG, PNG, WebP, GIF.';
            return null;
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => 'jpg',
        };

        $uploadDir = ROOT . '/public/uploads/' . trim($subDir, '/') . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        $target = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            $this->error = 'Không thể lưu file tải lên.';
            return null;
        }

        if ($oldFile !== '') {
            $oldPath = ROOT . '/public/' . ltrim($oldFile, '/');
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }

        return 'uploads/' . trim($subDir, '/') . '/' . $filename;
    }

    public function getError(): string {
        return $this->error;
    }
}
