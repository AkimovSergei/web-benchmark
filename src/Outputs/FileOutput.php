<?php

namespace Sa\WebBenchmark\Outputs;

use Sa\WebBenchmark\WebBenchmark;

class FileOutput extends PlainTextOutput
{
    /**
     * Output path
     *
     * @var string
     */
    protected $path;

    /**
     * FileOutput constructor.
     * @param $path
     * @throws \Exception
     */
    public function __construct(string $path = null)
    {
        $this->path = $path;

        if (!empty($path) && file_exists($this->path) && !is_writable($this->path)) {
            throw new \Exception("File is not writable");
        }
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * Output data
     *
     * @param WebBenchmark $webBenchmark
     * @return mixed
     */
    public function output(WebBenchmark $webBenchmark)
    {
        $text = parent::output($webBenchmark);

        if (empty($this->path)) {
            header('Content-type: text/plain');
            header('Content-Disposition: attachment; filename="benchmark.txt"');

            echo $text;
            die;
        } else {
            file_put_contents($this->path, $text);
        }

        return $text;
    }

}
