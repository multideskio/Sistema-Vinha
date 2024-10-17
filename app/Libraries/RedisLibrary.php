<?php

namespace App\Libraries;

use Predis\Client;

class RedisLibrary
{
    protected Client $redis;

    public function __construct()
    {
        // Configuração da conexão com o Redis usando Predis e os dados fornecidos
        $this->redis = new Client([
            'scheme'   => 'tcp',
            'host'     => '5.161.224.162',  // IP do Redis
            'port'     => 6381,             // Porta do Redis
            'password' => null,             // Senha do Redis (se aplicável)
            'timeout'  => 600,                // Timeout da conexão
            'database' => 0,                // Banco de dados Redis (0 é o default)
        ]);
    }

    // Método para publicar mensagens no Redis (exemplo de notificação)
    public function publish($channel, $message): void
    {
        try {
            $this->redis->publish($channel, $message);
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
        }
    }

    // Método para definir uma chave no Redis
    public function set($key, $value): void
    {
        try {
            $this->redis->set($key, $value);
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
        }
    }

    // Método para obter uma chave do Redis
    public function get($key): ?string
    {
        return $this->redis->get($key);
    }
}
