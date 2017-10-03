<?php

namespace Sa\WebBenchmark;

use Sa\WebBenchmark\Contracts\Arrayable;
use Sa\WebBenchmark\Contracts\OutputInterface;
use Sa\WebBenchmark\Contracts\ResourceInterface;
use Sa\WebBenchmark\Events\NotFastestEvent;
use Sa\WebBenchmark\Events\TwoTimesSlowestEvent;
use Sa\WebBenchmark\Exceptions\InvalidArgumentException;
use Sa\WebBenchmark\Logger\FileLogger;
use Sa\WebBenchmark\Outputs\JsonOutput;

class WebBenchmark implements Arrayable
{

    /**
     * Resource
     *
     * @var WebResource
     */
    protected $resource;

    /**
     * Resources to compare
     *
     * @var array WebResource
     */
    protected $competitors = [];

    /**
     * Output interface
     *
     * @var null|OutputInterface
     */
    protected $output;

    /**
     * Event manager
     *
     * @var EventManager
     */
    protected $eventManager;

    /**
     * WebBenchmark constructor.
     * @param $resource
     * @param array $competitors
     * @param OutputInterface|null $output
     * @throws InvalidArgumentException
     */
    public function __construct($resource, array $competitors, OutputInterface $output = null)
    {

        if (empty($competitors)) {
            throw new InvalidArgumentException("Empty resources to compare");
        }

        $this->resource = $this->handleResource($resource);
        $this->resource->setIsMain(true);

        foreach ($competitors as $competitor) {
            $this->competitors[] = $this->handleResource($competitor);
        }

        if (is_null($output)) {
            $output = new JsonOutput();
        }

        $this->output = $output;
    }

    /**
     * Get event manager
     *
     * @return EventManager
     */
    public function getEventManager(): EventManager
    {
        return $this->eventManager;
    }

    /**
     * Set event manager
     *
     * @param EventManager $eventManager
     */
    public function setEventManager(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @return WebResource
     */
    public function getResource(): WebResource
    {
        return $this->resource;
    }

    /**
     * @return array
     */
    public function getCompetitors(): array
    {
        return $this->competitors;
    }

    /**
     * Get output
     *
     * @return OutputInterface
     */
    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    /**
     * Set output
     *
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * Handle resource
     *
     * @param $resource
     * @return WebResource
     * @throws InvalidArgumentException
     */
    public function handleResource($resource)
    {
        if (!($resource instanceof ResourceInterface || is_string($resource))) {
            throw new InvalidArgumentException("{$resource} must be an object of type ResourceInterface or a string");
        }

        if (is_string($resource)) {
            $resource = new WebResource($resource);
        }

        return $resource;
    }

    /**
     *
     * @throws Exceptions\FailedToLoadException
     * @throws InvalidArgumentException
     */
    public function run()
    {

        $startTime = microtime(true);

        FileLogger::info("Start benchmark for {$this->getResource()->getUrl()}");

        $this->resource->load();

        foreach ($this->competitors as $competitor) {
            $competitor->load();
        }

        FileLogger::info("All resources loaded");

        $this->processLoadingResults();

        FileLogger::info("Benchmark done in " . round((microtime(true) - $startTime) * 1000, 2) . "ms");
    }

    /**
     * Process loaded data
     * @throws InvalidArgumentException
     */
    protected function processLoadingResults()
    {

        FileLogger::info("Start processing results");

        /*
         * Sort competitors by loading time
         */
        usort($this->competitors, function (WebResource $l, WebResource $r) {
            return $l->getLoadTime() <=> $r->getLoadTime();
        });


        $slowestCompetitor = $fasterCompetitor = $this->competitors[0];

        /*
         * Check is resource fastest
         */
        if ($this->resource->getLoadTime() < $fasterCompetitor->getLoadTime()) {
            $this->resource->getAttributes()->setIsFastest(true);
            $this->resource->getAttributes()->setIsSlowest(false);
        } else {
            $fasterCompetitor->getAttributes()->setIsFastest(true);
            $fasterCompetitor->getAttributes()->setIsSlowest(false);
        }

        if (count($this->competitors) > 1) {
            $slowestCompetitor = array_reverse($this->competitors)[0];
        }

        /*
         * Check is resource slowest
         */
        if ($this->resource->getLoadTime() > $slowestCompetitor->getLoadTime()) {
            $this->resource->getAttributes()->setIsFastest(false);
            $this->resource->getAttributes()->setIsSlowest(true);
        }

        if ($this->eventManager) {

            /*
             * Trigger not fastest event
             */
            if (!$this->resource->getAttributes()->isFastest()) {
                $this->eventManager->trigger(NotFastestEvent::class, $this);
            }

            /*
             * Trigger slow event
             */
            if ($this->resource->getLoadTime() > 2 * $fasterCompetitor->getLoadTime()) {
                $this->eventManager->trigger(TwoTimesSlowestEvent::class, $this);
            }

        }

        FileLogger::info("Results processed");

    }

    /**
     * Object to array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'resource' => $this->resource->toArray(),
            'competitors' => array_map(function (WebResource $resource) {
                return $resource->toArray();
            }, $this->competitors),
        ];
    }

    /**
     * Output results
     *
     * @return mixed
     */
    public function output()
    {
        return $this->output->output($this);
    }

}
