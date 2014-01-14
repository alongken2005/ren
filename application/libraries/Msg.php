<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @class		Msg
 * @package
 * @author		Zhang Hao
 * @date		2010-10-12
 * @since		Version 1.0
 * @description	跳转页面
 */
class Msg {

	//对话框
	function showmessage($msg, $url_forward='', $second=1, $values=array(), $views='') {
		$CI =& get_instance();

		//语言
		if(isset($msg) && $values) {
			if($values) {
				foreach ($values as $k => $v) {
					$rk = $k + 1;
					$message = str_replace('\\'.$k, $v, $msg);
				}
			}
		} else {
			$message = $msg;
		}
		//显示
		if($url_forward && empty($second)) {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: $url_forward");
		} else {
			if($url_forward) {
				$message = "<a href=\"$url_forward\">$message</a><script>setTimeout(\"window.location.href ='$url_forward';\", ".($second*1000).");</script>";
			}
			$data['url_forward'] = $url_forward;
			$data['message'] = $message;
			if($views) {
				$view_url = $views;
			} else {
				$view_url = 'admin/showmessage';
			}
			echo $CI->load->view($view_url, $data, true);
			exit;
		}
	}
}