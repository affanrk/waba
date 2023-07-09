<?php

namespace App\Controllers;

use Google_Client;

class Home extends BaseController
{
    protected $request;
    protected $session;
    protected $uri;
    protected $googleClient;
    protected $user;
    
    public function __construct()
    {
        $this->session = \Config\Services::session();    
        $this->user = new \App\Models\UserModel();
        $this->googleClient = new Google_Client();
        
        $this->googleClient->setClientId('408926188996-5q0ituekcge81jcql8spjc2m0g8a7u8s.apps.googleusercontent.com');
        $this->googleClient->setClientSecret('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');
        $this->googleClient->setRedirectUri('http://localhost:8080/login/process');
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }
    
    public function index()
    {
        $data['link'] = $this->googleClient->createAuthUrl();
        return view('login/index', $data);
    }

    public function login()
    {
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar('code'));
        
        if(!isset($token['error'])){
            $this->googleClient->setAccessToken($token['access_token']);
            $googleService = new \Google_Service_Oauth2($this->googleClient);
            $data = $googleService->userinfo->get();
            
            $row = [
                'id' => $data['id'],
                'email' => $data['email'],
                'screen_name' => $data['name'],
                'profile' => $data['picture'],
            ];
            $this->user->save($row);

            session()->set($row);
        }

        $this->uri = service('uri');
        $id = $this->uri->getSegment(3);
        $this->session->set(['idUser' => $id]);

        $user = $this->user->find($id);
        $allUsers = $this->user->where('id!='.$id)->findAll();

        return view('home/index', [
            'user'=>$user,
            'allUsers'=>$allUsers,
        ]);
    }
    
    public function getRoomByUser()
    {
        if($this->request->isAJAX()){
            $idCurrentUser = $this->session->get('idUser');
            $idReceiver = $this->request->getGet('contactId');

            $roomModel = new \App\Models\RoomModel();
            $room = $roomModel->getRoomByUser([$idCurrentUser, $idReceiver]);

            return $this->response->setJSON($room);
        }
    }
}
