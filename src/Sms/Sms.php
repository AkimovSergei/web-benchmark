<?php
/**
 * Created by PhpStorm.
 * User: sergeiakimov
 * Date: 10/3/17
 * Time: 4:09 PM
 */

namespace Sa\WebBenchmark\Sms;


use Sa\WebBenchmark\Contracts\SmsInterface;

class Sms implements SmsInterface
{

    CONST ENDPOINT = "https://api.endpoint.mock";

    /**
     * API KEY
     *
     * @var string
     */
    protected $apiKey;

    /**
     * API KEY
     *
     * @var string
     */
    protected $sender;

    /**
     * Sms constructor.
     *
     * @param string $apiKey
     * @param string $sender
     */
    public function __construct(string $apiKey, string $sender)
    {
        $this->apiKey = $apiKey;
        $this->sender = $sender;
    }

    /**
     * Send SMS
     *
     * @param $phone
     * @param $message
     * @return mixed
     */
    public function send($phone, $message)
    {
        $body = [
            'from' => $this->sender,
            'api_key' => $this->apiKey,
            'to' => $phone,
            'message' => $message,
        ];

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, static::ENDPOINT);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $body);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($c);
        $httpStatus = curl_getinfo($c, CURLINFO_HTTP_CODE);

        if ($httpStatus != 200) {

        }

        curl_close($c);

        return $content;
    }

}
