<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @deprecated 图片管理
* @see Pic
* @version 12-3-28 下午8:48
* @author ZhangHao
*/
class Pic extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
		$this->permission->power_check();

		$this->load->config('common');
		$this->_data['typelist'] = $this->config->item('picType');
    }

    /**
    * @deprecated 默认方法
    */
    public function index ()
    {
        self::pic();
    }

    /**
    * @deprecated 图片管理
    */
    public function lists ()
    {
		//分页配置
        $this->load->library('gpagination');
		$total_num = $this->db->query('SELECT id FROM lz_pics WHERE place IN('.implode(',', array_keys($this->_data['typelist'])).')')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/pic/lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->db->query('SELECT * FROM lz_pics WHERE place IN('.implode(',', array_keys($this->_data['typelist'])).') ORDER BY sort DESC, id DESC LIMIT '.$offset.','.$limit)->result_array();
        $this->load->view('admin/pic_list', $this->_data);
    }

    /**
    * @deprecated 图片处理
    */
    public function op ()
    {
		if (!$_POST) {
			if ($id = $this->input->get('id')) {
				$this->_data['pic'] = $this->base->get_data('pics', array('id' => $id))->row_array();
			}
			$this->load->view('admin/pic_op', $this->_data);
		} else {
			$ac = $this->input->get_post('ac');

			$deal_data['title']		= $this->input->post('title');
			$deal_data['place']		= $this->input->post('place');
			$deal_data['sort']		= $this->input->post('sort');
			$deal_data['url']		= $this->input->post('url');
			$deal_data['ctime']		= time();

			$dirname = './data/uploads/pics/'.date('Y/m/');
			createFolder($dirname);

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
					$this->load->view('admin/pic_op', $this->_data);
				}

				$upload_data = $this->upload->data();
				$config2 = array(
					'create_thumb'	=> true,
					'source_image'	=> $upload_data['full_path'],
					'maintain_ratio'=> true,
					'width'			=> 200,
					'height'		=> 135
				);

				$this->load->library('image_lib', $config2);
				$this->image_lib->resize();

				$deal_data['filename'] = date('Y/m/').$upload_data['file_name'];
			}

			if ($id = $this->input->get('id')) {
				$this->base->update_data('pics', array('id' => $id), $deal_data);
			} else {
				$this->base->insert_data('pics', $deal_data);
			}

			$this->msg->showmessage('操作完成', site_url('admin/pic/lists'));
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
}