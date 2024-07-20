<?php
namespace App\Controllers;

class Gerente extends BaseController
{
    protected $modelConfig;
    protected $data;

    public function __construct()
    {
        $this->modelConfig = new \App\Models\AdminModel;

    }
    
    public function index()
    {
        //
        $data['titlePage'] = "Dashboard";
        return view('gerentes/pages/home', $data);
    }

    public function pagamentos(): string
    {
        $data['titlePage'] = "Dashboard";
        return view('gerentes/pages/gerar', $data);
        
    }
}
