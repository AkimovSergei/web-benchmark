<?php

namespace Sa\WebBenchmark\Events;

use Sa\WebBenchmark\Contracts\EventInterface;
use Sa\WebBenchmark\WebBenchmark;

class TwoTimesSlowestEvent implements EventInterface
{
    /**
     * @var WebBenchmark
     */
    protected $webBenchmark;

    /**
     * EventInterface constructor.
     *
     * @param WebBenchmark $webBenchmark
     */
    public function __construct(WebBenchmark $webBenchmark)
    {

        $this->webBenchmark = $webBenchmark;
    }

    /**
     * @return WebBenchmark
     */
    public function getWebBenchmark(): WebBenchmark
    {
        return $this->webBenchmark;
    }

}
