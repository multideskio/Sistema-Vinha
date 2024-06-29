<?php

namespace App\Controllers;

class Admin extends BaseController
{
    protected $modelConfig;
    protected $data;

    public function __construct()
    {
        $this->modelConfig = new \App\Models\AdminModel;
      //  $this->data['rowConfig'] = $this->modelConfig->cacheData();
    }

    public function index(): string
    {

        $data['idSearch']  = null ;
        $data['titlePage'] = 'Dashboard';
        
        //
        return view('admin/pages/home', $data);
    }

    public function regiao(): string
    {

        $data['idSearch']  = null ;
        $data['titlePage'] = 'Regiões';
        //
        return view('admin/pages/regiao/regiao', $data);
    }

    public function gerentes(): string
    {

        $data['idSearch']  = null ;
        $data['titlePage'] = 'Gerentes';

        //
        return view('admin/pages/gerentes/lista', $data);
    }
    
    public function gerente($id): string
    {
        $data['idSearch']  = $id ;
        $data['titlePage'] = 'Alterando Gerente';

        //
        return view('admin/pages/gerentes/altera', $data);
    }

    public function supervisores(): string
    {
        $data['idSearch']  = null ;
        $data['titlePage'] = 'Supervisores';
        //
        return view('admin/pages/supervisores/lista', $data);
    }

    public function supervisor($id): string
    {
        $data['idSearch']  = $id ;
        $data['titlePage'] = 'Alterando supervisores';
        //
        return view('admin/pages/supervisores/altera', $data);
    }

    public function pastores(): string
    {
        $data['idSearch']  = null ;
        $data['titlePage'] = 'Pastores';

        //
        return view('admin/pages/pastores/lista', $data);
    }

    public function pastor($id): string
    {
        $data['idSearch']  = $id ;
        $data['titlePage'] = 'Alterando Pastor';
        //
        return view('admin/pages/pastores/altera', $data);
    }

    public function igrejas(): string
    {
        $data['idSearch']  = null ;
        $data['titlePage'] = 'Igrejas';

        //
        return view('admin/pages/igrejas/lista', $data);
    }

    public function igreja($id): string
    {
        $data['idSearch']  = $id ;
        $data['titlePage'] = 'Alterando Igreja';
        //
        return view('admin/pages/igrejas/altera', $data);
    }

    public function admins(): string
    {
        $data['idSearch']  = null ;
        $data['titlePage'] = 'Administradores';
        
        //
        return view('admin/pages/admins/lista', $data);
    }
    /*

    public function recebimento(): string
    {
        $data['titlePage'] = 'Recebimentos';
        //
        return view('admin/pages/home', $data);
    }

    public function retorno(): string
    {
        $data['titlePage'] = 'Retorno';
        //
        return view('admin/pages/home', $data);
    }

    public function remessa(): string
    {
        $data['titlePage'] = 'Remessa';
        //
        return view('admin/pages/home', $data);
    }*/

    public function gateways():string{
        $data['idSearch']  = null ;
        $data['titlePage'] = "Gateways";
        return view('admin/pages/gateways', $data);
    }

    public function ajuda():string{
        $data['idSearch']  = null ;
        $data['titlePage'] = "Ajuda";
        return view('admin/pages/ajuda', $data);
    }

    public function config():string{
        $data['idSearch']  = session('data')['idAdm'] ;
        $data['titlePage'] = "Configurações gerais";
        return view('admin/pages/admin/config', $data);
    }

    public function perfil():string{
        $data['idSearch']  = session('data')['idAdm'] ;
        $data['titlePage'] = "Meu perfil";
        $data['tipo']      = session('data')['tipo'];
        return view('admin/pages/user/perfil', $data);
    }
}
