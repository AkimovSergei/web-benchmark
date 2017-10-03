<?php
/**
 * Created by PhpStorm.
 * User: sergeiakimov
 * Date: 10/3/17
 * Time: 11:22 AM
 */

namespace Sa\WebBenchmark\Contracts;

/**
 * Interface LoggerInterface
 *
 * @package Sa\WebBenchmark\Contracts
 */
interface LoggerInterface
{

    CONST LEVEL_INFO = "info";
    CONST LEVEL_DEBUG = "debug";
    CONST LEVEL_NOTICE = "notice";
    CONST LEVEL_WARNING = "warning";
    CONST LEVEL_ERROR = "error";
    CONST LEVEL_FATAL = "fatal";

    /**
     * Log
     *
     * @param $message
     * @param $level
     * @param array $params
     * @return mixed
     */
    public function log($message, $level, array $params);

}