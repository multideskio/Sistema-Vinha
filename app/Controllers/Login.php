<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\UsuariosModel;

class Login extends BaseController
{
    protected AdminModel $modelConfig;
    //protected array $config;
    protected array $dataConfig;

    public function __construct()
    {
        $this->modelConfig = new AdminModel();
        //$this->config      = $this->modelConfig->searchCacheData(1);
        $this->dataConfig = $this->modelConfig->select('logo')->first();
    }

    public function index()
    {
        // Página inicial do login
    }

    public function novaconta(): string
    {

        $data['titlePage'] = 'Criar conta';
        $data['data']      = $this->dataConfig;

        return view('login/pages/nova-conta', $data);
    }

    public function novasenha($token): string
    {
        $data['data'] = $this->dataConfig;

        if ($row = $this->getUserByToken($token)) {
            $data['titlePage'] = 'Nova senha';
            $data['token']     = $token;

            return view('login/pages/nova-senha', $data);
        }
        $data['titlePage'] = 'Token inválido ou expirado';

        return view('login/pages/expirado', $data);
    }

    public function primeiroAcesso($token): string
    {
        $data['data'] = $this->dataConfig;

        if ($row = $this->getUserByToken($token)) {
            $data['titlePage'] = 'Nova senha';
            $data['token']     = $token;

            return view('login/pages/nova-senha', $data);
        }
        $data['titlePage'] = 'Token inválido ou expirado';

        return view('login/pages/expirado', $data);
    }

    public function recuperacao(): string
    {
        $data['titlePage'] = 'Recuperar conta';
        $data['data']      = $this->dataConfig;

        $this->cachePage(getCacheExpirationTimeInSeconds(2));

        return view('login/pages/recupera', $data);
    }

    // Confirma a verificação de e-mail usando o token
    public function confirmacao($token = null): string
    {
        $data['data'] = $this->dataConfig;

        if ($row = $this->getUserByToken($token)) {
            $modelUser = new UsuariosModel();
            $modelUser->update($row['id'], ['confirmado' => 1]);
            $data['titlePage'] = 'Confirmação de e-mail bem-sucedida';

            return view('login/pages/confirmacao', $data);
        }
        $data['titlePage'] = 'Erro ao confirmar e-mail';

        return view('login/pages/expirado', $data);
    }

    // Função utilitária para buscar usuário pelo token
    private function getUserByToken(string $token)
    {
        if ($token) {
            $modelUser = new UsuariosModel();

            return $modelUser->where('token', $token)->first();
        }

        return null;
    }
}