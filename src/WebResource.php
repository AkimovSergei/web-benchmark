<?php

namespace Sa\WebBenchmark;

use Sa\WebBenchmark\Contracts\Arrayable;
use Sa\WebBenchmark\Contracts\ResourceInterface;
use Sa\WebBenchmark\Exceptions\FailedToLoadException;
use Sa\WebBenchmark\Exceptions\InvalidArgumentException;

/**
 * Class WebResource
 *
 * @package Sa\WebBenchmark
 */
class WebResource implements ResourceInterface, Arrayable
{

    /**
     * Resource url
     *
     * @var string
     */
    protected $url;

    /**
     * Loading time
     *
     * @var float
     */
    protected $loadTime;

    /**
     * Content size
     *
     * @var integer
     */
    protected $size;

    /**
     * Is resource loaded
     *
     * @var bool
     */
    protected $isLoaded = false;

    /**
     * Resource attributes
     *
     * @var WebResourceAttributes
     */
    protected $attributes;

    /**
     * @var bool
     */
    private $isMain;

    /**
     * WebResource constructor.
     *
     * @param string $url
     * @param bool $isPrimary
     * @throws InvalidArgumentException
     */
    public function __construct(string $url, $isPrimary = false)
    {
        $this->url = $this->validateUrl($url);
        $this->attributes = new WebResourceAttributes();
        $this->isMain = $isPrimary;
    }

    /**
     * Get requested url
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get loading time
     *
     * @return float
     */
    public function getLoadTime(): float
    {
        return $this->loadTime;
    }

    /**
     * Check is resource loaded
     *
     * @return bool
     */
    public function isLoaded(): bool
    {
        return $this->isLoaded;
    }

    /**
     * Get resource attributes
     *
     * @return WebResourceAttributes
     */
    public function getAttributes(): WebResourceAttributes
    {
        return $this->attributes;
    }

    /**
     * Get formatted load time
     *
     * @return string
     */
    public function getLoadTimeFormatted()
    {
        return number_format($this->getLoadTime(), 2, '.', '') . 'ms';
    }

    /**
     * Check is main resource
     *
     * @return bool
     */
    public function isMain(): bool
    {
        return $this->isMain;
    }

    /**
     * Set is main resource
     *
     * @param bool $isMain
     */
    public function setIsMain(bool $isMain)
    {
        $this->isMain = $isMain;
    }

    /**
     * Get size
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Get size formatted
     *
     * @return string
     */
    public function getSizeFormatted(): string
    {
        $units = ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB'];
        return @round($this->size / pow(1024, ($i = floor(log($this->size, 1024)))), 2) . ' ' . $units[$i];
    }


    /**
     * Validate url
     *
     * @param $url
     * @return string
     * @throws InvalidArgumentException
     */
    public function validateUrl(string $url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException("'{$url}' is not a valid URL");
        }

        return $url;
    }

    /**
     * Load resource
     *
     * @return $this
     * @throws FailedToLoadException
     */
    public function load()
    {
        $startTime = microtime(true);

        $content = @file_get_contents($this->url);

        if (!$content) {
            throw new FailedToLoadException("Can not load {$this->url}");
        }

        $this->size = strlen($content);

        $this->loadTime = round((microtime(true) - $startTime) * 1000, 2);

        $this->isLoaded = true;

        return $this;
    }


    /**
     * Object to array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'url' => $this->getUrl(),
            'load_time' => $this->getLoadTimeFormatted(),
            'is_loaded' => $this->isLoaded(),
            'attributes' => $this->attributes->toArray(),
        ];
    }
    
}
