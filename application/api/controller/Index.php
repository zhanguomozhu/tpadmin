<?php 
namespace app\api\controller;
use think\Controller;
class Index extends Controller
{
	
	public function index()
	{

		if(request()->isGet()){
			$data = input();
			if(isset($data['act']) && !empty($data['act']) && isset($data['id']) && !empty($data['id'])){
				//日排行数据
				if($data['act'] == 'day'){
					$res = model('cai')->get_day($data['id']);
				}

				//周排行数据
				if($data['act'] == 'zhou'){
					$res = model('cai')->get_zhou($data['id']);
				}

				//月排行数据
				if($data['act'] == 'yue'){
					$res = model('cai')->get_yue($data['id']);
				}


				//年排行数据
				if($data['act'] == 'nian'){
					$res = model('cai')->get_nian($data['id']);
				}


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