<?php

use Sa\WebBenchmark\EventManager;
use Sa\WebBenchmark\Events\NotFastestEvent;
use Sa\WebBenchmark\Events\TwoTimesSlowestEvent;
use Sa\WebBenchmark\Listeners\NotifyViaEmailEventListener;
use Sa\WebBenchmark\Listeners\NotifyViaSmsEventListener;
use Sa\WebBenchmark\Logger\FileLogger;
use Sa\WebBenchmark\Outputs\HtmlOutput;
use Sa\WebBenchmark\WebBenchmark;

require_once "../vendor/autoload.php";

$eventManager = new EventManager();
$eventManager->attach(NotFastestEvent::class, new NotifyViaEmailEventListener(['akimov.sergii@gmail.com']));
$eventManager->attach(TwoTimesSlowestEvent::class, new NotifyViaSmsEventListener(['123456789']));

try {
    $competitors = [
        "https://www.facebook.com",
        "https://twitter.com",
        "https://laravel.com",
        "https://symfony.com",
        "https://github.com",
        "https://xsolve.software",
    ];

    $webBenchmark = new WebBenchmark("https://www.google.com", $competitors, new HtmlOutput);

    $webBenchmark->setEventManager($eventManager);

    $webBenchmark->run();

    echo $webBenchmark->output();

} catch (\Exception $e) {
    FileLogger::error($e);
    echo "Error: " . $e->getMessage() . PHP_EOL;
    die;
}