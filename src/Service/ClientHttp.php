<?php

namespace App\Service;

use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ClientHttp
{
    private $clientHttp;

    public function __construct()
    {
        $this->clientHttp = HttpClient::create();        
    }

    public function getHttpCodeStatus(string $url)
    {
        $response = 404;

        try {
            $response = $this->clientHttp->request('GET', $url)->getStatusCode();
        } catch(Exception $exception) {
            return $response;
        }

        return $response;
    }
}