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
    public function run() {
        $this->assign([
            'items' => $this->generator->get()
        ]);

        return view('run');
    }

}
