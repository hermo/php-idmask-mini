<?php

use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Warmup;

require("main.php");

/**
 * @BeforeMethods({"init"})
 */
class MaskUnmaskBenchmark
{
    private $key;
    private $checkValue;
    private $valueToMask;
    private $base64url;

    public function init()
    {
        $this->key = "7086e571d8dfb475f5c13afc0fceb1fe";
        $this->checkValue = 0xbaadf00d;
        $this->valueToMask = 1234567901;
        $this->base64url = "_tUK-o3a4N713zdVj60yEQ";

        mt_srand(1234);
    }

    /**
     * @Revs(10000)
     * @Iterations(5)
     * @Warmup(100000)
     * @RetryThreshold(2.0)
     */
    public function benchMask()
    {
        mask($this->key, $this->checkValue, $this->valueToMask);
    }

    /**
     * @Revs(10000)
     * @Iterations(5)
     * @Warmup(100000)
     * @RetryThreshold(2.0)
     */
    public function benchUnmask()
    {
        unmask($this->key, $this->checkValue, $this->base64url);
    }

    /**
     * @Revs(50000)
     * @Iterations(5)
     * @Warmup(50000)
     * @RetryThreshold(2.0)
     */
    public function benchMaskAndUnmask()
    {
        $id = mt_rand();
        $masked = mask($this->key, $this->checkValue, $id);
        $unmasked = unmask($this->key, $this->checkValue, $masked);
        assert($id === $unmasked);
    }

    /**
     * @Revs(10000)
     * @Iterations(5)
     * @Warmup(100000)
     * @RetryThreshold(2.0)
     */
    public function benchHashing()
    {
        hash_hmac('sha256', $this->valueToMask, $this->key);
    }

}

