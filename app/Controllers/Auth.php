<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Google_Client;

class Auth extends BaseController
{
    protected $request;
    protected $session;
    protected $googleClient;
    protected $user;
    
    function __construct()
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

    public function login()
    {
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar('code'));
        
        if(!isset($token['error'])){
            $this->googleClient->setAccessToken($token['access_token']);
            session()->set('AccessToken', $token['access_token']);
            
            $googleService = new \Google_Service_Oauth2($this->googleClient);
            $data = $googleService->userinfo->get();
            // echo "<pre>"; print_r($data);die;
            
            date_default_timezone_set('Asia/Jakarta');
            $currentDateTime = date("Y-m-d H:i:s");
            $userData = array();
            if($this->user->isAlreadyRegister($data['email']))
            {
                $userData = [
                    'screen_name'   => $data['givenName']." ".$data['familyName'],
                    'email'         => $data['email'],
                    'updated_at'    => $currentDateTime
                ];
                $this->user->updateUserData($userData, $data['email']);
                $id = $this->user->getId($userData['email']);
                session()->set('idUser', $id);
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

    function logout() 
    {
        session()->destroy();
        return redirect()->to(base_url());
    }
}
