<?php

namespace App\Controllers;

use App\Models\RegioesModel;
use App\Models\UsuariosModel;

class Admin extends BaseController
{
    protected $modelConfig;
    protected $data;

    public function __construct()
    {
        $this->modelConfig = new \App\Models\AdminModel;
        $this->data['rowConfig'] = $this->modelConfig->cacheData();
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
        return view('admin/pages/regiao', $data);
    }

    public function gerentes(): string
    {

        $data['idSearch']  = null ;
        $data['titlePage'] = 'Gerentes';

        //
        return view('admin/pages/gerentes', $data);
    }
    
    public function gerente($id): string
    {
        $data['idSearch']  = $id ;
        $data['titlePage'] = 'Alterando Gerente';

        //
        return view('admin/pages/gerentesUpdate', $data);
    }

    public function supervisores(): string
    {
        $data['idSearch']  = null ;
        $data['titlePage'] = 'Supervisores';
        //
        return view('admin/pages/supervisores', $data);
    }

    public function supervisor($id): string
    {
        $data['idSearch']  = $id ;
        $data['titlePage'] = 'Alterando supervisores';
        //
        return view('admin/pages/supervisores', $data);
    }

    public function pastores(): string
    {
        $data['idSearch']  = null ;
        $data['titlePage'] = 'Pastores';

        //
        return view('admin/pages/pastores', $data);
    }

    public function pastor($id): string
    {
        $data['idSearch']  = $id ;
        $data['titlePage'] = 'Alterando Pastor';
        //
        return view('admin/pages/pastores', $data);
    }

    public function igrejas(): string
    {
        $data['idSearch']  = null ;
        $data['titlePage'] = 'Igrejas';

        //
        return view('admin/pages/igrejas', $data);
    }

    public function igreja($id): string
    {
        $data['idSearch']  = $id ;
        $data['titlePage'] = 'Alterando Igreja';
        //
        return view('admin/pages/igrejas', $data);
    }

    /*public function usuarios(): string
    {
        $data['titlePage'] = 'Usuários';
        
        //
        return view('admin/pages/usuarios', $data);
    }

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
}
