<?php

namespace Bridging\Bpjs\ICare;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ICareService
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $clients;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var int|string
     */
    private $cons_id;

    /**
     * @var string
     */
    private $timestamp;

    /**
     * @var string
     */
    private $signature;

    /**
     * @var string
     */
    private $authorization;

    /**
     * @var string
     */
    private $secret_key;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $app_code;

    /**
     * @var string
     */
    private $base_url;

    /**
     * @var string
     */
    private $service_name;

    /**
     * @var string
     */
    private $user_key;

    /**
     * Key decrypt (cons_id + secret_key + timestamp)
     * @var string
     */
    public $key_decrypt;

    protected function buildUrl(string $feature): string
    {
        $base = rtrim((string) $this->base_url, '/');
        $serviceName = trim((string) $this->service_name, '/');
        $feature = ltrim($feature, '/');

        if ($serviceName !== '') {
            $needle = '/' . $serviceName;
            if ($base !== $serviceName && substr($base, -strlen($needle)) !== $needle) {
                $base .= $needle;
            }
        }

        return $base . '/' . $feature;
    }

    public function __construct($configurations = [])
    {
        $this->clients = new Client([
            'verify' => false,
        ]);

        foreach ($configurations as $key => $val) {
            if (property_exists($this, $key)) {
                $this->{$key} = $val;
            }
        }

        $this->setTimestamp()
            ->setSignature()
            ->setAuthorization()
            ->setHeaders();
    }

    protected function setHeaders()
    {
        $this->headers = [
            'X-cons-id' => $this->cons_id,
            'X-Timestamp' => $this->timestamp,
            'X-Signature' => $this->signature,
            'user_key' => $this->user_key,
        ];

        if (!empty($this->authorization)) {
            $this->headers['X-Authorization'] = $this->authorization;
        }

        return $this;
    }

    protected function setTimestamp()
    {
        date_default_timezone_set('UTC');
        $this->timestamp = (string) (time() - strtotime('1970-01-01 00:00:00'));

        if (function_exists('env')) {
            date_default_timezone_set(env('APP_TIMEZONE', 'Asia/Singapore'));
        }

        return $this;
    }

    protected function setSignature()
    {
        $data = "{$this->cons_id}&{$this->timestamp}";
        $signature = hash_hmac('sha256', $data, $this->secret_key, true);
        $encodedSignature = base64_encode($signature);

        $this->key_decrypt = "{$this->cons_id}{$this->secret_key}{$this->timestamp}";
        $this->signature = $encodedSignature;

        return $this;
    }

    protected function setAuthorization()
    {
        if (!empty($this->username) && !empty($this->password) && !empty($this->app_code)) {
            $data = "{$this->username}:{$this->password}:{$this->app_code}";
            $encodedAuth = base64_encode($data);
            $this->authorization = "Basic {$encodedAuth}";
        }

        return $this;
    }

    protected function responseDecoded($response)
    {
        $responseArray = json_decode($response, true);

        if (!is_array($responseArray)) {
            return [
                'metaData' => [
                    'message' => $response,
                    'code' => 201,
                ],
            ];
        }

        if (!isset($responseArray['response']) || $responseArray['response'] === []) {
            return $responseArray;
        }

        if (!is_string($responseArray['response'])) {
            return $responseArray;
        }

        $responseDecrypt = $this->stringDecrypt($responseArray['response']);
        $responseArrayDecrypt = json_decode($responseDecrypt, true);

        if (!is_array($responseArrayDecrypt) || $responseDecrypt === '') {
            return $responseArray;
        }

        $responseArray['response'] = $responseArrayDecrypt;

        return $responseArray;
    }

    protected function stringDecrypt($string)
    {
        $encryptMethod = 'AES-256-CBC';
        $keyHash = hex2bin(hash('sha256', $this->key_decrypt));
        $iv = substr(hex2bin(hash('sha256', $this->key_decrypt)), 0, 16);

        $output = openssl_decrypt(
            base64_decode($string),
            $encryptMethod,
            $keyHash,
            OPENSSL_RAW_DATA,
            $iv
        );

        if (!class_exists('\\LZCompressor\\LZString')) {
            return (string) $output;
        }

        return \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
    }

    protected function post($feature, $data = [], $headers = [])
    {
        $this->headers['Accept'] = 'application/json';
        $this->headers['Content-Type'] = 'application/json; charset=utf-8';

        if (!empty($headers)) {
            $this->headers = array_merge($this->headers, $headers);
        }

        $url = $this->buildUrl((string) $feature);

        $response = $this->sendRequest('POST', $url, [
            'headers' => $this->headers,
            'json' => $data,
        ]);

        return $response;
    }

    /**
     * Send HTTP request and normalize error handling.
     */
    protected function sendRequest(string $method, string $url, array $options = [])
    {
        try {
            $response = $this->clients->request($method, $url, $options);

            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return (string) $e->getResponse()->getBody()->getContents();
            }

            return $e->getMessage();
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}
