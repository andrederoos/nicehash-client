<?php
namespace NiceHashClient\message;

use GuzzleHttp\Psr7\Request;
use NiceHashClient\object\HttpMethod;

class GetAccountBalance
{
    /**
     * Message path.
     */
    public const PATH_ACCOUNT_BY_CURRENCY = '/main/api/v2/accounting/account2/%s?extendedResponse=%s';

    /**
     * @var string
     */
    private static $currency;
    
    /**
     * @var string
     */
    private static $exendedResponse;
    
    /**
     * @param string $currency
     */
    public function __construct(string $currency, bool $exendedResponse = false)
    {
        static::$currency = $currency;
        static::$exendedResponse = $exendedResponse;
    }

    /**
     * @return Request
     */
    public static function generateRequest(): Request
    {
        return new Request(HttpMethod::GET, vsprintf(self::PATH_ACCOUNT_BY_CURRENCY, [static::$currency, static::$exendedResponse]));
    }
}