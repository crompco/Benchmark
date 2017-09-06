# Benchmark Utility

## BenchmarkTarget

```php
// You can construct a target with a closure.
$benchmark = new Crompco\Benchmark\BenchmarkTarget("My Benchmark", function() {
    // Everything in here will be measured.
});

$elapsed = $benchmark->run()->getElapsed();

// Or use the start() and stop() methods
$benchmark = new Crompco\Benchmark\BenchmarkTarget("My benchmark");

$benchmark->start();
// Do some stuff.
$benchmark->stop();

// Make sure to call reset() before each capture.
$benchmark->reset();

$elapsed = $benchmark->getElapsed();
```

By default, execution times are reported in milliseconds. This can configured during construction
or by using the ```$benchmark->setMetric();``` method. See ```Crompco\Benchmark\BenchmarkMetric``` for available metrics.