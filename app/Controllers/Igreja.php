<?php

namespace App\Controllers;



class Igreja extends BaseController
{
    protected $modelConfig;
    protected $data;
    public function __construct()
    {
        $this->modelConfig = new \App\Models\AdminModel;
        //$this->data['rowConfig'] = $this->modelConfig->cacheData();
    }
    public function index(): string
    {
        $data['titlePage'] = "Dashboard";
        return view('igrejas/pages/home', $data);
    }
    public function pagamentos(): string
    {
        $data['titlePage'] = "Dashboard";
        return view('igrejas/pages/gerar', $data);
        
    }
    public function transacoes(): string
    {
        $data['titlePage'] = "Transações";
        return view('igrejas/pages/transacoes', $data);
    }
}
