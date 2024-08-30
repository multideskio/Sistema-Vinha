<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cors extends BaseConfig
{
    public array $default = [
        'allowedOrigins' => ['http://localhost:8080', 'https://vinhaonline.com', 'https://vinha.conect.app'],
        'allowedOriginsPatterns' => [],
        'supportsCredentials' => false,
        'allowedHeaders' => [
            'X-API-KEY', 
            'Origin', 
            'X-Requested-With', 
            'Content-Type', 
            'Accept', 
            'Access-Control-Requested-Method', 
            'Authorization', 
            'Accept-Language'
        ],
        'exposedHeaders' => [],
        'allowedMethods' => ['GET', 'POST', 'OPTIONS', 'PATCH', 'PUT', 'DELETE'],
        'maxAge' => 7200
    ];

    public array $api = [
        'allowedOrigins' => ['https://vinha.conect.app'],
        'allowedOriginsPatterns' => [],
        'supportsCredentials' => true,
        'allowedHeaders' => ['Authorization', 'Content-Type', 'X-Requested-With'],
        'exposedHeaders' => [],
        'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE'],
        'maxAge' => 3600
    ];
}
