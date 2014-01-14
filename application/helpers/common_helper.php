<?php
/**
 * 字符截取
 * @param $str 字符串
 * @param $len 截取长度
 * @param $char 截取后缀
 * @return string
 */
function cutstr($str, $len, $char = '...') {
	if(mb_strlen($str, 'utf-8') > $len) {
		return mb_substr($str, 0, $len, 'utf-8').$char;
	} else {
		return $str;
	}
}

/**
 * 邮箱格式验证
 * @param $value 邮箱地址
 * @return boolean
 */
function is_email($value) {
	return preg_match("/^[0-9a-zA-Z]+(?:[\_\-][a-z0-9\-]+)*@[a-zA-Z0-9]+(?:[-.][a-zA-Z0-9]+)*\.[a-zA-Z]+$/i", $value);
}

/**
 * 创建文件夹
 * @param type $path
 */
function createFolder($path) {
	if (!file_exists($path)) {
		createFolder(dirname($path));
		mkdir($path, 0777);
	}
 }

 /**
  * 日志
  * @param string $msg	内容
  * @param type $level	说明
  * @param type $filename  文件前缀
  * @param type $cf  是否每天生成一个文件
  */
 function write_log($msg, $level='info', $filename = 'ci', $cf = true) {
	$fname = $cf == true ? $filename.date('-Y-m-d') : $filename;
	$msg = $level.'-'.date('Y-m-d H:i:s'). ' --> '.$msg."\r\n";
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/'.$fname.'.log', $msg, FILE_APPEND);
}

/**
 * 获取缩略图
 * @param type $picUrl
 * @return type
 */
function get_thumb($picUrl, $baseUrl = './data/uploads/pics/') {
	if(!$picUrl) return false;
	$cover = pathinfo($picUrl);
	$thumbUrl = $baseUrl.$cover['dirname'].'/'.$cover['filename'].'_thumb.'.$cover['extension'];
	if(file_exists($thumbUrl)) {
		return $thumbUrl;
	} else {
		return $baseUrl.$picUrl;
	}
}