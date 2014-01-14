<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @deprecated om管理员
* @see Adminer
* @version 1.0.0 (Thu Feb 09 08:45:18 GMT 2012)
* @author ZhangHao
*/
class Adminer extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();

		$this->_data['thisClass'] = __CLASS__;
		$this->load->model('adminer_mdl', 'adminer');
		$this->load->model('base_mdl', 'base');
		//$this->permission->power_check();
    }

    /**
    * @deprecated 默认方法
    */
    public function index ()
    {
        self::adminer_list();
    }

    /**
    * @deprecated 管理员列表
    */
    public function lists ()
    {
    	$this->_data['lists'] = $this->base->get_data('adminer')->result_array();
        $this->load->view('admin/adminer_list', $this->_data);
    }

    /**
    * @deprecated 管理员添加，修改
    */
    public function op ()
    {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', '用户名', 'required|trim');
		$this->form_validation->set_rules('password', '密码', 'required');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($this->form_validation->run() == FALSE)
		{
			if ($id = $this->input->get('uid'))
			{
				$this->_data['adminer'] = $this->base->get_data('adminer', array('uid' => $id))->row_array();
			}
			$this->load->view('admin/adminer_op', $this->_data);
		}
		else
		{
			$insert_data = array (
				'username'		=> $this->input->post('username'),
				'password'		=> $this->input->post('password'),
			);

			if ($id = $this->input->get('uid'))
			{
				if ($this->base->update_data('adminer', array('uid' => $id), $insert_data))
				{
					$this->msg->showmessage('更新成功', site_url('admin/adminer/op?uid='.$id));
				}
				else
				{
					$this->msg->showmessage('更新失败', site_url('admin/adminer/op?uid='.$id));
				}
			}
			else
			{
				$insert_data['ctime'] = time();

				if ($this->base->insert_data('adminer', $insert_data))
				{
					$this->msg->showmessage('添加成功', site_url('admin/adminer/lists'));
				}
				else
				{
					$this->msg->showmessage('添加失败', site_url('admin/adminer/op'));
				}
			}

		}
    }

    /**
    * @deprecated 文章删除
    */
    public function adminer_del () {
        $id = intval($this->input->get('uid'));
        if($id && $this->base->del_data('adminer', array('uid' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }
}