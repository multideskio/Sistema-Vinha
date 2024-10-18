<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\AjudaModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Home extends BaseController
{
    protected AdminModel $modelConfig;
    protected array $data;
    protected array $dataConfig;
    protected AjudaModel $modelAjuda;

    public function __construct()
    {
        $this->modelConfig = new AdminModel();
        $this->dataConfig  = $this->modelConfig->select('logo')->first();

        $this->modelAjuda = new AjudaModel();

        //$this->data['rowConfig']  = $this->modelConfig->searchCacheData(1);
        $this->data['textResult'] = "";
        $this->data['titlePage']  = "";
    }

    public function index()
    {

        $this->cachePage(60);

        return view('login/pages/login', [
            'data'      => $this->dataConfig,
            'titlePage' => 'Entrar',
        ]);
    }

    public function busca_ajuda()
    {
        //$this->cachePage(getCacheExpirationTimeInSeconds(60));

        if ($this->request->getGet('search')) {
            $rows = $this->modelAjuda->groupStart()
                ->like('titulo', $this->request->getGet('search'))
                ->orLike('tags', $this->request->getGet('search'))
                ->orLike('conteudo', $this->request->getGet('search'))
                ->groupEnd()
                ->findAll();

            $this->data['rows'] = $rows;

            if (count($rows) == 1) {
                $this->data['textResult'] = "<h1 class='text-primary mt-3'>1 resultado encontrado!</h1>";
            } elseif (count($rows) > 1) {
                $tt                       = count($rows);
                $this->data['textResult'] = "<h1 class='text-primary mt-3'>{$tt} resultados encontrados!</h1>";
            } else {
                $this->data['textResult'] = "<h1 class='text-danger mt-3'>Nenhum resultado encontrado!</h1>";
            }
        } else {
            $this->data['rows'] = $this->modelAjuda->findAll();
        }
        helper('text');

        return view('ajuda/home', $this->data);
    }

    public function ajuda($slug)
    {

        helper('auxiliar');
        $cache = \Config\Services::cache();

        if (!$cache->get($slug)) {
            $row = $this->modelAjuda->where('slug', $slug)->first();
            $cache->save($slug, $row, getCacheExpirationTimeInSeconds(30));
        } else {
            $row = $cache->get($slug);
        }

        if (!$row) {
            throw PageNotFoundException::forPageNotFound();
        }

        $this->data['result'] = $row;

        return view('ajuda/post', $this->data);
    }

    public function sair()
    {
        session_destroy();

        return redirect()->to(site_url());
    }

    public function teste(): void
    {

    }

    public function phpinfo()
    {
        phpinfo();
    }
}