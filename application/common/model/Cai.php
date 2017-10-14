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
		//时间
		$d = date('z');//日
		$w = date('W');//周
		$m = date('m');//月
		$y = date('Y');//年
		//排名
		$dayData = $this->get_day($d,50,'');//日排名信息
		$ydayData = $this->get_day($d-1,50,'');//上一日排名信息
		$zhouData = $this->get_zhou($w,200,'');//周排名信息
		$yzhouData = $this->get_zhou($w-1,200,'');//上一周排名信息
		$yueData = $this->get_yue($m,300,'');//月排名信息
		$yyueData = $this->get_yue($m-1,300,'');//上一月排名信息
		$nianData = $this->get_nian($y,500,'');//年排名信息
		

		//***********************************************************************************************************查日年记录
		//没有记录
		if(!$dayData){//**********************************************当天没有数据
			//当天
			$res['day'][] = $this->find_day('',$zhanghao,$d,$y);
			//前一天
			if(!$ydayData){//前一天没有数据
				$res['day'][] = $this->find_day('',$zhanghao,$d-1,$y);
			}else{//前一天有数据
			    //获取排名
				foreach ($ydayData as $key => $value) {
					$paiming= $this->get_paiming($ydayData,$zhanghao); 
				}
				$res['day'][] = $this->find_day($paiming,$zhanghao,$d-1,$y);
			}
		}else{//*********************************************************当天有数据
			//当天
			$paiming= $this->get_paiming($dayData,$zhanghao); 
			$res['day'][] = $this->find_day($paiming,$zhanghao,$d,$y);
			//上一周
			$paiming1= $this->get_paiming($ydayData,$zhanghao);
			$res['day'][] = $this->find_day($paiming1,$zhanghao,$d-1,$y); 
		}





		//***********************************************************************************************************查询周记录
		if(!$zhouData){//**********************************************当周没有数据
			//当周
			$res['zhou'][]=$this->find_zhou('',$zhanghao,$w,$y);
			//前一周
			if(!$ydayData){//前一周没有数据
				$res['zhou'][]=$this->find_zhou('',$zhanghao,$w-1,$y);
			}else{//前一周有数据
			    //获取排名
				foreach ($yzhouData as $key => $value) {
					$paiming= $this->get_paiming($yzhouData,$zhanghao); 
				}
				$res['zhou'][]=$this->find_zhou($paiming,$zhanghao,$w-1,$y);
			}
		}else{
			//***********************************************************当周有数据
			//当前周
			$paiming= $this->get_paiming($zhouData,$zhanghao); 
			$res['zhou'][]=$this->find_zhou($paiming,$zhanghao,$w,$y);
			
			
			//上一周
			$paiming1= $this->get_paiming($yzhouData,$zhanghao); 
			$res['zhou'][]=$this->find_zhou($paiming1,$zhanghao,$w-1,$y);
		}





		//***********************************************************************************************************查询月记录
		if(!$yueData){//**********************************************当月没有数据
			//当月
			$res['yue'][]=$this->find_yue($paiming,$zhanghao,$d,$y);
			//下一月
			if(!$yyueData){//下一月没有数据
				$res['yue'][]=$this->find_yue($paiming,$zhanghao,$d-30,$y);
			}else{//下一月有数据
			    //获取排名
				foreach ($yyueData as $key => $value) {
					$paiming= $this->get_paiming($yyueData,$zhanghao); 
				}
				$res['yue'][]=$this->find_yue($paiming,$zhanghao,$d-30,$y);
				
			}
		}else{
			//**********************************************************当月有数据
			//当前月
			$paiming= $this->get_paiming($yueData,$zhanghao);
			$res['yue'][]=$this->find_yue($paiming,$zhanghao,$d,$y);
			
			//上一月
			$paiming1= $this->get_paiming($yyueData,$zhanghao); 
			$res['yue'][]=$this->find_yue($paiming1,$zhanghao,$d-30,$y);
		

		}





		//***********************************************************************************************************查询年记录
		if(!$nianData){//**********************************************当年没有数据
			$res['nian'][]=$this->find_nian('',$zhanghao);
		}else{//*******************************************************当年有数据
			//格式化时间
			$paiming= $this->get_paiming($nianData,$zhanghao); 
			$res['nian'][]=$this->find_nian($paiming,$zhanghao);

		}
		return $res;
	}


	/**
	 * 获取天排名
	 * @return [type] [description]
	 */
	private function find_day($day_paiming,$zhanghao,$d,$y){
		if(!$day_paiming){//当日没有数据
			$res = ['zhanghao'=>$zhanghao,'total'=>'没有投注','paiming'=>'未进入排名','cate'=>$this->format_day($d,$y)];
		}else{//当日有数据
			$res['zhanghao'] = $day_paiming['zhanghao'];
			$res['total'] = $day_paiming['total'];
			$res['paiming'] = $day_paiming['paiming'];
			$res['cate']=$this->format_day($d,$y);
		}
		return $res;
	}

	/**
	 * 获取周排名
	 * @param  [type] $zhou [description]
	 * @return [type]       [description]
	 */
	private function find_zhou($zhou_paiming,$zhanghao,$w,$y){
		if(!$zhou_paiming){//当周没有数据
			$res= ['zhanghao'=>$zhanghao,'total'=>'没有投注','paiming'=>'未进入排名','cate'=>$this->format_zhou($w,$y)];
		}else{//当周有数据
			$res['zhanghao'] = $zhou_paiming['zhanghao'];
			$res['total'] = $zhou_paiming['total'];
			$res['paiming'] = $zhou_paiming['paiming'];
			$res['cate'] = $this->format_zhou($w,$y);
		}
		return $res;
	}

	/**
	 * 获取月排名
	 * @param  [type] $yue [description]
	 * @return [type]      [description]
	 */
	private function find_yue($yue_paiming,$zhanghao,$d,$y){
		if(!$yue_paiming){//当周没有数据
			$res= ['zhanghao'=>$zhanghao,'total'=>'没有投注','paiming'=>'未进入排名','cate'=>$this->format_yue($d,$y)];
		}else{//当周有数据
			$res['zhanghao'] = $yue_paiming['zhanghao'];
			$res['total'] = $yue_paiming['total'];
			$res['paiming'] = $yue_paiming['paiming'];
			$res['cate'] = $this->format_yue($d,$y);
		}
		return $res;
	}

	/**
	 * 获取年排名
	 * @param  [type] $nian [description]
	 * @return [type]       [description]
	 */
	private function find_nian($nian_paiming,$zhanghao){
		if(!$nian_paiming){//当年没有数据
			$res = ['zhanghao'=>$zhanghao,'total'=>'没有投注','paiming'=>'未进入排名','cate'=>$this->format_nian()];
		}else{//当年没有数据
			$res['zhanghao'] = $nian_paiming['zhanghao'];
			$res['total'] = $nian_paiming['total'];
			$res['paiming'] = $nian_paiming['paiming'];
			$res['cate'] = $this->format_nian();
		}
		return $res;
	}










	/**
	 * 格式化天
	 * @return [type] [description]
	 */
	public function format_day($day,$nian){

		$today = mktime(0,0,0,1,1,$nian)+$day*86400;
		$res = date("Y年m月d日",$today);
		return $res;
	}

	/**
	 * 格式化周
	 * @param  [type] $zhou [description]
	 * @return [type]       [description]
	 */
	private function format_zhou($w,$y){
		$dayNumber = $w * 7;  
	    $weekDayNumber = date("w", mktime(0, 0, 0, 1, $dayNumber, $y));//当前周的第几天  
	    $startNumber = $dayNumber - $weekDayNumber;  
	    $starttime = mktime(0, 0, 0, 1, $startNumber + 1, $y);//开始日期  
	    $endtime =  mktime(23, 59, 59, 1, $startNumber + 7, $y);//结束日期   
		$data=[
			'starttime'=>['elt',$starttime],
			'endtime'=>['egt',$endtime],
			'level'=>2
		];
		$res = model('cate')->where($data)->field('name')->find();//周
		return $res['name'];
	}

	/**
	 * 格式化月
	 * @param  [type] $yue [description]
	 * @return [type]      [description]
	 */
	private function format_yue($d,$y){
		$time = mktime(0,0,0,1,$d,$y);//当前时间
		$data=[
			'starttime'=>['elt',$time],
			'endtime'=>['egt',$time],
			'level'=>1
		];
		$res = model('cate')->where($data)->field('name')->find();//月
		return $res['name'];
	}

	/**
	 * 格式化年
	 * @param  [type] $nian [description]
	 * @return [type]       [description]
	 */
	private function format_nian(){
		$time = time();//当前时间
		$data=[
			'starttime'=>['elt',$time],
			'endtime'=>['egt',$time],
			'level'=>0
		];
		$res = model('cate')->where($data)->field('name')->find();//年
		return $res['name'];
	}


	/**
	 * 获取排名信息
	 * @param  [type] $data [description]
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	private function get_paiming($data,$name){
		$res = [];
		foreach ($data as $key => $value) {
			if($value['zhanghao'] == $name){
				$res = $value;
			}
		}
		return $res;
	}

	
}