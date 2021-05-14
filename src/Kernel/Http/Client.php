<?php
namespace CherryneChou\EasyYilianYun\Kernel\Http;

use CherryneChou\EasyYilianYun\Application;

/**
 * Class Client
 * @package CherryneChou\EasyYilianYun\Kernel\Http
 */
class Client
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var string
     */
    protected $baseUri = "https://open-api.10ss.net";

    /**
     * BaseClient constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

}
