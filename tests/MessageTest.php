<?php
namespace NiceHashClient\Tests;

use GuzzleHttp\Psr7\Request;
use \PHPUnit\Framework\TestCase;
use NiceHashClient\object\CryptoCurrency;
use NiceHashClient\message\MessageGetAccountBalance;
use NiceHashClient\message\MessageGetAccountActivity;
use NiceHashClient\message\MessageGetAccountBalances;
use NiceHashClient\message\MessageGetAccountDepositAddresses;

class MessageTest extends TestCase
{
    /**
     */
    public function testAccountActivityGenerateRequest()
    {
        $message = new MessageGetAccountActivity(CryptoCurrency::BTC);

        static::assertInstanceOf(Request::class, $message->generateRequest());
    }

    /**
     */
    public function testAccountBalanceGenerateRequest()
    {
        $message = new MessageGetAccountBalance(CryptoCurrency::BTC);

        static::assertInstanceOf(Request::class, $message->generateRequest());
    }

    /**
     */
    public function testAccountBalancesGenerateRequest()
    {
        $message = new MessageGetAccountBalances();

        static::assertInstanceOf(Request::class, $message->generateRequest());
    }

    /**
     */
    public function testAccountDepositAddressesGenerateRequest()
    {
        $message = new MessageGetAccountDepositAddresses(CryptoCurrency::BTC);

        static::assertInstanceOf(Request::class, $message->generateRequest());
    }
}