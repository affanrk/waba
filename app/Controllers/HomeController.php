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
        $allUsers = $this->userModel->getAllUsers($id);
        $this->addEncryptedIds($allUsers);
        $this->getLastChatData($allUsers, $id);

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

    public function uploadMedia()
    {
        $media = $this->request->getFile('media-upload');
        if ($media->isValid() && !$media->hasMoved()) {
            $randomName = $media->getRandomName();
            $extension = $media->getExtension();
            $newName = $randomName . '.' . $extension;
    
            $media->move(ROOTPATH . 'public/uploads', $newName);
            
            $chatMedia = [
                'media' => $newName, // Tambahkan media dalam respons
            ];
    
            return $this->response->setJSON($chatMedia);
        }
    }
    
    public function sendMessage()
    {
        if ($this->request->isAJAX()) {
            $message = $this->request->getPost('message');
            $roomId = $this->request->getPost('id_room');
            $userId = $this->getCurrentUserId();
            $media = $this->request->getPost('media'); // Ambil nilai media dari request
            
            $chat = new Chat();
            $chat->id_room = $roomId;
            $chat->id_user = $userId;
            $chat->message = $message;
            $chat->media = $media; // Set nilai media
            $chat->is_active = 1;
            $chat->created_at = date("Y-m-d H:i:s");
            
            $this->chatModel->save($chat);
            
            $chatMessage = [
                'created_at' => $chat->created_at,
                'message' => $message,
                'media' => $media, // Tambahkan media dalam respons
            ];
    
            return $this->response->setJSON($chatMessage);
        }
    }    

    private function getCurrentUserId()
    {
        return $this->session->get('idUser');
    }

    private function addEncryptedIds(&$allUsers)
    {
        foreach ($allUsers as &$u) {
            $u->encryptedId = base64_encode($this->encrypter->encrypt($u->id));
        }
    }

    private function getLastChatData(&$allUsers, $currentUserId)
    {
        foreach ($allUsers as $u) {
            $otherUserId = $u->id;
            $roomId = $this->chatModel->getRoomId($currentUserId, $otherUserId);
    
            if ($roomId) {
                $lastMessageData = $this->chatModel->getLastMessage($roomId, $currentUserId, $otherUserId);
                $message = $lastMessageData->message;
                $media = $lastMessageData->media;
                $unformattedTime = $lastMessageData->unformattedTime;
                $formattedTime = $lastMessageData->formattedTime;
    
                
                if ($message === '' && $media !== '') {
                    $u->last_message = 'image';
                } else if ($media === '') {
                    $limitedMessage = strlen($message) > 20 ? substr($message, 0, 20) . "..." : $message;
                    $u->last_message = $limitedMessage;
                }
                $u->last_chat_time = $formattedTime;
                $u->unformatted_last_chat_time = $unformattedTime;
            }
        }
    
        usort($allUsers, function ($a, $b) {
            $aTimestamp = strtotime($a->unformatted_last_chat_time);
            $bTimestamp = strtotime($b->unformatted_last_chat_time);
            return $bTimestamp - $aTimestamp;
        });
    }
}
    
