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
	 * 修改数据
	 * @param [type] $data [description]
	 */
	public function add($data,$id)
	{
		if($this->save($data,['id'=>$id])){
			return true;
		}else{
			return false;
		}
		
	}


	/**
	 * 获取搜索结果
	 * @return [type] [description]
	 */
	public function get_search($data,$order)
	{
		$res = $this->where($data)->order($order)->paginate('',false,['query' => request()->param()]);
		//echo $this->getLastSql();
		return $res;
	}


	/**
	 * 日排行
	 * @return [type] [description]
	 */
	public function get_day($id)
	{
		// $data=[
		// 	'tian'=>['in',range($id-2,$id)],
		// ];
		$res = $this->where('tian',$id)->field('zhanghao,SUM(yxtotal) total,create_time')->group('zhanghao')->order('SUM(yxtotal) desc')->limit(50)->select();
		//echo $this->getLastSql();
		looparr($res,'zhanghao');//隐藏账号
		return $res;
	}



	/**
	 * 周排行
	 * @return [type] [description]
	 */
	public function get_zhou($id)
	{
		$res = $this->where('zhou',$id)->field('zhanghao,SUM(yxtotal) total,create_time')->group('zhanghao')->order('SUM(yxtotal) desc')->limit(200)->select();
		looparr($res,'zhanghao');//隐藏账号
		return $res;
	}



	/**
	 * 月排行
	 * @return [type] [description]
	 */
	public function get_yue($id)
	{
		$res = $this->where('yue',$id)->field('zhanghao,SUM(yxtotal) total,create_time')->group('zhanghao')->order('SUM(yxtotal) desc')->limit(300)->select();
		looparr($res,'zhanghao');//隐藏账号
		return $res;
	}


	/**
	 * 年排行
	 * @return [type] [description]
	 */
	public function get_nian($id)
	{
		$res = $this->where('nian',$id)->field('zhanghao,SUM(yxtotal) total,create_time')->group('zhanghao')->order('SUM(yxtotal) desc')->limit(500)->select();
		looparr($res,'zhanghao');//隐藏账号
		return $res;
	}



	/**
	 * 查询用户
	 * @return [type] [description]
	 */
	public function get_zhanghao($zhanghao)
	{
		$res = $this->where('zhanghao',$zhanghao)->field('zhanghao,SUM(yxtotal) total,create_time')->group('zhanghao')->order('SUM(yxtotal) desc')->limit(500)->select();
		looparr($res,'zhanghao');//隐藏账号
		return $res;
	}






	
}