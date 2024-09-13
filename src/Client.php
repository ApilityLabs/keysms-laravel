<?php

namespace KeySMS;

use Closure;
use Illuminate\Support\Facades\Http;
use KeySMS\Exception\CredentialsNotSetException;
use KeySMS\Exception\Exception;
use KeySMS\Traits\ThrowOnError;

class Client
{
    use ThrowOnError;

    protected $username;
    protected $apiKey;
    protected $options = [];

    /**
     * @param string $username 
     * @param string $apiKey 
     */
    public function __construct($username, $apiKey, $options = [])
    {
        $this->username = $username;
        $this->apiKey = $apiKey;

        $this->options = array_merge([
            'host' => 'https://app.keysms.no',
            'default_sender' => null,
        ], $options);

        if (!isset($this->username) || !isset($this->apiKey)) {
            throw new CredentialsNotSetException();
        }
    }

    protected function sign($payload)
    {
        if (is_array($payload) && empty($payload)) {
            $payload = (object) [];
        }

        return md5(json_encode($payload) . $this->apiKey);
    }

    /**
     * @throws Exception
     */
    public function get(string $endpoint, array $query = []): ?array
    {
        $uri = sprintf('%s/%s?%s', trim($this->options['host'], '/'), trim($endpoint, '/'), http_build_query($query));

        $response = Http::get($uri, [
            'payload' => json_encode((object) $query),
            'username' => $this->username,
            'signature' => $this->sign($query),
        ])
            ->throw();

        return $this->throwOnError($response);
    }

    /**
     * @throws Exception
     */
    public function post(string $endpoint, array $payload): ?array
    {
        $uri = sprintf('%s/%s', trim($this->options['host'], '/'), trim($endpoint, '/'));

        $response = Http::post($uri, [
            'payload' => json_encode($payload),
            'username' => $this->username,
            'signature' => $this->sign($payload),
        ])
            ->throw();

        return $this->throwOnError($response);
    }

    /**
     * @throws Exception
     */
    public function put(string $endpoint, array $payload): ?array
    {
        $uri = sprintf('%s/%s', trim($this->options['host'], '/'), trim($endpoint, '/'));

        $response = Http::put($uri, [
            'payload' => json_encode($payload),
            'username' => $this->username,
            'signature' => $this->sign($payload),
        ])
            ->throw();

        return $this->throwOnError($response);
    }

    /**
     * @throws Exception
     */
    public function delete(string $endpoint): ?array
    {
        $uri = sprintf('%s/%s', trim($this->options['host'], '/'), trim($endpoint, '/'));

        $response = Http::put($uri, [
            'payload' => json_encode((object)[]),
            'username' => $this->username,
            'signature' => $this->sign([]),
        ])
            ->throw();

        return $this->throwOnError($response);
    }
}
