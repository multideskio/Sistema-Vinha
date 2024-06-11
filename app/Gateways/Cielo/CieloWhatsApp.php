<?php

namespace App\Gateways\Cielo;

use App\Libraries\WhatsappLibraries;
use App\Models\ConfigMensagensModel;

class CieloWhatsApp
{
    public function __construct()
    {
        helper('auxiliar');
    }

    public function pixGerado($row, $response)
    {
        $builderWa = new ConfigMensagensModel();
        $rowMessage = $builderWa->where(['id_adm' => session('data')['idAdm'], 'tipo' => 'cobranca_gerada_pix', 'status' => true])->findAll(1);
        if (count($rowMessage)) {
            $data = [
                '{nome}' => $row['nome'],
                'number' => $row['celular'],
                '{valor}' => centavosParaReaisBrasil($response['Payment']['Amount']),
                '{copia_cola}' => $response['Payment']['QrCodeString']
            ];
        }
        $msg = $rowMessage[0]['mensagem'];

        // Faz a substituição
        $mensagem_final = str_replace(array_keys($data), array_values($data), $msg);

        //
        $whatsapp = new WhatsappLibraries();
        
        $whatsapp->verifyNumber(['message' => $mensagem_final, 'image' => $response['Payment']['QrCodeBase64Image']], $data['number'], 'image');

        $whatsapp->verifyNumber(['message' => $response['Payment']['QrCodeString']], $data['number'], 'text');
    }

    
    public function pixGeradoPago($row, $response)
    {

    }
}
