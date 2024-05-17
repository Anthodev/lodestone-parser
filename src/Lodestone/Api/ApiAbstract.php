<?php

namespace Lodestone\Api;

use Lodestone\Enum\LocaleEnum;
use Lodestone\Http\Http;
use Lodestone\Http\Request;
use Lodestone\Http\RequestConfig;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiAbstract
{
    const STRING_FIXES = [
        ['+', '’'],
        [' ', "'"],
    ];

    protected Http $http;

    public function __construct()
    {
        $this->http = new Http();
    }

    /**
     * Handle a request
     */
    protected function handle(
        string $parser,
        array $requestOptions,
        array $extraRequestOptions = [],
        string $locale = LocaleEnum::EN->value,
    ) {
        if(!isset($requestOptions['user-agent'])) {
            $requestOptions['user-agent'] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36";
        }
        $request = new Request($requestOptions);

        if (!empty($extraRequestOptions)) {
            $extraRequestOptions['request']['user-agent'] = $requestOptions['user-agent'];
            $extraRequest = new Request($extraRequestOptions['request']);

            /** @var ResponseInterface $response */
            $response = $this->http->request(
                parser: $parser,
                request: $request,
                extraRequestOptions: [
                    'parser' => $extraRequestOptions['parser'],
                    'request' => $extraRequest,
                    'dataTarget' => $extraRequestOptions['dataTarget'],
                ],
                locale: $locale,
            );
        } else {
            /** @var ResponseInterface $response */
            $response = $this->http->request(
                parser: $parser,
                request: $request,
                locale: $locale,
            );
        }

        if (RequestConfig::$isAsync) {
            return null;
        }

        return $response;
    }
}
