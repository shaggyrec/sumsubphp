<?php

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Shaggyrec\Sumsubphp\RequestSigner;

class RequestSignerTest extends TestCase
{
    public function testAddSignatureToRequest()
    {
        $time = 1666694567;
        $token = 'test-token';
        $request = (new RequestSigner(
            'test-token',
            'test-key',
        ))->setTime($time)
        ->sign(new Request('POST', ''));

        self::assertSame(
            [$token],
            $request->getHeader('X-App-Token')
        );
        self::assertSame([(string) $time], $request->getHeader('X-App-Access-Ts'));
        self::assertSame(
            ['720a21d7205a336eff3e4cadc14ee2adb89bc58b68a4907ed99ca1a1a82e50aa'],
            $request->getHeader('X-App-Access-Sig')
        );
    }
}
