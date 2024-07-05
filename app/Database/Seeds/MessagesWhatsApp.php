<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MessagesWhatsApp extends Seeder
{
    public function run()
    {
        //
        $data = [
            [
                'id' => 1,
                'id_adm' => 1,
                'id_user' => 1,
                'tipo' => 'novo_usuario',
                'mensagem' => '*️⃣ A Graça e a Paz! *️⃣\r\n\r\n_{NOME}_, você está recebendo esta mensagem porque se cadastrou no sistema de contribuição mensal da Vinha.\r\n\r\nPara garantir a segurança de sua conta e evitar registros falsos, por favor, verifique seu e-mail _{EMAIL}_ e clique no botão ou no link de confirmação.\r\n\r\n🔔 Não se esqueça de salvar nosso contato para receber notificações automáticas.\r\n\r\n🤖 _Esta é uma mensagem automática._',
                'status' => 1
            ],
            [
                'id' => 2,
                'id_adm' => 1,
                'id_user' => 1,
                'tipo' => 'cobranca_gerada',
                'mensagem' => 'Olá {nome},\n\nUma nova cobrança por {gateway} foi gerada na plataforma.',
                'status' => 1
            ],
            [
                'id' => 3,
                'id_adm' => 1,
                'id_user' => 1,
                'tipo' => 'pagamento_atrasado',
                'mensagem' => 'Olá {nome},\n\nPercebemos que a data programada para o seu dízimo passou e ainda não identificamos nenhuma transação referente a sua conta.\n\nComo podemos ajudar?',
                'status' => 1
            ],
            [
                'id' => 4,
                'id_adm' => 1,
                'id_user' => 1,
                'tipo' => 'pagamento_realizado',
                'mensagem' => '*Recebemos seu pagamento*\n\n_Obrigado por manter em dias seu compromisso conosco._\n\nQualquer Dúvida, estamos à disposição.\n\n{data}',
                'status' => 1
            ],
            [
                'id' => 5,
                'id_adm' => 1,
                'id_user' => 1,
                'tipo' => 'confirmacao_conta',
                'mensagem' => 'Olá, {nome}...\r\n\r\nEsse é o código *{COD}* para confirmar o uso de notificações via WhatsApp.\r\n\r\nTambém enviamos um e-mail de confirmação, Caso o email não seja confirmado em 48 horas sua conta poderá ser excluída de nossos sistema.\r\n\r\n_Essas verificações são importantes para manter nosso sistema livre de ameaças de spam_\r\n\r\nAtt Pr João Fulano\r\nBoletos Vinha',
                'status' => 1
            ],
            [
                'id' => 6,
                'id_adm' => 1,
                'id_user' => 1,
                'tipo' => 'cobranca_gerada_pix',
                'mensagem' => 'Olá {nome},\n\nSeguem os dados do seu pagamento via PIX:\n\n*Valor*:\n{valor}\n\n*Prazo para pagamento:*\n30 minutos\n\nCopie e cola todo o código e não apenas a parte azul do código:',
                'status' => 1
            ],
        ];
        

        // Insert the data into the database
        $this->db->table('config_mensagens')->insertBatch($data);
    }
}
