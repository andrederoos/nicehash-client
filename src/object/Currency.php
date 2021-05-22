<?php
namespace NiceHashClient\object;

class Currency
{
    public const BTC = 'BTC';

    private $currency;

    protected function __construct(string $currency)
    {
        $this->currency = $currency;
    }

    public function getValue(): string
    {
        return $this->currency;
    }

    public static function BTC()
    {
        return new static(self::BTC);
    }
}