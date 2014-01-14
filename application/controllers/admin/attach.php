<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 附件管理
* @see Attach
* @version 1.0.0 (12-12-13 下午7:25)
* @author ZhangHao
*/
class Attach extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();

		$this->_data['thisClass'] = __CLASS__;
		$this->_data['kinds'] = array('stuff'=>'教案');
		$this->load->model('base_mdl', 'base');
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
		//分页配置
        $this->load->library('gpagination');
		$total_num = $this->base->get_data('attach')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/attach/lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('attach', array(), '*', $limit, $offset)->result_array();
        $this->load->view('admin/attach_list', $this->_data);
    }

    /**
    * @deprecated 处理
    */
    public function op () {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', '名字', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($this->form_validation->run() == FALSE) {
			if ($id = $this->input->get_post('id')) {
				$this->_data['content'] = $this->base->get_data('author', array('id' => $id))->row_array();
			}
			$this->load->view('admin/author_op', $this->_data);
		} else {
			$deal_data = array(
				'content'		=> $this->input->post('content'),
				'name'			=> $this->input->post('name'),
			);

			if ($id = $this->input->get('id')) {
				if ($this->base->update_data('author', array('id' => $id), $deal_data)) {
					$this->msg->showmessage('更新成功', site_url('admin/author/lists'));
				} else {
					$this->msg->showmessage('更新失败', site_url('admin/author/op?cid='.$id));
				}
			} else {
				if ($this->base->insert_data('author', $deal_data)) {
					$this->msg->showmessage('添加成功', site_url('admin/author/lists'));
				} else {
					$this->msg->showmessage('添加失败', site_url('admin/author/op'));
				}
			}
		}
    }

    /**
    * @deprecated 删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
		$row = $this->base->get_data('attach', array('id'=>$id))->row_array();
		@unlink('./data/uploads/attach/'.$row['filename']);
        if($id && $this->base->del_data('attach', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }
}