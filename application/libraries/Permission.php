<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* @deprecated 权限检查
* @see Power
* @version 1.0.0 (Mon Feb 13 02:49:16 GMT 2012)
* @author ZhangHao
*/
class Permission
{
	private $ci;

	public function __construct()
	{
		$this->ci = & get_instance();
	}
	/**
	* @deprecated 权限检测
	*/
	public function power_check ($power = '', $class = false)
	{
//		$pre = get_cookie('power');

		//登录检查
		if(!get_cookie('uid')) {
			$this->ci->msg->showmessage('您还未登录！', site_url('admin/login'));
		}

//		$pre = unserialize($pre);
//		if($class)
//		{
//			return (!$pre || !in_array(strtolower($power), $pre)) ? false : true;
//		}
//		else
//		{
//			if(!$pre || !in_array($power, $pre))
//			{
//				$this->ci->msg->showmessage('您没有该操作权限！', site_url('data'));
//			}
//			else
//			{
//				return true;
//			}
//		}
	}

	/**
	* 前台权限检测
	*/
	public function login_check($url = '')
	{
		//登录检查
		if(!$this->ci->session->userdata('uid')) {
			if($url) {
				$this->ci->input->set_cookie('redirect', $url, 0);
			}
			$this->ci->msg->showmessage('您还未登录！', site_url('reg'));
		}
	}
}