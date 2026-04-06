<?php

require_once 'models/Blog.php';

class ClientBlogController extends BaseController
{
    private $blogModel;

    public function __construct()
    {
        parent::__construct();
        $this->blogModel = new Blog();
    }

    public function index()
    {
        $blogs = $this->blogModel->getAllPublished();
        $latestBlogs = $this->blogModel->getLatest(3);

        $this->render('blogs/index', [
            'blogs' => $blogs,
            'latestBlogs' => $latestBlogs,
            'title' => 'Cẩm nang du lịch & Tin tức'
        ]);
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

        $latestBlogs = $this->blogModel->getLatest(5);

        $this->render('blogs/detail', [
            'blog' => $blog,
            'latestBlogs' => $latestBlogs,
            'title' => $blog['title']
        ]);
    }
}
