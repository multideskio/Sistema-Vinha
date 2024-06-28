<?php

namespace App\Controllers;

class Login extends BaseController
{
    protected $modelConfig;
    protected $config;

    public function __construct()
    {

        $this->modelConfig = new \App\Models\AdminModel;
        $this->config = $this->modelConfig->searchCacheData();
    }

    public function index()
    {
        //
    }

    public function novaconta(): string{
        $data['rowConfig'] = $this->config ;
        return view('login/nova', $data);
    }

    //Apenas avisa que a verificação foi realizada
    public function confirmacao(): string{
        $data['rowConfig'] = $this->config ;
        return view('login/confirmacao', $data);
    }
}
