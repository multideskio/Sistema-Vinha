<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Redis as RedisConfig;
use Predis\Client as RedisClient;

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
        $config = new RedisConfig();
        // Instancia o cliente Redis com as configurações fornecidas
        $this->redis = new RedisClient($config->default);
    }

    /**
     * Executa o worker que processa tarefas da fila em segundo plano.
     *
     * @param array $params Parâmetros passados para o comando (não utilizados).
     *
     * O loop principal do worker busca e processa as tarefas da fila 'jobs_queue'.
     * Se encontrar um job válido, ele tenta instanciar a classe do handler e executar o método handle.
     * Se a classe ou o método handle não estiverem disponíveis, registra um erro.
     */
    public function run(array $params)
    {
        CLI::write('Worker iniciado...', 'green'); // Mensagem de início do worker no CLI

        // Loop infinito para manter o worker ativo processando tarefas em segundo plano
        while (true) {
            // Tenta pegar a próxima tarefa da fila 'jobs_queue' no Redis
            $job = $this->redis->lpop('jobs_queue');

            // Verifica se há um job para processar
            if ($job) {
                CLI::write('Job encontrado na fila.', 'yellow');

                // Decodifica o job de JSON para array associativo
                $job = json_decode($job, true);

                // Verifica se o job é válido e contém as chaves esperadas 'handler' e 'data'
                if (!$job || !isset($job['handler'], $job['data'])) {
                    // Loga um erro caso o job esteja mal formado ou inválido
                    log_message('error', "Job inválido ou mal formado: " . print_r($job, true));
                    CLI::write("Job inválido ou mal formado: " . print_r($job, true), 'red');
                    continue; // Pula para a próxima iteração do loop
                }

                // Obtém o nome da classe do handler do job
                $handlerClass = $job['handler'];
                CLI::write("Processando handler: " . $handlerClass, 'yellow');

                // Verifica se a classe do handler existe
                if (class_exists($handlerClass)) {
                    try {
                        // Instancia a classe do handler
                        $handler = new $handlerClass();
                        // Executa o método handle passando os dados do job
                        $handler->handle($job['data']);
                        CLI::write("Tarefa processada com sucesso.", 'green');

                        cache()->deleteMatching("*_listSearchRelatorio");

                    } catch (\Exception $e) {
                        // Loga e mostra um erro se ocorrer uma exceção ao processar o handler
                        CLI::write("Erro ao processar o handler: " . $e->getMessage(), 'red');
                        log_message('error', 'Erro ao processar o handler: ' . $e->getMessage());
                    }
                } else {
                    // Loga e mostra um erro se o handler não for encontrado
                    CLI::write("Handler não encontrado: " . $handlerClass, 'red');
                    log_message('error', 'Handler não encontrado: ' . $handlerClass);
                }
            } else {
                // Mensagem quando não há jobs disponíveis na fila
                //CLI::write('Nenhum job encontrado na fila.', 'yellow');
            }

            // Pausa para evitar uso excessivo de recursos e dar tempo para novos jobs chegarem na fila
            sleep(10); // Pausa de 10 segundos
        }
    }
}