<?php

/**
 * api返回数据
 * @param  [type] $status  [状态]
 * @param  [type] $message [提示]
 * @param  array  $data    [数据]
 * @return [type]          [json]
 */
function show($status,$message,$data=[])
{
	return [
		'status' => $status,
		'message' => $message,
		'data' => $data,
	];
}