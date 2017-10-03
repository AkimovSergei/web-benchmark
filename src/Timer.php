<?php

namespace Sa\WebBenchmark;

/**
 * Class Timer
 *
 * @package Sa\WebBenchmark
 */
class Timer
{

    /**
     * Timers
     *
     * @var array
     */
    protected $timers = [];

    /**
     * Sets the timer with the specified name.
     *
     * @param $name
     */
    public function start($name)
    {
        $this->timers[$name]['start'] = microtime(true);
        $this->timers[$name]['count'] = isset($this->timers[$name]['count']) ? ++$this->timers[$name]['count'] : 1;
    }

    /**
     * Reads the timer current time without stopping it.
     *
     * @param $name
     * @return float
     */
    public function read($name)
    {
        if (isset($this->timers[$name]['start'])) {
            $stop = microtime(true);
            $diff = round(($stop - $this->timers[$name]['start']) * 1000, 2);
            if (isset($this->timers[$name]['time'])) {
                $diff += $this->timers[$name]['time'];
            }
            return $diff;
        }
        return $this->timers[$name]['time'];
    }

    /**
     * Stop timer
     *
     * @param string $name
     * @param bool $clear
     * @return mixed
     */
    public function stop($name, $clear = false)
    {
        if (isset($this->timers[$name]['start'])) {
            $stop = microtime(true);
            $diff = round(($stop - $this->timers[$name]['start']) * 1000, 2);
            if (isset($this->timers[$name]['time'])) {
                $this->timers[$name]['time'] += $diff;
            } else {
                $this->timers[$name]['time'] = $diff;
            }
            unset($this->timers[$name]['start']);
        }

        $timer = $this->timers[$name];

        if ($clear) {
            $this->clear($name);
        }

        return $timer;
    }

    /**
     * Clear timers
     *
     * @param null $name
     */
    public function clear($name = null)
    {
        if (is_null($name) && isset($this->timers[$name])) {
            unset($this->timers[$name]);
        } else {
            $this->timers = [];
        }
    }

}
