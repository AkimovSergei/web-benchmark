<?php

namespace Sa\WebBenchmark;

use Sa\WebBenchmark\Contracts\Arrayable;

/**
 * Class WebResourceAttributes
 *
 * @package Sa\WebBenchmark
 */
class WebResourceAttributes implements Arrayable
{

    /**
     * Is fastest loaded resource
     *
     * @var bool
     */
    protected $isFastest = false;

    /**
     * Is slowest resource
     *
     * @var bool
     */
    protected $isSlowest = false;

    /**
     * Check is fastest resource
     *
     * @return bool
     */
    public function isFastest(): bool
    {
        return $this->isFastest;
    }

    /**
     * Set is fastest resource
     *
     * @param bool $isFastest
     */
    public function setIsFastest(bool $isFastest)
    {
        $this->isFastest = $isFastest;
    }

    /**
     * Check is resource slowest
     *
     * @return bool
     */
    public function isSlowest(): bool
    {
        return $this->isSlowest;
    }

    /**
     * Set is slowest resource
     *
     * @param bool $isSlowest
     */
    public function setIsSlowest(bool $isSlowest)
    {
        $this->isSlowest = $isSlowest;
    }

    /**
     * return array
     */
    public function toArray()
    {
        return [
            'is_fastest' => $this->isFastest(),
            'is_slowest' => $this->isSlowest(),
        ];
    }

}
