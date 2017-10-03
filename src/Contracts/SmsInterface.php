<?php

namespace Sa\WebBenchmark\Contracts;

/**
 * Interface SmsInterface
 *
 * @package Sa\WebBenchmark\Contracts
 */
interface SmsInterface
{

    /**
     * Send SMS
     *
     * @param $phone
     * @param $message
     * @return mixed
     */
    public function send($phone, $message);

}
