<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @desc 资源管理
* @see Content
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class Resource extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();

		$this->_data['thisClass'] = __CLASS__;
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
    * @deprecated 文章管理
    */
    public function lists () {
		//分页配置
        $this->load->library('gpagination');
		$total_num = $this->base->get_data('resource')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/resource/lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();

		$this->_data['lists'] = $this->db->query('SELECT r.ctime,r.id,r.title,t.name child,t2.name parent FROM mt_resource r LEFT JOIN mt_type t ON r.type=t.tid LEFT JOIN mt_type t2 ON t.parent=t2.tid LIMIT '.$offset.', '.$limit.' ORDER BY sort DESC, r.id DESC')->result_array();
        $this->load->view('admin/resource_list', $this->_data);
    }

    /**
    * @deprecated 文章处理
    */
    public function op () {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '标题', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($this->form_validation->run() == FALSE) {
			$this->_data['docType'] = $this->base->get_data('type', array('cate'=>'doc', 'parent'=>0))->result_array();
			//echo ini_get("post_max_size").'<br>';
			//echo ini_get("upload_max_filesize").'<br>';
			if ($id = $this->input->get_post('id')) {
				$this->_data['row'] = $row =  $this->base->get_data('resource', array('id' => $id))->row_array();

				$this->_data['types'] = $this->db->query('SELECT t2.tid parent,t.tid child FROM mt_type t LEFT JOIN mt_type t2 ON t.parent=t2.tid WHERE t.tid='.$row['type'])->row_array();
			}
			$this->load->view('admin/resource_op', $this->_data);
		} else {
			$deal_data = array(
				'title'	=> $this->input->post('title'),
				'type'	=> $this->input->post('type'),
				'ctime'	=> strtotime($this->input->post('ctime')),
				'sort'	=> $this->input->post('sort')
			);

			if($_FILES['userfile']['size'] > 0) {
				$config['upload_path']		= './data/uploads/doc';
				$config['allowed_types']	= 'doc|docx|txt|pdf|rar|xls|xlsx|ppt|jpg|png|gif';
				$config['max_size']			= 10000;
				$config['max_width']		= '3000';
				$config['max_height']		= '3000';
				$config['encrypt_name']		= TRUE;
				$config['overwrite']		= FALSE;

				$this->load->library('upload', $config);

				if(!$this->upload->do_upload()) {
					$this->_data['upload_err'] = $this->upload->display_errors();
					$this->load->view('admin/resource_op', $this->_data);
				}
				$upload_data = $this->upload->data();
				$deal_data['doc'] = $upload_data['file_name'];
			}

			if ($id = $this->input->get('id')) {
				if ($this->base->update_data('resource', array('id' => $id), $deal_data)) {
					$this->msg->showmessage('更新成功', site_url('admin/resource/lists'));
				} else {
					$this->msg->showmessage('更新失败', site_url('admin/resource/op'));
				}
			}
			else {
				if ($this->base->insert_data('resource', $deal_data)) {
					$this->msg->showmessage('添加成功', site_url('admin/resource/lists'));
				} else {
					$this->msg->showmessage('添加失败', site_url('admin/resource/op'));
				}
			}
		}
    }

    /**
    * @deprecated 文章删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('resource', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }

	public function getType() {
		$id = $this->input->post('parent');
		$value = (int)$this->input->post('value');

		$downType = $this->base->get_data('type', array('cate'=>'doc', 'parent'=>$id))->result_array();
		$option = '';
		foreach($downType as $v) {
			$select = ($value && $v['tid'] == $value) ? 'selected' : '';
			$option .= '<option value='.$v['tid'].' '.$select.'>'.$v['name'].'</option>';
		}

		echo $option;exit;
	}
}