<?php namespace App\Controllers;

use App\Models\TransacoesModel;
use CodeIgniter\Controller;

class Health extends Controller
{
    public function index()
    {
        //
        return $this->response->setStatusCode(200)->setBody('OK');
    }
}
