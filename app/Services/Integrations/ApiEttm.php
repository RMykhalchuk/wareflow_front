<?php

namespace App\Services\Integrations;

use GuzzleHttp\Client;

final class ApiEttm
{
    private string $baseUrl = 'https://edo-v2.edin.ua/api/';

    private string $accessToken = '';
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
        ]);
    }

    public function login(string $email, string $password): void
    {
        $response = $this->client->post('authorization/hash', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);

        $this->accessToken = json_decode($response->getBody()->getContents(), true)['SID'];
    }

    public function getDocuments(string $gln): array
    {
        //Фільтр для пошуку всіх вхідних і вихідних е-ТТН
        //Для розширеного фільтру https://wiki.edin.ua/uk/latest/API_ETTN/Methods/EveryBody/XDocPage.html
        $filter = [
            'direction' => [
                'sender' => [$gln],
                'receiver' => [$gln],
                'type' => 'OR'
            ],
            'families' => [7],
            'type' => [
                [
                    'type' => '0'
                ]
            ]];

        $response = $this->client->post('eds/docs/search', [
            'headers' => [
                'Authorization' => $this->accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $filter,
            'query' => [
                'gln' => $gln,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function updateDocument(string $gln, string $documentID, array $documentData): int
    {
        $response = $this->client->post('eds/doc/ettn/ttn', [
            'headers' => [
                'Authorization' => $this->accessToken,
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'gln' => $gln,
                'doc_uuid' => $documentID
            ],
            'json' => $documentData
        ]);

        return $response->getStatusCode();
    }

    public function deleteDocuments(string $gln, array $documentsArray): int
    {
        $response = $this->client->patch('eds/docs', [
            'headers' => [
                'Authorization' => $this->accessToken,
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'gln' => $gln,
            ],
            'json' => $documentsArray
        ]);

        return $response->getStatusCode();
    }

    public function createConsignor(string $gln, array $documentData): string
    {
        $response = $this->client->post('eds/doc/ettn/ttn', [
            'headers' => [
                'Authorization' => $this->accessToken,
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'gln' => $gln,
            ],
            'json' => $documentData
        ]);

        return json_decode($response->getBody()->getContents(), true)['doc_uuid'];
    }

    //Отримання тіла документа
    public function getDocumentBody(string $gln, string $documentID): string
    {
        $response = $this->client->get("eds/doc/ettn/body?doc_uuid=$documentID&gln=$gln", [
            'headers' => [
                'Authorization' => $this->accessToken,
                'Content-Type' => 'application/json',
            ],

        ]);

        return $response->getBody()->getContents();
    }


    //Відправка підписаного документа
    public function send(string $gln, string $documentID): int
    {
        $response = $this->client->patch('eds/doc/ettn/ttn/send', [
            'headers' => [
                'Authorization' => $this->accessToken,
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'gln' => $gln,
                'doc_uuid' => $documentID
            ],

        ]);

        return $response->getStatusCode();
    }

    public function getGLN(string $gln, string $query): array
    {
        $response = $this->client->get('oas/identifiers', [
            'headers' => [
                'Authorization' => $this->accessToken,
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'gln' => $gln,
                'query' => $query
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
