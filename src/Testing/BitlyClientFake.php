<?php

namespace Shivella\Bitly\Testing;

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
class BitlyClientFake
{
    /**
     * @param string $url raw URL.
     * @return string shorten URL.
     */
    public function getUrl(string $url): string
    {
        return 'http://bit.ly/'.substr(sha1($url), 0, 6);
    }
}
