<?php

namespace Sa\WebBenchmark\Outputs;

use Sa\WebBenchmark\Contracts\OutputInterface;
use Sa\WebBenchmark\WebBenchmark;
use Sa\WebBenchmark\WebResource;

class HtmlOutput implements OutputInterface
{

    /**
     * Output data
     *
     * @param WebBenchmark $webBenchmark
     * @return mixed
     */
    public function output(WebBenchmark $webBenchmark)
    {

        $allResources = array_merge($webBenchmark->getCompetitors(), [$webBenchmark->getResource()]);

        usort($allResources, function (WebResource $l, WebResource $r) {
            return $l->getLoadTime() <=> $r->getLoadTime();
        });

        ob_start();
        require_once realpath(dirname(__FILE__)) . "/../Resources/Views/html-output.php";
        $html = ob_get_clean();

        return $html;

    }

}
