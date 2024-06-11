<?php

namespace App\Controllers;

class Login extends BaseController
{
    protected $modelConfig;
    protected $data;

    public function __construct()
    {
        $this->modelConfig = new \App\Models\AdminModel;
        $this->data['rowConfig'] = $this->modelConfig->cacheData();
    }

    public function index()
    {
        //
    }

    public function novaconta(): string{
        return view('login/nova', $this->data);
    }

    //Apenas avisa que a verificação foi realizada
    public function confirmacao(): string{
        return view('login/confirmacao', $this->data);
    }
}
