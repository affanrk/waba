<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'email', 'screen_name', 'phone'
    ];
    protected $returnType = 'App\Entities\User';
    protected $useTimestamps = false;

    public function isAlreadyRegistered($authId)
    {
        $user = $this->db->table('user')
            ->where(['email' => $authId])
            ->get()
            ->getRow();

        return $user ? true : false;
    }

    public function updateUserData($userData, $authId)
    {
        return $this->db->table('user')
            ->where(['email' => $authId])
            ->update($userData);
    }

    public function getIdByEmail($email)
    {
        $query = $this->db->table('user')
            ->select('id')
            ->where('email', $email)
            ->get();

        $user = $query->getRow();

        return $user ? $user->id : null;
    }
}
