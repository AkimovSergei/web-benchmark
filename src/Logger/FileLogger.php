<?php

namespace Sa\WebBenchmark\Logger;

use Sa\WebBenchmark\Contracts\LoggerInterface;
use Sa\WebBenchmark\Exceptions\FileLoggerException;
use Sa\WebBenchmark\Exceptions\InvalidArgumentException;

/**
 * Class FileLogger
 *
 * @package Sa\WebBenchmark\Logger
 */
class FileLogger implements LoggerInterface
{

    /**
     * Instance
     *
     * @var FileLogger
     */
    private static $instance;

    /**
     * File permissions
     */
    const FILE_CHMOD = 756;

    /**
     * Log levels
     *
     * @var array
     */
    public static $logLevels = [
        self::LEVEL_INFO,
        self::LEVEL_DEBUG,
        self::LEVEL_NOTICE,
        self::LEVEL_WARNING,
        self::LEVEL_ERROR,
        self::LEVEL_FATAL,
    ];

    /**
     * Log file handler
     *
     * @var resource
     */
    protected $handler;

    /**
     * Log datetime format
     *
     * @var string
     */
    protected $datetimeFormat;

    /**
     * FileLogger constructor.
     *
     * @throws FileLoggerException
     */
    private function __construct()
    {
        if ($this->handler == NULL) {
            $this->openLogFile(realpath(dirname(__FILE__)) . "/../Resources/Logs/web-benchmark.log");
        }

        $this->datetimeFormat = 'Y-m-d H:i:s';
    }

    /**
     * Prevent creating instances while deserialization
     */
    private function __wakeup()
    {
    }

    /**
     * Prevent creating object on clone
     */
    private function __clone()
    {
    }

    /**
     * Get FileLogger instance
     *
     * @return FileLogger
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Log INFO
     *
     * @param $message
     * @param $params
     * @throws InvalidArgumentException
     */
    public static function info($message, array $params = [])
    {
        static::getInstance()->log($message, static::LEVEL_INFO, $params);
    }

    /**
     * Log DEBUG
     *
     * @param $message
     * @param $params
     * @throws InvalidArgumentException
     */
    public static function debug($message, array $params = [])
    {
        static::getInstance()->log($message, static::LEVEL_DEBUG, $params);
    }

    /**
     * Log NOTICE
     *
     * @param $message
     * @param $params
     * @throws InvalidArgumentException
     */
    public static function notice($message, array $params = [])
    {
        static::getInstance()->log($message, static::LEVEL_NOTICE, $params);
    }

    /**
     * Log WARNING
     *
     * @param $message
     * @param $params
     * @throws InvalidArgumentException
     */
    public static function warning($message, array $params = [])
    {
        static::getInstance()->log($message, static::LEVEL_WARNING, $params);
    }

    /**
     * Log ERROR
     *
     * @param $message
     * @param $params
     * @throws InvalidArgumentException
     */
    public static function error($message, array $params = [])
    {
        static::getInstance()->log($message, static::LEVEL_ERROR, $params);
    }

    /**
     * Log FATAL
     *
     * @param $message
     * @param $params
     * @throws InvalidArgumentException
     */
    public static function fatal($message, array $params = [])
    {
        static::getInstance()->log($message, static::LEVEL_FATAL, $params);
    }

    /**
     * Log message
     *
     * @param $message
     * @param $level
     * @param array $params
     * @throws InvalidArgumentException
     */
    public function log($message, $level, array $params = [])
    {
        if ($message instanceof \Exception) {
            $message = $message->getMessage() . PHP_EOL . $message->getTraceAsString();
        }

        if (!is_string($message)) {
            throw new InvalidArgumentException("Message should be type of Exception or string");
        }

        if (!$this->validateLogLevel($level)) {
            throw new InvalidArgumentException("Wrong log level.");
        }

        if (!empty($params)) {
            $message .= json_encode($params);
        }

        $this->writeToLogFile($this->getDateTime() . " {$level} {$message}");
    }

    /**
     * @param $level
     * @return bool
     */
    protected function validateLogLevel($level)
    {
        return in_array($level, static::$logLevels);
    }

    /**
     * Get timestamp
     *
     * @return string
     */
    protected function getDateTime(): string
    {
        return "[" . date($this->datetimeFormat) . "]";
    }


    /**
     * Writes content to the log file.
     *
     * @param string $message
     */
    protected function writeToLogFile($message)
    {
        flock($this->handler, LOCK_EX);
        fwrite($this->handler, $message . PHP_EOL);
        flock($this->handler, LOCK_UN);
    }

    /**
     * Opens a file handle.
     *
     * @param string $logFile Path to log file.
     * @throws FileLoggerException
     */
    protected function openLogFile($logFile)
    {
        $this->closeLogFile();

        if (!is_dir(dirname($logFile))) {
            if (!mkdir(dirname($logFile), static::FILE_CHMOD, true)) {
                throw new FileLoggerException('Could not find or create directory for log file.');
            }
        }

        if (!$this->handler = fopen($logFile, 'a+')) {
            throw new FileLoggerException('Could not open file handle.');
        }
    }

    /**
     * Closes the current log file.
     */
    protected function closeLogFile()
    {
        if (NULL !== $this->handler) {
            fclose($this->handler);
            $this->handler = NULL;
        }
    }


}
