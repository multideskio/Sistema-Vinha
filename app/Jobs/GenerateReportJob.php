<?php

namespace App\Jobs;

use App\Models\TransacoesModel;
use App\Models\UsuariosModel;
use App\Libraries\UploadsLibraries;
use App\Libraries\WhatsappLibraries;

class GenerateReportJob
{
    protected $tamanhoLote = 500; // Defina o tamanho do lote de processamento

    public function handle($data)
    {
        log_message('info', 'Iniciando o processo de geração do relatório.');
        $dataInicio = $data['data_inicio'] ?? null;
        $dataFim = $data['data_fim'] ?? null;
        $tipoPagamento = $data['tipo_pagamento'] ?? false;
        $status = $data['status'] ?? false;
        $idAdmin = $data['id_admin'];
        $idUser = $data['id_user'];
        $celular = $data['whatsapp'];

        if (!$dataInicio || !$dataFim) {
            log_message('error', 'Datas de início ou fim não fornecidas.');
            $this->notificarErro('Datas de início ou fim não fornecidas.', $idAdmin, $celular);
            return;
        }

        // Nome do arquivo CSV
        $nomeArquivo = 'relatorio_transacoes_' . date('Y-m-d_H-i-s') . '.csv';
        $caminhoArquivo = WRITEPATH . 'uploads/' . $nomeArquivo;

        // Abre o arquivo para escrita
        $arquivo = fopen($caminhoArquivo, 'w');

        // Escreve o cabeçalho no arquivo CSV
        $this->escreverCabecalhoCsv($arquivo);

        // Processa os dados em lotes e escreve no CSV
        $this->processarLotes($arquivo, $dataInicio, $dataFim, $tipoPagamento, $status, $celular);

        // Fecha o arquivo CSV
        fclose($arquivo);

        // Envia o arquivo para o S3 e registra as informações no banco
        $this->enviarRelatorio($caminhoArquivo, $nomeArquivo, $dataInicio, $dataFim, $tipoPagamento, $status, $idAdmin, $idUser, $celular);
    }

    protected function processarLotes($arquivo, $dataInicio, $dataFim, $tipoPagamento, $status, $celular)
    {
        log_message('info', 'Iniciando o processamento de lotes para o relatório.');

        $transacoesQuery = (new TransacoesModel())
            ->where('created_at >=', $dataInicio)
            ->where('created_at <=', $dataFim);

        if ($tipoPagamento) {
            $transacoesQuery->where('tipo_pagamento', $tipoPagamento);
        }

        if ($status) {
            $transacoesQuery->where('status_text', $status);
        }

        $offset = 0;

        do {
            // Busca o lote de transações
            $transacoes = $transacoesQuery->findAll($this->tamanhoLote, $offset);

            // Processa e escreve cada transação no CSV
            foreach ($transacoes as $transacao) {
                $linha = $this->processarTransacao($transacao, $celular);
                if ($linha) {
                    fputcsv($arquivo, $linha);
                }
            }

            log_message('info', "Processado lote com offset: {$offset}.");

            // Atualiza o offset para o próximo lote
            $offset += $this->tamanhoLote;
        } while (!empty($transacoes));

        log_message('info', 'Processamento de lotes concluído.');
    }

    protected function processarTransacao($transacao, $celular)
    {
        try {
            $modelUsuarios = new UsuariosModel();
            $idPerfil = $modelUsuarios->select('id_perfil, tipo, email')->find($transacao['id_user']);

            if (!$idPerfil) {
                log_message('warning', "Perfil de usuário não encontrado para transação ID: {$transacao['id']}.");
                return null;
            }

            $perfil = (new TransacoesModel())->obterPerfilUsuario($idPerfil);

            $clienteNome = ($idPerfil['tipo'] == 'igreja') ?
                $perfil['nome_tesoureiro'] . ' ' . $perfil['sobrenome_tesoureiro'] :
                $perfil['nome'] . ' ' . $perfil['sobrenome'];

            log_message('info', "Processando transação ID: {$transacao['id']}.");

            // Retorna a linha formatada para o CSV
            return [
                'ID no Sistema' => $transacao['id'],
                'ID do Pedido' => $transacao['id_pedido'],
                'ID do Cliente' => $perfil['id'],
                'Cliente' => $clienteNome,
                'Tipo de acesso' => $idPerfil['tipo'],
                'Telefone/WhatsApp' => $perfil['celular'],
                'E-mail' => $idPerfil['email'],
                'ID da Transacao' => $transacao['id_transacao'],
                'Gateway' => $transacao['gateway'],
                'Tipo de Pagamento' => $transacao['tipo_pagamento'],
                'Descricao' => $transacao['descricao'],
                'Descricao longa' => $transacao['descricao_longa'],
                'Data de pagamento' => $transacao['data_pagamento'],
                'Status da transacao' => $transacao['status_text'],
                'Valor' => $transacao['valor'],
            ];
        } catch (\Exception $e) {
            log_message('error', 'Erro ao processar transação: ' . $e->getMessage());
            $this->notificarErro('Erro ao processar transação: ' . $e->getMessage(), $transacao['id'], $celular);
            return null;
        }
    }

    protected function escreverCabecalhoCsv($arquivo)
    {
        // Cabeçalho do CSV
        fputcsv($arquivo, [
            'ID no Sistema',
            'ID do Pedido',
            'ID do Cliente',
            'Cliente',
            'Tipo de acesso',
            'Telefone/WhatsApp',
            'E-mail',
            'ID da Transacao',
            'Gateway',
            'Tipo de Pagamento',
            'Descricao',
            'Descricao longa',
            'Data de pagamento',
            'Status da transacao',
            'Valor'
        ]);
    }

    protected function enviarRelatorio($caminhoArquivo, $nomeArquivo, $dataInicio, $dataFim, $tipoPagamento, $status, $idAdmin, $idUser, $celular)
    {
        try {
            log_message('info', 'Enviando relatório para o S3.');
            $uploadLib = new UploadsLibraries();
            $caminhoS3 = 'relatorios/' . $nomeArquivo;
            $urlS3 = $uploadLib->uploadToS3($caminhoArquivo, $caminhoS3, 'text/csv');

            // Deleta o arquivo local após upload
            if (file_exists($caminhoArquivo)) {
                unlink($caminhoArquivo);
            }

            log_message('info', 'Relatório gerado e enviado para S3 com sucesso: ' . $urlS3);

            // Registra o relatório na tabela de relatórios gerados
            $relatoriosModel = new \App\Models\RelatoriosGeradosModel();
            $relatoriosModel->insert([
                'nome_arquivo' => $nomeArquivo,
                'url_download' => $urlS3,
                'parametros_busca' => json_encode([
                    'data_inicio' => $dataInicio,
                    'data_fim' => $dataFim,
                    'tipo_pagamento' => $tipoPagamento,
                    'status' => $status,
                ]),
                'id_adm' => $idAdmin,
                'id_user' => $idUser
            ]);

            $this->notificarSucesso($urlS3, $idAdmin, $celular);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao enviar o relatório para S3: ' . $e->getMessage());
            $this->notificarErro("Erro ao enviar o relatório para S3: \n" . $e->getMessage(), $idAdmin, $celular);
        }
    }

    protected function notificarErro($mensagem, $idAdmin, $celular)
    {
        // Enviar notificação pelo WhatsApp
        $whatsApp = new WhatsappLibraries();
        $dataWp['message'] = "Não foi possivel gerar o relatório.\nVerifique o erro a seguir:\n\n$mensagem";
        //$dataWp['csv'] = $urlS3;
        $whatsApp->verifyNumber($dataWp, $celular);

        // Implementar lógica para notificar o administrador por e-mail, WhatsApp, etc.
        log_message('error', 'Notificação de erro enviada: ' . $mensagem);
    }

    protected function notificarSucesso($url, $idAdmin, $celular)
    {
        // Enviar notificação pelo WhatsApp
        $whatsApp = new WhatsappLibraries();
        $dataWp['message'] = "Seu relatório está pronto...\n\n{$url}";
        $dataWp['csv'] = $url;

        $whatsApp->verifyNumber($dataWp, $celular, 'csv');

        // Implementar lógica para notificar o administrador sobre o sucesso da geração do relatório
        log_message('info', 'Notificação de sucesso enviada para o administrador.');
    }
}
