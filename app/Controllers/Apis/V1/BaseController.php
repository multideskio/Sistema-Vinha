<?php

namespace App\Controllers\Apis\V1;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class BaseController extends ResourceController
{
    use ResponseTrait;

    protected $request;
    protected $cache;
    protected $session;

    public function __construct()
    {
        $this->cache   = \Config\Services::cache();
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        helper('auxiliar');
    }
}
