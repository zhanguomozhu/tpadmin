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
	public function get_day($id,$limit=50,$field='zhanghao')
	{
		$data=[
			'tian'=>['eq',$id],
			'cate_id'=>$this->get_cate_id(),//栏目id
		];
		$res = $this->where($data)->field('zhanghao,SUM(yxtotal) total')->group('zhanghao')->order('SUM(yxtotal) desc')->limit($limit)->select();
		//echo $this->getLastSql();
		looparr($res,$field);//隐藏账号字段
		return $res;
	}



	/**
	 * 周排行
	 * @return [type] [description]
	 */
	public function get_zhou($id,$limit=200,$field='zhanghao')
	{
		$data=[
			'zhou'=>['eq',$id],
			'cate_id'=>$this->get_cate_id(),
		];
		$res = $this->where($data)->field('zhanghao,SUM(yxtotal) total')->group('zhanghao')->order('SUM(yxtotal) desc')->limit($limit)->select();
		looparr($res,$field);//隐藏账号字段
		return $res;
	}



	/**
	 * 月排行
	 * @return [type] [description]
	 */
	public function get_yue($id,$limit=300,$field='zhanghao')
	{
		$data=[
			'yue'=>['eq',$id],
			'cate_id'=>$this->get_cate_id(),
		];
		$res = $this->where($data)->field('zhanghao,SUM(yxtotal) total')->group('zhanghao')->order('SUM(yxtotal) desc')->limit($limit)->select();
		looparr($res,$field);//隐藏账号字段
		return $res;
	}


	/**
	 * 年排行
	 * @return [type] [description]
	 */
	public function get_nian($id,$limit=500,$field='zhanghao')
	{
		$data=[
			'nian'=>['eq',$id],
			'cate_id'=>$this->get_cate_id(),
		];
		$res = $this->where($data)->field('zhanghao,SUM(yxtotal) total')->group('zhanghao')->order('SUM(yxtotal) desc')->limit($limit)->select();
		looparr($res,$field);//隐藏账号字段
		return $res;
	}






	/**
	 * 获取当前属于哪一个栏目
	 * @return [type] [description]
	 */
	private function get_cate_id(){
		$time = time();//当前时间戳
			
		//判断当前属于哪一个栏目
		$cates = model('cate')->where('level',2)->field('id,starttime,endtime')->select();
		foreach ($cates as $key => $value) {
			if($time>=$value['starttime']  && $time<=$value['endtime']){
				$cate_id = $value['id'];
			}
		}

		return $cate_id;
	}



	/**
	 * 查询用户
	 * @return [type] [description]
	 */
	public function get_zhanghao($zhanghao)
	{
		//最后记录时间
		$last = model('cai')->field('tian,nian,cate_id')->order('id desc')->limit(1)->select();

		//排名
		$dayData = $this->get_day(date('z'),50,'');//日排名信息
		$zhouData = $this->get_zhou(date('W'),200,'');//周排名信息
		$yueData = $this->get_yue(date('m'),300,'');//月排名信息
		$nianData = $this->get_nian(date('Y'),500,'');//年排名信息




		//***********************************************************查询日记录
		$data = [
			'tian'=>['in',[date('z'),date('z')-1]],//查询两天
			'zhanghao'=>['eq',$zhanghao],
		];
		$res['day'] = $this->where($data)->field('zhanghao,SUM(yxtotal) total,tian,nian')->group('tian')->order('tian desc')->select();
		
		//格式化时间
		foreach ($res['day'] as $key => $value) {
			$res['day'][$key]['cate'] = $this->format_day($value['tian'],$value['nian']);
			$res['day'][$key]['paiming'] = $this->get_paiming($dayData,$value['zhanghao']); 
			unset($res['day'][$key]['tian']);
			unset($res['day'][$key]['nian']);
		}
		//没有记录
		if(!$res['day']){
			$res['day'] = ['zhanghao'=>$zhanghao,'total'=>'没有投注','paiming'=>'未进入排名','tian'=>$this->format_day($last[0]['tian'],$last[0]['nian'])];
		}



		//***********************************************************查询周记录
		$data = [
			'zhou'=>['in',[date('W'),date('W')-1]],//查询两周
			'zhanghao'=>['eq',$zhanghao],
		];
		$res['zhou'] = $this->where($data)->field('zhanghao,SUM(yxtotal) total,zhou,cate_id')->group('zhou')->order('zhou desc')->select();

		//格式化时间
		foreach ($res['zhou'] as $key => $value) {
			$res['zhou'][$key]['cate'] = $this->format_zhou($value['cate_id']);
			$res['zhou'][$key]['paiming'] = $this->get_paiming($zhouData,$value['zhanghao']);
			unset($value['zhou']);
			unset($value['cate_id']);
		}

		//没有记录
		if(!$res['zhou']){
			$res['zhou'] = ['zhanghao'=>$zhanghao,'total'=>'没有投注','paiming'=>'未进入排名','tian'=>$this->format_zhou($last[0]['cate_id'])];
		}





		//************************************************************查询月记录
		$data = [
			'yue'=>['in',[date('m'),date('m')-1]],//查询两月
			'zhanghao'=>['eq',$zhanghao],
		];
		$res['yue'] = $this->where($data)->field('zhanghao,SUM(yxtotal) total,yue,cate_id')->group('yue')->order('yue desc')->select();

		//格式化时间
		foreach ($res['yue'] as $key => $value) {
			$res['yue'][$key]['cate'] = $this->format_yue($value['cate_id']);
			$res['yue'][$key]['paiming'] = $this->get_paiming($yueData,$value['zhanghao']); 
			unset($value['yue']);
			unset($value['cate_id']);
		}

		//没有记录
		if(!$res['yue']){
			$res['yue'] = ['zhanghao'=>$zhanghao,'total'=>'没有投注','paiming'=>'未进入排名','tian'=>$this->format_yue($last[0]['cate_id'])];
		}





		//**************************************************************查询年记录
		$data = [
			'nian'=>['in',[date('Y'),date('Y')-1]],//查询两月
			'zhanghao'=>['eq',$zhanghao],
		];
		$res['nian'] = $this->where($data)->field('zhanghao,SUM(yxtotal) total,nian,cate_id')->group('nian')->order('nian desc')->select();

		//格式化时间
		foreach ($res['nian'] as $key => $value) {
			$res['nian'][$key]['cate'] = $this->format_nian($value['cate_id']);
			$res['nian'][$key]['paiming'] = $this->get_paiming($nianData,$value['zhanghao']); 
			unset($value['nian']);
			unset($value['cate_id']);
		}

		//没有记录
		if(!$res['nian']){
			$res['nian'] = ['zhanghao'=>$zhanghao,'total'=>'没有投注','paiming'=>'未进入排名','tian'=>$this->format_nian($last[0]['cate_id'])];
		}

		return $res;
	}

	/**
	 * 格式化天
	 * @return [type] [description]
	 */
	private function format_day($day,$nian){

		$today = mktime(0,0,0,1,1,$nian)+$day*86400;
		$res = date("Y年m月d日",$today);
		return $res;
	}

	/**
	 * 格式化周
	 * @param  [type] $zhou [description]
	 * @return [type]       [description]
	 */
	private function format_zhou($cate_id){
		$res = model('cate')->field('name')->find(['id'=>$cate_id,'level'=>2]);//周
		return $res['name'];
	}

	/**
	 * 格式化月
	 * @param  [type] $yue [description]
	 * @return [type]      [description]
	 */
	private function format_yue($cate_id){
		//父id
		$res = model('cate')->field('pid')->find(['id'=>$cate_id,'level'=>2]);//周
		$res1 = model('cate')->field('name')->find(['id'=>$res['pid'],'level'=>1]);//月
		return $res1['name'];
	}

	/**
	 * 格式化年
	 * @param  [type] $nian [description]
	 * @return [type]       [description]
	 */
	private function format_nian($cate_id){
		//父id
		$res = model('cate')->field('pid')->find(['id'=>$cate_id,'level'=>2]);//周
		$res1 = model('cate')->field('pid')->find(['id'=>$res['pid'],'level'=>1]);//月
		$res2 = model('cate')->field('name')->find(['id'=>$res1['pid'],'level'=>0]);//年
		return $res2['name'];
	}


	/**
	 * 获取排名信息
	 * @param  [type] $data [description]
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	private function get_paiming($data,$name){
		foreach ($data as $key => $value) {
			if($value['zhanghao'] == $name){
				$paiming = $value['paiming'];
			}
		}
		return $paiming;
	}

	
}