<?php

namespace App\Workers;

use Predis\Client as RedisClient;
use Config\Redis as RedisConfig;

class RedisWorker
{
    protected $redis;

    public function __construct()
    {
        // Carrega as configurações do Redis
        $config = new RedisConfig();
        $this->redis = new RedisClient($config->default); // Conecta ao Redis com as configurações
    }

    public function listen()
    {
        log_message('info', 'Worker iniciado e escutando a fila de tarefas Redis.');

        while (true) {
            // Ouve a fila "jobs_queue" e aguarda por novos jobs
            $job = $this->redis->lpop('jobs_queue');

            if ($job) {
                log_message('info', 'Tarefa recebida da fila: ' . $job);
                $job = json_decode($job, true);

                try {
                    $handlerClass = $job['handler'];
                    $handler = new $handlerClass();

                    log_message('info', 'Iniciando o handler para a tarefa: ' . $handlerClass);
                    $handler->handle($job['data']);

                    log_message('info', 'Tarefa processada com sucesso: ' . $handlerClass);
                } catch (\Exception $e) {
                    log_message('error', 'Erro ao processar a tarefa: ' . $e->getMessage());
                }
            }

            // Dorme por um curto período para evitar consumir muitos recursos
            sleep(1);
        }
    }
}
