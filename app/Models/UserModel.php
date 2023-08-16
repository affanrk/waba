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

    public function getAllUsers($userId){
        /*
        SELECT c.* 
        FROM  `room_user` a 
        JOIN `room_user` b ON a.id_room = b.id_room AND b.id_user != 1
        JOIN `user` c ON b.id_user = c.id
        WHERE a.id_user = 1
        ;
        */

        $query = $this->db->table('`room_user` a')
            ->select('c.*')
            ->join('`room_user` b','a.id_room = b.id_room', 'INNER')
            ->join('`user` c','b.id_user = c.id AND b.id_user != ' . $userId, 'INNER')
            ->where('a.id_user', $userId)
            ->get();

        return $query->getResult();

    }

}
