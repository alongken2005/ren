<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @deprecated 文字管理
* @see Content
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class Content extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();

		$this->load->model('base_mdl', 'base');
		$this->permission->power_check();
		$this->_data['ctype'] = $this->config->item('ctype');
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
		$total_num = $this->base->get_data('content')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/content/content'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('content', array(), '*', $limit, $offset, 'dis ASC, id DESC')->result_array();
		$typeList = $this->base->get_data('type')->result_array();
		foreach($typeList as $v) {
			$tList[$v['id']] = array('name'=>$v['name'], 'type'=>$v['type']); 
		}
		$this->_data['typeList'] = $tList;
        $this->load->view('admin/content_list', $this->_data);
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
			if ($id = $this->input->get_post('id')) {
				$this->_data['row'] = $row = $this->base->get_data('content', array('id' => $id))->row_array();
				$type = $this->base->get_data('type', array('id'=>$row['tid']), 'type')->row_array();

				$this->_data['selectedType'] = $type['type'];

			}
			$this->load->view('admin/content_op', $this->_data);
		} else {
			$deal_data = array(
				'content'		=> $this->input->post('content'),
				'description'	=> mb_substr(trim(strip_tags($this->input->post('content'))), 0, 180, 'utf-8'),
				'title'			=> $this->input->post('title'),
				'tid'			=> $this->input->post('tid'),
				'ctime'			=> strtotime($this->input->post('ctime')),
				'dis'			=> $this->input->post('dis')
			);

			if ($id = $this->input->get('id')) {
				$this->base->update_data('content', array('id' => $id), $deal_data);
				$this->msg->showmessage('更新成功', site_url('admin/content/lists'));
			} else {
				$this->base->insert_data('content', $deal_data);
				$this->msg->showmessage('添加成功', site_url('admin/content/lists'));
			}
		}
    }

    /**
    * 文章删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('content', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }


    public function getType() {
    	$type = $this->input->get('type');
    	$tid = $this->input->get('tid');

    	$typeList = $this->base->get_data('type', array('type'=>$type))->result_array();
    	$op = "";
    	foreach($typeList as $v) {
    		$selected = $tid == $v['id'] ? 'selected' : '';
    		$op .= "<option value='".$v['id']."' ".$selected.">".$v['name']."</option>";
    	}

    	echo $op;
    	exit;

    }
}