<?php
/**
 * (c) Vinícius Silva <vinicius.ls@live.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shivella\Bitly\Facade;

use Illuminate\Support\Facades\Facade;

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
}
