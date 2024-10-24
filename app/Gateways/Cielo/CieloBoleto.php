<?php

namespace App\Gateways\Cielo;

use App\Models\AdminModel;
use App\Models\UsuariosModel;
use DateMalformedStringException;
use DateTime;
use Exception;
use RuntimeException;

class CieloBoleto extends CieloBase
{
    protected array|null|object $dataAdm ;
    protected AdminModel $admModel ;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        // Chama o construtor da classe pai para inicializar os modelos
        parent::__construct();

        $this->admModel = new AdminModel();
        $this->dataAdm  = $this->admModel->first();
        helper('auxiliar');
    }

    /**
     * @throws Exception
     */
    public function boleto(int $valor, string $tipo, string $descricao): array
    {
        $cielo = $this->data();

        $modelUser = new UsuariosModel();
        $dados     = $modelUser->userData();

        if (!$cielo['active_boletos']) {
            throw new RuntimeException('Cobrança por boleto não está ativa.');
        }

        $dataVencimento = $this->calculateDueDate($this->dataAdm['prazo_boleto']);

        $countOrders = $this->transactionsModel->select('id')->orderBy('id', 'DESC')->first();
        $numOrder    = ($countOrders) ? ++$countOrders['id'] : 1 ;

        //trata nome completo
        // Verifica se nome e/ou sobrenome estão presentes
        $nome      = !empty($dados['nome']) ? removerAcentos($dados['nome']) : '';
        $sobrenome = !empty($dados['sobrenome']) ? removerAcentos($dados['sobrenome']) : '';

        // Formata o nome e sobrenome (capitaliza as primeiras letras)
        $nome      = !empty($nome) ? ucwords(strtolower($nome)) : '';
        $sobrenome = !empty($sobrenome) ? ucwords(strtolower($sobrenome)) : '';

        // Concatena nome e sobrenome, garantindo que os espaços fiquem corretos
        $nomeCompleto = trim($nome . ' ' . $sobrenome);

        if (empty($nomeCompleto)) {
            $nomeCompleto = 'Nome ou sobrenome ausente';
        }

        if (isset($dados)) {
            if (!empty($dados['cpf'])) {
                $doc = $dados['cpf'];
            } elseif (!empty($dados['cnpj'])) {
                $doc = $dados['cnpj'];
            } else {
                throw new RuntimeException('Documento CPF ou CNPJ não foi preenchido, por favor, atualize seu perfil.');
            }
        }

        if (!empty($doc)) {
            $params = [
                // Identificador único do pedido na sua aplicação (pode ser gerado por time() para garantir unicidade)
                "MerchantOrderId" => $numOrder,
                // Informações do cliente que será cobrado
                "Customer" => [
                    // Nome completo do cliente
                    "Name" => $nomeCompleto,
                    // Documento de identidade (CPF ou CNPJ do cliente)
                    "Identity" => $doc,
                    // Endereço do cliente
                    "Address" => [
                        // Rua onde o cliente reside
                        "Street" => $dados['rua'],
                        // Número da casa ou apartamento
                        "Number" => $dados['numero'],
                        // Complemento do endereço (se aplicável)
                        "Complement" => $dados['complemento'],
                        // CEP do endereço (Código Postal)
                        "ZipCode" => $dados['cep'],
                        // Bairro onde o cliente mora
                        "District" => $dados['bairro'],
                        // Cidade do endereço do cliente
                        "City" => $dados['cidade'],
                        // Estado (UF) onde o cliente reside
                        "State" => $dados['uf'],
                        // País do cliente (no caso, Brasil)
                        "Country" => $dados['pais'] ?? 'Brasil',
                    ],
                ],

                // Informações de pagamento do boleto
                "Payment" => [
                    // Tipo de pagamento (no caso, "Boleto")
                    "Type" => "Boleto",
                    // Provedor de pagamento do boleto (neste caso, "bradesco2")
                    "Provider" => "bradesco2",
                    // Valor a ser cobrado, em centavos (exemplo: R$ 10,00 = 1000)
                    "Amount" => $valor,
                    // Número do boleto gerado (pode ser um valor único, aqui você está usando time() para isso)
                    "BoletoNumber" => time(),
                    // Nome do cedente (quem está emitindo o boleto, geralmente a sua empresa)
                    "Assignor" => $this->dataAdm['empresa'],
                    // Informações demonstrativas que aparecerão no boleto (exemplo: descrição da cobrança)
                    "Demonstrative" => $descricao,
                    // Data de vencimento do boleto (geralmente no formato "YYYY-MM-DD")
                    "ExpirationDate" => $dataVencimento,
                    // Documento de identificação do cliente (CPF ou CNPJ)
                    "Identification" => $this->dataAdm['cnpj'],
                    // Instruções para pagamento que serão exibidas no boleto (exemplo: "Pagável em qualquer banco até o vencimento")
                    "Instructions" => $this->dataAdm['instrucoes_boleto'],
                ],
            ];
        }

        return $this->createBoletoCharge($params, $tipo, $descricao);
    }

    /**
     * @throws DateMalformedStringException
     */
    private function calculateDueDate($days): string
    {
        $currentDate = new DateTime(); // Sem qualificação
        $currentDate->modify("+$days days");

        return $currentDate->format('Y-m-d');
    }

    private function createBoletoCharge(array $params, string $descricao, string $desc_longa): array
    {
        try {
            $this->validateParams($params, ['MerchantOrderId', 'Customer', 'Payment']);
            $params['Payment']['Type'] = 'Boleto';
            $endPoint                  = '/1/sales/';
            $response                  = $this->makeRequest('POST', $endPoint, $params, 'handleCreateChargeResponse');

            $this->saveTransactionBoleto($params, $response, $descricao, $desc_longa);

            // Verificar se a URL do boleto está presente na resposta (correta chave 'Url')
            if (isset($response['Payment']['Url'])) {
                return [
                    'status'    => 'success',
                    'boletoUrl' => $response['Payment']['Url'], // Usando 'Url' ao invés de 'BoletoUrl'
                    'response'  => $response, // Usando 'Url' ao invés de 'BoletoUrl'
                ];
            }

            throw new RuntimeException("URL do boleto não encontrada na resposta.");
        } catch (Exception $e) {
            throw new RuntimeException("Erro ao criar cobrança de boleto: " . $e->getMessage());
        }
    }
}
