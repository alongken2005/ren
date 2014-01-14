<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @deprecated 文字管理
* @see Content
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class Flink extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();

		$this->load->model('base_mdl', 'base');
		$this->permission->power_check();
    }

    /**
    * @deprecated 默认方法
    */
    public function index ()
    {
        self::lists();
    }

    /**
    * @deprecated 友情链接管理
    */
    public function lists ()
    {

		$this->_data['lists'] = $this->base->get_data('flink')->result_array();
        $this->load->view('admin/flink_list', $this->_data);

    }

    /**
    * @deprecated 友情链接处理
    */
    public function op ()
    {
    	$id = intval($this->input->get('fid'));
		$link_path = './data/uploads/flink/';

		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', '链接名称', 'required|trim');
		$this->form_validation->set_rules('url', '链接地址', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if($this->form_validation->run() === FALSE) {
			if($id) {
				$this->_data['flink'] = $this->base->get_data('flink', array('fid'=>$id))->row_array();
			}
			$this->load->view('admin/flink_op', $this->_data);
		} else {
			if($_FILES['userfile']['size'] > 0) {
				$config['upload_path']		= $link_path;
				$config['allowed_types']	= 'gif|jpg|png';
				$config['max_size']			= '5000';
				$config['max_width']		= '3000';
				$config['max_height']		= '3000';
				$config['encrypt_name']		= TRUE;
				$config['overwrite']		= FALSE;

				$this->load->library('upload', $config);

				if(!$this->upload->do_upload()) {
					$this->_data['upload_err'] = $this->upload->display_errors();
					$this->load->view('admin/flink_op', $this->_data);
				}
				$upload_data = $this->upload->data();
			}

			$link_data = array(
				'name'	=>  $this->input->post('name'),
				'url'	=>  $this->input->post('url'),
				'order'	=>  $this->input->post('order')
			);

			if(!$id) {
				$link_data['picfile'] = isset($upload_data['file_name']) ? $upload_data['file_name'] : '';
				if($this->base->insert_data('flink', $link_data)) {
					$this->msg->showmessage('添加成功', site_url('admin/flink/lists'));
				} else {
					$this->msg->showmessage('添加失败', site_url('admin/flink/op'));
				}
			} else {
				if($_FILES['userfile']['size'] > 0) {
					$link = $this->base->get_data('flink', array('fid'=>$id))->row_array();
					@unlink($link_path.$link['picfile']);
					$link_data['picfile'] = isset($upload_data['file_name']) ? $upload_data['file_name'] : '';
				}
				if($this->base->update_data('flink', array('fid'=>$id), $link_data))
				{
					$this->msg->showmessage('修改成功', site_url('admin/flink/lists'));
				}
				else
				{
					$this->msg->showmessage('修改失败', site_url('admin/flink/op?fid='.$id));
				}
			}
		}
    }

    /**
    * @deprecated 文章删除
    */
    public function flink_del () {
        $id = intval($this->input->get('fid'));
        if($id && $this->base->del_data('flink', array('fid' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }
}