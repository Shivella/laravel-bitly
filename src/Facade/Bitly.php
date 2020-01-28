<?php
/**
 * (c) Vinícius Silva <vinicius.ls@live.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shivella\Bitly\Facade;

use Illuminate\Support\Facades\Facade;
use Shivella\Bitly\Testing\BitlyClientFake;

/**
 * Bitly is a facade for the Bitly client.
 *
 * @see \Shivella\Bitly\Client\BitlyClient
 *
 * @method string getUrl(string $url)
 */
class Bitly extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'bitly';
    }

    /**
     * Replace the bound instance with a fake.
     *
     * @return \Shivella\Bitly\Testing\BitlyClientFake
     */
    public static function fake()
    {
        static::swap($fake = new BitlyClientFake);

        return $fake;
    }
}
