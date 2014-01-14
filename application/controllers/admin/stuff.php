<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 视频教案管理
* @see Content
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class Stuff extends CI_Controller
{
	private $_data;

    public function __construct()
    {
		parent::__construct();
		$this->_data['thisClass'] = $this->input->get('kind') ? $this->input->get('kind') : 'video';
		$this->_data['kinds'] = array('video'=>'视频', 'stuff'=>'教案');
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
		$this->_data['kind'] = $kind = $this->input->get('kind') ? $this->input->get('kind') : 'video';

		//分页配置
        $this->load->library('gpagination');
		$total_num = $this->base->get_data('stuff', array('kind'=>$kind))->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/stuff/lists?kind='.$kind));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('stuff', array('kind'=>$kind), '*', $limit, $offset, 'sort ASC, ctime DESC')->result_array();
        $this->load->view('admin/stuff_list', $this->_data);
    }

    /**
    * @deprecated 文章处理
    */
    public function op () {
		$this->_data['kind'] = $kind = $this->input->get_post('kind') ? $this->input->get_post('kind') : 'video';

    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '标题', 'required|trim');
		$this->form_validation->set_rules('kind', '分类', 'required|trim');
		$this->form_validation->set_rules('ctime', '创建时间', 'required|trim');
		$this->form_validation->set_rules('authorid', '作者', 'required|trim');
		if($kind == 'stuff') {

		} else {
			$this->form_validation->set_rules('fname', '视频', 'required|trim');
		}
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');


		if ($this->form_validation->run() == FALSE) {
			$this->load->helper('file');
			$this->_data['type_list'] = $this->base->get_data('type', array('type'=>$kind))->result_array();
			$this->_data['authorlist'] = $this->base->get_data('author')->result_array();

			if ($id = $this->input->get('id')) {
				if($kind == 'video') {
					$rows = $this->db->query('SELECT t.name FROM ab_video_tag vt LEFT JOIN ab_tag t ON vt.tid=t.id WHERE vt.vid='.$id)->result_array();
					foreach($rows as $v) {
						$tags[] = $v['name'];
					}
					$this->_data['tags'] = implode(' ', $tags);
				}
				$this->_data['content'] = $this->base->get_data('stuff', array('id'=>$id, 'kind'=>$kind))->row_array();
			}
			$this->load->view('admin/stuff_op', $this->_data);
		} else {
			$id = $this->input->get('id') ? (int)$this->input->get('id') : 0;
			$timestamp = time();
			$filename = uniqid();
			$dirname = './data/uploads/stuff/'.date('Y/m/');
			createFolder($dirname);

			$deal_data = array(
				'title'		=> $this->input->post('title'),
				'kind'		=> $kind,
				'type'		=> $this->input->post('type'),
				'sort'		=> $this->input->post('sort'),
				'authorid'	=> $this->input->post('authorid'),
				'is_free'	=> $this->input->post('is_free'),
				'ctime'		=> strtotime($this->input->post('ctime')),
				'mtime'		=> $timestamp
			);

			if($_FILES['userfile']['size'] > 0) {
				$config['upload_path']		= $dirname;
				$config['allowed_types']	= 'gif|jpg|png';
				$config['file_name']		= $filename;
				$config['max_size']			= '5000';
				$config['max_width']		= '3000';
				$config['max_height']		= '3000';
				$config['overwrite']		= FALSE;

				$this->load->library('upload', $config);

				if(!$this->upload->do_upload()) {
					$this->_data['upload_err'] = $this->upload->display_errors();
					$this->load->view('admin/stuff_op', $this->_data);
				}
				$upload_data = $this->upload->data();

				$config2['source_image']	= $upload_data['full_path'];
				$config2['maintain_ratio']	= FALSE;
				$config2['width']			= 172;
				$config2['height']			= 128;

				$this->load->library('image_lib', $config2);
				$this->image_lib->resize();

				$deal_data['filepic'] = date('Y/m/').$upload_data['file_name'];
			}

			if($kind == 'video') {
				$fname = $filename.'.'.pathinfo($this->input->post('fname'), PATHINFO_EXTENSION);
				if(copy('./data/tmp/'.$this->input->post('fname'), $dirname.$fname)) {
					unlink('./data/tmp/'.$this->input->post('fname'));
				}
				$deal_data['filename'] = date('Y/m/').$fname;
				$deal_data['realname'] = $this->input->post('fname');
			} elseif($kind == 'stuff') {
				$deal_data['content'] = $this->input->post('content');
			}

			if($id) {
				$this->base->update_data('stuff', array('id' => $id), $deal_data);
				if($kind == 'stuff') $this->base->del_data('attach', array('relaid'=>$id, 'kind'=>'stuff'));
			} else {
				$id = $this->base->insert_data('stuff', $deal_data);
				if($kind == 'video') {
					$tag = array_filter(explode(' ', $this->input->post('tag')));
					if($tag) {
						foreach($tag as $v) {
							$tagrow = $this->base->get_data('tag', array('name'=>$v), 'id')->row_array();
							if(!$tagrow) {
								$tid = $this->base->insert_data('tag', array('name'=>$v));
							} else {
								$tid = $tagrow['id'];
							}
							$this->base->insert_data('video_tag', array('vid'=>$id, 'tid'=>$tid));
						}
					}
				}

			}

			if($kind == 'stuff') {
				$attach = $this->input->post('attach');
				$attachdir = './data/uploads/attach/'.date('Y/m/');
				createFolder($attachdir);
				$values = '';
				foreach($attach as $v) {
					$size = (int)(filesize('./data/tmp/'.$v)/1024);
					if(copy('./data/tmp/'.$v, $attachdir.uniqid().'.'.pathinfo($v, PATHINFO_EXTENSION))) {
						unlink('./data/tmp/'.$v);
					}

					$values .= "(".$id.", 'stuff', '".$v."', '".$realname."', ".$size.",".$timestamp."),";
				}
				$values = substr($values, 0, -1);
				$this->db->query("INSERT INTO ab_attach (`relaid`, `kind`, `filename`, `realname`, `filesize`, `ctime`) VALUES ".$values);
			}

			$this->msg->showmessage('添加成功', site_url('admin/stuff/lists?kind='.$kind));
		}
    }

    /**
    * @deprecated 文章删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('stuff', array('id' => $id))) {
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