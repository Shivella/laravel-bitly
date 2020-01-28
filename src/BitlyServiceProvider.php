<?php
/**
 * (c) Wessel Strengholt <wessel.strengholt@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shivella\Bitly;

use GuzzleHttp\Client;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Shivella\Bitly\Client\BitlyClient;

/**
 * BitlyServiceProvider registers Bitly client as an application service.
 */
class BitlyServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->singleton('bitly', function () {
            return new BitlyClient(new Client(), $this->app->make('config')->get('bitly.accesstoken', ''));
        });

        $this->app->bind(BitlyClient::class, 'bitly');
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if ( ! $this->app->runningInConsole()) {
            return;
        }

        $configPath = $this->app->make('path.config');

        $this->publishes([__DIR__ . '/../config/bitly.php' => $configPath.'/bitly.php']);
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            BitlyClient::class,
            'bitly'
        ];
    }
}
