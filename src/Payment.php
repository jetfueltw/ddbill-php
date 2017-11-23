<?php

namespace Jetfuel\Ddbill;

use Jetfuel\Ddbill\HttpClient\GuzzleHttpClient;

class Payment
{
    const BASE_API_URL = 'https://api.ddbill.com/';
    const TIME_ZONE    = 'Asia/Shanghai';
    const TIME_FORMAT  = 'Y-m-d H:i:s';
    const API_VERSION  = 'V3.3';
    const SIGN_TYPE    = 'RSA-S';

    /**
     * @var string
     */
    protected $merchantId;

    /**
     * @var string
     */
    protected $privateKey;

    /**
     * @var string
     */
    protected $baseApiUrl;

    /**
     * @var string|null
     */
    protected $httpReferer;

    /**
     * @var \Jetfuel\Ddbill\HttpClient\HttpClientInterface
     */
    protected $httpClient;

    /**
     * Payment constructor.
     *
     * @param string $merchantId
     * @param string $privateKey
     * @param string $baseApiUrl
     * @param null|string $httpReferer
     */
    protected function __construct($merchantId, $privateKey, $baseApiUrl = null, $httpReferer = null)
    {
        $this->merchantId = $merchantId;
        $this->privateKey = $privateKey;
        $this->baseApiUrl = $baseApiUrl === null ? self::BASE_API_URL : $baseApiUrl;
        $this->httpReferer = $httpReferer;

        $this->httpClient = new GuzzleHttpClient($this->baseApiUrl, $this->httpReferer);
    }

    /**
     * Sign request payload.
     *
     * @param array $payload
     * @return array
     */
    protected function signPayload(array $payload)
    {
        $payload['merchant_code'] = $this->merchantId;
        $payload['interface_version'] = self::API_VERSION;
        $payload['sign'] = Signature::generate($payload, $this->privateKey);
        $payload['sign_type'] = self::SIGN_TYPE;

        return $payload;
    }

    /**
     * Get current time.
     *
     * @return string
     */
    protected function getCurrentTime()
    {
        return (new \DateTime('now', new \DateTimeZone(self::TIME_ZONE)))->format(self::TIME_FORMAT);
    }
}
