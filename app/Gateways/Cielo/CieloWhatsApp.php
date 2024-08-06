<?php

namespace App\Gateways\Cielo;

use App\Libraries\WhatsappLibraries;
use App\Models\ConfigMensagensModel;

class CieloWhatsApp
{
    protected $logger;

    public function __construct()
    {
        helper('auxiliar');
        $this->logger = service('logger');
    }

    public function pixGerado($row, $response)
    {
        try {
            $this->logger->info('Iniciando pixGerado.');

            $builderWa = new ConfigMensagensModel();
            $rowMessage = $builderWa->where(['id_adm' => session('data')['idAdm'], 'tipo' => 'cobranca_gerada_pix', 'status' => true])->first();
            $this->logger->info('Configuração da mensagem carregada.', ['rowMessage' => $rowMessage]);

            if ($rowMessage) {
                $data = [
                    '{nome}' => $row['nome'],
                    'number' => $row['celular'],
                    '{valor}' => centavosParaReaisBrasil($response['Payment']['Amount']),
                    '{copia_cola}' => $response['Payment']['QrCodeString']
                ];

                $msg = $rowMessage['mensagem'];
                $this->logger->info('Dados preparados para substituição.', ['data' => $data]);

                // Faz a substituição
                $mensagem_final = str_replace(array_keys($data), array_values($data), $msg);
                $this->logger->info('Mensagem final preparada.', ['mensagem_final' => $mensagem_final]);

                $whatsapp = new WhatsappLibraries();

                $this->logger->info('Enviando mensagem com imagem via WhatsApp.');
                $whatsapp->verifyNumber([
                    'message' => $mensagem_final,
                    'image' => $response['Payment']['QrCodeBase64Image']
                ], $data['number'], 'image');

                $this->logger->info('Enviando mensagem de texto via WhatsApp.');
                $whatsapp->verifyNumber([
                    'message' => $response['Payment']['QrCodeString']
                ], $data['number'], 'text');
            } else {
                $this->logger->warning('Configuração de mensagem não encontrada.');
            }
        } catch (\Exception $e) {
            $this->logger->error('Erro em pixGerado: ' . $e->getMessage());
        }
    }

    public function pago($client, $response)
    {
        try {
            $this->logger->info('Iniciando pago.');

            $builderWa = new ConfigMensagensModel();
            $rowMessage = $builderWa->where([
                'id_adm' => session('data')['idAdm'],
                'tipo' => 'pagamento_realizado',
                'status' => true
            ])->first();
            $this->logger->info('Configuração da mensagem carregada.', ['rowMessage' => $rowMessage]);

            if ($rowMessage) {
                $data = [
                    '{nome}' => $client['nome'],
                    'number' => $client['celular'],
                    '{data}' => date('d/m/Y h:i:s')
                ];

                $msg = $rowMessage['mensagem'];
                $this->logger->info('Dados preparados para substituição.', ['data' => $data]);

                // Faz a substituição
                $mensagem_final = str_replace(array_keys($data), array_values($data), $msg);
                $this->logger->info('Mensagem final preparada.', ['mensagem_final' => $mensagem_final]);

                $whatsapp = new WhatsappLibraries();

                $this->logger->info('Enviando mensagem de texto via WhatsApp.');
                $whatsapp->verifyNumber([
                    'message' => $mensagem_final
                ], $data['number'], 'text');
            } else {
                $this->logger->warning('Configuração de mensagem não encontrada.');
                return false;
            }
        } catch (\Exception $e) {
            $this->logger->error('Erro em pago: ' . $e->getMessage());
        }
    }
}
