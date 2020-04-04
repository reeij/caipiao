<?php

namespace app\implementation;

use app\interfaces\Generator as GeneratorInterface;

class Generator Implements GeneratorInterface
{

    /**
     * @var array
     */
    public $prefixDomain = [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13,
        14, 15, 16, 17, 18, 19, 20, 21, 22, 23,
        24, 25, 26, 27, 28, 29, 30, 31, 32, 33
    ];

    /**
     * @var array
     */
    public $suffixDomain = [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16
    ];

    /**
     * @var array
     */
    public $queue = [];

    /**
     * @var array
     */
    public $queues = [];

    /**
     * @var array
     */
    private $lastTimeNumbers = [];

    /**
     * @return string
     */
    public function get() {

        $prefixDomain = $this->prefixNumbers();
        $suffixDomain = $this->suffixNumbers();

        array_push($prefixDomain, $suffixDomain);

        foreach ($prefixDomain as $key => &$value) {
            $value = sprintf('%02s', $value);
        }

        return $prefixDomain;
    }

    /**
     * @return array
     */
    public function prefixNumbers()
    {
        $this->pieces();

        return $this->queues;
    }

    /**
     * @return mixed
     */
    public function suffixNumbers()
    {
        shuffle($this->suffixDomain);

        $idx = mt_rand(0, 15);

        return $this->suffixDomain[$idx];
    }

    /**
     * @param $numbers
     */
    public function setLastTimeNum($numbers)
    {
        $this->lastTimeNumbers = $numbers;
    }

    /**
     * @return $this
     */
    public function pieces()
    {
        $this->queues = [];

        while (sizeof($this->queues) < 6) {
            if ($range = $this->range()) {
                $this->queues[] = $range;
            }
        }

        sort($this->queues);

        return $this;
    }

    /**
     * @return $this
     */
    protected function range()
    {

        shuffle($this->suffixDomain);
        for ($i = 0, $idx = 0; $i <= bcpow(2, 8); $i++) {
            $idx = (mt_rand(32, 32 * 111111) % 32);
        }

        if (!isset($this->prefixDomain[$idx])) {
            return false;
        }

        if (true === in_array($this->prefixDomain[$idx],
                $this->queues)) {

            return false;
        }

        // 去掉上一期的 7个号码;
        if (true === in_array($this->prefixDomain[$idx],
                $this->lastTimeNumbers)) {

            return false;
        }

        return $this->prefixDomain[$idx];
    }

}
