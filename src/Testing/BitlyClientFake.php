<?php

namespace Shivella\Bitly\Testing;

use Shivella\Bitly\Client\BitlyClient;

/**
 * BitlyClientFake is a mock for the regular Bitly client.
 *
 * It creates a fake short URLs using hashing.
 * This class may be used in unit tests to speed up their execution, removing REST API calls.
 *
 * > Attention: URLs generated via this class will not respond correctly, do not use it in production environment.
 *
 * @see \Shivella\Bitly\Client\BitlyClient
 * @see \Shivella\Bitly\Facade\Bitly::fake()
 */
class BitlyClientFake extends BitlyClient
{
    public function __construct()
    {
        // Unlike with other methods, PHP will not generate an E_STRICT level error message when __construct() is overridden
        // with different parameters than the parent __construct() method has.
        // @see https://www.php.net/manual/en/language.oop5.decon.php
    }

    /**
     * @param string $url raw URL.
     * @return string shorten URL.
     */
    public function getUrl(string $url): string
    {
        return 'http://bit.ly/'.substr(sha1($url), 0, 6);
    }
}
