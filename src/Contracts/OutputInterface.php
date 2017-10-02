<?php

namespace Sa\WebBenchmark\Contracts;

use Sa\WebBenchmark\WebBenchmark;

/**
 * Interface OutputInterface
 *
 * @package Sa\WebBenchmark\Contracts
 */
interface OutputInterface
{

    /**
     * Output data
     *
     * @param WebBenchmark $webBenchmark
     * @return mixed
     */
    public function output(WebBenchmark $webBenchmark);

}
