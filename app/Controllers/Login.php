<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use Google_Client;

class Login extends BaseController
{
    protected $googleClient;

    public function __constract()
    {
        $this->googleClient = new Google_Client();
        
        $this->googleClient->setClientId('408926188996-5q0ituekcge81jcql8spjc2m0g8a7u8s.apps.googleusercontent.com');
        $this->googleClient->setClientSecret('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');
        $this->googleClient->setRedirectUri('http://localhost:8080/login/proses');
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    public function index()
    {
        // $data['link'] = $this->googleClient->createAuthUrl();
        return view('login/index');
    }
}
