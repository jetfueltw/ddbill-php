<?php

namespace Jetfuel\Ddbill;

class BankPayment extends Payment
{
    const BASE_API_URL = 'https://pay.ddbill.com/';
    const SERVICE_TYPE = 'direct_pay';
    const PRODUCT_NAME = 'PRODUCT_NAME';
    const CHARSET      = 'UTF-8';

    /**
     * BankPayment constructor.
     *
     * @param string $merchantId
     * @param string $privateKey
     * @param null|string $httpReferer
     * @param null|string $baseApiUrl
     */
    public function __construct($merchantId, $privateKey, $httpReferer = null, $baseApiUrl = null)
    {
        $baseApiUrl = $baseApiUrl === null ? self::BASE_API_URL : $baseApiUrl;

        parent::__construct($merchantId, $privateKey, $baseApiUrl, $httpReferer);
    }

    /**
     * Create bank payment order.
     *
     * @param string $tradeNo
     * @param string $bank
     * @param float $amount
     * @param string $clientIp
     * @param string $notifyUrl
     * @return string
     */
    public function order($tradeNo, $bank, $amount, $clientIp, $notifyUrl)
    {
        $payload = $this->signPayload([
            'order_no'      => $tradeNo,
            'service_type'  => self::SERVICE_TYPE,
            'order_amount'  => $amount,
            'order_time'    => $this->getCurrentTime(),
            'client_ip'     => $clientIp,
            'notify_url'    => $notifyUrl,
            'product_name'  => self::PRODUCT_NAME,
            'input_charset' => self::CHARSET,
            'bank_code'     => $bank,
        ]);

        return $this->httpClient->post('gateway', $payload);
    }
}
