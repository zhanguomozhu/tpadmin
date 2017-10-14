<?php
namespace app\index\controller;
use \think\Controller;
class Index extends Controller
{
    public function index()
    {
    	$data = array();
    	//日排行栏目
    	$day = model('cai')->field('tian,nian')->group('tian')->limit(3)->select();
    	foreach ($day as $key => $value) {
    		$result = model('cai')->format_day($value['tian'],$value['nian']);
    		$data['day'][$key]['day']=$value['tian'];
    		$data['day'][$key]['data']=$result;
    	}
    	//周排行
    	$zhou = model('cate')->field('name,starttime')->where(['level'=>2])->limit(4)->order(['starttime'=>'desc'])->select();
    	foreach ($zhou as $key => $value) {
    		$data['zhou'][$key]['zhou']=date('W',$value['starttime']);;
    		$data['zhou'][$key]['data']=$value['name'];
    	}
    	//月排行
    	$yue = model('cate')->field('name,starttime')->where(['level'=>1])->limit(12)->order(['starttime'=>'desc'])->select();
    	foreach ($yue as $key => $value) {
    		$data['yue'][$key]['yue']=date('m',$value['starttime']);;
    		$data['yue'][$key]['data']=$value['name'];
    	}
    	//年排行
    	$nian = model('cate')->field('name,starttime')->where(['level'=>0])->limit(1)->select();
    	foreach ($nian as $key => $value) {
    		$data['nian'][$key]['nian']=date('Y',$value['starttime']);;
    		$data['nian'][$key]['data']=$value['name'];
    	}
        return $this->fetch('',['res'=>$data]);
    }
}
