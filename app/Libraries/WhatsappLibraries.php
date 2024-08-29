<?php

namespace App\Libraries;

use App\Models\AdminModel;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use CodeIgniter\Log\Logger;
use InvalidArgumentException;

class WhatsappLibraries
{
    protected string $apiUrl;
    protected string $instance;
    protected string $apikey;
    protected array $headers;
    protected Client $client;
    protected AdminModel $modelAdmin;
    protected Logger $logger;

    public function __construct(Client $client = null, AdminModel $modelAdmin = null, Logger $logger = null)
    {
        try {
            $this->modelAdmin = $modelAdmin ?? new AdminModel();
            $this->client = $client ?? new Client();
            $this->logger = $logger ?? \Config\Services::logger();

            $this->logger->info('Construtor chamado.');

            $dataSettings = $this->data();
            if (!$dataSettings) {
                throw new Exception('Nenhuma configuração da API Multidesk encontrada.');
            }

            $this->apiUrl = rtrim($dataSettings['apiUrl'], '/');
            $this->apikey = $dataSettings['apikey'];
            $this->instance = $dataSettings['instance'];
            $this->headers = [
                'apikey' => $this->apikey,
                'Content-Type' => 'application/json'
            ];

            $this->logger->info('Configuração carregada com sucesso.', [
                'apiUrl' => $this->apiUrl,
                'instance' => $this->instance
            ]);
        } catch (Exception $e) {
            $this->logger->error('Erro no construtor: ' . $e->getMessage());
        }
    }

    public function data(): ?array
    {
        $this->logger->info('Método data chamado.');
        try {
            $row = $this->modelAdmin->first();
            $this->logger->info('Dados obtidos do modelo Admin.');

            if ($row && isset($row['url_api'], $row['instance_api'], $row['key_api'])) {
                $this->logger->info('Dados da API encontrados.', $row);
                return [
                    'apiUrl' => $row['url_api'],
                    'instance' => $row['instance_api'],
                    'apikey' => $row['key_api']
                ];
            }

            $this->logger->warning('Dados da API não encontrados.');
            return null;
        } catch (Exception $e) {
            $this->logger->error('Erro ao obter dados: ' . $e->getMessage());
            return null;
        }
    }

    public function verifyNumber(array $message, string $number, string $tipo = 'text'): bool
    {
        // Restringe `$tipo` a apenas 'text' ou 'image' ou 'csv'
        if (!in_array($tipo, ['text', 'image', 'csv'])) {
            $this->logger->error('Tipo inválido fornecido: ' . $tipo);
            throw new InvalidArgumentException('Tipo deve ser "text" ou "image".');
        }

        $this->logger->info('Método verifyNumber chamado.', [
            'number' => $number,
            'tipo' => $tipo
        ]);

        try {
            $this->logger->info('Enviando requisição para verificar número.');
            $body = $this->postRequest('/chat/whatsappNumbers/' . $this->instance, [
                "numbers" => [$number]
            ]);

            if (isset($body['Code'], $body['Message'])) {
                throw new Exception("Erro {$body['Code']}: {$body['Message']}");
            }

            if ($body[0]['exists']) {
                $this->logger->info('Número verificado com sucesso.');

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
            $this->logger->error('Erro em verifyNumber: ' . $e->getMessage());
            return false;
        }
    }

    public function sendMessageImage(array $message, string $number): bool
    {
        $this->logger->info('Método sendMessageImage chamado.', [
            'number' => $number,
            'message' => $message
        ]);

        try {
            $params = [
                "number"    => $number,
                "mediatype" => "image",
                "mimetype"  => "image/png",
                "caption"   => $message['message'],
                "media"     => $message['image'],
                "fileName"  => "imagem.png"
            ];

            $this->logger->info('Enviando imagem.', $params);
            $body = $this->postRequest('/message/sendMedia/' . $this->instance, $params);

            if (isset($body['Code'], $body['Message'])) {
                throw new Exception("Erro {$body['Code']}: {$body['Message']}");
            }

            $this->logger->info('Imagem enviada com sucesso.');
            return true;
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response ? $response->getBody()->getContents() : 'Sem resposta';
            $this->logger->error('Erro na requisição sendMessageImage: ' . $responseBodyAsString);
            return false;
        }
    }


    public function sendMessageCsv(array $message, string $number): bool
    {
        $this->logger->info('Método sendMessageImage chamado.', [
            'number' => $number,
            'message' => $message
        ]);

        try {
            $params = [
                "number"    => $number,
                "mediatype" => "document",
                "mimetype"  => "text/csv",
                "caption"   => $message['message'],
                "media"     => $message['csv'],
                "fileName"  => "relatorio.csv"
            ];

            $this->logger->info('Enviando imagem.', $params);
            $body = $this->postRequest('/message/sendMedia/' . $this->instance, $params);

            if (isset($body['Code'], $body['Message'])) {
                throw new Exception("Erro {$body['Code']}: {$body['Message']}");
            }

            $this->logger->info('Imagem enviada com sucesso.');
            return true;
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response ? $response->getBody()->getContents() : 'Sem resposta';
            $this->logger->error('Erro na requisição sendMessageImage: ' . $responseBodyAsString);
            return false;
        }
    }


    public function sendMessageText(array $message, string $number): bool
    {
        $this->logger->info('Método sendMessageText chamado.', [
            'number' => $number,
            'message' => $message
        ]);

        try {
            $params = [
                "number" => $number,
                "text" => $message['message'],
                "options" => [
                    "delay" => 600,
                    "presence" => "composing",
                    "linkPreview" => true,
                ]
            ];

            $this->logger->info('Enviando texto.', $params);
            $body = $this->postRequest('/message/sendText/' . $this->instance, $params);

            if (isset($body['Code'], $body['Message'])) {
                throw new Exception("Erro {$body['Code']}: {$body['Message']}");
            }

            $this->logger->info('Texto enviado com sucesso.');
            return true;
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response ? $response->getBody()->getContents() : 'Sem resposta';
            $this->logger->error('Erro na requisição sendMessageText: ' . $responseBodyAsString);
            return false;
        }
    }

    protected function postRequest(string $endpoint, array $data): ?array
    {
        $this->logger->info('Método postRequest chamado.', [
            'endpoint' => $endpoint,
            'data' => $data
        ]);

        try {
            $options = [
                'headers' => $this->headers,
                'json' => $data
            ];

            $this->logger->info('Enviando requisição POST para ' . $endpoint);

            $response = $this->client->post($this->apiUrl . $endpoint, $options);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            $this->logger->info('Resposta da requisição recebida.', $responseBody);

            return $responseBody;
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response ? $response->getBody()->getContents() : 'Sem resposta';
            $this->logger->error('Erro na requisição postRequest: ' . $responseBodyAsString);
            return null;
        }
    }
}
