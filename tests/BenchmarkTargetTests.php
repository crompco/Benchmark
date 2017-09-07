<?php

use Crompco\Benchmark\BenchmarkMetric;
use Crompco\Benchmark\BenchmarkTarget;
use PHPUnit\Framework\TestCase;

class BenchmarkTargetTests extends TestCase
{
    public function testThatItSetsTheName()
    {
        $benchmark = new BenchmarkTarget("my benchmark", function () {

        });

        $this->assertEquals("my benchmark", $benchmark->getName());

        $benchmark->setName("A different benchmark name");

        $this->assertEquals("A different benchmark name", $benchmark->getName());
    }

    public function testThatItCanResetsItsStateAfterEachRun() {
        $benchmark = new BenchmarkTarget("benchmark", function () {
            sleep(1);
        }, BenchmarkMetric::SECONDS);

        $benchmark->run();

        $this->assertEquals(1, (int)$benchmark->getElapsed());

        $benchmark->reset();

        $this->assertEquals(0.0, $benchmark->getElapsed());
    }

    public function testThatItSetsTheMetric()
    {
        // Default test
        $benchmark = new BenchmarkTarget("my benchmark", function () {
        });
        $this->assertEquals(BenchmarkMetric::MILLISECONDS, $benchmark->getMetric());

        // Specific metric.
        $another_benchmark = new BenchmarkTarget("Another benchmark", function () {
        }, BenchmarkMetric::SECONDS);
        $this->assertEquals(BenchmarkMetric::SECONDS, $another_benchmark->getMetric());

        $another_benchmark->setMetric(BenchmarkMetric::MILLISECONDS);
        $this->assertEquals(BenchmarkMetric::MILLISECONDS, $another_benchmark->getMetric());
    }

    public function testThatItFailsWhenAnInvalidMetricIsSet()
    {
        $this->expectException(Crompco\Benchmark\BenchmarkException::class);
        $this->expectExceptionMessage('Invalid metric');

        $benchmark = new BenchmarkTarget("my benchmark", null, "not an actual metric");
    }

    public function testThatIfFailsWhenItsNotGivenACallback() {
        $this->expectException(Crompco\Benchmark\BenchmarkException::class);
        $this->expectExceptionMessage('Invalid callback');

        $benchmark = new BenchmarkTarget("benchmark");
        $benchmark->run();
    }

    public function testThatItCanMeasureProcessTimeInMillisecondsUsingRun()
    {
        $benchmark = new BenchmarkTarget("benchmark", function () {
            sleep(3.0);
        });

        $benchmark->run();

        $this->assertEquals(3000, (int)$benchmark->getElapsed());
    }

    public function testThatItCanMeasureProcessTimeInSecondsUsingRun()
    {
        $benchmark = new BenchmarkTarget("benchmark", function () {
            sleep(5.0);
        }, BenchmarkMetric::SECONDS);

        $benchmark->run();

        $this->assertEquals(5, (int)$benchmark->getElapsed());
    }

    public function testThatItCanMeasureProcessTimeInMillisecondsUsingStartStop()
    {
        $benchmark = new BenchmarkTarget("benchmark");

        $benchmark->start();
        sleep(3);
        $benchmark->stop();

        $this->assertEquals(3000, (int)$benchmark->getElapsed());

        $benchmark->start();
        sleep(4);
        $benchmark->stop();

        $this->assertEquals(4000, (int)$benchmark->getElapsed());
    }

    public function testThatItCanMeasureProcessTimeInSecondsUsingStartStop()
    {
        $benchmark = new BenchmarkTarget("benchmark", null, BenchmarkMetric::SECONDS);

        $benchmark->start();
        sleep(6);
        $benchmark->stop();

        $this->assertEquals(6, (int)$benchmark->getElapsed());

        $benchmark->start();
        sleep(1);
        $benchmark->stop();

        $this->assertEquals(1, (int)$benchmark->getElapsed());
    }
}
