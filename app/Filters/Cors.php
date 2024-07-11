<?PHP namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
//use CodeIgniter\Log\Logger;

class Cors implements FilterInterface
{
    protected $logger;

    public function __construct()
    {
        $this->logger = service('logger');
    }

    public function before(RequestInterface $request, $arguments = null)
    {

        $response = service('response');

        session()->set(['id' => '']);

        // Lista de origens permitidas
        $allowedOrigins = ['http://localhost:8080', 'https://vinhaonline.com', 'https://vinha.conect.app'];
        
        $origin = $request->getServer('HTTP_ORIGIN') ?? '';

        // Se a origem não é permitida e não é uma requisição do mesmo domínio, retornar 403
        if (!empty($origin) && !in_array($origin, $allowedOrigins)) {
            $this->logger->debug('CORS bloqueado para a origem: ' . $origin);
            return $response->setStatusCode(403)->setBody('CORS policy does not allow this origin')->send();
        }

        // Definir cabeçalhos CORS se a origem é permitida ou se é uma requisição do mesmo domínio
        if (empty($origin) || in_array($origin, $allowedOrigins)) {
            $response->setHeader('Access-Control-Allow-Origin', $origin ? $origin : '*');
            $response->setHeader('Access-Control-Allow-Headers', 'X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Requested-Method, Authorization');
            $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PATCH, PUT, DELETE');
        }

        // Verificar se o método é OPTIONS e parar a execução
        if ($request->getServer('REQUEST_METHOD') === 'OPTIONS') {
            $response->setStatusCode(200);
            return $response->send();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nenhuma ação necessária após a resposta
    }
}
