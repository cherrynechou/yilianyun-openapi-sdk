<?php
/**
 * Created by : PhpStorm
 * User: cherrynechou
 * Date: 2021/5/14 0014
 * Time: 16:39
 */
namespace CherryneChou\EasyYilianYun\Auth;

use CherryneChou\EasyYilianYun\Application;
use CherryneChou\EasyYilianYun\Kernel\Http\Client as BaseClient;
use CherryneChou\EasyYilianYun\Kernel\Traits\HasHttpRequests;

/**
 * Class Client
 * @package CherryneChou\EasyYilianYun\Auth
 */
class Client extends BaseClient
{
    use HasHttpRequests;

    /**
     * @var Application
     */
    protected $app;

    /**
     * Client constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        parent::__construct($app);
    }

    /**
     * @param String $url
     * @param array $data
     * @return mixed
     */
    public function httpPost(String $url, array $data=[])
    {
        return $this->request($url, 'POST', ['form_params' => $data]);
    }
}
