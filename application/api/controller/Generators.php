<?php

namespace app\api\controller;

use think\App;
use think\Request;
use think\Controller;
use \app\implementation\Generator as GeneratorImplement;

class Generators extends Controller
{

    /**
     * @var Generator
     */
    private $generator;

    /**
     * Generators constructor.
     * @param App|null $app
     * @param GeneratorImplement $generator
     * @param Request $request
     */
    public function __construct(App $app = null,
                                Request $request,
                                GeneratorImplement $generator)
    {
        parent::__construct($app);

        $this->request   = $request;
        $this->generator = $generator;
    }

    /**
     * 生成随机号码
     *
     * @return array
     */
    public function sixPlusOne() {
        $this->assign([
            'items' => $this->generator->sixPlusOne()
        ]);

        return view('six-plus-one');
    }

    public function fivePlusTwo() {
        $this->assign([
            'items' => $this->generator->fivePlusTwo()
        ]);

        return view('five-plus-two');
    }

}
