<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index', ['filter' => 'verifyLogged']);
$routes->get('sair', 'Home::sair');
$routes->get('teste', 'Home::teste');
$routes->get('phpinfo', 'Home::phpinfo');
$routes->get('ajuda', 'Home::busca_ajuda');
$routes->get('ajuda/(:any)', 'Home::ajuda/$1');


/*$routes->get('pix', 'Home::pix');
$routes->get('debito', 'Home::debito');
$routes->get('credito', 'Home::credito');
$routes->match(['get', 'post', 'put', 'delete', 'options', 'patch'], 'teste', 'Home::teste');*/

$routes->group('api/v1', ['namespace' => '\App\Controllers\Apis\V1'], static function ($routes) {

    $routes->post('administracao/update/upload/(:num)', 'Administracao::foto/$1',       ['filter' => 'logged']);
    $routes->put('administracao/update/links/(:num)',   'Administracao::links/$1',      ['filter' => 'logged']);
    $routes->put('administracao/update/info/(:num)',    'Administracao::updateInfo/$1', ['filter' => 'logged']);
    $routes->put('administracao/update/smtp/(:num)',    'Administracao::updateSmtp/$1', ['filter' => 'logged']);
    $routes->put('administracao/update/wa/(:num)',      'Administracao::updateWa/$1',   ['filter' => 'logged']);

    $routes->resource('administracao'); //['filter' => 'logged']

    $routes->post('email/teste', 'Emails::teste');
    $routes->resource('emails', ['filter' => 'logged']);
    $routes->resource('ajuda', ['filter' => 'logged']);

    
    $routes->get('transacoes/user', 'Transacoes::usuario', ['filter' => 'logged']);

    $routes->resource('transacoes', ['filter' => 'logged']);

    $routes->post('gerentes/update/upload/(:num)', 'Gerentes::foto/$1');
    $routes->put('gerentes/update/links/(:num)', 'Gerentes::links/$1');

    $routes->get('gerentes/list', 'Gerentes::list');
    $routes->resource('gerentes', ['filter' => 'logged']);

    $routes->post('igrejas/update/upload/(:num)', 'Igrejas::foto/$1');
    $routes->put('igrejas/update/links/(:num)', 'Igrejas::links/$1');

    $routes->get('igrejas/list', 'Igrejas::list');
    $routes->resource('igrejas', ['filter' => 'logged']);

    $routes->resource('logs', ['filter' => 'logged']);

    $routes->get('regioes/list', 'Regioes::list');
    $routes->resource('regioes', ['filter' => 'logged']);

    $routes->post('supervisores/update/upload/(:num)', 'Supervisores::foto/$1');
    $routes->put('supervisores/update/links/(:num)', 'Supervisores::links/$1');

    $routes->get('supervisores/list', 'Supervisores::list');
    $routes->resource('supervisores', ['filter' => 'logged']);


    $routes->post('pastores/update/upload/(:num)', 'Pastores::foto/$1');
    $routes->put('pastores/update/links/(:num)', 'Pastores::links/$1');

    $routes->resource('pastores', ['filter' => 'logged']);

    $routes->get('cielo/cron', 'Cielo::cron');
    $routes->post('cielo/credit-card-charge', 'Cielo::createCreditCardCharge');
    $routes->post('cielo/debit-card-charge', 'Cielo::createDebitCardCharge');
    $routes->post('cielo/boleto-charge', 'Cielo::createBoletoCharge');
    $routes->post('cielo/pix-charge', 'Cielo::createPixCharge');
    $routes->get('cielo/payment-status/(:segment)', 'Cielo::checkPaymentStatus/$1');

    $routes->resource('gateways', ['filter' => 'logged']); //['filter' => 'logged']


    //Acçoes de login e usuários  
    $routes->get('usuarios/user', 'Usuarios::userData', ['filter' => 'logged']);
    $routes->resource('usuarios', ['filter' => ['logged', 'admin']]); //, ['filter' => 'logged']

    $routes->get('google', 'Usuarios::google');
    $routes->match(['post', 'get'], 'authenticate', 'Usuarios::authenticate');
    $routes->get('confirmacao/(:any)', 'Usuarios::confirmacao/$1');
    
    //(:any)
    $routes->resource('whatsapp', ['filter' => 'logged']);
});


//Acesso de quem administra o sistema
$routes->group('admin', ['filter' => ['logged', 'admin']], static function ($routes) {
    $routes->get('',                    'Admin::index');
    $routes->get('home',                'Admin::index');
    $routes->get('regiao',              'Admin::regiao');

    $routes->get('gerentes',           'Admin::gerentes');
    $routes->get('gerente/(:num)',     'Admin::gerente/$1');
    
    $routes->get('supervisores',        'Admin::supervisores');
    $routes->get('supervisor/(:num)', 'Admin::supervisor/$1');
    $routes->get('pastores',            'Admin::pastores');
    $routes->get('pastor/(:num)',     'Admin::pastor/$1');
    $routes->get('igrejas',             'Admin::igrejas');
    $routes->get('igreja/(:num)',      'Admin::igreja/$1');
    
    $routes->get('usuarios',        'Admin::usuarios');

    $routes->get('recebimento',  'Admin::recebimento');
    $routes->get('retorno',      'Admin::retorno');
    $routes->get('remessa',      'Admin::remessa');

    $routes->get('gateways',      'Admin::gateways');
    $routes->get('ajuda',      'Admin::ajuda');

    $routes->get('config', 'Admin::config');
});




//Acesso de quem administra uma supervisao
$routes->group('supervisao', ['filter' => 'supervisor'], static function ($routes) {
    $routes->get('', 'Supervisor::index');
});



//Acesso mais baixo de quem dizima
$routes->group('igreja', ['filter' => 'igreja'], static function ($routes) {
    $routes->get('', 'Igreja::index');
    $routes->get('pagamentos', 'Igreja::pagamentos');
    $routes->get('transacoes', 'Igreja::transacoes');
});


$routes->group('gerente', ['filter' => 'gerente'],static function ($routes) {
    $routes->get('', 'Gerente::index');
});




//Login
$routes->group('login', static function ($routes) {
    $routes->get('', 'Login::index');
    $routes->get('nova-conta', 'Login::novaconta');
    $routes->get('reset', 'Login::index');

    //Confirma
    $routes->get('confirmacao/(:any)', 'Login::confirmacao/$1');
});
