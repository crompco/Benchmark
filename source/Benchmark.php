<?php

namespace Crompco\Benchmark;

class Benchmark
{
    /**
     * Array of BenchmarkTargets.
     *
     * @var array
     */
    private $targets = [];

    /**
     * @param string $name
     * @param string $metric
     * @param callable|null $callback
     * @return $this
     * @internal param string $key
     */
    public function addTarget(
        string $name,
        $metric = BenchmarkMetric::MILLISECONDS,
        callable $callback = null
    ) {
        $this->targets[$name] = new BenchmarkTarget($name, $metric, $callback);

        return $this;
    }

    /**
     * Execute the benchmark.
     *
     * @return $this
     */
    public function run()
    {
        foreach ($this->targets as $target) {
            $target->run();
        }

        return $this;
    }

    /**
     * Start a target's timer.
     *
     * @param string $target_key
     * @throws BenchmarkException
     */
    public function startTarget(string $target_key)
    {
        $this->target($target_key)->start();
    }

    /**
     * Stop a target's timer.
     *
     * @param string $target_key
     * @throws BenchmarkException
     */
    public function stopTarget(string $target_key)
    {
        $this->target($target_key)->stop();
    }

    /**
     * Get the results for the benchmark run().
     *
     * @return array
     */
    public function getResults(): array
    {
        $results = [];

        foreach ($this->targets as $target) {
            $results[$target->getName()] = $target->getElapsed() . $target->getMetric();
        }

        return $results;
    }

    private function target(string $target_key): BenchmarkTarget
    {
        if (!array_key_exists($target_key, $this->targets)) {
            throw new BenchmarkException("Benchmark target '{$target_key}' not found.");
        }

        return $this->targets[$target_key];
    }
}

