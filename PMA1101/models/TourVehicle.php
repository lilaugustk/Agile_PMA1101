<?php
require_once 'models/BaseModel.php';

/**
 * Model quản lý xe cụ thể cho tour (Đã loại bỏ liên kết Nhà xe)
 */
class TourVehicle extends BaseModel
{
    protected $table = 'tour_vehicles';
    protected $columns = [
        'id',
        'tour_assignment_id',
        'vehicle_plate',
        'vehicle_type',
        'vehicle_brand',
        'driver_name',
        'driver_phone',
        'driver_license',
        'notes',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Lấy xe theo tour assignment
     */
    public function getByTourAssignment($tourAssignmentId)
    {
        $sql = "SELECT tv.* 
                FROM {$this->table} tv
                WHERE tv.tour_assignment_id = :tour_assignment_id
                ORDER BY tv.created_at DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['tour_assignment_id' => $tourAssignmentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết xe
     */
    public function getById($id)
    {
        $sql = "SELECT tv.*, ta.tour_id
                FROM {$this->table} tv
                LEFT JOIN tour_assignments ta ON tv.tour_assignment_id = ta.id
                WHERE tv.id = :id";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật trạng thái xe
     */
    public function updateStatus($id, $status)
    {
        return $this->update(
            ['status' => $status],
            'id = :id',
            ['id' => $id]
        );
    }

    /**
     * Kiểm tra biển số xe đã được assign cho tour chưa
     */
    public function isVehicleAssigned($vehiclePlate, $tourAssignmentId)
    {
        $result = $this->find(
            'id',
            'vehicle_plate = :plate AND tour_assignment_id = :tour_id',
            [
                'plate' => $vehiclePlate,
                'tour_id' => $tourAssignmentId
            ]
        );
        return !empty($result);
    }

    /**
     * Lấy thống kê xe theo trạng thái
     */
    public function getStatsByStatus()
    {
        $sql = "SELECT 
                    status,
                    COUNT(*) as count
                FROM {$this->table}
                GROUP BY status";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
