# Web benchmark

## Installation

```composer require sa/web-benchmark```

## Configuration

Configuration file: __Configs/app.php__

```php

<?php

return [
    'mail' => [
        'driver' => 'smtp',
        'smtp' => [
            // SMTP configs
            'host' => 'smtp.mailtrap.io',
            'port' => 2525,
            'username' => '',
            'password' => '',
        ],
        'from' => [
            // Sender 
            'email' => 'report@web.benchmark',
            'name' => 'Web Benchmark',
        ],
        'to' => [
            // Send reports to
            'email@example.com'
        ],
    ],
    'sms' => [
        'api_key' => 'API_KEY_EXAMPLE',
        'from' => 'Web Benchmark',
    ]
];


```


## Usage

**Creating instance**

```php

use Sa\WebBenchmark\WebBenchmark;
use Sa\WebBenchmark\Exceptions\InvalidArgumentException;

$url = "https://www.google.com";

$competitors = [
    "https://laravel.com",
    "https://symfony.com",
    "https://github.com",
];

try {
    $webBenchmark = new WebBenchmark($url, $competitors);
} catch (InvalidArgumentException $e) {}


```

**Create event manager**

```php

use Sa\WebBenchmark\EventManager;
use Sa\WebBenchmark\Listeners\NotifyViaEmailEventListener;
use Sa\WebBenchmark\Listeners\NotifyViaSmsEventListener;


$eventManager = new EventManager();
$eventManager->attach(NotFastestEvent::class, new NotifyViaEmailEventListener(['email@example.com']));
$eventManager->attach(TwoTimesSlowestEvent::class, new NotifyViaSmsEventListener(['123456789']));

$webBenchmark->setEventManager($eventManager);


```



__Available event managers:__

* ```NotifyViaEmailEventListener``` - Send email, if benchmarked website is loaded slower than at least one of the competitors
* ```NotifyViaSmsEventListener``` - Send SMS, if benchmarked website is loaded twice as slow as at least one of the competitors




**Run benchmark**

```php

try {
    $webBenchmark->run();
} catch (\Exception $e) {
}


```

**Output data**

```php

use Sa\WebBenchmark\Outputs\JsonOutput;

$webBenchmark->setOutput(new JsonOutput);

$json = $webBenchmark->output();

```

__Available output:__

* ```JsonOutput```
* ```HtmlOutput```
* ```ConsoleOutput```
* ```FileOutput```
* ```PlainTextOutput```



