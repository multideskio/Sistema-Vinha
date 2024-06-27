<?php

namespace App\Libraries;

use App\Models\AdminModel;
use Aws\S3\S3Client;
use CodeIgniter\HTTP\Files\UploadedFile;
use Exception;

class UploadsLibraries
{
    protected $s3Client;
    protected $cdnUrl;
    protected $bucketName;

    public function __construct()
    {
        try {
            $this->configureS3();
        } catch (Exception $e) {
            log_message('error', 'Erro ao configurar o S3: ' . $e->getMessage());
            throw new Exception('Erro ao configurar o S3: ' . $e->getMessage());
        }
    }

    private function configureS3()
    {
        $credentialsModel = new AdminModel();
        $credentials = $credentialsModel->first();

        if (!$credentials) {
            throw new Exception("Credenciais não encontradas no banco de dados.");
        }

        $requiredKeys = ['s3_access_key_id', 's3_secret_access_key', 's3_region', 's3_bucket_name'];
        foreach ($requiredKeys as $key) {
            if (empty($credentials[$key])) {
                throw new Exception("A chave {$key} não está definida nas credenciais.");
            }
        }

        $this->s3Client = new S3Client([
            'credentials' => [
                'key'    => $credentials['s3_access_key_id'],
                'secret' => $credentials['s3_secret_access_key'],
            ],
            'region'  => $credentials['s3_region'],
            'version' => 'latest',
        ]);

        $this->bucketName = $credentials['s3_bucket_name'];
        $this->cdnUrl = !empty($credentials['s3_cdn'])
            ? rtrim($credentials['s3_cdn'], '/')
            : "https://{$credentials['s3_bucket_name']}.s3.{$credentials['s3_region']}.amazonaws.com";
    }

    private function uploadToS3($key, $sourceFile, $contentType)
    {
        return $this->s3Client->putObject([
            'Bucket'      => $this->bucketName,
            'Key'         => $key,
            'SourceFile'  => $sourceFile,
            'ACL'         => 'public-read',
            'ContentType' => $contentType,
        ]);
    }

    public function perfil(UploadedFile $upload)
    {
        if ($upload->isValid() && !$upload->hasMoved()) {
            $filename = $upload->getRandomName();
            $tempPath = $upload->getTempName();

            try {
                $result = $this->uploadToS3('profile_pictures/' . $filename, $tempPath, $upload->getClientMimeType());
                $cdnPath = $this->cdnUrl . '/profile_pictures/' . $filename;
                echo 'Upload bem-sucedido! URL: ' . $cdnPath;
            } catch (Exception $e) {
                echo 'Erro ao fazer upload: ' . $e->getMessage();
            }
        } else {
            echo 'Nenhum arquivo enviado ou ocorreu um erro.';
        }
    }

    public function filePond(string $dir, int $idBd, array $input): array
    {
        try {
            if (isset($input['filepond'])) {
                $file = json_decode($input['filepond']);

                if ($file && isset($file->data)) {
                    $image_data = base64_decode($file->data);
                    if ($image_data === false) {
                        throw new Exception("Falha ao decodificar os dados da imagem.");
                    }

                    $image_name = uniqid() . '.webp';
                    $path = "{$dir}/{$idBd}/{$image_name}";

                    $result = $this->s3Client->putObject([
                        'Bucket'      => $this->bucketName,
                        'Key'         => $path,
                        'Body'        => $image_data,
                        'ACL'         => 'public-read',
                        'ContentType' => 'image/webp',
                    ]);

                    $cdnPath = $this->cdnUrl . '/' . $path;

                    return ['file' => $cdnPath, 'newName' => $cdnPath];
                }
            }

            throw new Exception("Nenhum arquivo de imagem foi encontrado no input.");
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function uploadCI(UploadedFile $file, int $id, string $tipo)
    {
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $path = "{$tipo}/{$id}/";

            try {
                $contents = $this->s3Client->listObjects([
                    'Bucket' => $this->bucketName,
                    'Prefix' => $path,
                ]);

                if (!empty($contents['Contents'])) {
                    foreach ($contents['Contents'] as $object) {
                        $this->s3Client->deleteObject([
                            'Bucket' => $this->bucketName,
                            'Key'    => $object['Key'],
                        ]);
                    }
                }

                $image_name = uniqid() . '.png';
                $tempPath = $file->getTempName();

                $result = $this->uploadToS3($path . $image_name, $tempPath, $file->getClientMimeType());

                $cdnPath = $this->cdnUrl . '/' . $path . $image_name;

                return ['foto' => $cdnPath];
            } catch (Exception $e) {
                throw new Exception("Erro ao fazer upload da imagem para o S3: " . $e->getMessage());
            }
        } else {
            throw new Exception("Nenhum arquivo de imagem foi encontrado no input.");
        }
    }

    public function testeS3()
    {
        try {
            $contents = $this->s3Client->listObjects([
                'Bucket' => $this->bucketName,
                'Prefix' => '',
                'MaxKeys' => 1
            ]);

            if ($contents !== false && isset($contents['Contents'])) {
                return ['status' => 'success', 'message' => 'Conexão com S3 estabelecida com sucesso.'];
            } else {
                throw new Exception("Falha ao listar o conteúdo do bucket. Resposta inesperada.");
            }
        } catch (Exception $e) {
            log_message('error', 'Erro ao testar a conexão com o S3: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro ao conectar com o S3: ' . $e->getMessage()];
        }
    }
}
