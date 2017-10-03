<?php

namespace Sa\WebBenchmark\Listeners;

use Sa\WebBenchmark\Contracts\EventInterface;
use Sa\WebBenchmark\Contracts\EventListenerInterface;

class NotifyViaSmsEventListener implements EventListenerInterface
{

    /**
     * @var array
     */
    private $to;

    /**
     * NotifyViaSmsEventListener constructor.
     *
     * @param array $to
     */
    public function __construct(array $to = [])
    {
        $this->to = $to;
    }

    /**
     * Handle event
     *
     * @param EventInterface $event
     * @return mixed
     */
    public function handle(EventInterface $event)
    {
        // TODO Send SMS
        dump("NotifyViaSmsEventListener");
    }

}
