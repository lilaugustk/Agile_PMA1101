<?php
class CustomerProfileModel extends BaseModel
{
    protected $table = 'customer_profiles';

    /**
     * Get a profile for a given user.
     * @param int $userId
     * @return array|false
     */
    public function getByUserId($userId)
    {
        if (empty($userId)) return false;
        return $this->find('*', 'user_id = :uid', ['uid' => $userId]);
    }

    /**
     * Upsert a profile for a given user.
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function upsertProfile($userId, $data)
    {
        if (empty($userId)) return false;

        $existing = $this->find('id', 'user_id = :uid', ['uid' => $userId]);
        
        if (!empty($existing)) {
            return $this->update($data, 'user_id = :uid', ['uid' => $userId]);
        } else {
            $data['user_id'] = $userId;
            return $this->insert($data);
        }
    }
}
