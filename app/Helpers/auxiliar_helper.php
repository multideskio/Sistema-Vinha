<?php

if (!function_exists('gera_token')) {
    /**
     * The function "gera_token" generates a token using SHA-256 hashing algorithm with optional input
     * text or current timestamp.
     *
     * @param text The `text` parameter in the `gera_token` function is optional. If a value is
     * provided for `text`, the function will generate a token based on the SHA-256 hash of the
     * provided text. If no value is provided for `text`, the function will generate a token based on
     *
     * @return string The function `gera_token` returns a string that is a 60-character long base64
     * encoded token generated using SHA-256 hashing algorithm. If a text input is provided, the token
     * is generated based on the hash of the text. If no text is provided, the token is generated based
     * on the hash of the current timestamp.
     */
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

//Gera quantidade de dias em segundos para o cache
if (!function_exists('getCacheExpirationTimeInSeconds')) {
    /**
     * The function `getCacheExpirationTimeInSeconds` converts days to seconds.
     *
     * @param int days The function `getCacheExpirationTimeInSeconds` takes an integer parameter
     * `` representing the number of days for which the cache should be valid. The function
     * calculates and returns the expiration time in seconds based on the provided number of days.
     *
     * @return string the cache expiration time in seconds, which is calculated by multiplying the
     * number of days by 24 hours, 60 minutes, and 60 seconds.
     */
    function getCacheExpirationTimeInSeconds(int $days): string
    {
        // Convertendo dias em segundos
        return $days * 24 * 60 * 60;
    }
}

//limpa as strings antes de mandar para o banco de dados
if (!function_exists('limparString')) {
    /**
     * The function "limparString" in PHP removes unwanted characters from a given CNPJ string.
     *
     * @param cnpj The parameter `` in the `limparString` function is expected to be a string
     * representing a CNPJ (Cadastro Nacional da Pessoa Jurídica), which is a unique identifier for
     * Brazilian legal entities. The function is designed to clean up the CNPJ string by removing any
     * characters that are
     *
     * @return string The function `limparString` is returning a string that is the result of removing
     * any non-numeric characters from the input `` using a regular expression.
     */
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
    /**
     * The function "primeira_letra" in PHP takes a string input and returns the first letter of the
     * string in uppercase.
     *
     * @param value The function `primeira_letra` takes a string as input and returns the first letter
     * of that string in uppercase.
     *
     * @return The function `primeira_letra` takes a string as input, converts it into an array of
     * characters, retrieves the first element of the array (which is the first letter of the string),
     * converts it to uppercase, and then returns this first letter.
     */
    function primeira_letra($value)
    {
        // Converter a string em um array de caracteres
        $caracteres = str_split($value);
        // Pegar o primeiro elemento do array (a primeira letra)
        $primeiraLetra = strtoupper($caracteres[0]);

        return $primeiraLetra;
    }
}

if (!function_exists('placehold')) {
    /**
     * The function `placehold` generates a URL for a placeholder image with customizable dimensions,
     * text, background color, and font color.
     *
     * @param height The `height` parameter is used to specify the height of the image placeholder that
     * will be generated. It determines the vertical size of the placeholder image.
     * @param width The `width` parameter in the `placehold` function is used to specify the width of
     * the image placeholder that will be generated. If a specific width is provided when calling the
     * function, it will be used to determine the width of the placeholder image. If no width is
     * provided, a default width
     * @param text The `text` parameter in the `placehold` function is used to specify the text that
     * will be displayed on the generated image. If provided, the function will extract the first
     * letter of the text and add it as a parameter to the URL of the image. If the `text` parameter is
     * @param colorFundo The `` parameter in the `placehold` function is used to specify the
     * background color of the image generated by the function. If a color is provided for
     * ``, it will be used as the background color in the image URL. If no color is
     * provided, a
     * @param colorFonte The `colorFonte` parameter in the `placehold` function is used to specify the
     * color of the font in the generated image. If you provide a color value for `colorFonte`, it will
     * be added to the URL of the image. If you don't provide a value for `
     *
     * @return The function `placehold` returns a URL for a placeholder image based on the provided
     * parameters. The URL includes the specified height, width, background color, font color, and text
     * (if provided). If certain parameters are not provided, default values or random values are used
     * to generate the URL.
     */
    function placehold($height = false, $width = false, $text = false, $colorFundo = false, $colorFonte = false)
    {
        // Construir a parte inicial da URL da imagem
        $image = "https://placehold.co/";

        // Verificar se $height e $width estão definidos
        if ($height && $width) {
            // Adicionar altura e largura à URL da imagem
            $image .= "{$width}x{$height}/";
        } else {
            $image .= "90/";
        }

        // Verificar se $colorFundo está definido
        if ($colorFundo) {
            // Adicionar cor de fundo à URL da imagem
            $image .= "{$colorFundo}/";
        } else {
            // Gerar uma cor de fundo aleatória se não for fornecida uma cor de fundo
            $randomColor = dechex(mt_rand(0, 16777215));
            $image .= "{$randomColor}/";
        }

        // Verificar se $colorFonte está definido
        if ($colorFonte) {
            // Adicionar cor da fonte à URL da imagem
            $image .= "{$colorFonte}/";
        }

        // Verificar se o texto não está vazio e é uma string
        if ($text && is_string($text)) {
            // Obter a primeira letra do texto
            $letra = primeira_letra($text);
            // Adicionar o parâmetro de texto à URL da imagem
            $image .= "?text={$letra}";
        }

        return $image;
    }
}

if (!function_exists('saudacao')) {
    /**
     * This PHP function returns a greeting message based on the current time of day in the timezone
     * set to America/Sao_Paulo.
     *
     * @return The function `saudacao()` returns a greeting based on the current time in the timezone
     * set to 'America/Sao_Paulo'. If the time is between 5:00 and 11:59, it returns "Bom dia" (Good
     * morning). If the time is between 12:00 and 17:59, it returns "Boa tarde" (Good afternoon).
     */
    function saudacao()
    {
        date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para o Brasil

        $hora = date('H');

        if ($hora >= 5 && $hora < 12) {
            return "Bom dia";
        } elseif ($hora >= 12 && $hora < 18) {
            return "Boa tarde";
        } else {
            return "Boa noite";
        }
    }
}

if (!function_exists('deleteFolder')) {
    // Função para excluir uma pasta e seu conteúdo recursivamente
    /**
     * The function `deleteFolder` recursively deletes a folder and all its contents in PHP.
     *
     * @param folderPath The `deleteFolder` function you provided is a PHP function that recursively
     * deletes a folder and all its contents. The `folderPath` parameter is the path to the folder that
     * you want to delete.
     *
     * @return The function `deleteFolder` returns `true` if the folder deletion process is successful,
     * and `false` if the specified path is not a directory.
     */
    function deleteFolder($folderPath)
    {
        // Verifica se o caminho especificado é um diretório
        if (is_dir($folderPath)) {
            // Abre o diretório
            $folderHandle = opendir($folderPath);

            // Percorre todos os itens no diretório
            while ($item = readdir($folderHandle)) {
                // Ignora os itens especiais '.' e '..'
                if ($item != "." && $item != "..") {
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
        } else {
            return false;
        }
    }
}

if (!function_exists('centavosParaReais')) {
    function centavosParaReais($valorEmCentavos)
    {
        // Divide o valor recebido por 100 para converter de centavos para reais
        $valorEmReais = $valorEmCentavos / 100;

        // Formata o número para 2 casas decimais
        return number_format($valorEmReais, 2, '.', '');
    }
}

if (!function_exists('centavosParaReaisBrasil')) {
    function centavosParaReaisBrasil($valorEmCentavos)
    {
        // Divide o valor recebido por 100 para converter de centavos para reais
        $valorEmReais = $valorEmCentavos / 100;

        // Formata o número para o formato brasileiro de moeda
        return 'R$ ' . number_format($valorEmReais, 2, ',', '.');
    }
}

if (!function_exists('decimalParaReaisBrasil')) {
    function decimalParaReaisBrasil($valor)
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
    function getCardType($number)
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
    function formatDate($date)
    {
        // Verifica se a data não está vazia e se é uma data válida
        if ($date && strtotime($date) !== false) {
            // Formata a data para o formato desejado
            return date("d/m/Y H:i:s", strtotime($date));
        } else {
            return ""; // Retorna uma string vazia se a data não for válida
        }
    }
}

if (!function_exists('createSlug')) {
    function createSlug($string)
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
        $string = trim($string, '-');

        return $string;
    }
}
