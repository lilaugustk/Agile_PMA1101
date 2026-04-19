<?php

class FileService
{
    /**
     * Tải tệp lên thư mục assets/uploads
     * @param array $file Biến $_FILES['key']
     * @param string $folder Thư mục con trong assets/uploads
     * @return string|null Đường dẫn tệp hoặc null nếu thất bại
     */
    public static function upload($file, $folder = 'others')
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = 'assets/uploads/' . $folder . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $targetPath;
        }

        return null;
    }
}
