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
            ->select('message, created_at')
            ->where('id_room', $roomId)
            ->whereIn('id_user', [$userId, $otherUserId])
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->get();

        $result = $query->getRow();

        if ($result) {
            return (object) [
                'message' => $result->message,
                'unformattedTime' => $result->created_at,
                'formattedTime' => $this->formatChatTime($result->created_at),
            ];
        }

        return (object) [
            'message' => '',
            'unformattedTime' => null,
            'formattedTime' => null,
        ];
    }

    // public function getLastChat($roomId, $userId, $otherUserId)
    // {
    //     $builder = $this->select('id_user, MAX(created_at) as last_chat_time, MAX(message) as last_message')
    //         ->where('id_room', $roomId)
    //         ->whereIn('id_user', [$userId, $otherUserId])
    //         ->groupBy('id_user')
    //         ->get();

    //     $results = $builder->getResult();

    //     // var_dump($results);

    //     $user1LastMessage = null;
    //     $user2LastMessage = null;
    //     $user1LastChatTime = null;
    //     $user2LastChatTime = null;

    //     foreach ($results as $result) {
    //         if ($result->id_user === $userId) {
    //             $user1LastMessage = $result->last_message;
    //             $user1LastChatTime = $result->last_chat_time;
    //             // var_dump($user1LastMessage);
    //         } elseif ($result->id_user === $otherUserId) {
    //             $user2LastMessage = $result->last_message;
    //             $user2LastChatTime = $result->last_chat_time;
    //             // var_dump($user2LastMessage);
    //         }
    //     }

    //     return [
    //         'user1_last_message' => $user1LastMessage,
    //         'user2_last_message' => $user2LastMessage,
    //         'user1_last_chat_time' => $user1LastChatTime,
    //         'user2_last_chat_time' => $user2LastChatTime,
    //     ];
    // }

    // public function getLastMessage($roomId, $userId, $otherUserId)
    // {
    //     $lastChatData = $this->getLastChat($roomId, $userId, $otherUserId);

    //     $yourLastChatTime = $lastChatData['user1_last_chat_time'];
    //     $theirLastChatTime = $lastChatData['user2_last_chat_time'];
    //     $yourLastMessage = $lastChatData['user1_last_message'];
    //     $theirLastMessage = $lastChatData['user2_last_message'];
        
    //     if ($theirLastChatTime && $theirLastChatTime > $yourLastChatTime) {
    //         return (object) [
    //             'message' => $theirLastMessage,
    //             'unformattedTime' => $theirLastChatTime,
    //             'formattedTime' => $this->formatChatTime($theirLastChatTime),
    //         ];
    //     }
    
    //     return (object) [
    //         'message' => $yourLastMessage,
    //         'unformattedTime' => $yourLastChatTime,
    //         'formattedTime' => $this->formatChatTime($yourLastChatTime),
    //     ];
    // }
    
    private function formatChatTime($timestamp)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($timestamp){
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

        return $this->formatAsHoursMinutes($interval);
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

        $formattedTime = date('g:i A', $timestamp); // Format in AM and PM

        return $formattedTime;
    }

}
