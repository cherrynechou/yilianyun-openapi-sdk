<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/26 0026
 * Time: 10:31
 */
namespace CherryneChou\EasyYilianYun\Kernel\Traits;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;

/**
 * Trait HasHttpRequests
 * @package CherryneChou\EasyYilianYun\Kernel\Traits
 */
trait HasHttpRequests
{
    /**
     * Http client.
     *
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * The middlewares.
     *
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var \GuzzleHttp\HandlerStack
     */
    protected $handlerStack;

    /**
     * Guzzle client default settings.
     *
     * @var array
     */
    protected static $defaults = [
        'curl' => [
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ]
    ];

    /**
     * Set guzzle default settings.
     *
     * @param array $defaults
     */
    public static function setDefaultOptions($defaults = [])
    {
        self::$defaults = $defaults;
    }

    /**
     * Return current guzzle default settings.
     *
     * @return array
     */
    public static function getDefaultOptions()
    {
        return self::$defaults;
    }

    /**
     * Set GuzzleHttp\Client.
     *
     * @param \GuzzleHttp\Client $client
     *
     * @return Http
     */
    public function setClient(HttpClient $client)
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * Return GuzzleHttp\Client instance.
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        if (!($this->httpClient instanceof HttpClient)) {
            $this->httpClient = new HttpClient();
        }

        return $this->httpClient;
    }

    /**
     * Add a middleware.
     *
     * @param callable $middleware
     *
     * @return $this
     */
    public function addMiddleware(callable $middleware)
    {
        array_push($this->middlewares, $middleware);

        return $this;
    }

    /**
     * Return all middlewares.
     *
     * @return array
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     * @param bool $returnRaw
     * @return mixed
     */
    public function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false)
    {
        $method = strtoupper($method);

        $options = array_merge(self::$defaults, $options, ['handler' => $this->getHandlerStack()]);

        if (property_exists($this, 'baseUri') && !is_null($this->baseUri)) {
            $options['base_uri'] = $this->baseUri;
        }

        return $this->getHttpClient()->request($method, $url, $options);

    }

    /**
     * @param HandlerStack $handlerStack
     * @return $this
     */
    public function setHandlerStack(HandlerStack $handlerStack)
    {
        $this->handlerStack = $handlerStack;

        return $this;
    }

    /**
     * Build a handler.
     *
     * @return HandlerStack
     */
    protected function getHandlerStack(): HandlerStack
    {
        if ($this->handlerStack) {
            return $this->handlerStack;
        }

        $this->handlerStack = HandlerStack::create($this->getGuzzleHandler());

        foreach ($this->middlewares as $name => $middleware) {
            $this->handlerStack->push($middleware, $name);
        }

        return $this->handlerStack;
    }

    /**
     * Get guzzle handler.
     *
     * @return callable
     */
    protected function getGuzzleHandler()
    {
        if (property_exists($this, 'app') && isset($this->app['guzzle_handler'])) {
            return is_string($handler = $this->app->raw('guzzle_handler'))
                ? new $handler()
                : $handler;
        }

        return \GuzzleHttp\choose_handler();
    }
}
