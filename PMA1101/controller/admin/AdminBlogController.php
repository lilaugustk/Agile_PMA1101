<?php

class AdminBlogController
{
    protected $model;

    public function __construct()
    {
        $this->model = new Blog();
    }

    public function index()
    {
        $blogs = $this->model->select('*', 'deleted_at IS NULL', [], 'created_at DESC');
        require_once PATH_VIEW_ADMIN . 'pages/blogs/index.php';
    }

    public function create()
    {
        require_once PATH_VIEW_ADMIN . 'pages/blogs/create.php';
    }

    public function store()
    {
        $data = [
            'title' => $_POST['title'],
            'slug' => $this->createSlug($_POST['title']),
            'summary' => $_POST['summary'],
            'content' => $_POST['content'],
            'category' => $_POST['category'],
            'status' => $_POST['status'] ?? 'draft',
            'author_id' => $_SESSION['user']['user_id'] ?? null,
            'published_at' => ($_POST['status'] == 'published') ? date('Y-m-d H:i:s') : null,
        ];

        // Handle thumbnail upload
        if (!empty($_FILES['thumbnail']['name'])) {
            $data['thumbnail'] = $this->uploadImage($_FILES['thumbnail']);
        }

        $this->model->insert($data);
        header('Location: ' . BASE_URL_ADMIN . '&action=blogs');
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'];
        $blog = $this->model->find('*', 'id = :id', ['id' => $id]);
        require_once PATH_VIEW_ADMIN . 'pages/blogs/edit.php';
    }

    public function update()
    {
        $id = $_POST['id'];
        $data = [
            'title' => $_POST['title'],
            'slug' => $this->createSlug($_POST['title']),
            'summary' => $_POST['summary'],
            'content' => $_POST['content'],
            'category' => $_POST['category'],
            'status' => $_POST['status'],
            'published_at' => ($_POST['status'] == 'published' && empty($_POST['old_published_at'])) ? date('Y-m-d H:i:s') : $_POST['old_published_at'],
        ];

        if (!empty($_FILES['thumbnail']['name'])) {
            $data['thumbnail'] = $this->uploadImage($_FILES['thumbnail']);
        }

        $this->model->update($data, 'id = :id', ['id' => $id]);
        header('Location: ' . BASE_URL_ADMIN . '&action=blogs');
        exit;
    }

    public function delete()
    {
        $id = $_POST['id'];
        $this->model->update(['deleted_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $id]);
        header('Location: ' . BASE_URL_ADMIN . '&action=blogs');
        exit;
    }

    private function createSlug($string) {
        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
            '#(ì|í|ị|ỉ|ĩ)#',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#',
            '#(đ)#',
            '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
            '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
            '#(Ì|Í|Ị|Ỉ|Ĩ)#',
            '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
            '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
            '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
            '#(Đ)#',
            "/[^a-zA-Z0-9\-\_]/",
        );
        $replace = array(
            'a', 'e', 'i', 'o', 'u', 'y', 'd',
            'a', 'e', 'i', 'o', 'u', 'y', 'd',
            '-',
        );
        $string = preg_replace($search, $replace, $string);
        $string = preg_replace('/(-)+/', '-', $string);
        $string = strtolower($string);
        return $string;
    }

    private function uploadImage($file)
    {
        $targetDir = PATH_ROOT . 'assets/uploads/blogs/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . '_' . basename($file["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return 'assets/uploads/blogs/' . $fileName;
        }
        return '';
    }
}
