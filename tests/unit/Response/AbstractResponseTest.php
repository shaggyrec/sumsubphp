<?php

use PHPUnit\Framework\TestCase;
use Shaggyrec\Sumsubphp\DTO\AccessToken;

class AbstractResponseTest extends TestCase
{
    public function testToArray()
    {
        $sumSubResponse = [
            'token' => 'token',
            'userId' => 'userId',
        ];

        self::assertSame(
            $sumSubResponse,
            (new AccessToken($sumSubResponse))->toArray(),
        );
    }


    public function testFillsPublicProperties()
    {
        $sumSubResponse = [
            'token' => 'token',
            'userId' => 'userId',
        ];

        $r = new AccessToken($sumSubResponse);

        self::assertSame(
            $sumSubResponse['userId'],
            $r->userId,
        );

        self::assertSame(
            $sumSubResponse['token'],
            $r->token,
        );
    }
}
