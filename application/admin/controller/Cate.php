<?php 
namespace app\admin\controller;
use think\Controller;
class Cate extends Controller
{
	/**
	 * 列表
	 * @return [type] [description]
	 */
	public function index()
	{
		$cates = model('cate')->catetree();
		return $this->fetch('',['cates'=>$cates]);
	}

	/**
	 * 添加
	 */
	public function add(){
		if(request()->isPost()){
			$data = input();
			$data['starttime']=strtotime($data['starttime'].' 00:00:00');
			$data['endtime']=strtotime($data['endtime'].' 23:59:59');
			$res = model('cate')->add_one($data);
			if($res){
				$this->success('添加成功','index');
			}else{
				$this->error('添加失败');
			}
		}
		$cates = model('cate')->catetree();
		return $this->fetch('',['cates'=>$cates]);
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
			$data['starttime']=strtotime($data['starttime'].' 00:00:00');
			$data['endtime']=strtotime($data['endtime'].' 23:59:59');
			$res = model('cate')->save_one($data,$id);
			if($res){
				$this->success('更新成功','index');
			}else{
				$this->error('更新失败');
			}
			return;
		}
		$cates = model('cate')->catetree();
		$data = model('cate')->find(input('id'));
		return $this->fetch('',['cates'=>$cates,'data'=>$data]);
	}

	/**
	 * 删除
	 * @return [type] [description]
	 */
	public function del()
	{
		$data = model('cate')->destroy(input('id'));
		if($data){
				$this->success('删除成功','index');
			}else{
				$this->error('删除失败');
			}
	}


}