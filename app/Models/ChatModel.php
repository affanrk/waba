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

    public function getChatsByRoom($idRoom)
    {
        return $this->where('id_room', $idRoom)->get()->getResult();
    }

    public function getRoomIdByUsers($idUser, $allUserIds)
    {
        //
    }

    public function getLastChatFormattedTime($timestamp)
    {
        $currentDateTime = new \DateTime();
        $lastChatTime = new \DateTime($timestamp);
        $interval = $currentDateTime->diff($lastChatTime);

        return $this->formatTimeInterval($interval);
    }

    private function formatTimeInterval($interval)
    {
        if ($interval->days >= 2) {
            return $this->formatAsDate($interval);
        }

        if ($interval->days === 1) {
            return "Yesterday";
        }

        if ($interval->h >= 1) {
            return $this->formatAsHoursMinutes($interval);
        }

        if ($interval->i >= 1) {
            return $this->formatAsMinutes($interval);
        }

        if ($interval->s >= 1) {
            return $this->formatAsSeconds($interval);
        }

        return "Just now";
    }

    private function formatAsDate($interval)
    {
        return $interval->format('d/m/y');
    }

    private function formatAsHoursMinutes($interval)
    {
        return $interval->format('H:i');
    }

    private function formatAsMinutes($interval)
    {
        $minutes = $interval->i;
        return "$minutes minute" . ($minutes > 1 ? 's' : '') . " ago";
    }

    private function formatAsSeconds($interval)
    {
        $seconds = $interval->s;
        return "$seconds second" . ($seconds > 1 ? 's' : '') . " ago";
    }
}
