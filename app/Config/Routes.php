<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index', ['filter' => 'verifyLogged']);

$routes->get('health', 'Health::index');


$routes->get('nova-conta', 'Login::novaconta', ['filter' => 'csrf']);
$routes->get('recuperacao', 'Login::recuperacao', ['filter' => 'csrf']);

$routes->get('recupera/(:any)', 'Login::novasenha/$1', ['filter' => 'csrf']);
$routes->get('primeiro-acesso/(:any)', 'Login::primeiroAcesso/$1', ['filter' => 'csrf']);



$routes->get('sair', 'Home::sair');
$routes->get('teste', 'Home::teste');
$routes->get('phpinfo', 'Home::phpinfo');
$routes->get('ajuda', 'Home::busca_ajuda');

$routes->get('ajuda/(:any)', 'Home::ajuda/$1');

//$routes->get('confirma/(:any)', 'Home::index/$1');
$routes->get('confirma/(:any)', 'Login::confirmacao/$1');

/*$routes->get('pix', 'Home::pix');
$routes->get('debito', 'Home::debito');
$routes->get('credito', 'Home::credito');
$routes->match(['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'], 'teste', 'Home::teste');*/

$routes->group('api/v1/public', ['namespace' => '\App\Controllers\Apis\V1\Publica'], static function ($routes) {
    $routes->get('supervisor', 'Open::supervisor');
    $routes->post('pastor', 'Open::pastor');
    $routes->post('igreja', 'Open::igreja');
    $routes->post('recover', 'Open::recover');
    $routes->post('newpass', 'Open::newpass');
});

$routes->group('api/v1', ['namespace' => '\App\Controllers\Apis\V1'], static function ($routes) {

    $routes->post('administradores/update/upload/(:num)', 'Administradores::foto/$1');
    $routes->put('administradores/update/links/(:num)', 'Administradores::links/$1');
    $routes->resource('administradores');

    $routes->put('administracao/testwhatsapp', 'Administracao::testWhatsApp', ['filter' => 'logged']);

    $routes->post('administracao/update/upload/(:num)', 'Administracao::foto/$1', ['filter' => 'logged']);
    $routes->put('administracao/update/links/(:num)', 'Administracao::links/$1', ['filter' => 'logged']);
    $routes->put('administracao/update/info/(:num)', 'Administracao::updateInfo/$1', ['filter' => 'logged']);
    $routes->put('administracao/update/smtp/(:num)', 'Administracao::updateSmtp/$1', ['filter' => 'logged']);
    $routes->put('administracao/update/wa/(:num)', 'Administracao::updateWa/$1', ['filter' => 'logged']);
    $routes->put('administracao/update/s3/(:num)', 'Administracao::updateS3/$1', ['filter' => 'logged']);
    $routes->get('administracao/teste/s3', 'Administracao::testeS3', ['filter' => 'logged']);
    $routes->resource('administracao'); //['filter' => 'logged']

    $routes->post('email/teste', 'Emails::teste');
    $routes->resource('emails', ['filter' => 'logged']);
    $routes->resource('ajuda', ['filter' => 'logged']);


    $routes->group('transacoes', ['filter' => 'admin'], static function ($routes) {
        $routes->get('user', 'Transacoes::usuario');
        $routes->post('user/reembolso/(:any)', 'Transacoes::reembolso/$1');
        $routes->get('user/(:num)', 'Transacoes::adminUsers/$1');
        $routes->get('dashboard', 'Transacoes::dashboardAdmin');
        $routes->get('lista', 'Transacoes::ultimasTransacoes');
        $routes->match(['POST', 'GET'], 'relatorio', 'Transacoes::gerarRelatorio');
        $routes->get('relatorios/lista', 'Transacoes::listRelatorios');
    });

    $routes->resource('transacoes', ['filter' => 'logged']);

    $routes->post('gerentes/update/upload/(:num)', 'Gerentes::foto/$1');
    $routes->put('gerentes/update/links/(:num)', 'Gerentes::links/$1');
    $routes->get('gerentes/list', 'Gerentes::list');
    $routes->resource('gerentes', ['filter' => 'logged']);

    $routes->post('igrejas/update/upload/(:num)', 'Igrejas::foto/$1');
    $routes->put('igrejas/update/links/(:num)', 'Igrejas::links/$1');

    $routes->get('igrejas/dashboard', 'Igrejas::dashboard', ['filter' => 'igreja']);
    $routes->get('igrejas/list', 'Igrejas::list');
    $routes->resource('igrejas', ['filter' => 'logged']);

    $routes->resource('logs', ['filter' => 'logged']);

    $routes->get('regioes/list', 'Regioes::list');
    $routes->resource('regioes', ['filter' => 'logged']);

    $routes->post('supervisores/update/upload/(:num)', 'Supervisores::foto/$1');
    $routes->put('supervisores/update/links/(:num)', 'Supervisores::links/$1');

    $routes->get('supervisores/list', 'Supervisores::list');
    $routes->resource('supervisores', ['filter' => 'logged']);

    $routes->get('pastores/dashboard', 'Pastores::Dashboard', ['filter' => 'igreja']);

    $routes->post('pastores/update/upload/(:num)', 'Pastores::foto/$1', ['filter' => ['logged']]);
    $routes->put('pastores/update/links/(:num)', 'Pastores::links/$1', ['filter' => ['logged']]);
    $routes->resource('pastores', ['filter' => ['admin']]);

    $routes->get('cielo/cron', 'Cielo::cron');

    $routes->post('cielo/credit-card-charge', 'Cielo::createCreditCardCharge');
    $routes->post('cielo/debit-card-charge', 'Cielo::createDebitCardCharge');
    $routes->post('cielo/boleto-charge', 'Cielo::createBoletoCharge');
    $routes->post('cielo/pix-charge', 'Cielo::createPixCharge');
    $routes->get('cielo/payment-status/(:segment)', 'Cielo::checkPaymentStatus/$1');

    $routes->resource('gateways', ['filter' => 'logged']); //['filter' => 'logged']

    // Ações de login e usuários  
    $routes->get('usuarios/user', 'Usuarios::userData', ['filter' => 'logged']);
    $routes->resource('usuarios', ['filter' => ['logged', 'admin']]); //, ['filter' => 'logged']

    $routes->get('google', 'Usuarios::google');
    $routes->match(['POST', 'GET'], 'authenticate', 'Usuarios::authenticate');
    $routes->get('confirmacao/(:any)', 'Usuarios::confirmacao/$1');

    //(:any)
    $routes->resource('whatsapp', ['filter' => 'logged']);
});

// Acesso de quem administra o sistema
$routes->group('admin', ['filter' => ['logged', 'admin']], static function ($routes) {
    $routes->get('', 'Admin::index');
    $routes->get('home', 'Admin::index');
    $routes->get('regiao', 'Admin::regiao');

    $routes->get('gerentes', 'Admin::gerentes');
    $routes->get('gerente/(:num)', 'Admin::gerente/$1');

    $routes->get('supervisores', 'Admin::supervisores');
    $routes->get('supervisor/(:num)', 'Admin::supervisor/$1');
    $routes->get('pastores', 'Admin::pastores');
    $routes->get('pastor/(:num)', 'Admin::pastor/$1');
    $routes->get('igrejas', 'Admin::igrejas');
    $routes->get('igreja/(:num)', 'Admin::igreja/$1');

    $routes->get('admins', 'Admin::admins');
    $routes->get('admin/(:num)', 'Admin::admin/$1');
    $routes->get('superadmin/(:num)', 'Admin::admin/$1');

    /*$routes->get('recebimento', 'Admin::recebimento');
    $routes->get('retorno', 'Admin::retorno');
    $routes->get('remessa', 'Admin::remessa');*/

    $routes->get('relatorio', 'Admin::relatorio');

    $routes->get('gateways', 'Admin::gateways');
    $routes->get('ajuda', 'Admin::ajuda');

    $routes->get('config', 'Admin::config');

    $routes->get('perfil', 'Admin::perfil');
});

$routes->group('gerente', ['filter' => 'gerente'], static function ($routes) {
    $routes->get('', 'Gerente::index');
    $routes->get('pagamentos', 'Gerente::pagamentos');
    $routes->get('(:any)', 'Gerente::index');
});

// Acesso de quem administra uma supervisão
$routes->group('supervisao', ['filter' => 'supervisor'], static function ($routes) {
    $routes->get('', 'Supervisor::index');
    $routes->get('(:any)', 'Supervisor::index');
});

// Acesso mais baixo de quem dizima
$routes->group('igreja', ['filter' => 'igreja'], static function ($routes) {
    $routes->get('', 'Igreja::index');
    $routes->get('pagamentos', 'Igreja::pagamentos');
    $routes->get('transacoes', 'Igreja::transacoes');
    $routes->get('(:any)', 'Igreja::index');
});

// Login
$routes->group('login', static function ($routes) {
    $routes->get('', 'Login::index');
    $routes->get('reset', 'Login::index');

    // Confirma
    $routes->get('confirmacao/(:any)', 'Login::confirmacao/$1');
});
