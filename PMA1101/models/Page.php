<?php

class Page extends BaseModel
{
    protected $table = 'pages';

    public function getBySlug($slug)
    {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
