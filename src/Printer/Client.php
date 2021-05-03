<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/25 0025
 * Time: 17:22
 */
namespace CherryneChou\EasyYilianYun\Printer;
use CherryneChou\EasyYilianYun\Kernel\Http\Client as BaseClient;


/**
 * Class Client
 * @package CherryneChou\EasyYilianYun\Picture
 */
class Client extends BaseClient
{
    
    public function addPrinter($machine_code, $msign, $print_name = '', $phone = '')
    {
        return $this->post('/printer/addprinter', compact('machine_code', 'msign', 'print_name', 'phone'));
    }

    /**
     * @param $machineCode
     * @param $content
     * @param $originId
     * @return mixed
     * @throws \CherryneChou\EasyYilianYun\Kernel\Exceptions\InvalidArgumentException
     * @throws \CherryneChou\EasyYilianYun\Kernel\Exceptions\InvalidConfigException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function createPrinterTask($machineCode, $content, $originId)
    {
        return $this->httpPost('print/index', ['machine_code' => $machineCode, 'content' => $content, 'origin_id' => $originId]);
    }
}