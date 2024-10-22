<?php

namespace App\Controllers\Apis\V1;

use App\Gateways\Cielo\GatewayCielo;
use App\Models\TransacoesModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Gateways extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    protected $modelGateway;

    public function __construct()
    {
        $this->modelGateway = new \App\Models\GatewaysModel();
        helper('auxiliar');
    }

    public function index()
    {
        //

        return $this->respond([]);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
        $row = $this->modelGateway->where('tipo', $id)->findAll();

        return $this->respond($row[0]);
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {

        try {
            $msg   = null;
            $input = $this->request->getVar();

            if ($input['tipo'] == 'cielo') {
                $data = [
                    "id_adm"          => session('data')['idAdm'],
                    "id_user"         => session('data')['id'],
                    'tipo'            => 'cielo',
                    'status'          => ($input['status']) ? true : false,
                    'merchantid_pro'  => $input['idPro'],
                    'merchantkey_pro' => $input['keyPro'],
                    'merchantid_dev'  => $input['idDev'],
                    'merchantkey_dev' => $input['keyDev'],
                    "active_pix"      => (empty($input['activePix'])) ? false : true,
                    "active_credito"  => (empty($input['activeCredito'])) ? false : true,
                    "active_debito"   => (empty($input['activeDebito'])) ? false : true,
                    "active_boletos"  => (empty($input['activeBoletos'])) ? false : true,
                ];

                if ($row = $this->modelGateway->where(['id_adm' => session('data')['idAdm'], 'tipo' => 'cielo'])->findAll()) {
                    $status = $this->modelGateway->update($row[0]['id'], $data);

                    if ($status === false) {
                        return $this->fail($this->modelGateway->errors());
                    }
                    $msg = "Cielo atualizada com sucesso.";
                } else {
                    $status = $this->modelGateway->insert($data);

                    if ($status === false) {
                        return $this->fail($this->modelGateway->errors());
                    }
                    $msg = "Cielo cadastrada com sucesso.";
                }
            } elseif ($input['tipo'] == 'bradesco') {
            } else {
                throw new Exception('Solicitação invalída', 1);
            }

            return $this->respondCreated(['msg' => $msg, 'id' => null]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        //
    }

    public function cielo_pix()
    {

        $input       = $input = $this->request->getVar();
        $cielo       = new GatewayCielo();
        $mTransacoes = new TransacoesModel();

        try {
            if (!intval(limparString($input['valor']))) {
                throw new Exception("O valor não foi informado.");
            }

            $params = [
                'MerchantOrderId' => $mTransacoes->getInsertID() + 1,
                'Customer'        => [
                    'Name'         => $input['nome'],
                    'Identity'     => $input['doc'],
                    'IdentityType' => $input['tipo'],
                ],
                'Payment' => [
                    'Type'   => 'Pix',
                    'Amount' => intval(limparString($input['valor'])),
                ],
            ];

            return $this->respond($params);

            exit;

        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }

        try {
            $response = $cielo->createPixCharge($params);

            return $this->respond([
                'payment' => $response['Payment']['PaymentId'],
                'qrCode'  => "data:image/png;base64,{$response['Payment']['QrCodeBase64Image']}",
                'payload' => $response,
            ]);
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function cielo_credito()
    {
        $params = $this->request->getJSON(true);

        try {
            // Exemplo de dados de cobrança
            $mTransacoes = new TransacoesModel();
            $params      = [
                "MerchantOrderId" => $mTransacoes->getInsertID() + 1,
                "Customer"        => [
                    "Name" => "Comprador Teste",
                ],
                "Payment" => [
                    "Type"         => "CreditCard",
                    "Amount"       => 10000, // valor em centavos, 10000 = R$ 100,00
                    "Installments" => 1,
                    "CreditCard"   => [
                        "CardNumber"     => "1234123412341231",
                        "Holder"         => "Comprador Teste",
                        "ExpirationDate" => "12/2025",
                        "SecurityCode"   => "123",
                        "Brand"          => "Visa",
                    ],
                ],
            ];

            $cielo = new GatewayCielo();

            $response = $cielo->createCreditCardCharge($params);

            return $this->respond([
                'status'         => 'success',
                'message'        => 'Cobrança criada com sucesso.',
                'data'           => $response,
                'payment_status' => $response['Payment']['Status'],
            ], 200);

        } catch (Exception $e) {

            return $this->fail([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 400);

        }
    }

    public function cielo_status($id = false)
    {
        try {
            if ($id) {
                $cielo = new GatewayCielo();

                return $this->respond($cielo->checkPaymentStatus($id));
            } else {
                throw new Exception('ID do pagamento não foi definido', 1);
            }
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }
}
