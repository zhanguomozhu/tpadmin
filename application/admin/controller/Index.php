<?php 
namespace app\admin\controller;
use think\Controller;
class Index extends Controller
{
	public function index()
	{
		$dd = date('Y-m-d H:i:s',time());//日期
		//检查周期栏目是否包今天
		
		$res = model('cate')->get_time();
		if($res){
			$style = "";
		}else{
			$style = 'disabled';//禁止点击
		}
		
		return $this->fetch('',['dd'=>$dd,'style'=>$style]);
	}

	public function doupload()
	{
		//引入phpexcel类
		vendor("phpexcel.PHPExcel");
		// 获取表单上传文件 例如上传了001.jpg
	    $file = request()->file('excel');
	    //执行上传操作
	    $info = $file->validate(['ext' => 'xlsx,xls'])->move(ROOT_PATH . 'public' . DS . 'uploads');


	    if($info){
	         //获取文件名
	        $exclePath = $info->getSaveName();
	        //上传文件的地址
	        $filename = ROOT_PATH . 'public' . DS . 'uploads/'. $exclePath;

	        //判断截取文件
	        $extension = strtolower( pathinfo($filename, PATHINFO_EXTENSION) );

	        //区分上传文件格式
	        if($extension == 'xlsx') {
	            $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
	            $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
	        }else if($extension == 'xls'){
	            $objReader =\PHPExcel_IOFactory::createReader('Excel5');
	            $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
	        }

	        //获取execl有多少个sheet
			$sheetCount = $objPHPExcel->getSheetCount();
			//循环sheet,读取数据，放入到数组中
			for($i=0;$i<$sheetCount;$i++){
			    $data[$i] = $objPHPExcel->getSheet($i)->toArray();
			}

			//重组数据
			foreach ($data as $k => $v) {
				//删除头两行
				array_shift($data[$k]);
				array_shift($data[$k]);

				//去除空sheet
				if(!$v[0][0]){
					unset($data[$k]);
				}
			}


			//拼写插入数据库数据
			$list = array();
			$time = time();//当前时间戳
			
			//判断当前属于哪一个栏目
			$cates = model('cate')->field('id,starttime,endtime')->select();
			foreach ($cates as $key => $value) {
				if($time>$value['starttime']  && $time<$value['endtime']){
					$cate_id = $value['id'];
				}
			}


			foreach ($data as $k1 => $v1) {

				foreach ($v1 as $k2 => $v2) {
					$list[$k2]['zhantai']=$v2[1];
					$list[$k2]['zhanghao']=$v2[2];
					$list[$k2]['num']=$v2[3];
					$list[$k2]['total']=$v2[4];
					$list[$k2]['yxtotal']=$v2[5];
					$list[$k2]['result']=$v2[6];
					$list[$k2]['tian']=date('z');
					$list[$k2]['zhou']=date('W');
					$list[$k2]['yue']=date('m');
					$list[$k2]['nian']=date('Y');
					$list[$k2]['cate_id']=$cate_id;
				}
			}


			//插入数据库
			$result = model('cai')->add_all($list);
			if($result){
				echo show(200,'导入数据成功');
			}else{
				echo show(400,'导入数据失败,清重试');
			}
		}else{
			 echo show(400,'导入数据失败,,清重试');
		}


	}


}