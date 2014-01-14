<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * @deprecated 后台登录
 * @see Login
 * @version 1.0.0 (Wed Feb 22 13:09:00 GMT 2012)
 * @author ZhangHao
 */
class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @deprecated 默认控制器
	 */
	public function index() {
		self::login();
	}

	/**
	 * @deprecated 登录
	 */
	public function login() {
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', '账号', 'required|xss_clean');
		$this->form_validation->set_rules('password', '密码', 'trim|required');
		$this->form_validation->set_error_delimiters('<li>', '</li>');


		if ($this->form_validation->run() == FALSE) {
			$this->load->view('admin/login');
		} else {

			//检查用户名和密码
			if (!$this->check_admin($this->input->post('username'), $this->input->post('password'))) {
				$this->_data['loginerr'] = '用户名或密码错误!';
				$this->load->view('admin/login', $this->_data);
			} else {
				redirect('admin/main');
			}
		}
	}

	/**
	 * @deprecated 退出登录
	 */
	public function login_out() {
		delete_cookie('username');
		delete_cookie('uid');
		$this->msg->showmessage('安全退出', site_url('admin/login'));
	}

    /**
    * @deprecated 登录检测
    */
	public function check_admin($username, $password) {
		$this->db->where(array('username' => $username, 'password' => md5($password)));
		$row = $this->db->get($this->db->dbprefix('adminer'))->row_array();

		if($row) {
			$this->input->set_cookie('username', $row['username'], 0);
			$this->input->set_cookie('uid', $row['uid'], 0);
			return true;
		} else {
			return false;
		}
	}
}