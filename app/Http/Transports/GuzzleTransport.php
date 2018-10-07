<?php

namespace App\Http\Transports;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GuzzleTransport implements TransportContract
{
    /** @var Client */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10,
            'http_errors' => false,
        ]);
    }

    public function get(string $uri, ?array $params = null, ?array $headers = null): string
    {
        try {
            $rsp = $this->client->get($uri, [
                'query' => $params ?? [],
                'headers' => $headers ?? [],
            ]);
        } catch (RequestException $e) {
            $exceptionClass = get_class($e);
            throw new TransportException("Guzzle exception: {$exceptionClass}; Message: {$e->getMessage()}", $e->getCode(), $e);
        }

        if (200 !== $rsp->getStatusCode()) {
            throw new TransportException('Invalid response code', $rsp->getStatusCode());
        }

        return $rsp->getBody()->getContents();
    }
}
