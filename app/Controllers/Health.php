<?php namespace App\Controllers;

use App\Models\TransacoesModel;
use CodeIgniter\Controller;

class Health extends Controller
{
    public function index()
    {
        //
        $modelTrans = new TransacoesModel();
        
        return $modelTrans->verificarEnvioDeLembretes();
        
        
        
        //return $this->response->setStatusCode(200)->setBody('OK');
    }
}
