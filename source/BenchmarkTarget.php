<?php

namespace Crompco\Benchmark;

/**
 * Class BenchmarkTarget - Represents a single benchmark target.
 *
 * @package Crompco\Benchmark
 */
class BenchmarkTarget
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $metric = BenchmarkMetric::MILLISECONDS;

    /**
     * @var callable|null
     */
    private $callback = null;

    /**
     * @var float
     */
    private $elapsed = 0.0;

    /**
     * @var float
     */
    public $start = 0.0;

    /**
     * @var float
     */
    public $end = 0.0;

    /**
     * Allowed metrics, at the moment.
     * @var array
     */
    static private $allowed_metrics = [
        BenchmarkMetric::MILLISECONDS,
        BenchmarkMetric::SECONDS
    ];

    /**
     * BenchmarkTarget constructor.
     *
     * @param string $name
     * @param callable|null $callback
     * @param string $metric
     */
    public function __construct(
        string $name,
        callable $callback = null,
        $metric = BenchmarkMetric::MILLISECONDS
    ) {
        $this->setName($name);
        $this->setMetric($metric);

        $this->callback = $callback;
    }

    /**
     * Start the benchmark.
     */
    public function start()
    {
        $this->start = microtime(true);
    }

    /**
     * Stop the benchmark.
     */
    public function stop()
    {
        $this->end = microtime(true);

        $this->calculateElapsed();
    }

    /**
     * Execute the benchmark run.
     */
    public function run()
    {
        $this->reset();

        if (!$this->callback) {
            // TODO: Throw an exception here.
            return $this;
        }

        $this->start();

        ($this->callback)();

        $this->stop();

        return $this;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $metric
     * @return $this
     * @throws BenchmarkException
     */
    public function setMetric($metric)
    {
        if (!in_array($metric, self::$allowed_metrics)) {
            throw new BenchmarkException("Invalid metric of '{$metric}'");
        }

        $this->metric = $metric;

        return $this;
    }

    /**
     * Get the name of the benchmark target.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getMetric(): string
    {
        return $this->metric;
    }

    /**
     * Return the elapsed time the callback took to execute, adjusted according
     * to the supplied time metric.
     *
     * @return float
     */
    public function getElapsed(): float
    {
        $time = $this->elapsed;

        // Adjust the elapsed time according to the
        // provided time metric, if applicable.
        switch ($this->metric) {
            case BenchmarkMetric::MILLISECONDS:
                $time *= 1000.00;
                break;
        }

        return (float)round($time, 2);
    }

    /**
     * Reset the internal state (times) of the target.
     */
    public function reset() {
        $this->start = $this->end = $this->elapsed = 0.0;
    }

    private function calculateElapsed()
    {
        $this->elapsed = -$this->start + $this->end;
    }
}

