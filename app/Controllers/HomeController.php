<?php

namespace App\Controllers;

use App\Entities\Chat;
use App\Models\ChatModel;
use App\Models\RoomModel;
use App\Models\UserModel;
use Config\Services;

class HomeController extends AuthController
{
    protected $roomModel;
    protected $userModel;
    protected $chatModel;
    protected $encrypter;
    protected $request;

    public function __construct()
    {
        parent::__construct();
        $this->roomModel = new RoomModel();
        $this->chatModel = new ChatModel();
        $this->userModel = new UserModel();
        $this->encrypter = Services::encrypter();
        $this->request = Services::request();
    }

    public function index()
    {
        $id = $this->getCurrentUserId();
        $user = $this->userModel->find($id);
        $allUsers = $this->userModel->where('id !=', $id)->findAll();

        $this->addEncryptedIds($allUsers);
        $this->getLastChatTimesForAllUsers($allUsers, $id);

        return view('home/index', [
            'user' => $user,
            'allUsers' => $allUsers,
            'idUser' => $id,
        ]);
    }

    public function decryptUserId()
    {
        $encryptedId = $this->request->getPost('encryptedId');
        $decryptedId = $this->encrypter->decrypt(base64_decode($encryptedId));

        return $this->response->setJSON(['decryptedId' => $decryptedId]);
    }

    public function getRoomByUser()
    {
        if ($this->request->isAJAX()) {
            $idCurrentUser = $this->getCurrentUserId();
            $idReceiver = $this->request->getGet('contactId');

            $room = $this->roomModel->getRoomByUser([$idCurrentUser, $idReceiver]);

            return $this->response->setJSON($room);
        }
    }

    public function getChatsByRoom()
    {
        if ($this->request->isAJAX()) {
            $roomId = $this->request->getGet('roomId');

            $chats = $this->chatModel->getChatsByRoom($roomId);

            return $this->response->setJSON($chats);
        }
    }

    public function sendMessage()
    {
        if ($this->request->isAJAX()) {
            date_default_timezone_set('Asia/Jakarta');
            $message = $this->request->getPost('message');
            $roomId = $this->request->getPost('id_room');
            $userId = $this->getCurrentUserId();

            $chat = new Chat();
            $chat->id_room = $roomId;
            $chat->id_user = $userId;
            $chat->message = $message;
            $chat->media = null;
            $chat->is_active = 1;
            $chat->created_at = date("Y-m-d H:i:s");

            $this->chatModel->save($chat);

            $chatMessage = [
                'created_at' => $chat->created_at,
                'message' => $message,
            ];

            return $this->response->setJSON($chatMessage);
        }
    }

    private function getCurrentUserId()
    {
        return $this->session->get('idUser');
    }

    private function addEncryptedIds(&$users)
    {
        foreach ($users as &$user) {
            $user->encryptedId = base64_encode($this->encrypter->encrypt($user->id));
        }
    }

    private function getLastChatTimesForAllUsers(&$allUsers, $currentUserId)
    {
        foreach ($allUsers as $u) {
            $otherUserId = $u->id;
            $roomId = $this->chatModel->getRoomId($currentUserId, $otherUserId);

            if ($roomId) {
                $unformattedLastChatTime = $this->chatModel->getUnformattedLastChatTime($roomId, $currentUserId, $otherUserId);
                $formattedLastChatTime = $this->chatModel->getFormattedLastChatTime($roomId, $currentUserId, $otherUserId);
    
                $u->last_chat_time = $formattedLastChatTime;
                $u->unformatted_last_chat_time = $unformattedLastChatTime;
                var_dump($u->unformatted_last_chat_time);
            }
        }
    }
}
