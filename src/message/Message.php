<?php
namespace NiceHashClient\message;

use GuzzleHttp\Psr7\Request;

abstract class Message
{
    /**
     * String constants.
     */
    private const EMPTY_STRING = '';
    private const QUERY_START_STRING = '?';

    /**
     * @var array
     */
    protected $queryParameters = [];

    /**
     * @return Request
     */
    abstract public function generateRequest(): Request;

    /**
     * @return string
     */
    protected function generateQueryParameters(): string
    {
        if (empty($this->queryParameters)) {
            return self::EMPTY_STRING;
        } else {
            return self::QUERY_START_STRING . http_build_query($this->queryParameters);
        }
    }
}