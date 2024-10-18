<?php

namespace App\Gateways\Cielo;

use App\Libraries\RedisLibrary;
use App\Libraries\WebSocketLibrary;

class CieloNotifica
{
    protected WebSocketLibrary $websocket;
    protected RedisLibrary $redis;

    /**
     * Construtor sem injeção de dependências, as bibliotecas são instanciadas internamente.
     */
    public function __construct()
    {
        $this->websocket = new WebSocketLibrary();
        $this->redis     = new RedisLibrary();
    }

    /**
     * Handle live notifications based on type.
     *
     * @param string $tipo O tipo da notificação a ser processada.
     */
    public function notificationLive(string $tipo): void
    {
        switch ($tipo) {
            case 'pixGerado':
                $this->pixGerado();
                break;

            case 'pago':
                $this->pago();
                break;

            default:
                // Apenas registra o erro e continua sem interromper o fluxo
                log_message('error', "Tipo de notificação inválido: $tipo");
                break;
        }
    }

    /**
     * Notificação de PIX gerado.
     */
    private function pixGerado(): void
    {
        $notifica = [
            'tipo'    => 'gerado',
            'message' => 'Um novo pagamento por PIX foi gerado.',
        ];

        try {
            // Envia a mensagem via WebSocket
            $this->websocket->sendMessage($notifica);
            // Publica a notificação no Redis
            $this->redis->publish('cliente_event', json_encode($notifica));
        } catch (\Exception $e) {
            // Registrar log de erro sem parar a execução
            log_message('error', 'Falha ao enviar notificação PIX Gerado: ' . $e->getMessage());
        }
    }

    /**
     * Notificação de PIX pago.
     */
    private function pago(): void
    {
        $notifica = [
            'tipo'    => 'pago',
            'message' => 'Um PIX foi pago, verifique em seus relatórios.',
        ];

        try {
            // Envia a mensagem via WebSocket
            $this->websocket->sendMessage($notifica);
            // Publica a notificação no Redis
            $this->redis->publish('cliente_event', json_encode($notifica));
        } catch (\Exception $e) {
            // Registrar log de erro sem parar a execução
            log_message('error', 'Falha ao enviar notificação PIX Pago: ' . $e->getMessage());
        }
    }
}