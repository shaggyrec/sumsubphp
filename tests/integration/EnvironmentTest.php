<?php

namespace integration;

use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    public function testPHPUnitIsSetUpRight()
    {
        $this->assertTrue(defined('INTEGRATION_TESTS_IN_PROGRESS'));
        $this->assertNotEmpty($_ENV['TOKEN'], self::envErrorMessage());
        $this->assertNotEmpty($_ENV['KEY'], self::envErrorMessage());
    }


    private static function envErrorMessage(): string
    {
        return sprintf(
            'You must to set `TOKEN` and `KEY` variables in %s/phpunit.xml.dist',
            __DIR__,
        );
    }
}
