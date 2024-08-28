<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Predis\Client as RedisClient;
use App\Jobs\GenerateReportJob;
use Config\Redis as RedisConfig;

class Worker extends BaseCommand
{
    protected $group = 'App';
    protected $name = 'worker:start';
    protected $description = 'Inicia o worker para processar tarefas em segundo plano';
    protected $usage = 'worker:start';

    protected $redis;

    public function __construct()
    {
        // Carrega as configurações do Redis
        $config = new RedisConfig();
        $this->redis = new RedisClient($config->default); // Conecta ao Redis com as configurações
    }

    public function run(array $params)
    {
        CLI::write('Iniciando o worker...', 'green');

        // Loop para processar as tarefas em segundo plano
        while (true) {
            // Pega a próxima tarefa da fila 'jobs_queue'
            $job = $this->redis->lpop('jobs_queue');

            // Se houver um job, processa
            if ($job) {
                $job = json_decode($job, true);
                $handlerClass = $job['handler'];

                if (class_exists($handlerClass)) {
                    $handler = new $handlerClass();
                    $handler->handle($job['data']);
                    CLI::write("Tarefa processada com sucesso.", 'green');
                } else {
                    CLI::write("Handler não encontrado: " . $handlerClass, 'red');
                }
            }

            // Pequena pausa para evitar uso excessivo de recursos
            sleep(1);
        }
    }
}
