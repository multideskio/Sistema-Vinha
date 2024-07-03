<?php

namespace App\Controllers;

use App\Models\UsuariosModel;

class Login extends BaseController
{
    protected $modelConfig;
    protected $config;

    public function __construct()
    {

        $this->modelConfig = new \App\Models\AdminModel;
        $this->config = $this->modelConfig->searchCacheData(1);
    }

    public function index()
    {
        //
    }

    public function novaconta(): string
    {
        $data['titlePage'] = 'Criar conta';
        $data['rowConfig'] = $this->config;
        return view('login/nova', $data);
    }

    //Apenas avisa que a verificação foi realizada
    public function confirmacao($token = null): string
    {
        if ($token) {
            $modelUser = new UsuariosModel();
            $row = $modelUser->where('token', $token)->findAll();
            if (count($row)) {
                $modelUser->update($row[0]['id'], ['confirmado' => 1]);
                $data['titlePage'] = 'Confirma e-mail';
                $data['rowConfig'] = $this->config;
                return view('login/confirm/sucesso', $data);
            } else {
                $data['titlePage'] = 'Confirma e-mail';
                $data['rowConfig'] = $this->config;
                return view('login/confirm/erro', $data);
            }
        } else {
            $data['titlePage'] = 'Confirma e-mail';
            $data['rowConfig'] = $this->config;
            return view('login/confirm/erro', $data);
        }
    }
}
