<?php

namespace Shivella\Bitly\Test\Testing;

use PHPUnit\Framework\TestCase;
use Shivella\Bitly\Testing\BitlyClientFake;

class BitlyClientFakeTest extends TestCase
{
    /** @var \Shivella\Bitly\Testing\BitlyClientFake */
    private $bitlyClient;

    protected function setUp() : void
    {
        $this->bitlyClient = new BitlyClientFake();
    }

    public function testGetUrl()
    {
        $shortUrlFoo = $this->bitlyClient->getUrl('https://www.test.com/foo');

        $this->assertTrue(strlen($shortUrlFoo) < 22);
        $this->assertStringContainsString('://bit.ly', $shortUrlFoo);

        $shortUrlBar = $this->bitlyClient->getUrl('https://www.test.com/bar');
        $this->assertNotSame($shortUrlFoo, $shortUrlBar);
    }
}
