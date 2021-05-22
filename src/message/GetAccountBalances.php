<?php
namespace NiceHashClient\message;

use GuzzleHttp\Psr7\Request;
use NiceHashClient\object\HttpMethod;

class GetAccountBalances
{
    /**
     * Message path.
     */
    public const PATH_ACCOUNT = '/main/api/v2/accounting/accounts2';
      
    /**
     * @var string
     */
    private static $exendedResponse;

    /**
     * @param bool $exendedResponse
     */
    public function __construct(bool $exendedResponse = false)
    {
        static::$exendedResponse = $exendedResponse;
    }

    /**
     * @return Request
     */
    public static function generateRequest(): Request
    {
        return new Request(HttpMethod::GET, vsprintf(self::PATH_ACCOUNT, [static::$exendedResponse]));
    }
}