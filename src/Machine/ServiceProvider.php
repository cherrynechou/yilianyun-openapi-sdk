<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/25 0025
 * Time: 17:20
 */
namespace CherryneChou\EasyYilianYun\Machine;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 * @package CherryneChou\EasyYilianYun\Printer
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param \Pimple\Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['machine'] = function ($app) {
            return new Client($app,$app['access_token']);
        };
    }
}
