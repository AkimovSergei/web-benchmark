<?php

namespace Sa\WebBenchmark\Listeners;

require realpath(dirname(__FILE__)) . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Sa\WebBenchmark\Contracts\EventInterface;
use Sa\WebBenchmark\Contracts\EventListenerInterface;
use Sa\WebBenchmark\Exceptions\ConfigsNotFoundException;
use Sa\WebBenchmark\Outputs\HtmlOutput;
use Sa\WebBenchmark\WebBenchmark;

class NotifyViaEmailEventListener implements EventListenerInterface
{

    /**
     * Benchmark results
     *
     * @var WebBenchmark
     */
    protected $webBenchmark;

    /**
     * Configs
     *
     * @var array
     */
    protected $configs;

    /**
     * Params
     *
     * @var array
     */
    protected $to;

    /**
     * NotifyViaEmailEventListener constructor.
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
     * @throws ConfigsNotFoundException
     */
    public function handle(EventInterface $event)
    {
        $this->webBenchmark = $event->getWebBenchmark();

        if (!empty($this->to)) {
            $this->readConfigs();

            $this->send();
        }
    }

    /**
     * Read config file
     *
     * @throws ConfigsNotFoundException
     */
    public function readConfigs()
    {
        $configPath = realpath(dirname(__FILE__)) . '/../Configs/app.php';

        if (!file_exists($configPath)) {
            throw new ConfigsNotFoundException("Configuration file not found");
        }

        $configs = require "{$configPath}";

        if (!isset($configs['mail']['smtp'])) {
            throw new ConfigsNotFoundException("Smtp configuration not found");
        }

        $this->configs = $configs['mail'];
    }

    /**
     * Send email
     */
    public function send()
    {

        $mail = new PHPMailer(true);

        try {

            $this->webBenchmark->setOutput(new HtmlOutput);

            $html = $this->webBenchmark->output();

            //Server settings
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = $this->configs['smtp']['host'];
            $mail->Username = $this->configs['smtp']['username'];
            $mail->Password = $this->configs['smtp']['password'];
            $mail->Port = $this->configs['smtp']['port'];

            //Recipients
            $mail->setFrom($this->configs['from']['email'], $this->configs['from']['name']);

            foreach ($this->to as $to) {
                $mail->addAddress($to);
            }

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Web benchmark report';
            $mail->Body = $html;
            $mail->AltBody = strip_tags($html);

            $mail->send();
        } catch (\Exception $exception) {

        }

    }

}
