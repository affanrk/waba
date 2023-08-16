<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{
    protected $table = 'chat';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_room', 'id_user', 'message', 'media', 'is_active', 'created_at'
    ];
    protected $returnType = 'App\Entities\Chat';
    protected $useTimestamps = false;

    public function getChatsByRoom($roomId)
    {
        return $this->where('id_room', $roomId)->get()->getResult();
    }

    public function getRoomId($userId, $otherUserId)
    {
        $builder = $this->db->table('room_user')
            ->select('id_room')
            ->whereIn('id_user', [$userId, $otherUserId])
            ->groupBy('id_room')
            ->having('COUNT(DISTINCT id_user)', 2);

        $query = $builder->get();

        $result = $query->getRow();

        return ($result) ? $result->id_room : null;
    }

    public function getLastMessage($roomId, $userId, $otherUserId)
    {
        $query = $this->db->table('chat')
            ->select('message, media, created_at')
            ->where('id_room', $roomId)
            ->whereIn('id_user', [$userId, $otherUserId])
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->get();

        $result = $query->getRow();

        if ($result) {
            return (object) [
                'message' => $result->message,
                'media' => $result->media,
                'unformattedTime' => $result->created_at,
                'formattedTime' => $this->formatChatTime($result->created_at),
            ];
        }
        
        return (object) [
            'message' => '',
            'media' => '',
            'unformattedTime' => null,
            'formattedTime' => null,
        ];
    }

    private function formatChatTime($timestamp)
    {
        if ($timestamp){
            $currentDate = new \DateTime('today'); // Tanggal hari ini
            $lastChatDate = new \DateTime($timestamp);
    
            // Menghilangkan jam, menit, dan detik dari objek DateTime
            $currentDate->setTime(0, 0, 0);
            $lastChatDate->setTime(0, 0, 0);
    
            $interval = $lastChatDate->diff($currentDate); // Membandingkan dengan urutan yang benar
    
            return $this->formatTimeInterval($interval, $timestamp);
        }
        return "No chat history";
    }
    
    private function formatTimeInterval($interval, $timestamp)
    {
        if ($interval->days > 1) {
            return $this->formatAsDate($timestamp);
        } elseif ($interval->days === 1) {
            return "Yesterday";
        }
    
        return $this->formatAsHoursMinutes($timestamp);
    }
    
    private function formatAsDate($timestamp)
    {
        $formattedDate = date('j/n/y', strtotime($timestamp));
        
        return $formattedDate;
    }
    
    private function formatAsHoursMinutes($timestamp)
    {
        $formattedTime = date('g:i A', strtotime($timestamp)); // Format in AM and PM
    
        return $formattedTime;
    }

}
