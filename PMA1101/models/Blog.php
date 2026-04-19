<?php

class Blog extends BaseModel
{
    protected $table = 'blogs';

    public function getAllPublished()
    {
        return $this->select('*', 'status = "published" AND deleted_at IS NULL', [], 'published_at DESC');
    }

    public function findBySlug($slug)
    {
        return $this->find('*', 'slug = :slug AND deleted_at IS NULL', ['slug' => $slug]);
    }

    public function getLatest($limit = 5)
    {
        return $this->select('*', 'status = "published" AND deleted_at IS NULL', [], 'published_at DESC', $limit);
    }

    public function incrementView($id)
    {
        $sql = "UPDATE {$this->table} SET view_count = view_count + 1 WHERE id = :id";
        $stmt = self::$pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
