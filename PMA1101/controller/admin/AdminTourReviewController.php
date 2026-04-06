<?php

class AdminTourReviewController
{
    protected $model;

    public function __construct()
    {
        $this->model = new TourReview();
    }

    public function index()
    {
        $reviews = $this->model->select('tr.*, t.name as tour_name', 'TRUE', [], 'tr.created_at DESC');
        // Because select() doesn't support JOIN directly easily, I'll join in the query
        $sql = "SELECT tr.*, t.name as tour_name 
                FROM tour_reviews tr
                LEFT JOIN tours t ON tr.tour_id = t.id
                ORDER BY tr.created_at DESC";
        $pdo = BaseModel::getPdo();
        $reviews = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        require_once PATH_VIEW_ADMIN . 'pages/tour_reviews/index.php';
    }

    public function updateStatus()
    {
        header('Content-Type: application/json');
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$id || !$status) {
            echo json_encode(['success' => false, 'message' => 'Missing data']);
            exit;
        }

        try {
            $this->model->update(['status' => $status], 'id = :id', ['id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $this->model->delete('id = :id', ['id' => $id]);
        }
        header('Location: ' . BASE_URL_ADMIN . '&action=tour_reviews');
        exit;
    }
}
