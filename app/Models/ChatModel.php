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
    protected $useTimeStamps = false;

    function getChatsByRoom($idRoom)
    {
        $query = $this->db->table($this->table)
            ->where('id_room', $idRoom)
            ->get();
        
        $result = $query->getResult();
        return $result;
    }

    function getLastChatTimeByRoom($idRoom)
    {
        //
    }

    private function getLastChatFormattedTime($timestamp)
    {
        $currentDateTime = new \DateTime();
        $lastChatTime = new \DateTime($timestamp);
        $interval = $currentDateTime->diff($lastChatTime);

        // If more than 48 hours, return date format
        if ($interval->days >= 2) {
            return $lastChatTime->format('d/m/y');
        }

        // If more than 24 hours, return "Yesterday"
        if ($interval->days === 1) {
            return "Yesterday";
        }

        // If more than 1 hour, return hours and minutes format
        if ($interval->h >= 1) {
            return $lastChatTime->format('H:i');
        }

        // If less than 1 hour, return "X minutes ago" or "Just now"
        if ($interval->i >= 1) {
            return $interval->i . " minute" . ($interval->i > 1 ? "s" : "") . " ago";
        }

        if ($interval->i > 1) {
            return $interval->i . " minutes" . ($interval->i > 1 ? "s" : "") . " ago";
        }

        if ($interval->s > 1) {
            return $interval->i . " seconds" . ($interval->s > 1 ? "s" : "") . " ago";
        }

        return "Just now"; // If less than 2 seconds
    }
    
}
