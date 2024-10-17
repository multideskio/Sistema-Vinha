<?php

namespace App\Libraries;

use Exception;
use WebSocket\Client;

class WebSocketLibrary
{
    public function sendMessage($message): void
    {
        try {
            // Criar um cliente WebSocket e conectar à URL do servidor WebSocket
            //localhost:8088
            $client = new Client("ws://wss.conect.app");

            // Certifique-se de que a mensagem está sendo enviada como JSON
            $payload = json_encode($message, JSON_THROW_ON_ERROR);

            // Enviar a mensagem via WebSocket
            $client->send($payload);

            // Fechar a conexão
            $client->close();
        } catch (Exception $e) {
            log_message('error', "Erro ao enviar mensagem para o WebSocket: " . $e->getMessage());
        }
    }
}