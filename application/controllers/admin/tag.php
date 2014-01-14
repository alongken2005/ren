<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @desc 分类管理
* @see Type
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class Type extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();

		$this->_data['thisClass'] = __CLASS__;
		$this->load->model('base_mdl', 'base');
		$this->_data['kinds'] = array('video'=>'视频','stuff'=>'教案');
		$this->permission->power_check();
    }

    /**
    * @deprecated 默认方法
    */
    public function index () {
        self::lists();
    }

    /**
    * @deprecated 管理
    */
    public function lists () {

		$this->_data['lists'] = $this->base->get_data('type')->result_array();
        $this->load->view('admin/type_list', $this->_data);
    }

    /**
    * @deprecated 处理
    */
    public function op () {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', '名称', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($this->form_validation->run() == FALSE) {

			if ($id = $this->input->get('id')) {
				$this->_data['content'] = $this->base->get_data('type', array('id' => $id))->row_array();
			}
			$this->load->view('admin/type_op', $this->_data);
		} else {
			$deal_data = array(
				'name' 	=> $this->input->post('name'),
				'type'	=> $this->input->post('type'),
			);

			if ($id = $this->input->get('id')) {
				if ($this->base->update_data('type', array('id' => $id), $deal_data)) {
					$this->msg->showmessage('更新成功', site_url('admin/type/lists'));
				} else {
					$this->msg->showmessage('更新失败', site_url('admin/type/op'));
				}
			}
			else {
				if ($this->base->insert_data('type', $deal_data)) {
					$this->msg->showmessage('添加成功', site_url('admin/type/lists'));
				} else {
					$this->msg->showmessage('添加失败', site_url('admin/type/op'));
				}
			}
		}
    }

    /**
    * @deprecated 删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('type', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }
}