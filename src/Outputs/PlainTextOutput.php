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

        $text = "";

        $text .= $this->getNewLineChar();
        $text .= $this->getNewLineChar() . "[" . date(\DateTime::ISO8601) . "] Processed {$webBenchmark->getResource()->getUrl()} in {$webBenchmark->getResource()->getLoadTime()}ms";
        $text .= $this->getNewLineChar() . "\tIs fastest: " . ($webBenchmark->getResource()->getAttributes()->isFastest() ? 'true' : 'false');
        $text .= $this->getNewLineChar() . "\tIs slowest: " . ($webBenchmark->getResource()->getAttributes()->isSlowest() ? 'true' : 'false');

        $urlMaxLength = strlen($webBenchmark->getResource()->getUrl()) + 3;
        $sizeMaxLength = strlen($webBenchmark->getResource()->getSizeFormatted());
        $timeMaxLength = strlen($webBenchmark->getResource()->getLoadTimeFormatted());

        foreach ($webBenchmark->getCompetitors() as $compareResource) {
            $urlMaxLength = max($urlMaxLength, strlen($compareResource->getUrl()));
            $sizeMaxLength = max($sizeMaxLength, strlen($compareResource->getSizeFormatted()));
            $timeMaxLength = max($timeMaxLength, strlen($compareResource->getLoadTimeFormatted()));
        }

        $strLen = $urlMaxLength + $timeMaxLength + 7;

        $allResources = array_merge($webBenchmark->getCompetitors(), [$webBenchmark->getResource()]);

        usort($allResources, function ($l, $r) {
            return $l->getLoadTime() <=> $r->getLoadTime();
        });

        $text .= $this->getNewLineChar() . str_repeat("_", $strLen);
        $text .= $this->getNewLineChar() . "| " . str_pad("Url", $urlMaxLength, ' ') . " | " . str_pad("Size", $sizeMaxLength, ' ') . " | " . str_pad("Time", $timeMaxLength, ' ') . " |";
        $text .= $this->getNewLineChar() . str_repeat("_", $strLen);
        foreach ($allResources as $compareResource) {
            $text .= $this->getNewLineChar() . $this->generateTableRow($compareResource, $urlMaxLength, $sizeMaxLength, $timeMaxLength);
            $text .= $this->getNewLineChar() . str_repeat("_", $strLen);
        }

        return $text;
    }

    /**
     * generate table row
     *
     * @param WebResource $webResource
     * @param $urlMaxLength
     * @param $sizeMaxLength
     * @param $timeMaxLength
     * @return string
     */
    public function generateTableRow(WebResource $webResource, $urlMaxLength, $sizeMaxLength, $timeMaxLength)
    {
        $url = ($webResource->isMain() ? '*' : '') . $webResource->getUrl() . ($webResource->isMain() ? '*' : '');
        return "| " . str_pad($url, $urlMaxLength, ' ') . " | " . str_pad($webResource->getSizeFormatted(), $sizeMaxLength, ' ') . " | " . str_pad($webResource->getLoadTimeFormatted(), $timeMaxLength, ' ') . " |";
    }

    /**
     * New line char
     *
     * @return string
     */
    public function getNewLineChar()
    {
        return "\r\n";
    }

}
