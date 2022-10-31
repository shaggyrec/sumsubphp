<?php

namespace unit;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Shaggyrec\Sumsubphp\RequestSigner;
use Shaggyrec\Sumsubphp\DTO\AccessToken;
use Shaggyrec\Sumsubphp\SumSubClient;
use PHPUnit\Framework\TestCase;

class SumSubClientTest extends TestCase
{
    public function testGetAccessToken()
    {
        $ssr = ['token' => 'token', 'userId' => 'user-id'];
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->willReturn(new Response(200, [], json_encode($ssr)));

        $r = (new SumSubClient($httpClient, new RequestSigner('t', 's')))->getAccessToken(666, 'level');

        self::assertEquals(new AccessToken($ssr), $r);
    }
}
