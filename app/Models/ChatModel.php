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

    public function getLastChatTime($roomId, $userId, $otherUserId)
    {
        $builder = $this->select('id_user, MAX(created_at) as last_chat_time')
            ->where('id_room', $roomId)
            ->whereIn('id_user', [$userId, $otherUserId])
            ->groupBy('id_user')
            ->get();

        $results = $builder->getResult();

        $user1LastChatTime = null;
        $user2LastChatTime = null;

        foreach ($results as $result) {
            if ($result->id_user === $userId) {
                $user1LastChatTime = $result->last_chat_time;
            } elseif ($result->id_user === $otherUserId) {
                $user2LastChatTime = $result->last_chat_time;
            }
        }

        return [
            'user1_last_chat_time' => $user1LastChatTime,
            'user2_last_chat_time' => $user2LastChatTime,
        ];
    }

    public function getUnformattedLastChatTime($roomId, $userId, $otherUserId)
    {
        $lastChatTimes = $this->getLastChatTime($roomId, $userId, $otherUserId);

        $yourLastChatTime = $lastChatTimes['user1_last_chat_time'];
        $theirLastChatTime = $lastChatTimes['user2_last_chat_time'];

        if ($theirLastChatTime && $theirLastChatTime > $yourLastChatTime) {
            return $theirLastChatTime;
        }

        return $yourLastChatTime;
    }

    public function getFormattedLastChatTime($roomId, $userId, $otherUserId)
    {
        $lastChatTimes = $this->getLastChatTime($roomId, $userId, $otherUserId);

        $yourLastChatTime = $lastChatTimes['user1_last_chat_time'];
        $theirLastChatTime = $lastChatTimes['user2_last_chat_time'];

        if ($theirLastChatTime && $theirLastChatTime > $yourLastChatTime) {
            return $this->formatChatTime($theirLastChatTime);
        }

        return $this->formatChatTime($yourLastChatTime);
    }

    private function formatChatTime($timestamp)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($timestamp) {
            $currentDateTime = new \DateTime();
            $lastChatTime = new \DateTime($timestamp);
            $interval = $currentDateTime->diff($lastChatTime);

            return $this->formatTimeInterval($interval);
        }

        return "No chat history";
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
        $currentDateTime = new \DateTime();
        $timestamp = $currentDateTime->getTimestamp() - $interval->s - $interval->i * 60 - $interval->h * 3600 - $interval->days * 86400;
        
        return date('j/n/y', $timestamp);
    }

    private function formatAsHoursMinutes($interval)
    {
        $timestamp = time() - $interval->s - $interval->i * 60 - $interval->h * 3600;

        return date('H:i', $timestamp);
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
