<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Igreja implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {

        // Carregue o helper de URL
        helper('url');

        // Verifique se o usuário está conectado
        $loggedSession = session('data');


        if (!isset($loggedSession['isConnected'])) {
            // Se não estiver conectado, defina uma mensagem de erro na sessão flash
            session()->setFlashdata("error", lang("Errors.login"));

            // Obtenha a URL atual
            $href = current_url();

            // Redirecione para a página de login com o parâmetro de consulta 'redirect' contendo a URL atual
            return redirect()->to(site_url("?redirect={$href}"));
        }

        if ($loggedSession['nivel'] != 4) {
            if ($loggedSession['nivel'] == 2) {
                return redirect()->redirect('/gerente');
            }
            if ($loggedSession['nivel'] == 3) {
                return redirect()->redirect('/supervisao');
            }
            if ($loggedSession['nivel'] == 1) {
                return redirect()->redirect('/admin');
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
