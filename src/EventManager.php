<?php

namespace Sa\WebBenchmark;

use Sa\WebBenchmark\Contracts\EventListenerInterface;

class EventManager
{
    /**
     * @var array
     */
    private $events;

    /**
     * EventManager constructor.
     */
    public function __construct()
    {
        $this->events = [];
    }

    /**
     * Attach
     *
     * @param string $eventName
     * @param EventListenerInterface $eventListenerName
     */
    public function attach($eventName, EventListenerInterface $eventListenerName)
    {
        if (!isset($this->events[$eventName])) {
            $this->events[$eventName] = [];
        }

        $this->events[$eventName][] = $eventListenerName;
    }

    /**
     * Trigger
     *
     * @param $name
     * @param WebBenchmark $webBenchmark
     */
    public function trigger($name, WebBenchmark $webBenchmark)
    {
        if (!isset($this->events[$name])) {
            return;
        }

        $event = new $name($webBenchmark);

        foreach ($this->events[$name] as $eventListener) {
            $eventListener->handle($event);
        }
    }

}
