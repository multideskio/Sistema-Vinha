<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class HtmlMinify implements FilterInterface
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
        //
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
        // Obter o conteúdo da resposta
        $content = $response->getBody();

        // Minificar o HTML
        $minifiedContent = $this->minifyHtml($content);

        // Definir o conteúdo minificado de volta na resposta
        $response->setBody($minifiedContent);
    }

    protected function minifyHtml($html)
    {
        $search = [
            '/\>[^\S ]+/s',     // Remove espaços em branco depois de tags, exceto espaço
            '/[^\S ]+\</s',     // Remove espaços em branco antes de tags, exceto espaço
            '/(\s)+/s'          // Reduz múltiplos espaços em um único espaço
        ];

        $replace = [
            '>',
            '<',
            '\\1'
        ];

        return preg_replace($search, $replace, $html);
    }
}
