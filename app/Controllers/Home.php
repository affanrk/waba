<?php

namespace App\Controllers;

class Home extends BaseController
{
    protected $request;
    protected $session;
    protected $uri;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $this->uri = service('uri');
        $id = $this->uri->getSegment(3);
        $this->session->set(['idUser' => $id]);

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($id);

        $allUsers = $userModel->where('id!='.$id)->findAll();

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
