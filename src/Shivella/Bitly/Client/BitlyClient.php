<?php

namespace Shivella\Bitly\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Shivella\Bitly\Exceptions\AccessDeniedException;
use Shivella\Bitly\Exceptions\AccessTokenMissingException;
use Shivella\Bitly\Exceptions\InvalidResponseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BitlyClient
 */
class BitlyClient
{
    /** @var ClientInterface */
    private $client;

    /** @var string $token */
    private $token;

    /**
     * @param ClientInterface $client
     * @param string          $token
     */
    public function __construct(ClientInterface $client, $token)
    {
        $this->client = $client;
        $this->token  = $token;
    }

    /**
     * @param string $url
     *
     * @throws AccessTokenMissingException
     * @throws InvalidResponseException
     * @throws AccessDeniedException
     *
     * @return string
     */
    public function getUrl($url)
    {
        if ($this->token === null) {
            throw new AccessTokenMissingException('Access token is not set');
        }

        try {
            $requestUrl = sprintf('https://api-ssl.bitly.com/v3/link/lookup?url=%s&access_token=%s', $url, $this->token);
            $response = $this->client->send(new Request('GET', $requestUrl));

            if ($response->getStatusCode() === Response::HTTP_FORBIDDEN) {
                throw new AccessDeniedException('Invalid access token');
            }

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                throw new InvalidResponseException('The API does not return a 200 status code');
            }

            $data = json_decode($response->getBody()->getContents(), true);

            if (false === isset($data['data']['link_lookup'][0]['aggregate_link'])) {
                throw new InvalidResponseException('The response does not contain a aggregate link');
            }

            if ($data['status_code'] !== Response::HTTP_OK) {
                throw new InvalidResponseException('The API does not return a 200 status code');
            }

            return $data['data']['link_lookup'][0]['aggregate_link'];

        } catch (\Exception $exception) {
            throw new InvalidResponseException($exception->getMessage());
        }
    }
}
