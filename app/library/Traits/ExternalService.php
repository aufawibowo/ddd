<?php

namespace A7Pro\Traits;

use GuzzleHttp\Client;

trait ExternalService
{
    /**
     * Sends a Custom Guzzle Request.
     *
     * Sends a custom Guzzle request removing empty body params and standardizing thown Exceptions
     *
     * @param string $url Request's URL
     * @param string $method Request's Method (GET, POST, etc)
     * @param array $body Request's Body
     * @param array $headers Request's Header
     * @param array $guzzleClientConfig Custom Guzzle Client configuration
     *
     * @return array Guzzle Response and Http Code
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRequest($url, $method, $body = [], $headers = [], $guzzleClientConfig = [])
    {
        $client = new Client($guzzleClientConfig);
        $headers['Accept'] = 'application/json';

        $guzzleResponse = $client->request($method, $url, [
            'http_errors' => false,
            'headers' => $headers,
            'json' => $this->removeEmptyKeys($body)
        ]);

        return json_decode($guzzleResponse->getBody()->getContents(), true);
    }

    /**
     * Removes empty keys from array
     *
     * @param array $data Data array
     *
     * @return array
     */
    public function removeEmptyKeys($data)
    {
        if (!$data) return [];
        foreach ($data as $key => $value) {
            if (is_array($data[$key])) {
                $data[$key] = self::removeEmptyKeys($data[$key]);
            } else if ($value == null) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Generates url params string
     *
     * @param array $params Url params
     *
     * @return string
     */
    public function generateUrlParams($params)
    {
        $query = '';
        foreach ($params as $key => $param) {
            if (!empty($param)) {
                if (empty($query)) {
                    $query = "?";
                } else {
                    $query .= "&";
                }
                if (is_array($param)) {
                    $query .= http_build_query(["$key" => $param]);
                } else {
                    $query .= "$key=$param";
                }
            }
        }
        return $query;
    }
}