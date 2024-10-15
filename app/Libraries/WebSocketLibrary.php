<?php

namespace App\Libraries;

use WebSocket\Client;

class WebSocketLibrary
{
    public function sendMessage($message)
    {
        try {
            // Criar um cliente WebSocket e conectar à URL do servidor WebSocket
            $client = new Client("ws://localhost:8081");

            // Certifique-se de que a mensagem está sendo enviada como JSON
            $payload = json_encode($message);

            // Enviar a mensagem via WebSocket
            $client->send($payload);

            // Fechar a conexão
            $client->close();
        } catch (\Exception $e) {
            log_message('error', "Erro ao enviar mensagem para o WebSocket: " . $e->getMessage());
        }
    }
}