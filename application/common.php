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
function jiami($data,$num,$numb){
    $str = str_repeat("*",$numb);//替换字符数量
    $re = substr_replace($data,$str,$num,$numb);
    return $re;
}



/**
 * 循环加密某一数组的某一字段
 * @param  [type] $data  [description]
 * @param  [type] $field [description]
 * @return [type]        [description]
 */
function looparr($data,$field){
	foreach ($data as $key => $value) {
		$str = mb_strlen($value[$field])/2-1;
		$data[$key][$field] = jiami($value[$field],$str,3);
	}
	return $data;
}