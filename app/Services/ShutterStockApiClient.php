<?php

namespace App\Services;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\PromiseInterface as Promise;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Psr\Http\Message\ResponseInterface as Response;

class ShutterStockApiClient
{

    /** @var  Guzzle */
    protected $guzzle;

    /**
     * Client ID
     *
     * @var Repository|Application|mixed
     */
    protected $SHUTTERSTOCK_CONSUMER_KEY;

    /**
     * Client Secret
     *
     * @var Repository|Application|mixed
     */
    protected $SHUTTERSTOCK_CONSUMER_SECRET;

    /**
     * default count of images if not set
     *
     * @var int
     */
    protected $per_page = 50;

    public function __construct()
    {
        $this->SHUTTERSTOCK_CONSUMER_KEY = config('app.SHUTTERSTOCK_CONSUMER_KEY');
        $this->SHUTTERSTOCK_CONSUMER_SECRET = config('app.SHUTTERSTOCK_CONSUMER_SECRET');

        $stack = HandlerStack::create();
        $stack->push(Middleware::mapResponse(function (Response $response) {
            $jsonStream = new JsonStream($response->getBody());
            return $response->withBody($jsonStream);
        }));

        $guzzle = new Guzzle([
            'base_uri' => 'https://api.shutterstock.com/v2/',
            'auth' => [$this->SHUTTERSTOCK_CONSUMER_KEY, $this->SHUTTERSTOCK_CONSUMER_SECRET],
            'handler' => $stack,
        ]);
        $this->guzzle = $guzzle;
    }

    /**
     * @param string $uri
     * @param array  $query
     * @param array  $options
     *
     * @return Response
     */
    public function get($uri, array $query = [], array $options = [])
    {
        if (!empty($query)) {
            $options['query'] = $this->buildQuery($query);
        }
        return $this->guzzle->get($uri, $options);
    }

    /**
     * @param string $uri
     * @param array  $query
     * @param array  $options
     *
     * @return Promise
     */
    public function getAsync($uri, array $query = [], array $options = [])
    {
        if (!empty($query)) {
            $options['query'] = $this->buildQuery($query);
        }
        return $this->guzzle->getAsync($uri, $options);
    }

    /**
     * @param array  $query
     * @param string $separator
     *
     * @return string
     */
    public function buildQuery(array $query, string $separator = '&'): string
    {
        $queryPieces = [];
        if (!array_key_exists('per_page', $query)) {
            $query['per_page'] = $this->per_page;
        }
        foreach ($query as $key => $value) {
            if (!is_array($value)) {
                $piece = urlencode($key) . '=' . urlencode($value);
                $queryPieces[] = $piece;
                continue;
            }
            foreach ($value as $valuePiece) {
                $piece = urlencode($key) . '=' . urlencode($valuePiece);
                $queryPieces[] = $piece;
            }
        }

        $queryString = implode($separator, $queryPieces);
        return $queryString;
    }

    /**
     * @param string $uri
     * @param array  $body
     * @param array  $options
     *
     * @return Response
     */
    public function post($uri, array $body = [], array $options = [])
    {
        if (!empty($body)) {
            $options['json'] = $body;
        }
        return $this->guzzle->post($uri, $options);
    }

    /**
     * @param string $uri
     * @param array  $body
     * @param array  $options
     *
     * @return Promise
     */
    public function postAsync($uri, array $body = [], array $options = [])
    {
        if (!empty($body)) {
            $options['json'] = $body;
        }
        return $this->guzzle->postAsync($uri, $options);
    }
}