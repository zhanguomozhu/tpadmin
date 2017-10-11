<?php
namespace app\common\model;
use think\Model;

class Cai extends Model
{


/**
	 * 批量添加数据
	 * @param [type] $data [description]
	 */
	public function add_all($data)
	{
		if($this->saveAll($data)){
			return true;
		}else{
			return false;
		}
		
	}

	/**
	 * 根据id修改用户数据
	 * @param  [type] $data [description]
	 * @param  [type] $id   [description]
	 * @return [type]       [description]
	 */
	public function updateById($data,$id)
	{
		return $this->allowField(true)->save($data,['id'=>$id]);
	}

}