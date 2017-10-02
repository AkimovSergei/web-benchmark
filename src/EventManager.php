<?php

namespace Sa\WebBenchmark;

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
     * @param string $eventListenerName
     */
    public function attach($eventName, $eventListenerName)
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

        dump($event);

        foreach ($this->events[$name] as $eventListener) {

            dd($eventListener, new $eventListener);

            (new $eventListener)->handle($event);
        }
    }

}
