<?php

namespace Yosinan\Chapavel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yosinan\Chapavel\Exceptions\ChapaException;

class ChapaClient
{
    private Client $http;
    private string $secret;
    private string $base;

    public function __construct()
    {
        $this->secret = config('chapa.secret_key');
        $this->base   = rtrim(config('chapa.base_url'), '/');
        $this->http   = new Client([
            'timeout' => (int) config('chapa.timeout', 10),
            'headers' => ['Authorization' => "Bearer {$this->secret}", 'Accept' => 'application/json'],
        ]);
    }

    public function initialize(array $payload): array
    {
        // required keys enforced at controller layer; here do a minimal sanity check
        $endpoint = "{$this->base}/v1/transaction/initialize";
        return $this->request('POST', $endpoint, ['json' => $payload]);
    }

    public function verify(string $txRef): array
    {
        $endpoint = "{$this->base}/v1/transaction/verify/" . rawurlencode($txRef);
        return $this->request('GET', $endpoint);
    }

    private function request(string $method, string $url, array $opts = []): array
    {
        try {
            $res = $this->http->request($method, $url, $opts);
            return json_decode((string) $res->getBody(), true) ?: [];
        } catch (GuzzleException $e) {
            throw new ChapaException($e->getMessage(), previous: $e);
        }
    }
}
