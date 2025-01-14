<?php

namespace CryptoCloud;

use CryptoCloud\Types\Decimal;
use CryptoCloud\Types\Dict;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class CryptoCloud
{
    private Client $client;
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
        $this->client = new Client([
            'base_uri' => 'https://api.cryptocloud.plus/v2/',
            'timeout'  => 5.0,
        ]);
    }

    public function authorization()
    {
        $this->request('invoice/create');
    }

    public function create(Decimal $amount, string $shopId, string $currency = '', Dict $add_fields, string $order_id = '', string $email = '')
    {
        $data = [
            'amount' => $amount,
            'shop_id' => $shopId,
            'currency' => $currency,
            'add_fields' => $add_fields->toArray(),
            'order_id' => $order_id,
            'email' => $email
        ];

        $this->request('invoice/create', $data);
    }

    public function cancel(string $uuid)
    {
        $data = [
            'uuid' => $uuid,
        ];

        $this->request('invoice/merchant/canceled', $data);
    }

    public function list(string $start, string $end, int $offset = 0, int $limit = 10)
    {
        $data = [
            'start' => $start,
            'end' => $end,
            'offset' => $offset,
            'limit' => $limit,
        ];

        $this->request('invoice/merchant/list', $data);
    }

    public function info(array $uuids)
    {
        $data = [
            'uuids' => $uuids,
        ];

        $this->request('invoice/merchant/info', $data);
    }

    public function balance()
    {
        $this->request('merchant/wallet/balance/all');
    }

    public function statistics(string $start, string $end)
    {
        $data = [
            'start' => $start,
            'end' => $end,
        ];

        $this->request('invoice/merchant/statistics', $data);
    }

    public function static(string $shopId, string $currency, string $identify)
    {
        $data = [
            'shop_id' => $shopId,
            'currency' => $currency,
            'identify' => $identify,
        ];

        $this->request('invoice/static/create', $data);
    }

    public function postback()
    {
        $body = file_get_contents('php://input');
        $data = [];

        parse_str($body, $data);

        return [
            'status' => $data['status'] ?? null,
            'invoice_id' => $data['invoice_id'] ?? null,
            'amount_crypto' => $data['amount_crypto'] ?? null,
            'currency' => $data['currency'] ?? null,
            'order_id' => $data['order_id'] ?? null,
            'token' => $data['token'] ?? null,
        ];
    }

    private function request(string $endpoint, array $data = [])
    {
        try {
            $options = [
                'headers' => [
                    'Authorization' => 'Token ' . $this->token,
                    'Content-Type' => 'application/json',
                ],
            ];

            if (!empty($data)) {
                $options['json'] = $data;
            }

            $response = $this->client->post($endpoint, $options);

            if ($response->getStatusCode() === 200) {
                echo "Success: " . $response->getBody();
            } else {
                echo "Fail: " . $response->getStatusCode() . " " . $response->getBody();
            }
        } catch (RequestException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}


// // Пример использования
// $token = "eyJ0eXAi1iJKV1QiLCJhbGciOiJIAcI1NiJ9.eyJpZCI6MTMsImV4cCI6MTYzMTc4NjQyNn0.HQavV3z8dFnk56bX3MSY5X9lR6qVa9YhAoeTEH";
// $cryptoCloud = new Cryptocloud($token);
