<?php

namespace Vulpine\Services;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;
use InvalidArgumentException;
use Exception;

class Whmcs
{
    /**
     * The WHMCS identifier.
     *
     * @var string
     */
    protected $whmcsIdentifier;

    /**
     * The WHMCS Secret key.
     *
     * @var string
     */
    protected $whmcsSecret;

    /**
     * The current WHMCS installation's API URL.
     *
     * @var string
     */
    protected $whmcsApiUrl;

    /**
     * The client connection to WHMCS.
     *
     * @var \Guzzle\Http\Client
     */
    protected $client;

    /**
     * Whmcs constructor.
     *
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $this->whmcsApiUrl = config('vulpine.whmcs.api_url');
        $this->whmcsIdentifier = config('vulpine.whmcs.identifier');
        $this->whmcsSecret = config('vulpine.whmcs.secret');

        // Ensure required properties are set before continuing.
        $this->validate();

        // Initialize the client.
        $this->client = $this->initializeClient();
    }

    /**
     * Ensure required data is set.
     *
     * @throws InvalidArgumentException
     */
    private function validate()
    {
        if (empty($this->whmcsApiUrl)) {
            throw new InvalidArgumentException('Please set WHMCS_API_URL, environment variables.');
        }

        if (empty($this->whmcsIdentifier)) {
            throw new InvalidArgumentException('Please set WHMCS_IDENTIFIER, environment variables.');
        }

        if (empty($this->whmcsSecret)) {
            throw new InvalidArgumentException('Please set WHMCS_SECRET, environment variables.');
        }
    }

    /**
     * Initialize the Guzzle client.
     *
     * @return \Guzzle\Http\Client
     */
    private function initializeClient()
    {
        $client = new Client([
            'timeout' => 30,
            'headers' => [
                'User-Agent' => null,
            ]
        ]);

        return $client;
    }

    /**
     * Execute the call to WHMCS API.
     *
     * @param       $action
     * @param array $params
     *
     * @return mixed|string
     * @throws \Exception
     */
    public function execute($action, array $params = [])
    {
        $requiredParameters = [
            'username'      => $this->whmcsIdentifier,
            'password'      => $this->whmcsSecret,
            'responsetype'  => config('vulpine.whmcs.response', 'json'),
            'action'        => $action
        ];

        try {
            $response = $this->client->post($this->whmcsApiUrl, [
                'form_params' => array_merge($requiredParameters, $params)
            ]);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $exception = (string) $e->getResponse()->getBody();
                $exception = json_decode($exception);
                return $exception;
            } else {
                return $e->getMessage();
            }
        }

        $this->handleResponse($response);
    }

    /**
     * Handle the response and return json.
     *
     * @param $response
     *
     * @return mixed
     * @throws Exception
     */
    private function handleResponse($response)
    {
        $decodedResponse = json_decode($response->getBody()->getContents(), true);

        if (isset($decodedResponse['result'], $decodedResponse['status']) &&
            ($decodedResponse['result'] !== 'success' || $decodedResponse['status'] !== 'success')) {
                throw new Exception('WHMCS Response Error: ' . $decodedResponse['message']);
        }

        return json_decode(json_encode($decodedResponse), true);
    }

    /**
     * Execute actions using the magic method.
     *
     * @param $action
     * @param $params
     *
     * @return mixed|string
     * @throws \Exception
     */
    public function __call($action, $params)
    {
        return $this->execute($action, $params);
    }
}