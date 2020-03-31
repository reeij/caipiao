<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Index extends Controller
{

    function shuangseqiu(){
        $date = date('Y-m-d');
        $refresh = input('refresh', 0, 'intval');
        $model = Db::name('shuangseqiu');
        if(!$refresh && $res = $model->where('date', $date)->find()){
            $nums = [
                $res['num1'],
                $res['num2'],
                $res['num3'],
                $res['num4'],
                $res['num5'],
                $res['num6'],
                $res['num7'],
            ];
        }else{
            $red_max = 33;
            $blue_max = 16;
            $red = [];
            $blue = [];
            for($i=1;$i<=6;$i++){
                $red_flag = true;
                while ($red_flag) {
                    $num = rand(1, $red_max);
                    if(!in_array($num, $red)){
                        $red[] = $num;
                        $red_flag = false;
                    }
                }
            }
            sort($red);

            $blue_flag = true;
            while ($blue_flag) {
                $num = rand(1, $blue_max);
                if(!in_array($num, $blue)){
                    $blue[] = $num;
                    $blue_flag = false;
                }
            }
            $nums = [
                $red[0],
                $red[1],
                $red[2],
                $red[3],
                $red[4],
                $red[5],
                $blue[0],
            ];

            if($model->where('date', $date)->count() > 0){
                $model->where('date', $date)->update([
                    'date' => $date,
                    'num1' => $red[0],
                    'num2' => $red[1],
                    'num3' => $red[2],
                    'num4' => $red[3],
                    'num5' => $red[4],
                    'num6' => $red[5],
                    'num7' => $blue[0],
                ]);
            }else{
                $model->insert([
                    'date' => $date,
                    'num1' => $red[0],
                    'num2' => $red[1],
                    'num3' => $red[2],
                    'num4' => $red[3],
                    'num5' => $red[4],
                    'num6' => $red[5],
                    'num7' => $blue[0],
                ]);
            }
            if($refresh){
                return $nums;
            }
        }

        $this->assign('nums', $nums);
        return view();
    }

    function daletou(){
        $date = date('Y-m-d');
        $refresh = input('refresh', 0, 'intval');
        $model = Db::name('daletou');
        if(!$refresh && $res = $model->where('date', $date)->find()){
            $nums = [
                $res['num1'],
                $res['num2'],
                $res['num3'],
                $res['num4'],
                $res['num5'],
                $res['num6'],
                $res['num7'],
            ];
        }else{
            $red_max = 35;
            $blue_max = 12;
            $red = [];
            $blue = [];
            for($i=1;$i<=5;$i++){
                $red_flag = true;
                while ($red_flag) {
                    $num = rand(1, $red_max);
                    if(!in_array($num, $red)){
                        $red[] = $num;
                        $red_flag = false;
                    }
                }
            }
            sort($red);

            for($i=1;$i<=2;$i++){
            	$blue_flag = true;
	            while ($blue_flag) {
	                $num = rand(1, $blue_max);
	                if(!in_array($num, $blue)){
	                    $blue[] = $num;
	                    $blue_flag = false;
	                }
	            }
            }
            sort($blue);

            $nums = [
                $red[0],
                $red[1],
                $red[2],
                $red[3],
                $red[4],
                $blue[0],
                $blue[1],
            ];

            if($model->where('date', $date)->count() > 0){
                $model->where('date', $date)->update([
                    'date' => $date,
                    'num1' => $red[0],
                    'num2' => $red[1],
                    'num3' => $red[2],
                    'num4' => $red[3],
                    'num5' => $red[4],
                    'num6' => $blue[0],
                    'num7' => $blue[1],
                ]);
            }else{
                $model->insert([
                    'date' => $date,
                    'num1' => $red[0],
                    'num2' => $red[1],
                    'num3' => $red[2],
                    'num4' => $red[3],
                    'num5' => $red[4],
                    'num6' => $blue[0],
                    'num7' => $blue[1],
                ]);
            }
            if($refresh){
                return $nums;
            }
        }

        $this->assign('nums', $nums);
        return view();
    }
}
