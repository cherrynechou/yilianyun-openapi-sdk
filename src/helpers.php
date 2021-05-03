<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/25 0025
 * Time: 21:28
 */

if(!function_exists('tap')){
    function tap($value, $callback)
    {
        $callback($value);

        return $value;
    }
}
