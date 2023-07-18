<?php

namespace App\Controllers;

class Home extends Auth
{
    protected $roomModel;
    protected $user;
    
    function __construct()
    {
        parent::__construct();
        $this->roomModel = new \App\Models\RoomModel();
    }

    public function index()
    {
        $id = session()->get('idUser');
    
        $user = $this->user->find($id);
        $allUsers = $this->user->where('id!='.$id)->findAll();
    
        foreach ($allUsers as &$u) {
            $u->encryptedId = base64_encode($u->id);
        }
    
        return view('home/index', [
            'user' => $user,
            'allUsers' => $allUsers,
            'idUser' => $id,
        ]);
    }    

    function createRoom()
    {
        if ($this->request->isAJAX()) {
            $idCurrentUser = session()->get('idUser');
            $idReceiver = $this->request->getGet('contactId');
    
            $room = $this->roomModel->getRoomByUser([$idCurrentUser, $idReceiver]);

            return $this->response->setJSON($room);
        }
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

    function getChats()
    {
        if ($this->request->isAJAX())
        {
            $id_room = $this->request->getGet('roomId');

            $chatModel = new \App\Models\ChatModel();

            $chats = $chatModel->getChatsByRoom($id_room);

            return $this->response->setJSON($chats);
        }
    }
    
}
