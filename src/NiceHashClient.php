<?php
namespace NiceHashClient;

use NiceHashClient\request\NiceHashRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class NiceHashClient
{
    /**
     * Client url and headers.
     */
    private const URL_API = 'https://api2.nicehash.com';
    private const HEADER_X_TIME = 'X-Time';
    private const HEADER_X_NONCE = 'X-Nonce';
    private const HEADER_X_ORGANIZATION_ID = 'X-Organization-Id';
    private const HEADER_X_REQUEST_ID = 'X-Request-Id';
    private const HEADER_X_AUTH = 'X-Auth';

    /**
     * Guzzle constants.
     */
    private const FIELD_BASE_URI = 'base_uri';
    private const FIELD_HEADERS = 'headers';

    /**
     * Signature formatting.
     */
    private const FORMAT_AUTH = '%s:%s';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiSecret;

    /**
     * @var string
     */
    private $organisationId;

    /**
     * @param string $apiKey
     * @param string $apiSecret
     * @param string $organisationId
     */
    public function __construct(string $apiKey, string $apiSecret, string $organisationId)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->organisationId = $organisationId;
    }

    public function get(NiceHashRequest $request): Response
    {
        $time = $this->getTimeInMiliseconds();
        $nonce = $this->determineRequestId($request);

        $client = new Client([self::FIELD_BASE_URI => self::URL_API]);

        return $client->send(
            $request,
            [
                self::FIELD_HEADERS => [
                    self::HEADER_X_TIME => $time,
                    self::HEADER_X_NONCE => $nonce,
                    self::HEADER_X_ORGANIZATION_ID => $this->organisationId,
                    self::HEADER_X_REQUEST_ID => $nonce,
                    self::HEADER_X_AUTH => $this->generateRequestSignature($time, $nonce, $request)
                ]
            ]
        );
    }

    public function post(NiceHashRequest $request): Response
    {

    }

    private function determineRequestId(NiceHashRequest $request): string
    {
        if (is_null($request->getRequestIdOrNull())) {
            return uniqid();
        } else {
            return $request->getRequestIdOrNull();
        }
    }

    private function generateRequestSignature(string $time, string $nonce, NiceHashRequest $request): string
    {
        $signature = 
            $this->apiKey . 
            "\x00" .
            $time . 
            "\x00" . 
            $nonce . 
            "\x00" . 
            "\x00" . 
            $this->organisationId . 
            "\x00" . 
            "\x00" . 
            $request->getMethod() . 
            "\x00" . 
            $request->getUri()->getPath() . 
            "\x00" . 
            $request->getUri()->getQuery();

        $signatureHash = hash_hmac('sha256', $signature, $this->apiSecret);

        return vsprintf(self::FORMAT_AUTH, [$this->apiKey, $signatureHash]);
    }

    private function getTimeInMiliseconds(): int {
        $microTime = explode(' ', microtime());
        
        return ((int)$microTime[1]) * 1000 + ((int)round($microTime[0] * 1000));
    }
}