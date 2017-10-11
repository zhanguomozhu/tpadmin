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