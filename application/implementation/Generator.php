<?php

namespace app\implementation;

use think\Db;
use think\exception\DbException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
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
    public $dPrefix = [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13,
        14, 15, 16, 17, 18, 19, 20, 21, 22, 23,
        24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35
    ];

    /**
     * @var array
     */
    public $dSuffix = [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12
    ];

    /**
     * @var array
     */
    public $sPrefix = [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13,
        14, 15, 16, 17, 18, 19, 20, 21, 22, 23,
        24, 25, 26, 27, 28, 29, 30, 31, 32, 33
    ];

    /**
     * @var array
     */
    public $sSuffix = [
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
     * @var bool
     */
    private $type = true;

    /**
     * @return array
     */
    public function sixPlusOne() {
        $this->setLastTimeNum('ssq');
        $prefixDomain = $this->prefixNumbers();
        $suffixDomain = $this->suffixNumbers();

        $mergeDomain = array_merge($prefixDomain, $suffixDomain);

        foreach ($mergeDomain as $key => &$value) {
            $value = sprintf('%02s', $value);
        }

        return $mergeDomain;
    }

    public function fivePlusTwo() {
        $this->type = false;

        $this->setLastTimeNum('dlt');
        $prefixDomain = $this->prefixNumbers();
        $suffixDomain = $this->suffixNumbers(2);

        $mergeDomain = array_merge($prefixDomain, $suffixDomain);

        foreach ($mergeDomain as $key => &$value) {
            $value = sprintf('%02s', $value);
        }

        return $mergeDomain;
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
     * @param int $num
     *
     * @return mixed
     */
    public function suffixNumbers($num = 1)
    {

        $suffix = ($this->type === true
            ?$this->sSuffix :$this->dSuffix);

        $bag = [];
        for ($i = 0; $i < $num; $i++) {
            shuffle($suffix);

            $bag[$i] = array_pop($suffix);
        }

        return $bag;
    }

    /**
     * @param string $table
     *
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * @return Generator
     */
    public function setLastTimeNum($table)
    {
        // 查询开奖历史
        $history = Db::name($table .'_history')
            ->order('qi desc')->find();

        $this->lastTimeNumbers = [
            $history['num1'],
            $history['num2'],
            $history['num3'],
            $history['num4'],
            $history['num5'],
            $history['num6'],
            $history['num7'],
        ];

        return $this;
    }

    /**
     * @return $this
     */
    public function pieces()
    {
        $this->queues = [];

        $total = ($this->type === true
            ?6 :5);

        while (sizeof($this->queues) < $total) {
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

        $prefix = ($this->type === true ?
            $this->sPrefix :$this->dPrefix);


        shuffle($prefix);

        $number = array_pop($prefix);

        /**
        for ($i = 0, $idx = 0; $i <= bcpow(2, 8); $i++) {
            $idx = (mt_rand(32, 32 * 111111) % 32);
        }*/

        // 重复？
        if (true === in_array($number,
                $this->queues)) {

            return false;
        }

        // 去掉上一期的 7个号码;
        if (true === in_array($number,
                $this->lastTimeNumbers)) {

            return false;
        }

        return $number;
    }

}
