<?php
namespace CherryneChou\EasyYilianYun\AccessToken;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 * @package CherryneChou\EasyYilianYun\AccessToken
 */
class AccessTokenServiceProvider implements ServiceProviderInterface
{
	public function register(Container $pimple)
    {
        $pimple['access_token'] = function ($pimple) {
            return new AccessToken($pimple);
        };
    }
}