<?php

namespace App\Libraries;

use App\Models\AdminModel;
use CodeIgniter\Log\Logger;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;

class WhatsappLibraries
{
    protected $apiUrl;
    protected $instance;
    protected $apikey;
    protected array $headers;
    protected Client $client;
    protected AdminModel $modelAdmin;
    protected Logger $logger;

    public function __construct(Client $client = null, AdminModel $modelAdmin = null, Logger $logger = null)
    {
        $this->modelAdmin = $modelAdmin ?? new AdminModel();
        $this->client     = $client     ?? new Client();
        $this->logger     = $logger     ?? \Config\Services::logger();

        log_message('info', 'Construtor chamado.');

        $dataSettings = $this->data();

        if (!$dataSettings) {
            log_message('warning', 'Nenhuma configuração da API Multidesk encontrada. Continuando sem API.');
            $this->apiUrl   = null;
            $this->apikey   = null;
            $this->instance = null;

            return;
        }

        $this->apiUrl   = rtrim($dataSettings['apiUrl'], '/');
        $this->apikey   = $dataSettings['apikey'];
        $this->instance = $dataSettings['instance'];
        $this->headers  = [
            'apikey'       => $this->apikey,
            'Content-Type' => 'application/json',
        ];

        log_message('info', 'Configuração carregada com sucesso.', [
            'apiUrl'   => $this->apiUrl,
            'instance' => $this->instance,
        ]);
    }

    public function data(): ?array
    {
        log_message('info', 'Método data chamado.');
        try {
            $row = $this->modelAdmin->first();
            log_message('info', 'Dados obtidos do modelo Admin.');

            if ($row && isset($row['url_api'], $row['instance_api'], $row['key_api'])) {
                log_message('info', 'Dados da API encontrados.', $row);

                return [
                    'apiUrl'   => $row['url_api'],
                    'instance' => $row['instance_api'],
                    'apikey'   => $row['key_api'],
                ];
            }

            log_message('warning', 'Dados da API não encontrados.');

            return null;
        } catch (Exception $e) {
            log_message('error', 'Erro ao obter dados: ' . $e->getMessage());

            return null;
        }
    }

    public function verifyNumber(array $message, string $number, string $tipo = 'text'): bool
    {
        if (!in_array($tipo, ['text', 'image', 'csv'])) {
            log_message('error', 'Tipo inválido fornecido: ' . $tipo);

            throw new InvalidArgumentException('Tipo deve ser "text" ou "image".');
        }

        log_message('info', 'Método verifyNumber chamado.', [
            'number' => $number,
            'tipo'   => $tipo,
        ]);

        try {
            log_message('info', 'Enviando requisição para verificar número.');
            $body = $this->postRequest('/chat/whatsappNumbers/' . $this->instance, [
                "numbers" => [$number],
            ]);

            if (isset($body['Code'], $body['Message'])) {
                throw new Exception("Erro {$body['Code']}: {$body['Message']}");
            }

            if ($body[0]['exists']) {
                log_message('info', 'Número verificado com sucesso.');

                if ($tipo === 'text') {
                    return $this->sendMessageText($message, $number);
                }

                if ($tipo === 'image') {
                    return $this->sendMessageImage($message, $number);
                }

                if ($tipo === 'csv') {
                    return $this->sendMessageCsv($message, $number);
                }
            } else {
                throw new Exception("Erro: O número não está registrado no WhatsApp");
            }
        } catch (Exception $e) {
            log_message('error', 'Erro em verifyNumber: ' . $e->getMessage());

            return false;
        }
    }

    public function sendMessageImage(array $message, string $number): bool
    {
        if (!$this->apiUrl || !$this->apikey || !$this->instance) {
            log_message('error', 'Configuração da API ausente. Não é possível enviar a imagem.');

            return false;
        }

        log_message('info', 'Método sendMessageImage chamado.', [
            'number'  => $number,
            'message' => $message,
        ]);

        try {
            $params = [
                "number"    => $number,
                "mediatype" => "image",
                "mimetype"  => "image/png",
                "caption"   => $message['message'],
                "media"     => $message['image'],
                "fileName"  => "imagem.png",
            ];

            log_message('info', 'Enviando imagem.', $params);
            $body = $this->postRequest('/message/sendMedia/' . $this->instance, $params);

            if (isset($body['Code'], $body['Message'])) {
                throw new Exception("Erro {$body['Code']}: {$body['Message']}");
            }

            log_message('info', 'Imagem enviada com sucesso.');

            return true;
        } catch (RequestException $e) {
            $response             = $e->getResponse();
            $responseBodyAsString = $response ? $response->getBody()->getContents() : 'Sem resposta';
            log_message('error', 'Erro na requisição sendMessageImage: ' . $responseBodyAsString);

            return false;
        }
    }

    public function sendMessageCsv(array $message, string $number): bool
    {
        if (!$this->apiUrl || !$this->apikey || !$this->instance) {
            log_message('error', 'Configuração da API ausente. Não é possível enviar o CSV.');

            return false;
        }

        log_message('info', 'Método sendMessageCsv chamado.', [
            'number'  => $number,
            'message' => $message,
        ]);

        try {
            $params = [
                "number"    => $number,
                "mediatype" => "document",
                "mimetype"  => "text/csv",
                "caption"   => $message['message'],
                "media"     => $message['csv'],
                "fileName"  => "relatorio.csv",
            ];

            log_message('info', 'Enviando CSV.', $params);
            $body = $this->postRequest('/message/sendMedia/' . $this->instance, $params);

            if (isset($body['Code'], $body['Message'])) {
                throw new Exception("Erro {$body['Code']}: {$body['Message']}");
            }

            log_message('info', 'CSV enviado com sucesso.');

            return true;
        } catch (RequestException $e) {
            $response             = $e->getResponse();
            $responseBodyAsString = $response ? $response->getBody()->getContents() : 'Sem resposta';
            log_message('error', 'Erro na requisição sendMessageCsv: ' . $responseBodyAsString);

            return false;
        }
    }

    public function sendMessageText(array $message, string $number): bool
    {
        if (!$this->apiUrl || !$this->apikey || !$this->instance) {
            log_message('error', 'Configuração da API ausente. Não é possível enviar a mensagem de texto.');

            return false;
        }

        log_message('info', 'Método sendMessageText chamado.', [
            'number'  => $number,
            'message' => $message,
        ]);

        try {
            $params = [
                "number"  => $number,
                "text"    => $message['message'],
                "options" => [
                    "delay"       => 600,
                    "presence"    => "composing",
                    "linkPreview" => true,
                ],
            ];

            log_message('info', 'Enviando texto.', $params);
            $body = $this->postRequest('/message/sendText/' . $this->instance, $params);

            if (isset($body['Code'], $body['Message'])) {
                throw new Exception("Erro {$body['Code']}: {$body['Message']}");
            }

            log_message('info', 'Texto enviado com sucesso.');

            return true;
        } catch (RequestException $e) {
            $response             = $e->getResponse();
            $responseBodyAsString = $response ? $response->getBody()->getContents() : 'Sem resposta';
            log_message('error', 'Erro na requisição sendMessageText: ' . $responseBodyAsString);

            return false;
        }
    }

    protected function postRequest(string $endpoint, array $data): ?array
    {
        if(empty($this->headers)) {
            return null;
        }

        log_message('info', 'Método postRequest chamado.', [
            'endpoint' => $endpoint,
            'data'     => $data,
        ]);

        try {
            $options = [
                'headers' => $this->headers,
                'json'    => $data,
            ];

            log_message('info', 'Enviando requisição POST para ' . $endpoint);

            $response = $this->client->post($this->apiUrl . $endpoint, $options);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            log_message('info', 'Resposta da requisição recebida.', $responseBody);

            return $responseBody;
        } catch (RequestException $e) {
            $response             = $e->getResponse();
            $responseBodyAsString = $response ? $response->getBody()->getContents() : 'Sem resposta';
            log_message('error', 'Erro na requisição postRequest: ' . $responseBodyAsString);

            return null;
        }
    }
}