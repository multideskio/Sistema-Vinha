let ws;
let reconnectInterval = 5000; // Tempo de espera para reconectar (ms)
let reconnectAttempts = 0;    // Número de tentativas de reconexão
const maxReconnectAttempts = 10; // Número máximo de tentativas de reconexão

function connectWebSocket() {
    // Tentativa de conexão ao WebSocket
    ws = new WebSocket('ws://localhost:8081'); // Substitua pelo ID correto da empresa

    ws.onopen = () => {
        console.log('Conectado ao WebSocket');
        reconnectAttempts = 0;  // Reinicia a contagem de tentativas ao conectar
    };

    ws.onmessage = (event) => {
        try {
            // Log do dado recebido para ver sua estrutura
            console.log("Mensagem recebida:", event.data);

            const rawData = JSON.parse(event.data); // Parse do primeiro nível
            console.log("Dados após o primeiro parse:", rawData);

            // Verificar se 'rawData.message' existe e é uma string
            if (typeof rawData.message === 'string') {
                const data = JSON.parse(rawData.message); // Parse da string JSON dentro de 'message'
                console.log("Dados após o segundo parse:", data);

                // Acessar as propriedades 'tipo' e 'message'
                if (data.tipo && data.message) {
                    notifications(data.tipo, data.message);
                } else {
                    console.error("Erro: Propriedades 'tipo' ou 'message' não encontradas:", data);
                }
            } else {
                console.error('Estrutura inesperada, `rawData.message` não é uma string JSON:', rawData.message);
            }

        } catch (error) {
            console.error('Erro ao processar a mensagem:', error);
        }
    };

    ws.onclose = () => {
        console.log(`Conexão WebSocket fechada. Tentando reconectar (${reconnectAttempts}/${maxReconnectAttempts})...`);
        if (reconnectAttempts < maxReconnectAttempts) {
            reconnectAttempts++;
            setTimeout(connectWebSocket, reconnectInterval);  // Tentar reconectar após um intervalo
        } else {
            console.error('Número máximo de tentativas de reconexão atingido');
        }
    };

    ws.onerror = (error) => {
        console.error('Erro no WebSocket: ', error);
    };
}

// Conectar ao WebSocket
connectWebSocket();

// Inicializando o Notyf para notificações modernas
const notyf = new Notyf({
    duration: 10000,  // Duração da notificação (10 segundos)
    position: { x: 'right', y: 'top' },  // Posição da notificação (direita, topo)
    types: [
        {
            type: 'success',
            background: 'linear-gradient(to right, #00b09b, #96c93d)',  // Estilo para sucesso
            icon: {
                className: 'ri-check-line',  // Ícone para sucesso
                tagName: 'i',  // Tipo de elemento HTML para o ícone
            }
        },
        {
            type: 'error',
            background: 'linear-gradient(to right, #ff5f6d, #ffc371)',  // Estilo para erro
            icon: {
                className: 'ri-close-line',  // Ícone para erro
                tagName: 'i',
            }
        }
    ]
});

// Função para lidar com notificações usando Notyf
function notifications(tipo, message) {
    switch (tipo) {
        case 'gerado':
            notyf.success({
                message: `${message}`,
                background: 'linear-gradient(to right, #00b09b, #96c93d)'  // Estilo para 'gerado'
            });
            break;
        case 'pago':
            notyf.success({
                message: `${message}`,
                background: 'linear-gradient(to right, #00c6ff, #0072ff)'  // Estilo para 'pago'
            });
            break;
        default:
            notyf.error({
                message: `${message}`,
                background: 'linear-gradient(to right, #ff5f6d, #ffc371)'  // Estilo para erro ou outros tipos
            });
            break;
    }
}