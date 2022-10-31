<?php

namespace integration;

use GuzzleHttp\Client;
use Shaggyrec\Sumsubphp\RequestSigner;
use Shaggyrec\Sumsubphp\SumSubClient;
use PHPUnit\Framework\TestCase;

class SumSubClientTest extends TestCase
{
    public function testAccessToken()
    {
        $r = self::client()->getAccessToken('6', 'test-level');
        $this->assertSame('6', $r->userId);
        $this->assertNotEmpty($r->token);
    }

    public function testTestDigest()
    {
        $this->assertSame(
            '9598d33f25adcb236ea2d82ee50d9c010fa19d7a',
            self::client()->testDigest('ddd', 'payload'),
        );
    }

    public static function client(): SumSubClient
    {
        return new SumSubClient(
            new Client(),
            new RequestSigner($_ENV['TOKEN'], $_ENV['KEY'])
        );
    }
}
