<?php

namespace App\Controllers;

class Home extends Login
{
    // protected $request;
    protected $session;
    protected $uri;
    // protected $googleClient;
    protected $user;
    protected $roomModel;
    
    // public function __construct()
    // {
    //     $this->session = \Config\Services::session();
    //     $this->uri =  service('uri');  
    //     $this->user = new \App\Models\UserModel();
    //     $this->roomModel = new \App\Models\RoomModel();
    //     $this->googleClient = new Google_Client();
        
    //     $this->googleClient->setClientId('408926188996-5q0ituekcge81jcql8spjc2m0g8a7u8s.apps.googleusercontent.com');
    //     $this->googleClient->setClientSecret('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');
    //     $this->googleClient->setRedirectUri('http://localhost:8080/login');
    //     $this->googleClient->addScope('email');
    //     $this->googleClient->addScope('profile');
    // }
    
    // public function index()
    // {
    //     $data['link'] = $this->googleClient->createAuthUrl();
    //     return view('login/index', $data);
    // }

    public function index()
    {
        $id = $this->uri->getSegment(3);
        $this->session->set('idUser', $id);

        $user = $this->user->find($id);
        $allUsers = $this->user->where('id!='.$id)->findAll();

        return view('home/index', [
            'user'=>$user,
            'allUsers'=>$allUsers,
            'idUser' => $id,
        ]);
    }

    public function makeRoom()
    {
        if ($this->request->isAJAX()) {
            $idCurrentUser = $this->session->get('idUser');
            $idReceiver = $this->request->getGet('contactId');
    
            $room = $this->roomModel->getRoomByUser([$idCurrentUser, $idReceiver]);

            return $this->response->setJSON($room);
        }
    }

    public function sendMessage()
    {
        if ($this->request->isAJAX()) {
            $message = $this->request->getPost('message');
            $id_room = $this->request->getPost('id_room');
            $id_user = $this->session->get('idUser');
            
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

    public function getChats()
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
