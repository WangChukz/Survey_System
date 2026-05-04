<?php

namespace Core;

abstract class Controller
{
    /**
     * Render view và truyền dữ liệu.
     *
     * @param string $view Tên file view (VD: 'survey/step')
     * @param array $data Mảng dữ liệu truyền sang view
     */
    protected function render(string $view, array $data = [], string $layout = 'layout'): void
    {
        // Trích xuất các key của mảng $data thành các biến độc lập
        extract($data);
        
        // Require file view. Thư mục views nằm ở app/Views/
        $viewFile = __DIR__ . '/../app/Views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            // Bật buffer để lấy nội dung HTML của view con
            ob_start();
            require $viewFile;
            $content = ob_get_clean();
            
            // Render file layout chính và nhúng $content vào
            require_once __DIR__ . '/../app/Views/' . $layout . '.php';
        } else {
            die("View file not found: {$viewFile}");
        }
    }

    /**
     * Chuyển hướng trang
     *
     * @param string $url Đường dẫn cần chuyển hướng
     */
    protected function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }
}
