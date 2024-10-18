<?php


if (!function_exists('gera_token')) {
    function gera_token($text = false): string
    {
        if ($text) {
            $base64Token = substr(hash('sha256', $text), 0, 60);
        } else {
            $base64Token = substr(hash('sha256', time()), 0, 60);
        }

        return $base64Token;
    }
}

if(!function_exists('removerAcentos')){
    function removerAcentos($string): array|string|null
    {
        return preg_replace(
            array(
                '/[áàâãäå]/u', '/[ÁÀÂÃÄÅ]/u',
                '/[éèêë]/u',   '/[ÉÈÊË]/u',
                '/[íìîï]/u',   '/[ÍÌÎÏ]/u',
                '/[óòôõöø]/u', '/[ÓÒÔÕÖØ]/u',
                '/[úùûü]/u',   '/[ÚÙÛÜ]/u',
                '/[ñ]/u',      '/[Ñ]/u',
                '/[ç]/u',      '/[Ç]/u'
            ),
            array('a','A','e','E','i','I','o','O','u','U','n','N','c','C'),
            $string
        );
    }
}
//Gera quantidade de dias em segundos para o cache
if (!function_exists('getCacheExpirationTimeInSeconds')) {

    function getCacheExpirationTimeInSeconds(int $days): string
    {
        // Convertendo dias em segundos
        return $days * 24 * 60 * 60;
    }
}

//limpa as strings antes de mandar para o banco de dados
if (!function_exists('limparString')) {

    function limparString($string): string
    {
        // Limpa o strings removendo caracteres indesejados

        /* The line `return preg_replace('/[^0-9]/', '', );` in the `limparString` function is
        using a regular expression to remove all non-numeric characters from the input string
        ``. */
        return preg_replace('/[^0-9]/', '', $string);
        //return $string;
    }
}

//Primeira letra do nome
if (!function_exists('primeira_letra')) {

    function primeira_letra($value): string
    {
        // Converter a string em um array de caracteres
        $caracteres = str_split($value);
        // Pegar o primeiro elemento do array (a primeira letra)
        return strtoupper($caracteres[0]);
    }
}

if (!function_exists('placehold')) {


    function placehold($height = false, $width = false, $text = false, $colorFundo = false, $colorFonte = false): string
    {
        // Construir a parte inicial da URL da imagem
        $image = "https://placehold.co/";

        // Verificar se $height e $width estão definidos
        if ($height && $width) {
            // Adicionar altura e largura à URL da imagem
            $image .= $width."x".$height."/";
        } else {
            $image .= "90/";
        }

        // Verificar se $colorFundo está definido
        if ($colorFundo) {
            // Adicionar cor de fundo à URL da imagem
            $image .= "$colorFundo/";
        } else {
            // Gerar uma cor de fundo aleatória se não for fornecida uma cor de fundo
            $randomColor = dechex(random_int(0, 16777215));
            $image .= "$randomColor/";
        }

        // Verificar se $colorFonte está definido
        if ($colorFonte) {
            // Adicionar cor da fonte à URL da imagem
            $image .= "$colorFonte/";
        }

        // Verificar se o texto não está vazio e é uma string
        if ($text && is_string($text)) {
            // Obter a primeira letra do texto
            $letra = primeira_letra($text);
            // Adicionar o parâmetro de texto à URL da imagem
            $image .= "?text=$letra";
        }

        return $image;
    }
}

if (!function_exists('saudacao')) {

    function saudacao(): string
    {
        date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para o Brasil

        $hora = date('H');

        if ($hora >= 5 && $hora < 12) {
            return "Bom dia";
        }

        if ($hora >= 12 && $hora < 18) {
            return "Boa tarde";
        }

        return "Boa noite";
    }
}

if (!function_exists('deleteFolder')) {

    function deleteFolder($folderPath): bool
    {
        // Verifica se o caminho especificado é um diretório
        if (is_dir($folderPath)) {
            // Abre o diretório
            $folderHandle = opendir($folderPath);

            // Percorre todos os itens no diretório
            while ($item = readdir($folderHandle)) {
                // Ignora os itens especiais '.' e '..'
                if ($item !== "." && $item !== "..") {
                    // Se o item for um diretório, chama a função de exclusão recursivamente
                    if (is_dir($folderPath . "/" . $item)) {
                        deleteFolder($folderPath . "/" . $item);
                    } else {
                        // Se o item for um arquivo, exclui-o
                        unlink($folderPath . "/" . $item);
                    }
                }
            }

            // Fecha o manipulador do diretório
            closedir($folderHandle);

            // Exclui o diretório vazio
            rmdir($folderPath);

            return true;
        }

        return false;
    }
}

if (!function_exists('centavosParaReais')) {
    function centavosParaReais($valorEmCentavos): string
    {
        // Divide o valor recebido por 100 para converter de centavos para reais
        $valorEmReais = $valorEmCentavos / 100;

        // Formata o número para 2 casas decimais
        return number_format($valorEmReais, 2, '.', '');
    }
}

if (!function_exists('centavosParaReaisBrasil')) {
    function centavosParaReaisBrasil($valorEmCentavos): string
    {
        // Divide o valor recebido por 100 para converter de centavos para reais
        $valorEmReais = $valorEmCentavos / 100;

        // Formata o número para o formato brasileiro de moeda
        return 'R$ ' . number_format($valorEmReais, 2, ',', '.');
    }
}

if (!function_exists('decimalParaReaisBrasil')) {
    function decimalParaReaisBrasil($valor): string
    {
        // Verifica se o valor é null
        if (is_null($valor)) {
            // Decida o que fazer com valores nulos
            return '0,00'; // Por exemplo, retorna '0,00'
        }

        return number_format($valor, 2, ',', '.');
    }
}

if (!function_exists('getCardType')) {
    function getCardType($number): string
    {
        $number = preg_replace('/\D/', '', $number); // Remove caracteres não numéricos

        // Primeiro conjunto de padrões de cartão, baseado nos intervalos tradicionais
        $cardTypes = [
            'Visa'      => '/^4[0-9]{12}(?:[0-9]{3})?$/',
            'Master'    => '/^5[1-5][0-9]{14}$/',  // Mastercard tradicional (BINs 51-55)
            'Amex'      => '/^3[47][0-9]{13}$/',
            'ELO'       => '/^((636368|438935|504175|451416|636297|5067|4576|4011)\d{0,10})$/',
            'Aura'      => '/^50[0-9]{14,17}$/',
            'Diners'    => '/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',
            'Discover'  => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
            'JCB'       => '/^(?:2131|1800|35\d{3})\d{11}$/',
            'Hipercard' => '/^(606282\d{10}(\d{3})?)|(3841\d{15})$/',
        ];

        // Segundo conjunto de padrões, para detectar novos intervalos de Mastercard (incluindo Infinitpay)
        $extraMastercardPatterns = [
            // Mastercard novos BINs (2221-2720) e possível BIN da Infinitpay
            '/^(5[1-5][0-9]{14}|2(?:2[2-9][0-9]{12}|[3-6][0-9]{13}|7[0-1][0-9]{12}|720[0-9]{12}))$/', // Mastercard novos BINs
            '/^231038[0-9]{10}$/',  // Exemplo de BIN específico da Infinitpay (mas retorna "Master")
        ];

        // Primeiro loop: verificar no conjunto tradicional de cartões
        foreach ($cardTypes as $type => $pattern) {
            if (preg_match($pattern, $number)) {
                return $type;
            }
        }

        // Segundo loop: verificar no conjunto extra de Mastercard
        foreach ($extraMastercardPatterns as $pattern) {
            if (preg_match($pattern, $number)) {
                return 'Master';  // Retorna sempre "Master" se encontrado no segundo array
            }
        }

        return 'Unknown'; // Retorna 'Unknown' se não for reconhecido em nenhum dos arrays
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date): string
    {
        // Verifica se a data não está vazia e se é uma data válida
        if ($date && strtotime($date) !== false) {
            // Formata a data para o formato desejado
            return date("d/m/Y H:i:s", strtotime($date));
        }

        return ""; // Retorna uma string vazia se a data não for válida
    }
}

if (!function_exists('createSlug')) {
    function createSlug($string): string
    {
        // Converter todos os caracteres para minúsculas
        $string = strtolower($string);

        // Remover acentuação
        $unwanted_array = [
            'á' => 'a',
            'à' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'ä' => 'a',
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'í' => 'i',
            'ì' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'ó' => 'o',
            'ò' => 'o',
            'õ' => 'o',
            'ô' => 'o',
            'ö' => 'o',
            'ú' => 'u',
            'ù' => 'u',
            'û' => 'u',
            'ü' => 'u',
            'ç' => 'c',
            'ñ' => 'n',
            'Á' => 'A',
            'À' => 'A',
            'Ã' => 'A',
            'Â' => 'A',
            'Ä' => 'A',
            'É' => 'E',
            'È' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Í' => 'I',
            'Ì' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'Ó' => 'O',
            'Ò' => 'O',
            'Õ' => 'O',
            'Ô' => 'O',
            'Ö' => 'O',
            'Ú' => 'U',
            'Ù' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'Ç' => 'C',
            'Ñ' => 'N',
        ];
        $string = strtr($string, $unwanted_array);

        // Substituir quaisquer caracteres que não sejam letras, números ou espaços por hífens
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);

        // Substituir espaços e múltiplos hífens por um único hífen
        $string = preg_replace('/[\s-]+/', '-', $string);

        // Remover hífens iniciais e finais
        return trim($string, '-');
    }
}
