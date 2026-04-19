<?php

require_once 'models/Blog.php';

class ClientBlogController
{
    private $blogModel;

    public function __construct()
    {
        $this->blogModel = new Blog();
    }

    public function index()
    {
        $blogs = $this->blogModel->getAllPublished();
        $latestBlogs = $this->blogModel->getLatest(3);

        $data = [
            'blogs' => $blogs,
            'latestBlogs' => $latestBlogs,
            'title' => 'Cẩm nang du lịch & Tin tức'
        ];
        require_once PATH_VIEW_CLIENT . 'pages/blogs/index.php';
    }

    public function detail()
    {
        $slug = $_GET['slug'] ?? '';
        if (empty($slug)) {
            header('Location: ' . BASE_URL . '?action=blogs');
            exit;
        }

        $blog = $this->blogModel->findBySlug($slug);
        if (!$blog) {
            echo "<h1>Bài viết không tồn tại</h1>";
            exit;
        }

        // Increment view count
        $this->blogModel->incrementView($blog['id']);

        $latestBlogs = $this->blogModel->getLatest(5);

        $data = [
            'blog' => $blog,
            'latestBlogs' => $latestBlogs,
            'title' => $blog['title'] ?? 'Chi tiết bài viết'
        ];
        require_once PATH_VIEW_CLIENT . 'pages/blogs/detail.php';
    }
}
