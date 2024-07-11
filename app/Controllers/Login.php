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

        $this->cachePage(2000);
        return view('login/nova', $data);
    }

    public function novasenha($token): string
    {
        $data['rowConfig'] = $this->config;

        if ($token) {
            $modelUser = new UsuariosModel();
            $row = $modelUser->where('token', $token)->first();

            if ($row) {
                $data['titlePage'] = 'Nova senha';
                $data['token'] = $token;
                return view('login/novasenha', $data);
            }
        }

        $data['titlePage'] = 'Erro';
        return view('login/confirm/erro', $data);
    }


    public function recuperacao(): string
    {

        $data['titlePage'] = 'Recuperar conta';
        $data['rowConfig'] = $this->config;

        $this->cachePage(2000);
        return view('login/recover', $data);
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
