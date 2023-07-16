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
    protected $useTimeStamps = false;

    function isAlreadyRegister($authId)
    {
        return $this->db->table('user')->getWhere(['email' => $authId])->getRowArray()>0?true:false;
    }

    function updateUserData($userData, $authId)
    {
        return $this->db->table('user')->where(['email' => $authId])->update($userData);
    }

    function getId($email)
    {
        $query = $this->db->table('user')->select('id')->where('email', $email)->get()->getRow();
    
        if ($query) {
            return $query->id;
        } else {
            return null; 
        }
    }
    
}