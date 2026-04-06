<?php

class TourReview extends BaseModel
{
    protected $table = 'tour_reviews';

    public function getApprovedByTour($tourId)
    {
        return $this->select('*', 'tour_id = :tour_id AND status = "approved"', ['tour_id' => $tourId], 'created_at DESC');
    }

    public function getStatsByTour($tourId)
    {
        $sql = "SELECT 
                    COUNT(*) as total_reviews,
                    AVG(rating) as avg_rating,
                    SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as star_5,
                    SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as star_4,
                    SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as star_3,
                    SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as star_2,
                    SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as star_1
                FROM {$this->table} 
                WHERE tour_id = :tour_id AND status = 'approved'";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetch();
    }
}
