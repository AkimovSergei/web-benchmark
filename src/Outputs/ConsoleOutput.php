<?php

namespace Sa\WebBenchmark\Outputs;

use Sa\WebBenchmark\WebBenchmark;

class ConsoleOutput extends PlainTextOutput
{

    /**
     * Output data
     *
     * @param WebBenchmark $webBenchmark
     * @return mixed
     */
    public function output(WebBenchmark $webBenchmark)
    {
        return parent::output($webBenchmark);
    }

}
