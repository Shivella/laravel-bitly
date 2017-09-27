<?php
/**
 * Created by PhpStorm.
 * User: vinicius
 * Date: 26/09/17
 * Time: 22:16
 */

namespace Shivella\Bitly\Facade;


use Illuminate\Support\Facades\Facade;

class Bitly extends Facade
{
    protected static function getFacadeAccessor() {
        return 'bitly';
    }
}