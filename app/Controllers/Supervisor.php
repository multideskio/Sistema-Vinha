<?php
namespace App\Controllers;

class Supervisor extends BaseController
{
    protected $modelConfig;
    protected $data;

    public function __construct()
    {
        $this->modelConfig = new \App\Models\AdminModel;
        //$this->data['rowConfig'] = $this->modelConfig->cacheData();
    }
    
    public function index()
    {
        //
        $data['titlePage'] = "Dashboard";
        return view('supervisores/pages/home', $data);
    }
}
