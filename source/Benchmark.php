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
     * @param BenchmarkTarget $target
     * @return $this
     */
    public function addTarget(BenchmarkTarget $target) {

        $this->targets[$target->getName()] = $target;

        return $this;
    }

    public function removeTarget(string $name) {
        if (!$this->targetExists($name)) {
            throw new BenchmarkException("Benchmark Target '{$name}' not found.");
        }

        unset($this->targets[$name]);

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

    public function target(string $name): BenchmarkTarget
    {
        if (!$this->targetExists($name)) {
            throw new BenchmarkException("Benchmark target '{$name}' not found.");
        }

        return $this->targets[$name];
    }

    private function targetExists(string $name) : bool
    {
        return array_key_exists($name, $this->targets);
    }
}

