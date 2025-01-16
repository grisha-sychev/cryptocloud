<?php

namespace CryptoCloud;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class CryptoCloud
{
    private Client $client;
    private string $token;

    public function __construct(string $token, $verify = true)
    {
        $this->token = $token;
        $this->client = new Client([
            'base_uri' => 'https://api.cryptocloud.plus/v2/',
            'verify' => $verify,
        ]);
    }

    public function create(string $shopId, float $amount, string $currency = 'USD', string $order_id = '', string $email = '')
    {
        $data = [
            'shop_id' => $shopId,
            'amount' => $amount,
            'currency' => $currency,
            'order_id' => $order_id,
            'email' => $email,
        ];

        return $this->request('invoice/create', $data);
    }

    public function cancel(string $uuid)
    {
        $data = [
            'uuid' => $uuid,
        ];

        return $this->request('invoice/merchant/canceled', $data);
    }

    public function list(string $start, string $end, int $offset = 0, int $limit = 10)
    {
        $data = [
            'start' => $start,
            'end' => $end,
            'offset' => $offset,
            'limit' => $limit,
        ];

        return $this->request('invoice/merchant/list', $data);
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
        return $this->request('merchant/wallet/balance/all');
    }

    public function statistics(string $start, string $end)
    {
        $data = [
            'start' => $start,
            'end' => $end,
        ];

        return $this->request('invoice/merchant/statistics', $data);
    }

    public function static(string $shopId, string $currency, string $identify)
    {
        $data = [
            'shop_id' => $shopId,
            'currency' => $currency,
            'identify' => $identify,
        ];

        return $this->request('invoice/static/create', $data);
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
                return $response->getBody();
            } else {
                return [$response->getStatusCode(), $response->getBody()];
            }
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }
}
