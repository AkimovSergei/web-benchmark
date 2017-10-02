<?php

namespace Sa\WebBenchmark\Listeners;

use Sa\WebBenchmark\Contracts\EventInterface;
use Sa\WebBenchmark\Contracts\EventListenerInterface;

class NotifyViaEmailEventListener implements EventListenerInterface
{

    /**
     * Handle event
     *
     * @param EventInterface $event
     * @return mixed
     */
    public function handle(EventInterface $event)
    {
        // TODO send email
        dump("NotifyViaEmailEventListener");
    }

}
