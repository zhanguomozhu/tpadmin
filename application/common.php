<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * api返回数据
 * @param  [type] $status  [状态]
 * @param  [type] $message [提示]
 * @param  array  $data    [数据]
 * @return [type]          [json]
 */
function show($status,$message,$data=[])
{
	return json_encode([
		'status' => $status,
		'message' => $message,
		'data' => $data,
	]);
}



/**
 * 用户数据加密       加密数据  
 * 实现 显示信息部分用星号替换。
 * echo jiami(18575559980,3,4);
 */
/**
 * 加密字符串
 * @param  [type] $data [需要加密的字符串]
 * @param  [type] $start  [起始数]
 * @param  [type] $stop [结束位置]
 * @param  [type] $num [加密字符串长度]
 * @return [type]       [description]
 */
function jiami($data,$start,$stop,$num){
	$str = mb_strlen($data);//字符串长度
	if(!$num){
		$num =  $str-$start+1-$stop;//加密长度
	}
    $rep = str_repeat("*",$num);//生成替换字符
    $res = substr_replace($data,$rep,$start,$stop);
    return $res;
}



/**
 * 循环加密某一数组的某一字段
 * @param  [type] $data  [需要处理的数组]
 * @param  [type] $field [字段]
 * @return [type]        [description]
 */
function looparr($data,$field){
		foreach ($data as $key => $value) {
			//排名
			$data[$key]['paiming'] = $key+1;
			if(isset($field) && !empty($field)){
				//从第3位加密到-3位，加密字符串3个长度
				$data[$key][$field] = jiami($value[$field],3,-3,3);
			}
		}
	
	return $data;
}