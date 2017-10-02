<?php

namespace Sa\WebBenchmark\Outputs;


use Sa\WebBenchmark\Contracts\OutputInterface;
use Sa\WebBenchmark\WebBenchmark;

class JsonOutput implements OutputInterface
{

    /**
     * Output data
     *
     * @param WebBenchmark $webBenchmark
     * @return mixed
     */
    public function output(WebBenchmark $webBenchmark)
    {
        return json_encode($webBenchmark->toArray());
    }

}
