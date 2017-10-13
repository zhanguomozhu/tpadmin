<?php
namespace app\common\model;
use think\Model;

class Cate extends Model
{

	/**
	 * 修改数据
	 * @param [type] $data [description]
	 */
	public function add_one($data)
	{
		if($this->save($data)){
			return true;
		}else{
			return false;
		}
		
	}


	/**
	 * 修改数据
	 * @param [type] $data [description]
	 */
	public function save_one($data,$id)
	{
		if($this->save($data,['id'=>$id])){
			return true;
		}else{
			return false;
		}
		
	}



	/**
	 * 栏目树
	 * @return [type] [description]
	 */
	public function catetree()
	{
		$cates = $this->select();
		return $this->sort($cates);
	}

	/**
	 *	无限极分类
	 * @param  [type]  $data  [数据]
	 * @param  integer $pid   [父级ID]
	 * @param  integer $level [级别]
	 * @return [type]         [description]
	 */
	public function sort($data,$pid=0,$level=0)
	{
		static $arr = array();
		$depth_html = '';
		for ($i=0; $i < $level; $i++) { 
			if($i == 0){
				$depth_html = '|';
			}
			$depth_html .= '————';
		}
		foreach ($data as $k => $v) {
			if($v['pid'] == $pid){
				$v['level'] = $level;
				$v['name'] = $depth_html.$v['name'];
				$arr[] = $v;
				$this->sort($data,$v['id'],$level+1);
			}
		}
		return $arr;
	}

	/**
	 * 判断栏目是否包含今天
	 * @return [type] [description]
	 */
	public function get_time(){
		$data = [
			'starttime'=>['elt',time()],
			'endtime'=>['egt',time()],
			'level'=>2
		];
		$res = $this->where($data)->select();
		if($res){
			return true;
		}else{
			return false;
		}
	}




	
}