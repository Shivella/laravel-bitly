<?php
/**
 * (c) Vinícius Silva <vinicius.ls@live.com>
 */

namespace Shivella\Bitly\Facade;


use Illuminate\Support\Facades\Facade;

class Bitly extends Facade
{
    protected static function getFacadeAccessor() {
        return 'bitly';
    }
}