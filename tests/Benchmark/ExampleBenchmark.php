<?php


namespace Tests\Benchmark;


use Komma\KMS\QualityAssurance\BaseBenchMark;
use App\Shop\Products\Product\Transfer\ProductColumnMapsTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class ExampleBenchmark extends BaseBenchMark
{
    use WithFaker,
        DatabaseTransactions,
        ProductColumnMapsTrait;

    /*
    |--------------------------------------------------------------------------
    | Setup and teardown
    |--------------------------------------------------------------------------
    | We use setup and teardown methods to run respectively before and after
    | each benchmark. The time they take is not taken into account in the benchmark.
    |
    */
    /**
     * Setup the test environment before each benchmark
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }


    /*
    |--------------------------------------------------------------------------
    | Benchmark subjects
    |--------------------------------------------------------------------------
    | The next methods are benchmark subjects. They test the performance of
    | a part of the application. Various measures will be done while they run.
    | The results of these measures are visible in console, or if configured in
    | XML and HTML files for example. Their names must start with the subject
    | pattern, defined in phpbench_config.json. Usually this means they must start with
    | the word "bench"
    |
    */

    /**
     * Benchmark something
     *
     * @Revs({1, 10, 100})
     * @Iterations(1)
     * @Groups({"example"})
     * @OutputTimeUnit("seconds")
     * @throws \Exception
     */
    public function benchExample()
    {

    }

    /*
    |--------------------------------------------------------------------------
    | Helper methods
    |--------------------------------------------------------------------------
    | The next methods provide useful services to other methods. They can,
    | for example provide the data for a benchmark subject.
    |
    */
}