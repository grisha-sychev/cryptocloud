<?php

namespace CryptoCloud;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class CryptoCloud
{
    private Client $client;
    private string $token;
    private string $shopId;

    public function __construct(string $token = '', string $shopId = '',  $verify = true)
    {
        $this->token = $token;
        $this->shopId = $shopId;

        $this->client = new Client([
            'base_uri' => 'https://api.cryptocloud.plus/v2/',
            'verify' => $verify,
        ]);
    }

    public function create(float $amount, string $currency = 'USD', string $order_id = '', string $email = '')
    {
        $data = [
            'shop_id' => $this->shopId,
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

    public function static(string $currency, string $identify)
    {
        $data = [
            'shop_id' => $this->shopId,
            'currency' => $currency,
            'identify' => $identify,
        ];

        return $this->request('invoice/static/create', $data);
    }

    public function postback()
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        parse_str($body, $data);
        $array = json_decode(json_encode($data, JSON_PRETTY_PRINT));

        return (array) $array;
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
                return json_decode($response->getBody());
            } else {
                return [$response->getStatusCode(), $response->getBody()];
            }
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }
}
