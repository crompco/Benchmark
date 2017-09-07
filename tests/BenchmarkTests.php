<?php

use PHPUnit\Framework\TestCase;

class BenchmarkTests extends TestCase {

    public function testThatItCanContainBenchmarkTargets() {
        $target = new \Crompco\Benchmark\BenchmarkTarget("1");
        $target2 = new \Crompco\Benchmark\BenchmarkTarget("2");

        $benchmark = new \Crompco\Benchmark\Benchmark;

        $benchmark->addTarget($target)->addTarget($target2);

        $this->assertEquals("1", $benchmark->target("1")->getName());
        $this->assertEquals("2", $benchmark->target("2")->getName());

        $benchmark->removeTarget("1")->removeTarget("2");

        $this->expectException(\Crompco\Benchmark\BenchmarkException::class);
        $this->expectExceptionMessage("Benchmark Target '1' not found");

        $benchmark->removeTarget("1");

        $this->expectException(\Crompco\Benchmark\BenchmarkException::class);
        $this->expectExceptionMessage("Benchmark Target '2' not found");

        $benchmark->removeTarget("2");
    }
}