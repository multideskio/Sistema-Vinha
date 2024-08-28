<?php namespace App\Controllers;


use CodeIgniter\Controller;

class Health extends Controller
{
    public function index()
    {
        return $this->response->setStatusCode(200)->setJSON(['status' => 'ok']);
    }
}
