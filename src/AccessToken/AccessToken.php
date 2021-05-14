<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/25 0025
 * Time: 17:22
 */
namespace CherryneChou\EasyYilianYun\AccessToken;

use CherryneChou\EasyYilianYun\Auth\Client;
use CherryneChou\EasyYilianYun\Kernel\Exceptions\InvalidCredentialsException;
use CherryneChou\EasyYilianYun\Kernel\Support\Arr;
use CherryneChou\EasyYilianYun\Kernel\Traits\HasSign;
use CherryneChou\EasyYilianYun\Kernel\Traits\InteractsWithCache;
use CherryneChou\EasyYilianYun\Kernel\Traits\ResponseCastable;
use Ramsey\Uuid\Uuid;
use CherryneChou\EasyYilianYun\Application;

/**
 * Class AccessToken
 * @package CherryneChou\EasyYilianYun\AccessToken
 */
class AccessToken
{
    use InteractsWithCache, ResponseCastable, HasSign;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var int
     */
    protected $refreshExpired=35;     //刷新AccessToken凭证 失效时间35天

	/**
     * AccessToken constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return mixed
     * @throws \CherryneChou\EasyYilianYun\Kernel\Exceptions\InvalidArgumentException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function get(){
        //若有access_token
        if ($token = $this->getCache()->get($this->cacheForAccessToken())) {
            return $token;
        }

        //判断 是否存在 refresh_token
        if($refreshToken = $this->getCache()->get($this->cacheForRefreshToken())) {
            return $this->refreshToken($refreshToken);
        }

        return $this->getToken();
    }

    /**
     * @return mixed
     */
    protected function getToken()
    {
        $time = time();
        $params = [
            'client_id' => $this->app['config']->get('client_id'),
            'grant_type' => 'client_credentials',
            'sign'=>$this->getSign($time),
            'scope' => 'all',
            'timestamp' => $time,
            'id' => strtoupper(Uuid::uuid4()->toString()),
        ];

        return $this->send($params);
    }

    /**
     * @param $refreshToken
     * @return mixed
     */
    protected function refreshToken($refreshToken)
    {
        $time = time();
        $params = [
            'client_id' => $this->app['config']->get('client_id'),
            'grant_type' => 'refresh_token',
            'sign'=>$this->getSign($time),
            'scope' => 'all',
            'timestamp' => $time,
            'id' => strtoupper(Uuid::uuid4()->toString()),
            'refresh_token' => $refreshToken,
        ];

        return $this->send($params);
    }

    /**
     * @param $params
     * @return \Illuminate\Support\HigherOrderTapProxy|mixed
     * @throws \CherryneChou\EasyYilianYun\Kernel\Exceptions\InvalidArgumentException
     * @throws \CherryneChou\EasyYilianYun\Kernel\Exceptions\InvalidConfigException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function send($params)
    {
        $response = (new Client($this->app))->httpPost('oauth/oauth', $params);

        return tap($this->castResponseToType($response, 'array'), function ($value) {
            if ('success' !== Arr::get($value,'error_description')  ) {
                throw new InvalidCredentialsException(json_encode($value));
            }

            $ttl = $this->refreshExpired * 24 * 60 * 60;
            $this->getCache()->set($this->cacheForAccessToken(), $value['body']['access_token'], $value['body']['expires_in']);
            $this->getCache()->set($this->cacheForRefreshToken(), $value['body']['refresh_token'], $ttl);
        });
    }

    /**
     *
     * @return string
     */
    protected function cacheForAccessToken()
    {
        return sprintf('access_token.%s', $this->app['config']->get('client_secret'));
    }

    /**
     * @return string
     */
    protected function cacheForRefreshToken()
    {
        return sprintf('refresh_token.%s', $this->app['config']->get('client_secret'));
    }
}
