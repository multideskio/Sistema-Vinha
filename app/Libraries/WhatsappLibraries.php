<?php

namespace App\Libraries;

use App\Models\AdminModel;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;
use JsonException;
use RuntimeException;

class WhatsappLibraries
{
    protected string $apiUrl;
    protected string $instance;
    protected string $apikey;
    protected array $headers;
    protected Client $client;
    protected AdminModel $modelAdmin;

    public function __construct(Client $client = null, AdminModel $modelAdmin = null)
    {
        $this->modelAdmin = $modelAdmin ?? new AdminModel();
        $this->client     = $client     ?? new Client();

        log_message('info', 'Construtor chamado.');

        $dataSettings = $this->data();

        if (!$dataSettings) {
            log_message('warning', 'Nenhuma configuração da API Multidesk encontrada. Continuando sem API.');
            $this->apiUrl   = '';
            $this->apikey   = '';
            $this->instance = '';

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

            throw new InvalidArgumentException('Tipo deve ser "text", "image" ou "csv".');
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
                throw new RuntimeException("Erro {$body['Code']}: {$body['Message']}");
            }

            if ($body[0]['exists']) {
                log_message('info', 'Número verificado com sucesso.');

                switch ($tipo) {
                    case 'text':
                        return $this->sendMessageText($message, $number);

                    case 'image':
                        return $this->sendMessageImage($message, $number);

                    case 'csv':
                        return $this->sendMessageCsv($message, $number);
                }
            } else {
                log_message('warning', "Número não registrado no WhatsApp: $number");

                return false; // Retornar false se o número não existe
            }
        } catch (Exception $e) {
            log_message('error', 'Erro em verifyNumber: ' . $e->getMessage());

            return false; // Garantir que o método retorne false em caso de exceção
        }

        return false; // Garantir que o método retorne false em qualquer outro caso
    }

    /**
     * @throws Exception
     */
    public function sendMessageImage(array $message, string $number): bool
    {
        return $this->sendMessage($number, "image", $message['image'], $message['message'], "imagem.png", "image/png");
    }

    /**
     * @throws Exception
     */
    public function sendMessageCsv(array $message, string $number): bool
    {
        return $this->sendMessage($number, "document", $message['csv'], $message['message'], "relatorio.csv", "text/csv");
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
                throw new RuntimeException("Erro {$body['Code']}: {$body['Message']}");
            }

            log_message('info', 'Texto enviado com sucesso.');

            return true;
        } catch (RequestException $e) {
            return $this->handleRequestException($e, 'sendMessageText');
        }
    }

    protected function sendMessage(string $number, string $mediaType, string $media, string $caption, string $fileName, string $mimeType): bool
    {
        if (!$this->apiUrl || !$this->apikey || !$this->instance) {
            log_message('error', 'Configuração da API ausente. Não é possível enviar a mensagem.');

            return false;
        }

        log_message('info', 'Método sendMessage chamado.', [
            'number'    => $number,
            'mediaType' => $mediaType,
            'caption'   => $caption,
        ]);

        try {
            $params = [
                "number"    => $number,
                "mediatype" => $mediaType,
                "mimetype"  => $mimeType,
                "caption"   => $caption,
                "media"     => $media,
                "fileName"  => $fileName,
            ];

            log_message('info', 'Enviando mensagem.', $params);
            $body = $this->postRequest('/message/sendMedia/' . $this->instance, $params);

            if (isset($body['Code'], $body['Message'])) {
                throw new RuntimeException("Erro {$body['Code']}: {$body['Message']}");
            }

            log_message('info', 'Mensagem enviada com sucesso.');

            return true;
        } catch (RequestException $e) {
            return $this->handleRequestException($e, 'sendMessage');
        }
    }

    protected function handleRequestException(RequestException $e, string $method): bool
    {
        $response             = $e->getResponse();
        $responseBodyAsString = $response ? $response->getBody()->getContents() : 'Sem resposta';
        log_message('error', "Erro na requisição $method: " . $responseBodyAsString);

        return false;
    }

    protected function postRequest(string $endpoint, array $data): ?array
    {
        if (empty($this->headers)) {
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

            $response     = $this->client->post($this->apiUrl . $endpoint, $options);
            $responseBody = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            log_message('info', 'Resposta da requisição recebida.', $responseBody);

            return $responseBody;
        } catch (RequestException $e) {
            $response             = $e->getResponse();
            $responseBodyAsString = $response ? $response->getBody()->getContents() : 'Sem resposta';
            log_message('error', 'Erro na requisição postRequest: ' . $responseBodyAsString);

            return null;
        } catch (JsonException $e) {
            log_message('error', 'Erro ao decodificar JSON: ' . $e->getMessage());

            return null;
        } catch (GuzzleException $e) {
            log_message('error', 'Erro na requisição: ' . $e->getMessage());

            return null;
        }
    }
}
