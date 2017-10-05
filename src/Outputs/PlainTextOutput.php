<?php

namespace Sa\WebBenchmark\Outputs;

use Sa\WebBenchmark\Contracts\OutputInterface;
use Sa\WebBenchmark\WebBenchmark;
use Sa\WebBenchmark\WebResource;

class PlainTextOutput implements OutputInterface
{

    /**
     * Output data
     *
     * @param WebBenchmark $webBenchmark
     * @return mixed
     */
    public function output(WebBenchmark $webBenchmark)
    {

        $text = PHP_EOL;
        $text .= PHP_EOL . "[" . date(\DateTime::ISO8601) . "] Processed {$webBenchmark->getResource()->getUrl()} in {$webBenchmark->getResource()->getLoadTime()}ms";

        $outputLengths = [
            "url" => strlen($webBenchmark->getResource()->getUrl()) + 3,
            "size" => strlen($webBenchmark->getResource()->getSizeFormatted()),
            "time" => strlen($webBenchmark->getResource()->getLoadTimeFormatted()),
            "diff" => strlen($webBenchmark->getResource()->getLoadDiffFormatted($webBenchmark->getResource())),
        ];

        foreach ($webBenchmark->getCompetitors() as $compareResource) {
            $diffLoadTime = ($compareResource->getLoadTime() - $webBenchmark->getResource()->getLoadTime());
            $diffString = number_format($diffLoadTime, 2, '.', '');

            $outputLengths['url'] = max($outputLengths['url'], strlen($compareResource->getUrl()));
            $outputLengths['size'] = max($outputLengths['size'], strlen($compareResource->getSizeFormatted()));
            $outputLengths['time'] = max($outputLengths['time'], strlen($compareResource->getLoadTimeFormatted()));
            $outputLengths['diff'] = max($outputLengths['diff'], strlen($diffString));
        }

        $strLen = array_sum($outputLengths) + ((count($outputLengths) - 1) * 3) + 4;

        $allResources = array_merge($webBenchmark->getCompetitors(), [$webBenchmark->getResource()]);

        usort($allResources, function ($l, $r) {
            return $l->getLoadTime() <=> $r->getLoadTime();
        });

        $text .= PHP_EOL . str_repeat("-", $strLen);
        $text .= PHP_EOL
            . "| " . str_pad("Url", $outputLengths['url'], ' ')
            . " | " . str_pad("Size", $outputLengths['size'], ' ')
            . " | " . str_pad("Time", $outputLengths['time'], ' ')
            . " | " . str_pad("Diff", $outputLengths['diff'], ' ')
            . " |";
        $text .= PHP_EOL . str_repeat("-", $strLen);

        foreach ($allResources as $compareResource) {
            $text .= PHP_EOL . $this->generateTableRow($webBenchmark->getResource(), $compareResource, $outputLengths);
            $text .= PHP_EOL . str_repeat("-", $strLen);
        }

        return $text . PHP_EOL;
    }

    /**
     * Generate table row
     *
     * @param WebResource $resource
     * @param WebResource $competitor
     * @param $outputLengths
     * @return string
     */
    public function generateTableRow(WebResource $resource, WebResource $competitor, $outputLengths)
    {
        $url = ($competitor->isMain() ? '*' : '') . $competitor->getUrl() . ($competitor->isMain() ? '*' : '');
        return sprintf(
            "| %s | %s | %s | %s |",
            str_pad($url, $outputLengths['url'], ' '),
            str_pad($competitor->getSizeFormatted(), $outputLengths['size'], ' '),
            str_pad($competitor->getLoadTimeFormatted(), $outputLengths['time'], ' '),
            str_pad($resource->getLoadDiffFormatted($competitor), $outputLengths['diff'], ' ')
        );

    }

}
