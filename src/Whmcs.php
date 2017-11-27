<?php

namespace Vulpine;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use InvalidArgumentException;
use Vulpine\Exceptions\FatalErrorException;
use Vulpine\Exceptions\NotFoundException;
use Vulpine\Exceptions\WhmcsException;

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
     * WHMCS API Response type.
     *
     * @var string
     */
    protected $responseType;

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
        $this->responseType = strtolower(config('vulpine.whmcs.response', 'json'));

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
            throw new InvalidArgumentException('Please set WHMCS_API_URL, environment variable.');
        }

        if (empty($this->whmcsIdentifier)) {
            throw new InvalidArgumentException('Please set WHMCS_IDENTIFIER, environment variable.');
        }

        if (empty($this->whmcsSecret)) {
            throw new InvalidArgumentException('Please set WHMCS_SECRET, environment variable.');
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
                'Accept'     => 'application/json'
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
            'identifier'      => $this->whmcsIdentifier,
            'secret'      => $this->whmcsSecret,
            'responsetype'  => $this->responseType,
            'action'        => $action
        ];

        try {
            $response = $this->client->post($this->whmcsApiUrl, [
                'form_params' => array_merge($requiredParameters, $params)
            ]);

            return $this->handleResponse($response);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            if ($response && $response->getStatusCode() === 404) {
                throw new NotFoundException('The WHMCS API URL is invalid (404 - Not Found)');
            } else if ($response && $response->getStatusCode() === 500) {
                throw new FatalErrorException('The WHMCS API URL is unavailable (500 - Internal Server Error)');
            }
        }
    }

    /**
     * Handle the response and return in relevant format.
     *
     * @param $response
     *
     * @return mixed
     * @throws \Vulpine\Exceptions\WhmcsException
     */
    private function handleResponse($response)
    {
        $decodedResponse = json_decode($response->getBody(), true);

        if ((isset($decodedResponse['result']) && $decodedResponse['result'] === 'error') ||
            (isset($decodedResponse['status']) && $decodedResponse['status'] === 'error')) {
            throw new WhmcsException('WHMCS Response Error : ' . $decodedResponse['message']);
        }

        if ($this->responseType === 'xml') {
            return simplexml_load_string($response->getBody());
        }

        return $decodedResponse;
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