<?php

namespace App\Services;

use Config\Services;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $redis;

    public function __construct()
    {
        // Armazenar todas as conexões WebSocket
        $this->clients = new \SplObjectStorage();

        // Carregar o cache com o Redis configurado
        $this->redis = Services::cache();  // Usa o Redis com o prefixo 'ci4_'
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);

        // Gravar uma nova entrada no Redis com o prefixo 'ci4_'
        $this->redis->save('connected_' . $conn->resourceId, 'Cliente conectado', 3600);  // Expiração de 1 hora

        echo "Nova conexão estabelecida: ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo "Mensagem recebida: $msg\n";

        // Enviar a mensagem para todos os clientes conectados
        foreach ($this->clients as $client) {
            $client->send(json_encode(['message' => $msg]));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);

        // Remover a chave relacionada à conexão do Redis
        $this->redis->delete('connected_' . $conn->resourceId);

        echo "Conexão ({$conn->resourceId}) foi desconectada\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Erro: {$e->getMessage()}\n";
        $conn->close();
    }
}