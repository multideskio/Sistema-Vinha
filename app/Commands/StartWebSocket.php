<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use App\Services\WebSocketServer;

class StartWebSocket extends BaseCommand
{
    protected $group       = 'WebSocket';
    protected $name        = 'ws:start';
    protected $description = 'Inicia o servidor WebSocket';

    public function run(array $params)
    {
        // Configuração do servidor WebSocket
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new WebSocketServer()  // Instância do WebSocketServer
                )
            ),
            8081 // Porta do WebSocket
        );

        CLI::write('Servidor WebSocket rodando na porta 8081...', 'green');
        $server->run();
    }
}
