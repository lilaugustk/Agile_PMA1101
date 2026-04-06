<?php
// models/DepartureResource.php
require_once 'models/BaseModel.php';

class DepartureResource extends BaseModel
{
    protected $table = 'departure_resources';
    protected $columns = [
        'id',
        'departure_id',
        'supplier_id',
        'service_type', // bus, hotel, restaurant, guide, insurance
        'quantity',
        'unit_price',
        'total_amount',
        'payment_status', // unpaid, partial, paid
        'paid_amount',
        'notes',
        'created_at',
        'updated_at'
    ];

    /**
     * Lấy tất cả tài nguyên/chi phí gán cho 1 chuyến đi
     */
    public function getByDepartureId($departureId)
    {
        $sql = "SELECT dr.*, s.name as supplier_name, s.phone as supplier_phone 
                FROM {$this->table} dr
                LEFT JOIN suppliers s ON dr.supplier_id = s.id
                WHERE dr.departure_id = :departure_id
                ORDER BY dr.service_type ASC";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['departure_id' => $departureId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tính tổng chi phí cho 1 chuyến đi
     */
    public function getTotalCostByDepartureId($departureId)
    {
        $sql = "SELECT SUM(total_amount) as total FROM {$this->table} WHERE departure_id = :departure_id";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['departure_id' => $departureId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Lấy công nợ nhà cung cấp
     */
    public function getSupplierDebt()
    {
        $sql = "SELECT s.id, s.name as supplier_name, 
                       SUM(dr.total_amount) as total_payable,
                       SUM(dr.paid_amount) as total_paid,
                       (SUM(dr.total_amount) - SUM(dr.paid_amount)) as debt
                FROM suppliers s
                INNER JOIN {$this->table} dr ON s.id = dr.supplier_id
                GROUP BY s.id, s.name
                HAVING debt > 0
                ORDER BY debt DESC";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật trạng thái thanh toán
     */
    public function updatePayment($id, $paidAmount, $status)
    {
        $sql = "UPDATE {$this->table} SET paid_amount = :paid, payment_status = :status WHERE id = :id";
        $stmt = self::$pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'paid' => $paidAmount,
            'status' => $status
        ]);
    }
}
