<?php 

namespace App\Jobs;


use App\Libraries\WhatsappLibraries;
use App\Models\ConfigMensagensModel;
use App\Models\ControleEnviosModel;

class AvisosWhatsApp
{

    public function handle($data)
    {
        // Verifica se os dados necessários estão presentes
        if (!isset($data['usuario'], $data['diasDiferenca'])) {
            log_message('error', 'Dados insuficientes para envio de lembrete.');
            return;
        }

        // Executa o envio do lembrete com os dados recebidos
        $this->enviarLembrete($data['usuario'], $data['diasDiferenca']);
    }

    private function enviarLembrete($usuario, $diasDiferenca)
    {
        $modelMessages = new ConfigMensagensModel();

        /**BUSCA MENSAGEM DE LEMBRETE DE PAGAMENTO */
        $lembrete_pagamento = $modelMessages
            ->where('status', 1)
            ->where('tipo', 'lembrete_pagamento')->first();

        if (!$lembrete_pagamento) {
            log_message('info', 'Envio de lembrete desativado');
            return false;
        }

        /** BUSCA MENSAGEM DE PAGAMENTO EM ATRASO */
        $pagamento_atrasado = $modelMessages
            ->where('status', 1)
            ->where('tipo', 'pagamento_atrasado')->first();

        if (!$pagamento_atrasado) {
            log_message('info', 'Envio de lembrete desativado');
            return false;
        }

        $nome = !empty($usuario['nome']) ? $usuario['nome'] : (!empty($usuario['razao_social']) ? $usuario['razao_social'] : false);

        if ($nome) {
            // Prepara a mensagem dinâmica
            if ($diasDiferenca < 0) {
                $diasRestantes = abs($diasDiferenca);
                
                $dados = [
                    '{nome}' => $nome,
                    'number' => $usuario['celular'],
                    '{dias}' => ($diasRestantes > 1) ? $diasRestantes . ' dias' : $diasRestantes . ' dia',
                    '{data_dizimo}' => $usuario['data_dizimo'],
                    '{site}' => site_url()
                ];
                
                $mensagem = str_replace(array_keys($dados), array_values($dados), $lembrete_pagamento['mensagem']);

            } else {
                $diasPassados = $diasDiferenca;
                $dados = [
                    '{nome}' => $nome,
                    'number' => $usuario['celular'],
                    '{dias}' => ($diasPassados > 1) ? $diasPassados . ' dias' : $diasPassados . ' dia',
                    '{data_dizimo}' => $usuario['data_dizimo'],
                    '{site}' => site_url()
                ];
                $mensagem = str_replace(array_keys($dados), array_values($dados), $pagamento_atrasado['mensagem']);
            }

            // Envio da mensagem via WhatsApp
            $whatsApp = new WhatsappLibraries();
            $whatsApp->verifyNumber(['message' => $mensagem], $usuario['celular'], 'text');

            log_message('info', 'Lembrete enviado para o usuário: ' . $usuario['id']);
        } else {
            log_message('info', 'NÃO ENVIOU: Nome ou razão social não disponível para o usuário ' . json_encode($usuario));
        }
    }
}
