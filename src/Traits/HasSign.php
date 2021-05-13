<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/26 0026
 * Time: 15:53
 */

namespace CherryneChou\EasyYilianYun\Traits;

/**
 * Trait HasSign
 * @package CherryneChou\EasyYilianYun\Kernel\Traits
 */
trait  HasSign
{
    /**
     * @param $timestamp
     * @return string
     */
    public function getSign($timestamp)
    {
        return strtolower(md5(
            $this->app['config']->get('client_id').
            $timestamp.
            $this->app['config']->get('client_secret')
        ));
    }

}