<?php
namespace App\Controllers;

class Gerente extends BaseController
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
        echo "Gerente";
    }
}
