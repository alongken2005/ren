<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 视频教案管理
* @see Content
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class Mimage extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
		$this->permission->power_check();
		//$this->output->enable_profiler(TRUE);
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
		$total_num = $this->base->get_data('pics', array('place'=>2))->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/mimage/lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('pics', array('place'=>2), '*', $limit, $offset, 'id DESC')->result_array();
        $this->load->view('admin/mimage_list', $this->_data);
    }

    /**
    * @deprecated 文章处理
    */
    public function op() {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '标题', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($this->form_validation->run() == FALSE) {
			$this->load->helper('file');
			if ($id = $this->input->get('id')) {
				$this->_data['row'] = $this->base->get_data('pics', array('id'=>$id))->row_array();
			}
			$this->_data['movies'] = $this->base->get_data('movie', array(), '*', 0, 0, 'sort DESC, ctime DESC')->result_array();
			$this->load->view('admin/mimage_op', $this->_data);
		} else {
			$id = $this->input->get('id') ? (int)$this->input->get('id') : 0;

			$dirname = './data/uploads/pics/'.date('Y/m/');
			createFolder($dirname);

			$deal_data = array(
				'title'		=> $this->input->post('title'),
				'place'		=> 2,
				'place_id'	=> $this->input->post('mid'),
				'sort'		=> $this->input->post('sort'),
				'ctime'		=> time(),
			);

			if($_FILES['userfile']['size'] > 0) {
				$config = array(
					'upload_path'	=> $dirname,
					'allowed_types'	=> 'gif|jpg|png',
					'max_size'		=> 5000,
					'max_width'		=> 3000,
					'max_height'	=> 3000,
					'encrypt_name'	=> true,
				);

				$this->load->library('upload', $config);

				if(!$this->upload->do_upload()) {
					$this->_data['upload_err'] = $this->upload->display_errors();
					$this->load->view('admin/mimage_op', $this->_data);
				}
				$upload_data = $this->upload->data();

				$config2 = array(
					'create_thumb'	=> true,
					'source_image'	=> $upload_data['full_path'],
					'maintain_ratio'=> true,
					'width'			=> 240,
					'height'		=> 135
				);

				$this->load->library('image_lib', $config2);
				$this->image_lib->resize();

				$deal_data['filename'] = date('Y/m/').$upload_data['file_name'];
			}

			if($id) {
				$this->base->update_data('pics', array('id' => $id), $deal_data);
			} else {
				$id = $this->base->insert_data('pics', $deal_data);
			}

			$this->msg->showmessage('添加成功', site_url('admin/mimage/lists'));
		}
    }

    /**
    * @deprecated 文章删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('pics', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }

	public function file_del() {
		$id = $this->input->get('id');
		$type = $this->input->get('type');
		$pro = $this->base->get_data('stuff', array('id'=>$id))->row_array();
		if($type == 'img') {
			if(unlink('./data/uploads/stuff/'.$pro['filepic'])) {
				$this->base->update_data('stuff', array('id'=>$id), array('filepic'=>''));
				echo 'ok';
			} else {
				echo 'no';
			}
		} elseif($type == 'video') {
			if(unlink('./data/uploads/stuff/'.$pro['filename'])) {
				$this->base->update_data('stuff', array('id'=>$id), array('filename'=>''));
				echo 'ok';
			} else {
				echo 'no';
			}
		}
	}


}