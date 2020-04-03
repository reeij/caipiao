<?php

namespace app\implementation;

use app\interfaces\Generator as GeneratorInterface;

class Generator Implements GeneratorInterface
{

    /**
     * @var array
     */
    public $queue = [];

    /**
     * @var array
     */
    public $groups = [];

    /**
     * @return $this
     */
    public function piece()
    {
        for ($i = 1; $i <= 7; $i++) {
            $serialNumber = mt_rand(self::MIN_SEED_NUMBER,
                self::MAX_SEED_NUMBER);

            $this->queue[]
                = sprintf('%02d', $serialNumber);
        }

        sort($this->queue, SORT_NUMERIC);

        return $this;
    }

    /**
     * @return $this
     */
    public function groups()
    {
        for ($i = 1; $i <= 7; $i++) {
            $this->groups[$i] = $this->piece();
        }

        return $this;
    }
}
