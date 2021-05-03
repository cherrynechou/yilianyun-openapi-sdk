<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/19 0019
 * Time: 16:14
 */
namespace CherryneChou\EasyYilianYun\Kernel\Contracts;

use ArrayAccess;

/**
 * Interface Arrayable
 * @package CherryneChou\EasyDada\Kernel\Contracts
 */
interface Arrayable extends ArrayAccess
{
    public function toArray();
}