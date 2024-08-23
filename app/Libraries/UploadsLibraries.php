<?php

namespace App\Libraries;

use App\Models\AdminModel;
use Aws\S3\S3Client;
use CodeIgniter\HTTP\Files\UploadedFile;
use Exception;

/**
 * Class UploadsLibraries
 *
 * Gerencia o upload de arquivos, suportando Amazon S3 e armazenamento local.
 */
class UploadsLibraries
{
    protected $s3Client;
    protected $cdnUrl;
    protected $bucketName;
    protected $useS3 = false;

    /**
     * UploadsLibraries constructor.
     * Inicializa a configuração do S3 ou define o modo local.
     */
    public function __construct()
    {
        $this->initializeS3();
    }

    /**
     * Inicializa o cliente S3 com base nas credenciais armazenadas.
     */
    private function initializeS3()
    {
        try {
            $credentialsModel = new AdminModel();
            $credentials = $credentialsModel->first();

            if (!$credentials) {
                throw new Exception('Credenciais do S3 não encontradas.');
            }

            $requiredKeys = ['s3_access_key_id', 's3_secret_access_key', 's3_region', 's3_bucket_name'];
            foreach ($requiredKeys as $key) {
                if (empty($credentials[$key])) {
                    throw new Exception("Chave S3 faltante: {$key}");
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
                : "https://{$this->bucketName}.s3.{$credentials['s3_region']}.amazonaws.com";

            $this->useS3 = true;
            log_message('info', '[Linha ' . __LINE__ . '] S3 configurado com sucesso.');
        } catch (Exception $e) {
            log_message('error', '[Linha ' . __LINE__ . '] Falha ao configurar S3: ' . $e->getMessage());
            $this->useS3 = false;
        }
    }

    /**
     * Realiza o upload de um arquivo para o S3 ou localmente.
     *
     * @param UploadedFile $file Instância do arquivo a ser carregado.
     * @param string $path Caminho relativo onde o arquivo será salvo.
     * @return string URL do arquivo salvo.
     * @throws Exception Se houver erro durante o upload.
     */
    public function upload(UploadedFile $file, string $path): string
    {
        if (!$file->isValid() || $file->hasMoved()) {
            throw new Exception("Arquivo inválido ou já movido.");
        }

        try {
            // Tenta fazer o upload para o S3
            if ($this->useS3) {
                return $this->uploadToS3($file->getTempName(), $path, $file->getClientMimeType());
            }
        } catch (Exception $s3Exception) {
            // Se falhar, tenta o upload local
            log_message('error', '[Linha ' . __LINE__ . '] Falha no upload para S3: ' . $s3Exception->getMessage());
            // Continua para o upload local abaixo
        }

        // Se o upload para S3 falhar ou S3 não estiver configurado, faz o upload local
        try {
            return $this->uploadToLocal($file, $path);
        } catch (Exception $localException) {
            log_message('error', '[Linha ' . __LINE__ . '] Falha no upload local: ' . $localException->getMessage());
            throw new Exception('Falha ao fazer upload: ' . $localException->getMessage());
        }
    }


    /**
     * Faz o upload de um arquivo para o S3.
     *
     * @param string $tempPath Caminho temporário do arquivo.
     * @param string $path Caminho relativo no S3.
     * @param string $contentType Tipo de conteúdo MIME.
     * @return string URL do arquivo no S3.
     */
    private function uploadToS3(string $tempPath, string $path, string $contentType): string
    {
        $this->s3Client->putObject([
            'Bucket'      => $this->bucketName,
            'Key'         => $path,
            'SourceFile'  => $tempPath,
            'ACL'         => 'public-read',
            'ContentType' => $contentType,
        ]);
        return "{$this->cdnUrl}/{$path}";
    }

    /**
     * Faz o upload de um arquivo para o servidor local.
     *
     * @param UploadedFile $file Instância do arquivo.
     * @param string $path Caminho relativo onde o arquivo será salvo.
     * @return string URL do arquivo local.
     * @throws Exception Se houver erro ao mover o arquivo.
     */
    private function uploadToLocal(UploadedFile $file, string $path): string
    {
        $localDir = FCPATH . 'uploads/' . dirname($path);

        if (!is_dir($localDir)) {
            mkdir($localDir, 0755, true);
            log_message('info', '[Linha ' . __LINE__ . "] Diretório criado: {$localDir}");
        }

        $filePath = $localDir . '/' . basename($path);
        if (!$file->move($localDir, basename($path))) {
            throw new Exception('Erro ao mover o arquivo para o servidor local.');
        }

        return base_url('uploads/' . $path);
    }

    /**
     * Testa a conexão com o S3.
     *
     * @return array Status da conexão e mensagem.
     */
    public function testConnection(): array
    {
        if ($this->useS3) {
            try {
                $this->s3Client->listObjects(['Bucket' => $this->bucketName, 'MaxKeys' => 1]);
                log_message('info', '[Linha ' . __LINE__ . '] Conexão com S3 bem-sucedida.');
                return ['status' => 'success', 'message' => 'Conexão com S3 bem-sucedida.'];
            } catch (Exception $e) {
                log_message('error', '[Linha ' . __LINE__ . '] Falha ao conectar com S3: ' . $e->getMessage());
                return ['status' => 'error', 'message' => 'Falha ao conectar com S3: ' . $e->getMessage()];
            }
        } else {
            log_message('info', '[Linha ' . __LINE__ . '] Operando em modo local.');
            return ['status' => 'success', 'message' => 'S3 não configurado. Operando em modo local.'];
        }
    }
}
