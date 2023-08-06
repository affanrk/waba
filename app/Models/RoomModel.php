<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table = 'room';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'is_group'
    ];
    protected $returnType = 'App\Entities\Room';
    protected $useTimestamps = false;

    private $userModel;
    private $roomUserModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
        $this->roomUserModel = new RoomUserModel();
    }

    public function getRoomByUser($userArray, $is_group = 0)
    {
        $query = $this->roomUserModel->select('room.id AS roomId')
            ->join('room', 'room.id = room_user.id_room', 'right')
            ->whereIn('room_user.id_user', $userArray)
            ->where('room.is_group', $is_group)
            ->groupBy('room.id')
            ->having('COUNT(room.id)', 2);

        $roomUserCheck = $query->first();

        if (empty($roomUserCheck)) {
            return $this->createRoom($userArray);
        }

        return $this->getRoomById($roomUserCheck->roomId);
    }

    private function createRoom($userArray)
    {
        $roomData = [
            'name' => '',
            'is_group' => 0,
        ];

        $this->db->transStart();

        $room = $this->db->table($this->table)->insert($roomData);
        $idRoom = $this->db->insertID();

        $userData = [];

        foreach ($userArray as $u) {
            $temp = [
                'id_user' => $u,
                'id_room' => $idRoom,
            ];
            array_push($userData, $temp);
        }

        $roomUserBuilder = $this->roomUserModel->builder();
        $roomUser = $roomUserBuilder->insertBatch($userData);

        $this->db->transComplete();

        return $this->getRoomById($idRoom);
    }

    private function getRoomById($roomId)
    {
        $query = $this->db->table($this->table)
            ->where('id', $roomId)
            ->get();

        return $query->getRow();
    }
}
