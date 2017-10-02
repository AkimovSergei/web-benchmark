<?php

namespace Sa\WebBenchmark\Contracts;

use Sa\WebBenchmark\WebBenchmark;

interface EventInterface
{

    /**
     * EventInterface constructor.
     *
     * @param WebBenchmark $webBenchmark
     */
    public function __construct(WebBenchmark $webBenchmark);

}
