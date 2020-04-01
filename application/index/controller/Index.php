<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use QL\QueryList;

class Index extends Controller
{
	function shuangseqiu(){
		return $this->tuijian($table = 'shuangseqiu', $rednum = 5, $bluenum = 2, $red_max = 33, $blue_max = 16);
	}

	function daletou(){
		return $this->tuijian($table = 'daletou', $rednum = 6, $bluenum = 1, $red_max = 35, $blue_max = 12);
	}

	/*
		$table 		数据表
		$rednum		红球生成个数
		$bluenum	篮球生成个数
		$red_max	红球最大号码
		$blue_max	篮球最大号码
	*/
	protected function tuijian($table, $rednum, $bluenum, $red_max, $blue_max){
		// 当前用户
		$name = input('name');
		if(!$name){
			return view('prompt');
		}

		$date = date('Y-m-d');
		// 是否重新生成
		$refresh = input('refresh', 0, 'intval');
		// 实例化数据库操作对象
		$model = Db::name($table);

		// 如果当天生成过并且不是刷新
		$cur_id = 0;
		if(!$refresh && $res = $model->where('date', $date)->order('id desc')->find()){
			$cur_id = $res['id'];
			$nums = [$res['num1'], $res['num2'], $res['num3'], $res['num4'], $res['num5'], $res['num6'], $res['num7']];
		}else{
			// 当天没有生成过或者刷新将会走进这里

			// 生成
			$red = [];
			$blue = [];
			// 生成红球
			for($i=1;$i<=$rednum;$i++){
				// 声明是否跳出while循环
				$red_flag = true;
				while ($red_flag) {
					// 随机挑选号码
					$num = rand(1, $red_max);
					// 如果不在数组中才添加
					if(!in_array($num, $red)){
						$red[] = $num;
						// 跳出while循环
						$red_flag = false;
					}
				}
			}
			// 从小到大排序
			sort($red);

			// 生成蓝球
			for($i=1;$i<=$bluenum;$i++){
				// 声明是否跳出while循环
				$blue_flag = true;
				while ($blue_flag) {
					// 随机挑选号码
					$num = rand(1, $blue_max);
					// 如果不在数组中才添加
					if(!in_array($num, $blue)){
						$blue[] = $num;
						// 跳出while循环
						$blue_flag = false;
					}
				}
			}
			// 从小到大排序
			sort($blue);

			$ball = array_merge($red, $blue);

			$nums = [$ball[0], $ball[1], $ball[2], $ball[3], $ball[4], $ball[5], $ball[6]];

			$model->insert(['date'=>$date,'num1'=>$ball[0],'num2'=>$ball[1],'num3'=>$ball[2],'num4'=>$ball[3],'num5'=>$ball[4],'num6'=>$ball[5],'num7'=>$ball[6],'name'=>$name]);
			if($refresh){
				return $nums;
			}
		}

		// 查询生成记录
		$live_data = $model->order('id desc')->limit(10)->where('id', '<>', $cur_id)->select();
		foreach($live_data as $k => $v){
			$live_data[$k]['date'] = date('m-d', strtotime($v['date']));
		}

		// 查询开奖历史
		$history = Db::name($table.'_history')->order('qi desc')->limit(10)->select();
		foreach($history as $k => $v){
			$history[$k]['date'] = date('m-d', strtotime($v['date']));
		}

		$this->assign([
			'bluenum' => $bluenum,
			'nums' => $nums,
			'live_data' => $live_data,
			'history' => $history,
			'name' => $name,
		]);
		return view('tuijian');
	}

	function daletou_history(){
		$url = 'http://datachart.500.com/dlt/history/history.shtml';
		$table = QueryList::get($url)->find('#tablelist');

		$tableRows = $table->find('tr:gt(0)')->map(function($row){
		    return $row->find('td')->texts()->all();
		});
		$data = $tableRows->all();

		foreach($data as $k => $v){
			if($k == 0){
				continue;
			}
			$history = [
				'qi' => $data[$k][0],
				'num1' => $data[$k][1],
				'num2' => $data[$k][2],
				'num3' => $data[$k][3],
				'num4' => $data[$k][4],
				'num5' => $data[$k][5],
				'num6' => $data[$k][6],
				'num7' => $data[$k][7],
				'zhu1' => $data[$k][9],
				'jj1' => $data[$k][10],
				'zhu2' => $data[$k][11],
				'jj2' => $data[$k][12],
				'zong' => $data[$k][13],
				'date' => $data[$k][14],
			];

			if(Db::name('daletou_history')->where('qi', $data[$k][0])->count() == 0){
				Db::name('daletou_history')->insert($history);
			}
		}
	}
}
