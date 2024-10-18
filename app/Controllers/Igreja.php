<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\IgrejasModel;
use App\Models\PastoresModel;
use App\Models\UsuariosModel;

class Igreja extends BaseController
{
    protected AdminModel $modelConfig;
    protected $data;
    protected UsuariosModel $modelUsuarios;
    protected IgrejasModel $modelIgrejas;
    protected PastoresModel $modelPastores;

    /**
     * METHODOS RETORNANDOS DADOS DIRETO PARA A VIEW
     */
    public function __construct()
    {
        $this->modelConfig   = new AdminModel();
        $this->modelUsuarios = new UsuariosModel();
        $this->modelIgrejas  = new IgrejasModel();
        $this->modelPastores = new PastoresModel();
        //$this->data['rowConfig'] = $this->modelConfig->cacheData();
    }

    public function index(): string
    {
        $data['titlePage'] = "Dashboard";

        return view('igrejas/pages/home', $data);
    }

    /*public function pagamentos(): string
    {
        $data['titlePage'] = "Dashboard";

        return view('igrejas/pages/gerar', $data);
    }*/

    public function pix(): string
    {
        $data['titlePage'] = "Pix";

        return view('igrejas/pages/pix/home', $data);
    }

    public function boleto(): string
    {
        $data['titlePage'] = "Boleto";

        return view('igrejas/pages/boleto/home', $data);
    }

    public function credito(): string
    {
        $data['titlePage'] = "Cartão de Crédito";

        return view('igrejas/pages/credito/home', $data);
    }

    public function debito(): string
    {
        $data['titlePage'] = "Cartão de débito";

        return view('igrejas/pages/debito/home', $data);
    }

    public function transacoes(): string
    {
        $data['titlePage'] = "Transações";

        return view('igrejas/pages/transacoes', $data);
    }

    public function perfis(): string
    {
        $data['titlePage'] = "Seu perfil";
        $data['idSearch']  = session('data')['id_perfil'];

        if(session('data')['tipo'] === 'igreja') {
            //Perfil Igreja
            $data['data'] = $this->modelIgrejas->where('id', session('data')['id_perfil'])->first();

            return view('igrejas/pages/perfis/igreja', $data);
        } else {
            //Perfil Pastor
            return view('igrejas/pages/perfis/pastor', $data);
        }

    }
}