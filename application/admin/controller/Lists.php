<?php 
namespace app\admin\controller;
use think\Controller;
class Lists extends Controller
{
	/**
	 * 列表
	 * @return [type] [description]
	 */
	public function index()
	{

		//搜索
			$res = input();
			$where = [];//搜索条件数组
			$order = [];//搜索排序数组
			$ids = [];//账号id数组
			$pageParam    = ['query' =>[]];//分页参数
			if($res){
				if(isset($res['zhanghao']) && !empty($res['zhanghao'])){//搜索用户名
					$users = model('cai')->where('zhanghao','like','%'.$res['zhanghao'].'%')->select();
					foreach ($users as $user) {
						$ids[] = $user->id;
					}
					$where['id'] =['in',$ids];
				}
				if(isset($res['starttime']) && !empty($res['starttime'])){//搜索起始时间
					$where['create_time'] = ['egt',strtotime($res['starttime'])];
				}
				if(isset($res['endtime']) && !empty($res['endtime'])){//搜索结束时间
					$where['create_time'] = ['elt',strtotime($res['endtime'])];
				}
				if(isset($res['starttime']) && !empty($res['starttime']) && isset($res['endtime']) && !empty($res['endtime'])){//同时具有起始与结束
					$where['create_time'] = [['egt',strtotime($res['starttime'])],['elt',strtotime($res['endtime'])]];
				}

				//排序条件
				if(!empty($res['num'])){//搜索笔数
					if($res['num'] == 2){
						$order['num'] = 'desc';
					}else{
						$order['num'] = 'asc';
					}
				}

				if(!empty($res['total'])){//搜索总金额
					if($res['total'] == 2){
						$order['total'] = 'desc';
					}else{
						$order['total'] = 'asc';
					}
				}

				if(!empty($res['yxtotal'])){//搜索有效金额
					if($res['yxtotal'] == 2){
						$order['yxtotal'] = 'desc';
					}else{
						$order['yxtotal'] = 'asc';
					}
				}
				if(!empty($res['result'])){//搜索派彩结果
					if($res['result'] == 2){
						$order['result'] = 'desc';
					}else{
						$order['result'] = 'asc';
					}
				}
				if(!empty($res['create_time'])){//搜索时间
					if($res['create_time'] == 2){
						$order['create_time'] = 'desc';
					}else{
						$order['create_time'] = 'asc';
					}
				}

			}
			
			if(empty($order)){
				$order['id'] = 'desc';
			}

			$data = model('cai')->get_search($where,$order,$pageParam);//获取搜索结果

		return $this->fetch('',['data'=>$data,'res'=>$res]);
	}



	/**
	 * 编辑
	 * @return [type] [description]
	 */
	public function edit()
	{
		if(request()->isPost()){
			
			$data = input('post.');

			$id = $data['id'];
			
			$res = model('cai')->add($data,$id);
			if($res){
				$this->success('更新成功','index');
			}else{
				$this->error('更新失败');
			}
			return;
		}
		dump(input('id'));
		$data = model('cai')->find(input('id'));
		return $this->fetch('',['data'=>$data]);
	}

	/**
	 * 删除
	 * @return [type] [description]
	 */
	public function del()
	{
		$data = model('cai')->destroy(input('id'));
		if($data){
				$this->success('删除成功','index');
			}else{
				$this->error('删除失败');
			}
	}


}