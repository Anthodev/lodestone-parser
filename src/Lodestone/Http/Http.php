<?php

namespace Lodestone\Http;

use Lodestone\Enum\LocaleEnum;
use Lodestone\Enum\LodestoneBaseUriEnum;
use Lodestone\Exceptions\LodestoneException;
use Lodestone\Exceptions\LodestoneMaintenanceException;
use Lodestone\Exceptions\LodestoneNotFoundException;
use Lodestone\Exceptions\LodestonePrivateException;
use Lodestone\Parser\Parser;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Http
{
    const int TIMEOUT  = 30;

    private string $baseUri;

    /**
     * Get Symfony Client
     */
    private function getClient(string $baseUri = null): CurlHttpClient
    {
        return new CurlHttpClient([
            'base_uri' => $baseUri ?? $this->baseUri,
            'timeout'  => self::TIMEOUT
        ]);
    }

    /**
     * Perform a request
     * @throws
     */
    public function request(
        string $parser,
        Request $request,
        int $tryIndex = 0,
        array $extraRequestOptions = [],
        string $locale = LocaleEnum::EN->value,
    ) {
        $this->baseUri = match ($locale) {
            LocaleEnum::FR->value => LodestoneBaseUriEnum::FR->value,
            LocaleEnum::DE->value => LodestoneBaseUriEnum::DE->value,
            LocaleEnum::JA->value => LodestoneBaseUriEnum::JA->value,
            default => LodestoneBaseUriEnum::EN->value,
        };

        $response = $this->processRequest($parser, $request);

        if (!empty($extraRequestOptions)) {
            $responseExtraRequest = $this->processRequest($extraRequestOptions['parser'], $extraRequestOptions['request']);
        }

        // Asynchronous: Pop the response into the async handler, this returns the number
        // it was assigned to
        if (RequestConfig::$isAsync) {
            AsyncHandler::add($response);
            return null;
        }

        if ($response->getStatusCode() !== 200 && $tryIndex < 3) {
            sleep(2);
            return $this->request(
                parser: $parser,
                request: $request,
                tryIndex: $tryIndex + 1,
                extraRequestOptions: $extraRequestOptions,
                locale: $locale,
            );
        }

        if ($response->getStatusCode() == 503) {
            throw new LodestoneMaintenanceException(
                'Lodestone is currently down for maintenance.',
                $response->getStatusCode()
            );
        }

        if ($response->getStatusCode() == 404) {
            throw new LodestoneNotFoundException(
                'Could not find: ' . $request->userData['request_url'],
                $response->getStatusCode()
            );
        }

        if ($response->getStatusCode() == 403) {
            throw new LodestonePrivateException(
                'This page is private: ' . $request->userData['request_url'],
                $response->getStatusCode()
            );
        }

        if ($response->getStatusCode() != 200) {
            throw new LodestoneException(
                'Unknown exception status code (' . $response->getStatusCode() . ') for: ' . $request->userData['request_url'],
                $response->getStatusCode()
            );
        }

        /** @var Parser $parser */
        $parser = new $parser($request->userData);
        $content = $parser->handle($response->getContent(), $locale);

        if (!empty($extraRequestOptions)) {
            $extraParser = new $extraRequestOptions['parser']($extraRequestOptions['request']->userData);
            $dataTarget = $extraRequestOptions['dataTarget'];
            $content->$dataTarget = ($extraParser->handle($responseExtraRequest->getContent(), $locale))[strtolower($dataTarget)];
        }

        // Synchronous: Get the content
        return $content;
    }

    /**
     * Settle any async requests
     * @return array<string, mixed>
     * @throws
     */
    public function settle(): array
    {
        if (RequestConfig::$isAsync === false) {
            throw new \Exception("Request API is not in async mode. There will be no async requests to settle.");
        }

        $content   = [];
        $client    = $this->getClient();
        $responses = AsyncHandler::get();

        foreach ($client->stream($responses) as $response => $chunk) {
            // grab the user data
            $userdata = $response->getInfo('user_data');

            // grab request id
            $requestId = $userdata['request_id'];

            // if it wasn't a 200, return error
            if ($response->getStatusCode() != 200) {
                $content[$requestId] = (object)[
                    'Error' => true,
                    'StatusCode' => $response->getStatusCode()
                ];
                continue;
            }

            if ($chunk->isLast()) {
                // grab the parser class name
                /** @var Parser $parser */
                $parser = new $userdata['parser']($userdata);

                // handle response
                $content[$requestId] = $parser->handle(
                    $response->getContent(),
                );
            }
        }

        return $content;
    }

    private function processRequest(
        string $parser,
        Request $request,
    ): ResponseInterface {
        // get client
        $client = $this->getClient($request->baseUri);

        // set some custom user data
        $request->userData['request_url'] = $request->baseUri . $request->endpoint;
        $request->userData['request_id']  = AsyncHandler::$requestId ?: Uuid::uuid4()->toString();
        $request->userData['parser']      = $parser;

        // perform request
        return $client->request($request->method, $request->endpoint, [
            'query'     => $request->query,
            'headers'   => $request->headers,
            'json'      => $request->json,
            'user_data' => $request->userData
        ]);
    }
}
