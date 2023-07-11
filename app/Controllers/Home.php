<?php

namespace App\Controllers;

use App\Controllers\BaseController;

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
        $this->googleClient->setRedirectUri('http://localhost:8080/login');
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }
    
    public function index()
    {
        $data['link'] = $this->googleClient->createAuthUrl();
        return view('login/index', $data);
    }

    public function home()
    {
        return view('home/index');
    }

    public function loginWithGoogle()
    {
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar('code'));
        
        if(!isset($token['error'])){
            $this->googleClient->setAccessToken($token['access_token']);
            session()->set('AccessToken', $token['access_token']);
            
            $googleService = new \Google_Service_Oauth2($this->googleClient);
            $data = $googleService->userinfo->get();
            // echo "<pre>"; print_r($data);die;

            $currentDateTime = date("Y-m-d H:i:s");
            $userData = array();
            if($this->user->isAlreadyRegister($data['email']))
            {
                $userData = [
                    'email'=>$data['email'],
                    'updated_at'=>$currentDateTime
                ];
                $this->user->updateUserData($userData, $data['email']);
            }
            else
            {
                session()->setFlashData('error', "User Not Registered");
                return redirect()->to(base_url());
            }
        }
        else 
        {
            session()->setFlashData('error', "Something went Wrong");
            return redirect()->to(base_url());
        }
        return redirect()->to(base_url()."home");
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
