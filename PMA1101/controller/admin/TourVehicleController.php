<?php
require_once 'models/TourVehicle.php';
require_once 'models/TourAssignment.php';

class TourVehicleController
{
    private $model;
    private $assignmentModel;

    public function __construct()
    {
        $this->model = new TourVehicle();
        $this->assignmentModel = new TourAssignment();
    }

    /**
     * Danh sách xe của một tour assignment
     */
    public function index()
    {
        $assignment_id = $_GET['assignment_id'] ?? null;

        if (!$assignment_id) {
            $_SESSION['error'] = 'Không xác định được chuyến đi';
            header('Location: ' . BASE_URL_ADMIN);
            exit;
        }

        $vehicles = $this->model->getByTourAssignment($assignment_id);
        $assignment = $this->assignmentModel->getById($assignment_id);

        require_once PATH_VIEW_ADMIN . 'pages/tour_vehicles/index.php';
    }

    /**
     * Form thêm xe
     */
    public function create()
    {
        $assignment_id = $_GET['assignment_id'] ?? null;
        if (!$assignment_id) {
            $_SESSION['error'] = 'Thiếu ID chuyến đi';
            header('Location: ' . BASE_URL_ADMIN);
            exit;
        }

        $assignment = $this->assignmentModel->getById($assignment_id);

        require_once PATH_VIEW_ADMIN . 'pages/tour_vehicles/form.php';
    }

    /**
     * Lưu xe mới
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL_ADMIN);
            exit;
        }

        $assignment_id = $_POST['tour_assignment_id'] ?? null;
        if (!$assignment_id) {
            $_SESSION['error'] = 'Lỗi dữ liệu hệ thống';
            header('Location: ' . BASE_URL_ADMIN);
            exit;
        }

        // Validate
        $vehicle_plate = $_POST['vehicle_plate'] ?? '';
        $driver_name = $_POST['driver_name'] ?? '';
        $driver_phone = $_POST['driver_phone'] ?? '';

        if (empty($vehicle_plate)) {
            $_SESSION['error'] = 'Vui lòng nhập biển số xe';
            header('Location: ' . BASE_URL_ADMIN . '&action=tour_vehicles/create&assignment_id=' . $assignment_id);
            exit;
        }

        $data = [
            'tour_assignment_id' => $assignment_id,
            'vehicle_plate' => $vehicle_plate,
            'vehicle_type' => $_POST['vehicle_type'] ?? '',
            'vehicle_brand' => $_POST['vehicle_brand'] ?? '',
            'driver_name' => $driver_name,
            'driver_phone' => $driver_phone,
            'driver_license' => $_POST['driver_license'] ?? '',
            'notes' => $_POST['notes'] ?? '',
            'status' => 'assigned'
        ];

        try {
            $this->model->insert($data);
            $_SESSION['success'] = 'Đã thêm xe thành công';
            header('Location: ' . BASE_URL_ADMIN . '&action=tour_vehicles&assignment_id=' . $assignment_id);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: ' . BASE_URL_ADMIN . '&action=tour_vehicles/create&assignment_id=' . $assignment_id);
        }
    }

    /**
     * Form sửa xe
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy xe';
            header('Location: ' . BASE_URL_ADMIN);
            exit;
        }

        $vehicle = $this->model->getById($id);
        if (!$vehicle) {
            $_SESSION['error'] = 'Dữ liệu xe không tồn tại';
            header('Location: ' . BASE_URL_ADMIN);
            exit;
        }

        require_once PATH_VIEW_ADMIN . 'pages/tour_vehicles/form.php';
    }

    /**
     * Cập nhật xe
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit;
        }

        $id = $_POST['id'] ?? null;
        $assignment_id = $_POST['tour_assignment_id'] ?? null;

        $data = [
            'vehicle_plate' => $_POST['vehicle_plate'] ?? '',
            'vehicle_type' => $_POST['vehicle_type'] ?? '',
            'vehicle_brand' => $_POST['vehicle_brand'] ?? '',
            'driver_name' => $_POST['driver_name'] ?? '',
            'driver_phone' => $_POST['driver_phone'] ?? '',
            'driver_license' => $_POST['driver_license'] ?? '',
            'notes' => $_POST['notes'] ?? '',
            'status' => $_POST['status'] ?? 'assigned'
        ];

        try {
            $this->model->update($data, 'id=:id', ['id' => $id]);
            $_SESSION['success'] = 'Cập nhật xe thành công';
            header('Location: ' . BASE_URL_ADMIN . '&action=tour_vehicles&assignment_id=' . trim($assignment_id));
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: ' . BASE_URL_ADMIN . '&action=tour_vehicles/edit&id=' . $id);
        }
    }

    /**
     * Xóa xe
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        $assignment_id = $_GET['assignment_id'] ?? null;

        if ($id) {
            $this->model->delete('id=:id', ['id' => $id]);
            $_SESSION['success'] = 'Đã xóa xe khỏi tour';
        }

        if ($assignment_id) {
            header('Location: ' . BASE_URL_ADMIN . '&action=tour_vehicles&assignment_id=' . $assignment_id);
        } else {
            header('Location: ' . BASE_URL_ADMIN);
        }
    }
}
