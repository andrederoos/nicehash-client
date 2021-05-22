<?php
namespace NiceHashClient\request;

use GuzzleHttp\Psr7\Request;
use NiceHashClient\object\Currency;
use NiceHashClient\object\HttpMethod;

class NiceHashRequest extends Request
{
    public const PATH_ACCOUNT = '/main/api/v2/accounting/accounts2';
    public const PATH_ACCOUNT_BY_CURRENCY = '/main/api/v2/accounting/account2/%s';

    public const PATH_EXCHANGE_PUBLIC_STATISTICS = '/exchange/api/v2/info/marketStats';

    private $requestId;
        
    public function __construct(
        $method,
        $uri,
        array $headers = [],
        $body = null,
        $requestId = null,
        $version = '1.1'
    ) {
        parent::__construct($method, $uri, $headers, $body, $version);

        $this->requestId = $requestId;
    }

    public function getRequestIdOrNull(): ?string
    {
        return $this->requestId;
    }

    public static function createAccountBalanceRequest(string $requestId = null)
    {
        return new static(HttpMethod::GET, self::PATH_ACCOUNT);
    }

    public static function createAccountBalanceRequestByCurrency(Currency $currency,  string $requestId = null)
    {
        return new static(HttpMethod::GET, vsprintf(self::PATH_ACCOUNT_BY_CURRENCY, [$currency->getValue()]));
    }

    public static function createExchangePublicStatisticsRequest(string $requestId = null)
    {
        return new static(HttpMethod::GET, self::PATH_EXCHANGE_PUBLIC_STATISTICS);

    }
}