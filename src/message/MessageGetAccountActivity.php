<?php
namespace NiceHashClient\message;

use GuzzleHttp\Psr7\Request;
use NiceHashClient\object\HttpMethod;

/**
 * Get activities for specified currency matching the filtering criteria as specified by request parameters.
 */
class MessageGetAccountActivity extends Message
{
    /**
     * Message path.
     */
    public const PATH_ACCOUNT_ACTIVITY_BY_CURRENCY = '/main/api/v2/accounting/activity/';

    /**
     * Query parameters.
     */
    private const QUERY_TYPE = 'type';
    private const QUERY_TIMESTAMP = 'timestamp';
    private const QUERY_STAGE = 'stage';
    private const QUERY_LIMIT = 'limit';

    /**
     * @var string
     */
    private $currency;
        
    /**
     * @param string $currency
     * @param string|null $type
     * @param string|null $timestamp
     * @param string|null $stage
     * @param string|null $limit
     */
    public function __construct(string $currency, string $type = null, string $timestamp = null, string $stage = null, string $limit = null)
    {
        $this->currency = $currency;
        
        if (!is_null($type)) {
            $this->queryParameters[self::QUERY_TYPE] = $type;
        }

        if (!is_null($timestamp)) {
            $this->queryParameters[self::QUERY_TIMESTAMP] = $timestamp;
        }

        if (!is_null($stage)) {
            $this->queryParameters[self::QUERY_STAGE] = $stage;
        }

        if (!is_null($limit)) {
            $this->queryParameters[self::QUERY_LIMIT ] = $limit;
        }
    }

    /**
     * @return Request
     */
    public function generateRequest(): Request
    {
        return new Request(HttpMethod::GET, self::PATH_ACCOUNT_ACTIVITY_BY_CURRENCY . $this->currency . $this->generateQueryParameters());
    }
}