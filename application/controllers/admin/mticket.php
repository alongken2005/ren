<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 电影片花
* @see Content
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class Mticket extends CI_Controller
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
		$total_num = $this->base->get_data('mclips')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/mclips/lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('mclips', array(), '*', $limit, $offset, 'sort DESC, id DESC')->result_array();
        $this->load->view('admin/mclips_list', $this->_data);
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
			$this->load->helper('file');
			if ($id = $this->input->get('id')) {
				$this->_data['row'] = $this->base->get_data('mclips', array('id'=>$id))->row_array();
			}
			$this->_data['movies'] = $this->base->get_data('movie', array(), '*', 0, 0, 'sort DESC, ctime DESC')->result_array();
			$this->load->view('admin/mclips_op', $this->_data);
		} else {
			$id = $this->input->get('id') ? (int)$this->input->get('id') : 0;
			$timestamp = time();
			$dirname = './data/uploads/pics/'.date('Y/m/');
			createFolder($dirname);

			$deal_data = array(
				'title'		=> $this->input->post('title'),
				'mid'		=> $this->input->post('mid'),
				'sort'		=> $this->input->post('sort'),
			);

			$config['upload_path']		= $dirname;
			$config['allowed_types']	= 'gif|jpg|png';
			$config['max_size']			= '5000';
			$config['max_width']		= '3000';
			$config['max_height']		= '3000';
			$config['encrypt_name']		= true;

			$this->load->library('upload', $config);

			if($_FILES['cover1']['size'] > 0) {
				if(!$this->upload->do_upload('cover1')) {
					$this->_data['upload_err1'] = $this->upload->display_errors();
					$this->load->view('admin/mclips_op', $this->_data);
				}
				$upload_data = $this->upload->data();
				$config2 = array(
					'create_thumb'	=> true,
					'source_image'	=> $upload_data['full_path'],
					'maintain_ratio'=> false,
					'width'			=> 225,
					'height'		=> 300
				);

				$this->load->library('image_lib', $config2);
				$this->image_lib->resize();
				$deal_data['cover1'] = date('Y/m/').$upload_data['file_name'];
			}

			if($_FILES['cover2']['size'] > 0) {
				if(!$this->upload->do_upload('cover2')) {
					$this->_data['upload_err2'] = $this->upload->display_errors();
					$this->load->view('admin/mclips_op', $this->_data);
				}
				$upload_data = $this->upload->data();
				$config2 = array(
					'create_thumb'	=> true,
					'source_image'	=> $upload_data['full_path'],
					'maintain_ratio'=> false,
					'width'			=> 240,
					'height'		=> 135
				);

				$this->load->library('image_lib', $config2);
				$this->image_lib->resize();
				$deal_data['cover2'] = date('Y/m/').$upload_data['file_name'];
			}

			$lo = $this->input->post('local');
			$ol = $this->input->post('online');
			if($this->input->post('is_local') == 'local' && $lo) {
				$stuffdir = './data/uploads/stuff/'.date('Y/m/');
				createFolder($stuffdir);
				$fname = uniqid().'.'.pathinfo($lo, PATHINFO_EXTENSION);
				if(copy('./data/tmp/'.$lo, $stuffdir.$fname)) {
					unlink('./data/tmp/'.$lo);
				}
				$deal_data['video'] = date('Y/m/').$fname;
				$deal_data['is_local'] = 1;
			} elseif($this->input->post('is_local') == 'online' && $ol) {
				$deal_data['video'] = $this->input->post('online');
				$deal_data['is_local'] = 0;
			}

			if($id) {
				$this->base->update_data('mclips', array('id' => $id), $deal_data);
			} else {
				$id = $this->base->insert_data('mclips', $deal_data);
			}

			$this->msg->showmessage('添加成功', site_url('admin/mclips/lists'));
		}
    }

    /**
    * @deprecated 文章删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('mclips', array('id' => $id))) {
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