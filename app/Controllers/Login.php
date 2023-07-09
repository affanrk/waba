<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use Google_Client;

class Login extends BaseController
{
    protected $googleClient;
    protected $user;
    
    public function __construct()
    {
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

    public function process()
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
            return view('home/index');
        }
    }
}
