<?php

namespace App\Libraries;

class UploadsLibraries
{

    public function perfil($upload)
    {
        // Verifica se o upload foi bem-sucedido
        if ($upload->isValid() && !$upload->hasMoved()) {
            // Configurações de upload (você pode ajustar conforme necessário)
            $upload->setDestination('/caminho/para/armazenar/os/arquivos/');

            // Move o arquivo para o destino
            if ($upload->move()) {
                // Sucesso, faça o que precisar com o arquivo
                echo 'Upload bem-sucedido!';
            } else {
                // O upload falhou, obtenha os erros
                $errors = $upload->getErrors();
                print_r($errors);
            }
        } else {
            // Não há arquivo ou ocorreu um erro durante o upload
            echo 'Nenhum arquivo enviado ou ocorreu um erro.';
        }
    }

    public function filePond(string $dir, int $idBd, array $input): array
    {
        try {
            if (isset($input['filepond'])) {
                // Decodifica os dados JSON enviados pelo FilePond
                $file = json_decode($input['filepond']);

                // Verifica se os dados foram decodificados corretamente e se contêm uma imagem
                if ($file && isset($file->data)) {
                    // Decodifica a string base64 da imagem de volta para os dados binários da imagem
                    $image_data = base64_decode($file->data);
                    if ($image_data === false) {
                        throw new \Exception("Falha ao decodificar os dados da imagem.");
                    }

                    // Define o caminho onde você deseja salvar a imagem
                    $image_path = FCPATH . "assets/img/{$dir}/{$idBd}/";

                    // Cria o diretório se ele não existir
                    if (!is_dir($image_path) && !mkdir($image_path, 0777, true) && !is_dir($image_path)) {
                        throw new \Exception("Falha ao criar o diretório: {$image_path}");
                    }

                    // Gera um nome de arquivo único para a imagem
                    $image_name = uniqid();

                    // Salva a imagem no servidor
                    $file_path = $image_path . $image_name . '.png';
                    if (file_put_contents($file_path, $image_data) === false) {
                        throw new \Exception("Falha ao salvar a imagem no caminho: {$file_path}");
                    }

                    $image = \Config\Services::image();

                    // Converte a imagem para WEBP e salva
                    $webp_path = $image_path . $image_name . '.webp';
                    if (!$image->withFile($file_path)
                        ->resize(50, 50, true, 'height')
                        ->convert(IMAGETYPE_WEBP)
                        ->save($webp_path)) {
                        throw new \Exception("Falha ao converter a imagem para WEBP.");
                    }

                    // Remove o arquivo .png após a conversão
                    if (!unlink($file_path)) {
                        throw new \Exception("Falha ao remover o arquivo PNG temporário.");
                    }

                    return ['file' => $webp_path, 'newName' => "/assets/img/{$dir}/{$idBd}/{$image_name}.webp"];
                }
            }

            throw new \Exception("Nenhum arquivo de imagem foi encontrado no input.");
        } catch (\Exception $e) {
            // Retorna o erro encontrado para diagnóstico
            return ['error' => $e->getMessage()];
        }
    }
}
