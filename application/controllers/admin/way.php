<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @desc 文字管理
* @see Content
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class Way extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();

		$this->_data['thisClass'] = __CLASS__;
		$this->load->model('base_mdl', 'base');
		$this->config->load('common', TRUE);
		$this->permission->power_check();
		$this->_data['wayType'] = $this->config->item('wayType', 'common');
    }

    /**
    * @deprecated 默认方法
    */
    public function index () {
        self::lists();
    }

    /**
    * @deprecated 文章管理
    */
    public function lists () {

		//分页配置
        $this->load->library('gpagination');
		$total_num = $this->base->get_data('ways')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/way/lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('ways', array(), '*', $limit, $offset, 'sort DESC, ctime DESC')->result_array();
        $this->load->view('admin/way_list', $this->_data);
    }

    /**
    * @deprecated 文章处理
    */
    public function op () {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '标题', 'required|trim');
		$this->form_validation->set_rules('content', '内容', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($this->form_validation->run() == FALSE) {

			$this->_data['productes'] = $this->base->get_data('productes', array(), 'id,title')->result_array();
			if ($id = $this->input->get_post('id')) {
				$this->_data['content'] = $this->base->get_data('ways', array('id' => $id))->row_array();
			}
			$this->load->view('admin/way_op', $this->_data);
		} else {
			$deal_data = array(
				'content' 	=> $this->input->post('content'),
				'remark' 	=> $this->input->post('remark'),
				'title'		=> $this->input->post('title'),
				'pids'		=> ($this->input->post('pids') ? implode(',', $this->input->post('pids')) : ''),
				'type'		=> $this->input->post('type'),
				'ctime'		=> strtotime($this->input->post('ctime')),
				'sort'		=> $this->input->post('sort')
			);

			if ($id = $this->input->get('id')) {
				if ($this->base->update_data('ways', array('id' => $id), $deal_data)) {
					$this->msg->showmessage('更新成功', site_url('admin/way/lists'));
				} else {
					$this->msg->showmessage('更新失败', site_url('admin/way/op'));
				}
			}
			else {
				if ($this->base->insert_data('ways', $deal_data)) {
					$this->msg->showmessage('添加成功', site_url('admin/way/lists'));
				} else {
					$this->msg->showmessage('添加失败', site_url('admin/way/op'));
				}
			}
		}
    }

    /**
    * @deprecated 文章删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('ways', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }
}