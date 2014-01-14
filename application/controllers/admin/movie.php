<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 视频教案管理
* @see Content
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class Movie extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();
		$this->_data['thisClass'] = $this->input->get('kind') ? $this->input->get('kind') : 'video';
		$this->load->model('base_mdl', 'base');
		$this->permission->power_check();

		$this->load->config('common');
		$this->_data['areas'] = $this->config->item('area');
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
		$total_num = $this->base->get_data('movie')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/movie/lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('movie', array(), '*', $limit, $offset, 'sort DESC, ctime DESC')->result_array();
        $this->load->view('admin/movie_list', $this->_data);
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
				$tags = array();
				$rows = $this->db->query('SELECT t.name FROM ab_movie_tag vt LEFT JOIN ab_tag t ON vt.tid=t.id WHERE vt.mid='.$id)->result_array();
				foreach($rows as $v) {
					$tags[] = $v['name'];
				}
				$this->_data['tickets'] = $this->base->get_data('mticket', array('mid'=>$id), '*', 0, 0, 'stime DESC')->result_array();
				$this->_data['tags'] = implode(' ', array_unique($tags));
				$this->_data['row'] = $this->base->get_data('movie', array('id'=>$id))->row_array();
			}
			$this->load->view('admin/movie_op', $this->_data);
		} else {
			$id = $this->input->get('id') ? (int)$this->input->get('id') : 0;
			$timestamp = time();
			$dirname = './data/uploads/pics/'.date('Y/m/');
			createFolder($dirname);

			$deal_data = array(
				'title'		=> $this->input->post('title'),
				'score'		=> $this->input->post('score'),
				'intro'		=> $this->input->post('intro'),
				'area'		=> $this->input->post('area') ? implode(',', $this->input->post('area')) : '',
				'producer'	=> $this->input->post('producer'),
				'sort'		=> $this->input->post('sort'),
				'stime'		=> strtotime($this->input->post('stime')),
				'ctime'		=> $timestamp,
			);

			$config = array(
				'upload_path'	=> $dirname,
				'allowed_types'	=> 'gif|jpg|png',
				'max_size'		=> 5000,
				'max_width'		=> 3000,
				'max_height'	=> 3000,
				'encrypt_name'	=> true,
			);

			$this->load->library('upload', $config);

			if($_FILES['cover1']['size'] > 0) {
				if(!$this->upload->do_upload('cover1')) {
					$this->_data['upload_err1'] = $this->upload->display_errors();
					$this->load->view('admin/mview_op', $this->_data);
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
					$this->load->view('admin/movie_op', $this->_data);
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
				$deal_data['movie'] = date('Y/m/').$fname;
				$deal_data['is_local'] = 1;
			} elseif($this->input->post('is_local') == 'online' && $ol) {
				$deal_data['movie'] = $ol;
				$deal_data['is_local'] = 0;
			}

			if($id) {
				$this->base->del_data('movie_tag', array('mid'=>$id));
				$this->base->update_data('movie', array('id' => $id), $deal_data);
			} else {
				$id = $this->base->insert_data('movie', $deal_data);
			}

			$stime_n = $this->input->post('stime_n');
			$total_n = $this->input->post('total_n');
			$batch = array();
			if($stime_n) {
				foreach($stime_n as $k=>$v) {
					$batch[] = array('mid'=>$id, 'stime'=>strtotime($v), 'total'=>$total_n[$k]);
				}
				if(count($batch) > 0) $this->db->insert_batch('mticket', $batch);
			}
			$stime_o = $this->input->post('stime_o');
			$total_o = $this->input->post('total_o');
			if($stime_o) {
				foreach($stime_o as $k=>$v) {
					$this->base->update_data('mticket', array('id'=>$k), array('stime'=>strtotime($v), 'total'=>$total_o[$k]));
				}
			}

			$tag = array_filter(explode(' ', $this->input->post('tag')));
			if($tag) {
				foreach($tag as $v) {
					$tagrow = $this->base->get_data('tag', array('name'=>$v), 'id')->row_array();
					if(!$tagrow) {
						$tid = $this->base->insert_data('tag', array('name'=>$v));
					} else {
						$tid = $tagrow['id'];
					}
					$this->base->insert_data('movie_tag', array('mid'=>$id, 'tid'=>$tid));
				}
			}

			$this->msg->showmessage('操作成功', site_url('admin/movie/lists'));
		}
    }

    /**
    * @deprecated 文章删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('movie', array('id' => $id))) {
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

	public function ticket_del() {
		$id = $this->input->get('id');
		$this->base->del_data('mticket', array('id'=>$id));
		echo 'ok';
	}

	/**
	 * 索票记录
	 */
	public function ticketlog() {
		//分页配置
        $this->load->library('gpagination');
		$total_num = $this->base->get_data('mticket_log')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/movie/ticketlog'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->db->query('SELECT m.title, t.stime, l.uniqid, l.email, l.state, l.num, l.ctime FROM ab_mticket_log l LEFT JOIN ab_mticket t ON l.tid=t.id LEFT JOIN ab_movie m ON l.mid=m.id ORDER BY l.ctime DESC LIMIT '.$offset.', '.$limit)->result_array();
		$this->load->view('admin/ticketlog_list', $this->_data);
	}
}