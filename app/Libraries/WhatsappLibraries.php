<?php

namespace App\Libraries;

use App\Models\AdminModel;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use CodeIgniter\Log\Logger;

class WhatsappLibraries
{
    protected $apiUrl;
    protected $instance;
    protected $apikey;
    protected $headers;
    protected $client;
    protected $modelAdmin;
    protected $logger;

    public function __construct(Client $client = null, AdminModel $modelAdmin = null, Logger $logger = null)
    {
        try {
            $this->modelAdmin = $modelAdmin ?? new AdminModel();
            $this->client = $client ?? new Client();
            $this->logger = $logger ?? \Config\Services::logger();

            $dataSettings = $this->data();
            if (!$dataSettings) {
                throw new Exception('Nenhuma configuração da api Multidesk encontrada.');
            }

            $this->apiUrl = rtrim($dataSettings['apiUrl'], '/');
            $this->apikey = $dataSettings['apikey'];
            $this->instance = $dataSettings['instance'];
            $this->headers = [
                'apikey' => $this->apikey,
                'Content-Type' => 'application/json'
            ];

            $this->logger->info('Configurações da API carregadas.', ['dataSettings' => $dataSettings]);
        } catch (Exception $e) {
            $this->logger->error('Erro no construtor: ' . $e->getMessage());
        }
    }

    public function data(): ?array
    {
        try {
            $row = $this->modelAdmin->first();

            if ($row && isset($row['url_api'], $row['instance_api'], $row['key_api'])) {
                $this->logger->info('Dados da API obtidos do banco de dados.', ['row' => $row]);
                return [
                    'apiUrl' => $row['url_api'],
                    'instance' => $row['instance_api'],
                    'apikey' => $row['key_api']
                ];
            }

            $this->logger->warning('Dados da API não encontrados no banco de dados.');
            return null;
        } catch (Exception $e) {
            $this->logger->error('Erro ao obter dados: ' . $e->getMessage());
            return null;
        }
    }

    public function verifyNumber($message, $number, $tipo)
    {
        try {
            $this->logger->info('Verificando número.', ['number' => $number, 'tipo' => $tipo]);

            $body = $this->postRequest('/chat/whatsappNumbers/' . $this->instance, [
                "numbers" => [$number]
            ]);

            if (isset($body['Code'], $body['Message'])) {
                throw new Exception("Erro {$body['Code']}: {$body['Message']}");
            }

            if ($body[0]['exists']) {
                $this->logger->info('Número verificado com sucesso.', ['number' => $number]);

                if ($tipo == 'text') {
                    return $this->sendMessageText($message, $number);
                }
                if ($tipo == 'image') {
                    return $this->sendMessageImage($message, $number);
                }
            } else {
                throw new Exception("Erro: O número não está registrado no WhatsApp");
            }
        } catch (Exception $e) {
            $this->logger->error('Erro em verifyNumber: ' . $e->getMessage());
            return false;  // Retorna false para indicar falha, mas não lança exceção
        }
    }

    public function sendMessageImage(array $message, $number)
    {
        try {
            $this->logger->info('Enviando mensagem de imagem.', ['number' => $number]);

            $params = [
                "number" => $number,
                "mediaMessage" => [
                    "mediatype" => "image",
                    "caption"   => $message['message'],
                    "media"     => $message['image']
                ]
            ];

            $body = $this->postRequest('/message/sendMedia/' . $this->instance, $params);

            if (isset($body['Code'], $body['Message'])) {
                throw new Exception("Erro {$body['Code']}: {$body['Message']}");
            }

            $this->logger->info('Mensagem de imagem enviada com sucesso.', ['number' => $number]);
            return true;
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response ? $response->getBody()->getContents() : 'Sem resposta';
            $this->logger->error('Erro na requisição sendMessageImage: ' . $responseBodyAsString);
            return false;
        }
    }

    public function sendMessageText($message, $number)
    {
        try {
            $this->logger->info('Enviando mensagem de texto.', ['number' => $number]);

            $params = [
                "number" => $number,
                "textMessage" => [
                    "text" => $message['message']
                ],
                "options" => [
                    "delay" => 300,
                    "presence" => "composing",
                    "linkPreview" => false,
                ]
            ];

            $body = $this->postRequest('/message/sendText/' . $this->instance, $params);

            if (isset($body['Code'], $body['Message'])) {
                throw new Exception("Erro {$body['Code']}: {$body['Message']}");
            }

            $this->logger->info('Mensagem de texto enviada com sucesso.', ['number' => $number]);
            return true;
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response ? $response->getBody()->getContents() : 'Sem resposta';
            $this->logger->error('Erro na requisição sendMessageText: ' . $responseBodyAsString);
            return false;
        }
    }

    protected function postRequest($endpoint, array $data)
    {
        try {
            $this->logger->info('Realizando POST request.', ['endpoint' => $endpoint, 'data' => $data]);

            $options = [
                'headers' => $this->headers,
                'json' => $data
            ];

            $response = $this->client->post($this->apiUrl . $endpoint, $options);
            $body = json_decode($response->getBody(), true);

            $this->logger->info('POST request realizado com sucesso.', ['response' => $body]);
            return $body;
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response ? $response->getBody()->getContents() : 'Sem resposta';
            $this->logger->error('Erro na requisição postRequest: ' . $responseBodyAsString);
            return null;
        }
    }
}
