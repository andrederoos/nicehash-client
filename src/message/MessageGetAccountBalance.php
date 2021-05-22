<?php
namespace NiceHashClient\message;

use GuzzleHttp\Psr7\Request;
use NiceHashClient\object\HttpMethod;

/**
 * Get balance for selected currency. When setting extendedResponse to true pending details are added to the response.
 */
class MessageGetAccountBalance extends Message
{
    /**
     * Message path.
     */
    public const PATH_ACCOUNT_BY_CURRENCY = '/main/api/v2/accounting/account2/';

    /**
     * Query parameters.
     */
    private const QUERY_EXTENDED_RESPONSE = 'extendedResponse';
    
    /**
     * @var string
     */
    private $currency;
        
    /**
     * @param string $currency
     */
    public function __construct(string $currency, bool $exendedResponse = false)
    {
        $this->currency = $currency;
        $this->queryParameters[self::QUERY_EXTENDED_RESPONSE] = $exendedResponse;
    }

    /**
     * @return Request
     */
    public function generateRequest(): Request
    {
        return new Request(HttpMethod::GET, self::PATH_ACCOUNT_BY_CURRENCY . $this->currency . $this->generateQueryParameters());
    }
}