<?php
/*
* (c) Wessel Strengholt <wessel.strengholt@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Shivella\Bitly\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
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
	 * @return string
	 *
	 * @throws InvalidResponseException
	 * @throws AccessDeniedException*
	 * @throws GuzzleException
	 * @throws AccessTokenMissingException
	 */
    public function getUrl($url)
    {
        if ($this->token === null) {
            throw new AccessTokenMissingException('Access token is not set');
        }

        try {
            $requestUrl = sprintf('https://api-ssl.bitly.com/v3/shorten?longUrl=%s&access_token=%s', $url, $this->token);
            $response = $this->client->send(new Request('GET', $requestUrl));

            if ($response->getStatusCode() === Response::HTTP_FORBIDDEN) {
                throw new AccessDeniedException('Invalid access token');
            }

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                throw new InvalidResponseException('The API does not return a 200 status code');
            }

            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['status_txt']) && $data['status_txt'] === 'RATE_LIMIT_EXCEEDED') {
		        throw new InvalidResponseException('You have reached the API rate limit, please try again later');
            }

            if (false === isset($data['data']['url'])) {
                throw new InvalidResponseException('The response does not contain a shortened link');
            }

            if ($data['status_code'] !== Response::HTTP_OK) {
                throw new InvalidResponseException('The API does not return a 200 status code');
            }

            return $data['data']['url'];

        } catch (\Exception $exception) {
            throw new InvalidResponseException($exception->getMessage());
        }
    }

	/**
	 * @param string $bitlyUrl
	 * @param string $unit
	 * @param int $units
	 * @param bool $rollup
	 *
	 * @return string|array
	 *
	 * @throws AccessTokenMissingException https://dev.bitly.com/link_metrics.html#v3_link_clicks
	 * @throws GuzzleException
	 * @throws InvalidResponseException
	 */
    private function getClicks($bitlyUrl, $unit = "day", $units = -1, $rollup = true)
    {
        if ($this->token === null) {
            throw new AccessTokenMissingException('Access token is not set');
        }

        try {
            $requestUrl = sprintf(
                'https://api-ssl.bitly.com/v3/link/clicks?link=%s&access_token=%s&unit=%s&units=%s&rollup=%s',
                $bitlyUrl, $this->token, $unit, $units, ($rollup ? 'true' : 'false')
            );

            $response = $this->client->send(new Request('GET', $requestUrl));

            if ($response->getStatusCode() === Response::HTTP_FORBIDDEN) {
                throw new AccessDeniedException('Invalid access token');
            }

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                throw new InvalidResponseException('The API does not return a 200 status code');
            }

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['status_txt']) && $data['status_txt'] === 'RATE_LIMIT_EXCEEDED') {
		        throw new InvalidResponseException('You have reached the API rate limit, please try again later');
            }

            if (false === isset($data['data']['link_clicks'])) {
                throw new InvalidResponseException('The response does not contain the number of clicks');
            }
            
            if ($data['status_code'] !== Response::HTTP_OK) {
                throw new InvalidResponseException('The API does not return a 200 status code');
            }

            return $data['data']['link_clicks'];

        } catch (\Exception $exception) {
            throw new InvalidResponseException($exception->getMessage());
        }
    }

	/**
	 * @param string $bitlyUrl
	 * @param string $unit
	 * @param int $units
	 *
	 * @return string
	 *
	 * @throws AccessDeniedException
	 * @throws AccessTokenMissingException
	 * @throws GuzzleException
	 * @throws InvalidResponseException
	 */
	public function getTotalClicks($bitlyUrl, $unit = "day", $units = -1)
	{
		return $this->getClicks($bitlyUrl, $unit, $units, true);
	}

	/**
	 * @param string $bitlyUrl
	 * @param string $unit
	 * @param int $units
	 *
	 * @return array
	 *
	 * @throws AccessTokenMissingException
	 * @throws GuzzleException
	 * @throws InvalidResponseException
	 */
	public function getArrayOfClicks($bitlyUrl, $unit = "day", $units = -1)
	{
		return $this->getClicks($bitlyUrl, $unit, $units, false);
	}
}
