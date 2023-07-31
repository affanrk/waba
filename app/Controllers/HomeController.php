<?php

namespace App\Controllers;

class HomeController extends AuthController
{
    protected $roomModel;
    protected $userModel;
    protected $chatModel;
    protected $encrypter;

    function __construct()
    {
        parent::__construct();
        $this->roomModel = new \App\Models\RoomModel();
        $this->chatModel = new \App\Models\ChatModel();
        $this->encrypter = \Config\Services::encrypter();
    }

    public function index()
    {
        $id = session()->get('idUser');

        $user = $this->userModel->find($id);
        $allUsers = $this->userModel->where('id!=' . $id)->findAll();
        
        foreach ($allUsers as $u) {
            $u->encryptedId = base64_encode($this->encrypter->encrypt($u->id));
        }
        
        // foreach ($allUsers as &$u) {
        //     $u->encryptedId = base64_encode($u->id);
        // }

        return view('home/index', [
            'user' => $user,
            'allUsers' => $allUsers,
            'idUser' => $id,
        ]);
    }

    function decryptUserId()
    {
        $encryptedId = $this->request->getPost('encryptedId');
        $decryptedId = $this->encrypter->decrypt(base64_decode($encryptedId));

        return $this->response->setJSON(['decryptedId' => $decryptedId]);
    }

    function getRoomByUser()
    {
        if ($this->request->isAJAX()) {
            $idCurrentUser = session()->get('idUser');
            $idReceiver = $this->request->getGet('contactId');

            $room = $this->roomModel->getRoomByUser([$idCurrentUser, $idReceiver]);

            return $this->response->setJSON($room);
        }
    }

    function getChatsByRoom()
    {
        if ($this->request->isAJAX()) {
            $id_room = $this->request->getGet('roomId');

            $chatModel = new \App\Models\ChatModel();

            $chats = $chatModel->getChatsByRoom($id_room);

            return $this->response->setJSON($chats);
        }
    }

    function getLastChatTimeByRoom()
    {
        //
    }

    function sendMessage()
    {
        if ($this->request->isAJAX()) {
            $message = $this->request->getPost('message');
            $id_room = $this->request->getPost('id_room');
            $id_user = session()->get('idUser');

            $modelChat = new \App\Models\ChatModel();
            $chat = new \App\Entities\Chat();

            date_default_timezone_set('Asia/Jakarta');
            $currentDateTime = date("Y-m-d H:i:s");

            $chat->id_room = $id_room;
            $chat->id_user = $id_user;
            $chat->message = $message;
            $chat->media = NULL;
            $chat->is_active = 1;
            $chat->created_at = $currentDateTime;

            $modelChat->save($chat);

            $chatMessage = [
                'created_at' => $currentDateTime,
                'message' => $message,
            ];

            return $this->response->setJSON($chatMessage);
        }
    }
}
