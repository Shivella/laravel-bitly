<?php
/*
* (c) Wessel Strengholt <wessel.strengholt@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Shivella\Bitly;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider as AppServiceProvider;
use Shivella\Bitly\Client\BitlyClient;

/**
 * Class BitlyServiceProvider
 */
class BitlyServiceProvider extends AppServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Country Manager
        $this->app->singleton('bitly', function () {
            return new BitlyClient(new Client(), config('bitly.accesstoken'));
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/config/bitly.php' => config_path('bitly.php')]);
    }
}
