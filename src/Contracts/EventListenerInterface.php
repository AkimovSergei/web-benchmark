<?php

namespace Sa\WebBenchmark\Contracts;

interface EventListenerInterface
{

    /**
     * Handle event
     *
     * @param EventInterface $event
     * @return mixed
     */
    public function handle(EventInterface $event);

}
