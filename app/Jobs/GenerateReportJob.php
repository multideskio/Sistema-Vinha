<?php

namespace App\Jobs;

use App\Models\TransacoesModel;
use App\Models\UsuariosModel;
use App\Libraries\UploadsLibraries;
use App\Libraries\WhatsappLibraries;

class GenerateReportJob
{
    public function handle($data)
    {
        // Lógica para gerar o relatório (mesmo que discutido anteriormente)
        // Extrai os parâmetros recebidos
        $dataInicio    = $data['data_inicio'];
        $dataFim       = $data['data_fim'];
        $tipoPagamento = $data['tipo_pagamento'] ?? false;
        $status        = $data['status'] ?? false;

        $transacoesQuery = (new TransacoesModel())
            ->where('created_at >=', $dataInicio)
            ->where('created_at <=', $dataFim);

        if ($tipoPagamento) {
            $transacoesQuery->where('tipo_pagamento', $tipoPagamento);
        }

        if ($status) {
            $transacoesQuery->where('status_text', $status);
        }

        $transacoes = $transacoesQuery->findAll();

        if (empty($transacoes)) {
            log_message('error', 'Nenhuma transação encontrada para os critérios fornecidos.');
            return;
        }

        $data = [];
        $modelUsuarios = new UsuariosModel();

        foreach ($transacoes as $transacao) {
            $idPerfil = $modelUsuarios->select('id_perfil, tipo, email')->find($transacao['id_user']);

            if (!$idPerfil) {
                continue;
            }

            $perfil = (new TransacoesModel())->obterPerfilUsuario($idPerfil);

            $clienteNome = ($idPerfil['tipo'] == 'igreja') ?
                $perfil['nome_tesoureiro'] . ' ' . $perfil['sobrenome_tesoureiro'] :
                $perfil['nome'] . ' ' . $perfil['sobrenome'];

            $data[] = [
                'id' => $transacao['id'],
                'id_pedido' => $transacao['id_pedido'],
                'id_cliente' => $perfil['id'],
                'cliente' => $clienteNome,
                'tipo_de_acesso' => $idPerfil['tipo'],
                'telefone' => $perfil['celular'],
                'email' => $idPerfil['email'],
                'id_transacao' => $transacao['id_transacao'],
                'gateway' => $transacao['gateway'],
                'tipo_pagamento' => $transacao['tipo_pagamento'],
                'descricao' => $transacao['descricao'],
                'descricao_longa' => $transacao['descricao_longa'],
                'data_pagamento' => $transacao['data_pagamento'],
                'status_text' => $transacao['status_text'],
                'valor' => $transacao['valor'],
            ];
        }

        if (empty($data)) {
            log_message('error', 'Nenhum dado disponível para gerar o relatório.');
            return;
        }

        // Nome do arquivo CSV
        $nomeArquivo = time() . '_relatorio_transacoes_' . date('Y-m-d_H-i-s') . '.csv';
        $caminhoArquivo = WRITEPATH . 'uploads/' . $nomeArquivo;

        // Criar o arquivo CSV
        $arquivo = fopen($caminhoArquivo, 'w');
        fputcsv($arquivo, array_keys($data[0]));

        foreach ($data as $linha) {
            fputcsv($arquivo, $linha);
        }

        fclose($arquivo);

        // Enviar para o S3
        $uploadLib = new UploadsLibraries();
        try {
            $caminhoS3 = 'relatorios/' . $nomeArquivo;
            $urlS3 = $uploadLib->uploadToS3($caminhoArquivo, $caminhoS3, 'text/csv');

            // Deletar o arquivo local
            if (file_exists($caminhoArquivo)) {
                unlink($caminhoArquivo);
            }

            $whatsApp = new WhatsappLibraries();

            $dataWp['message'] = "Seu relatório está pronto...\n\n{$urlS3}";

            $whatsApp->verifyNumber($dataWp, '5562981154120');

            log_message('info', 'Relatório gerado e enviado para S3 com sucesso: ' . $urlS3);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao enviar o relatório para S3: ' . $e->getMessage());
        }
    }
}
