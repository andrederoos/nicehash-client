<?php
namespace NiceHashClient;

use NiceHashClient\object\HttpMethod;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use Psr\Log\LoggerInterface;

class NiceHashClient
{
    /**
     * Exception messages.
     */
    private const EXCEPTION_INVALID_METHOD = 'Invalid request method "%s". Expected "%s".';

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
     * @var Client
     */
    private $client;

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
        $this->client = new Client([self::FIELD_BASE_URI => self::URL_API]);
    }

    /**
     * @param Request $request
     * @param string|null $requestId
     *
     * @return Response
     */
    public function get(Request $request, string $requestId = null): Response
    {
        $this->assertValidHttpMethod($request, HttpMethod::GET);
        $time = $this->getTimeInMiliseconds();
        $nonce = $this->determineRequestId($requestId);

        return $this->client->send(
            $request,
            [
                self::FIELD_HEADERS => [
                    self::HEADER_X_TIME => $time,
                    self::HEADER_X_NONCE => $nonce,
                    self::HEADER_X_ORGANIZATION_ID => $this->organisationId,
                    self::HEADER_X_REQUEST_ID => $nonce,
                    self::HEADER_X_AUTH => $this->generateGetSignature($time, $nonce, $request)
                ]
            ]
        );
    }

    /**
     * @param Request $request
     * @param string|null $requestId
     *
     * @return Response
     */
    public function post(Request $request, string $requestId = null): Response
    {
        $this->assertValidHttpMethod($request, HttpMethod::POST);
        $time = $this->getTimeInMiliseconds();
        $nonce = $this->determineRequestId($requestId);

        return $this->client->send(
            $request,
            [
                self::FIELD_HEADERS => [
                    self::HEADER_X_TIME => $time,
                    self::HEADER_X_NONCE => $nonce,
                    self::HEADER_X_ORGANIZATION_ID => $this->organisationId,
                    self::HEADER_X_REQUEST_ID => $nonce,
                    self::HEADER_X_AUTH => $this->generatePostSignature($time, $nonce, $request),
                ]
            ]
        );
    }

    /**
     * @param Request $request
     * @param string $httpMethod
     * 
     * @throws \Exception
     */
    private function assertValidHttpMethod(Request $request, string $httpMethod): void
    {
        if ($request->getMethod() === $httpMethod) {
            // Correct method.
        } else {
            throw new \Exception(vsprintf(self::EXCEPTION_INVALID_METHOD, [$request->getMethod(), $httpMethod]));
        }
    }

    /**
     * @param string $requestId
     *
     * @return string
     */
    private function determineRequestId(string $requestId = null): string
    {
        if (is_null($requestId)) {
            return uniqid();
        } else {
            return $requestId;
        }
    }

    /**
     * @param string $time
     * @param string $nonce
     * @param Request $request
     *
     * @return string
     */
    private function generateGetSignature(string $time, string $nonce, Request $request): string
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

    /**
     * @param string $time
     * @param string $nonce
     * @param Request $request
     *
     * @return string
     */
    private function generatePostSignature(string $time, string $nonce, Request $request): string
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
            $request->getUri()->getQuery() . 
            "\x00" . 
            $request->getBody();

        $signatureHash = hash_hmac('sha256', $signature, $this->apiSecret);

        return vsprintf(self::FORMAT_AUTH, [$this->apiKey, $signatureHash]);
    }

    /**
     * @return int
     */
    private function getTimeInMiliseconds(): int {
        $microTime = explode(' ', microtime());
        
        return ((int)$microTime[1]) * 1000 + ((int)round($microTime[0] * 1000));
    }
}