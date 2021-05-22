<?php
namespace NiceHashClient\message;

use GuzzleHttp\Psr7\Request;
use NiceHashClient\object\HttpMethod;

/**
 * Get deposit address for selected currency for all wallet types.
 */
class MessageGetAccountDepositAddresses extends Message
{
    /**
     * Message path.
     */
    public const PATH_ACCOUNT_DEPOSIT_ADDRESS = '/main/api/v2/accounting/depositAddresses';

    /**
     * Query parameters.
     */
    private const QUERY_CURRENCY = 'extendedResponse';
    private const QUERY_WALLET_TYPE = 'walletType';

    /**
     * @var string
     */
    private $currency;
        
    /**
     * @param string $currency
     */
    public function __construct(string $currency, string $walletType = null)
    {
        $this->queryParameters[self::QUERY_CURRENCY] = $currency;
        
        if (!is_null($walletType)) {
            $this->queryParameters[self::QUERY_WALLET_TYPE] = $walletType;
        }
    }

    /**
     * @return Request
     */
    public function generateRequest(): Request
    {
        return new Request(HttpMethod::GET, self::PATH_ACCOUNT_DEPOSIT_ADDRESS . $this->generateQueryParameters());
    }
}