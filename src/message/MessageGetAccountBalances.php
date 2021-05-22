<?php
namespace NiceHashClient\message;

use GuzzleHttp\Psr7\Request;
use NiceHashClient\object\HttpMethod;

/**
 * Get total balance and for each currency separated. When setting extendedResponse to true pending details are added to each item in the response.
 */
class MessageGetAccountBalances extends Message
{
    /**
     * Message path.
     */
    public const PATH_ACCOUNT = '/main/api/v2/accounting/accounts2';

    /**
     * Query parameters.
     */
    private const QUERY_EXTENDED_RESPONSE = 'extendedResponse';
    private const QUERY_FIAT = 'fiat';

    /**
     * @param bool $exendedResponse
     */
    public function __construct(bool $exendedResponse = false, string $fiat = null)
    {
        $this->queryParameters[self::QUERY_EXTENDED_RESPONSE] = $exendedResponse;

        if (!is_null($fiat)) {
            $this->queryParameters[self::QUERY_FIAT] = $fiat;
        }
    }

    /**
     * @return Request
     */
    public function generateRequest(): Request
    {
        return new Request(HttpMethod::GET, self::PATH_ACCOUNT . $this->generateQueryParameters());
    }
}