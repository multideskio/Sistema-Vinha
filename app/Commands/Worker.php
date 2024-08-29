<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Predis\Client as RedisClient;
use Config\Redis  as RedisConfig;

class Worker extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'worker:start';
    protected $description = 'Inicia o worker para processar tarefas em segundo plano';
    protected $usage       = 'worker:start';

    protected $redis;

    public function __construct()
    {
        // Carrega as configurações do Redis
        $config      = new RedisConfig();
        $this->redis = new RedisClient($config->default); // Conecta ao Redis com as configurações
    }

    // File: app/Commands/Worker.php

    public function run(array $params)
    {
        CLI::write('Worker iniciado...', 'green');

        // Loop para processar as tarefas em segundo plano
        while (true) {
            // Pega a próxima tarefa da fila 'jobs_queue'
            $job = $this->redis->lpop('jobs_queue');

            // Se houver um job, processa
            if ($job) {
                CLI::write('Job encontrado na fila.', 'yellow');
                $job = json_decode($job, true);

                if (!$job || !isset($job['handler'], $job['data'])) {
                    CLI::write("Job inválido ou mal formado: " . print_r($job, true), 'red');
                    continue;
                }

                $handlerClass = $job['handler'];
                CLI::write("Processando handler: " . $handlerClass, 'yellow');

                if (class_exists($handlerClass)) {
                    try {
                        $handler = new $handlerClass();
                        $handler->handle($job['data']);
                        CLI::write("Tarefa processada com sucesso.", 'green');
                    } catch (\Exception $e) {
                        CLI::write("Erro ao processar o handler: " . $e->getMessage(), 'red');
                        log_message('error', 'Erro ao processar o handler: ' . $e->getMessage());
                    }
                } else {
                    CLI::write("Handler não encontrado: " . $handlerClass, 'red');
                    log_message('error', 'Handler não encontrado: ' . $handlerClass);
                }
            } else {
                CLI::write('Nenhum job encontrado na fila.', 'yellow');
            }

            // Pequena pausa para evitar uso excessivo de recursos
            sleep(10);
        }
    }
}
