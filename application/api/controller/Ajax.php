<?php 
namespace app\api\controller;
use think\Controller;
class Ajax extends Controller
{
	
	public function index()
	{
		if(request()->isGet()){
			$zhanghao = input('zhanghao');
			if(isset($zhanghao) && !empty($zhanghao)){
				$res = model('cai')->get_zhanghao($zhanghao);
				if($res){
					echo show(200,'成功',$res);
				}else{
					echo show(400,'失败');
				}
			}else{
				echo show(401,'参数有误');
			}
		}else{
			echo show(402,'请求方法不正确');
		}
	
	}




}