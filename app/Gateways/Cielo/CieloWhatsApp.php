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
            $builderWa = new ConfigMensagensModel();
            $rowMessage = $builderWa->where(['id_adm' => session('data')['idAdm'], 'tipo' => 'cobranca_gerada_pix', 'status' => true])->first();
            if ($rowMessage) {
                $data = [
                    '{nome}' => $row['nome'],
                    'number' => $row['celular'],
                    '{valor}' => centavosParaReaisBrasil($response['Payment']['Amount']),
                    '{copia_cola}' => $response['Payment']['QrCodeString']
                ];

                $msg = $rowMessage['mensagem'];

                // Faz a substituição
                $mensagem_final = str_replace(array_keys($data), array_values($data), $msg);

                $whatsapp = new WhatsappLibraries();
                
                $whatsapp->verifyNumber([
                    'message' => $mensagem_final,
                    'image' => $response['Payment']['QrCodeBase64Image']
                ],
                    $data['number'], 'image');

                $whatsapp->verifyNumber([
                    'message' => $response['Payment']['QrCodeString']
                ], $data['number'], 'text');
            }
        } catch (\Exception $e) {
            $this->logger->error('Erro em pixGerado: ' . $e->getMessage());
        }
    }

    public function pago($client, $response)
    {
        try {
            $builderWa = new ConfigMensagensModel();
            $rowMessage = $builderWa->where([
                'id_adm' => session('data')['idAdm'],
                'tipo' => 'pagamento_realizado',
                'status' => true
                ])->first();

            if ($rowMessage) {
                $data = [
                    '{nome}' => $client['nome'],
                    'number' => $client['celular'],
                    '{data}' => date('d/m/Y h:i:s')
                ];
                
                $msg = $rowMessage['mensagem'];

                // Faz a substituição
                $mensagem_final = str_replace(array_keys($data), array_values($data), $msg);

                $whatsapp = new WhatsappLibraries();
                
                $whatsapp->verifyNumber([
                    'message' => $mensagem_final
                ], $data['number'], 'text');
            } else {
                return false;
            }
        } catch (\Exception $e) {
            $this->logger->error('Erro em pago: ' . $e->getMessage());
        }
    }
}