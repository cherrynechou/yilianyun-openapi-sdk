<?php
/**
 * Created by : PhpStorm
 * User: cherrynechou
 * Date: 2021/5/14 0014
 * Time: 16:49
 */
namespace CherryneChou\EasyYilianYun;

use CherryneChou\EasyYilianYun\AccessToken\AccessToken;
use CherryneChou\EasyYilianYun\Kernel\Http\Client;
use CherryneChou\EasyYilianYun\Kernel\Traits\HasHttpRequests;
use CherryneChou\EasyYilianYun\Kernel\Traits\HasSign;
use Ramsey\Uuid\Uuid;

/**
 * Class BaseClient
 * @package CherryneChou\EasyYilianYun
 */
class BaseClient extends Client
{
    use HasHttpRequests, HasSign;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var AccessToken|mixed|null
     */
    protected $accessToken = null;

    /**
     * Client constructor.
     * @param Application $app
     * @param AccessToken $accessToken
     */
    public function __construct(Application $app, AccessToken $accessToken)
    {
        $this->app = $app;
        $this->accessToken = $accessToken ?? $this->app['access_token'];
        parent::__construct($app);
    }

    /**
     * @param String $url
     * @param array $data
     * @return mixed
     * @throws \CherryneChou\EasyYilianYun\Kernel\Exceptions\InvalidArgumentException
     * @throws \CherryneChou\EasyYilianYun\Kernel\Exceptions\InvalidConfigException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function httpPost(String $url, array $data=[], string $format = 'json')
    {
        $time = time();

        $params=[
            'client_id' => $this->app['config']->get('client_id'),
            'access_token' => $this->accessToken->get(),
            'sign'=> $this->getSign($time),
            'id' => strtoupper(Uuid::uuid4()->toString()),
            'timestamp' => $time
        ];

        $response = $this->request($url, 'POST', ['form_params' => $data + $params])
            ->getBody()->getContents();

        return 'json' === $format ? \json_decode($response, true) : $response;

    }

}
