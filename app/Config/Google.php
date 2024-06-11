<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/* The Google class extends BaseConfig and provides methods to create a Google client with specified
client ID and client secret. */
class Google extends BaseConfig
{
    public $clientId = null;
    public $clientSecret = null;

    /**
     * The above function is a PHP constructor that initializes the clientId and clientSecret
     * properties with values retrieved from environment variables.
     */
    public function __construct()
    {
        $this->clientId = getenv('Google.Client.Id');
        $this->clientSecret = getenv('Google.Client.Secret');
    }

    /**
     * The function creates a Google client object with specified client ID, client secret, and scopes.
     * 
     * @return An instance of the Google_Client class with the specified client ID, client secret, and
     * added scopes for 'openid', 'profile', and 'email'.
     */
    public function createGoogleClient()
    {
        $client = new \Google_Client();
        $client->setClientId($this->clientId);
        $client->setClientSecret($this->clientSecret);
        $client->addScope('openid');
        $client->addScope('profile');
        $client->addScope('email');

        // Adicione outras configurações necessárias, se houver

        return $client;
    }
}
