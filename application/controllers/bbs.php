<?php
/**
 * 工具类
 * @version 1.0.0 (Thu Feb 23 13:49:18 GMT 2012)
 * @author ZhangHao
 */
 
class Bbs extends CI_Controller {
	private $_data;
	
    public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
    }

	public function index() {

    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '咨询标题', 'required|trim');
		$this->form_validation->set_rules('name', '称呼', 'required|trim');
		$this->form_validation->set_rules('email', '邮箱', 'required|valid_email|trim');
		$this->form_validation->set_rules('captcha', '验证码', 'required|trim');
		$this->form_validation->set_rules('content', '咨询内容', 'required|min_length[5]|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($this->form_validation->run() == FALSE) { 
			$this->_data['captcha'] = $this->captcha();

			$keyword = $this->input->get('keyword') ? $this->input->get('keyword') : '';

			if($keyword) {
				$where = "WHERE title LIKE '%".$keyword."%'";
			} else {
				$where = '';
			}

			//分页配置
			$this->load->library('gpagination');
			$total_num = $this->db->query("SELECT * FROM lz_msg ".$where)->num_rows();
			$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
			$limit = 20;
			$offset = ($page - 1) * $limit;

			$this->gpagination->currentPage($page);
			$this->gpagination->items($total_num);
			$this->gpagination->limit($limit);
			$this->gpagination->target(site_url('bbs/index'));

			$this->_data['pagination'] = $this->gpagination->getOutput();


			$this->_data['lists'] = $this->db->query("SELECT * FROM lz_msg ".$where." ORDER BY replytime DESC LIMIT ".$offset.", ".$limit." ")->result_array();
			$this->load->view(THEME.'/header');
			$this->load->view(THEME.'/bbs', $this->_data);
			$this->load->view(THEME.'/footer');			
		} else {
			$deal_data = array(
				'content'	=> $this->input->post('content'),
				'email'		=> $this->input->post('email'),
				'title'		=> $this->input->post('title'),
				'name'		=> $this->input->post('name'),
				'replytime'	=> time(),
				'ctime'		=> time()
			);

			// 首先删除旧的验证码
			$expiration = time()-600; // 2小时限制
			$this->db->query("DELETE FROM lz_captcha WHERE captcha_time < ".$expiration); 

			// 然后再看是否有验证码存在:
			$sql = "SELECT COUNT(*) AS count FROM lz_captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
			$binds = array($_POST['captcha'], $this->input->ip_address(), $expiration);
			$row = $this->db->query($sql, $binds)->row();

			if ($row->count == 0) $this->msg->showmessage('验证码错误', site_url('bbs'));	

			$this->base->insert_data('msg', $deal_data);
			$this->msg->showmessage('添加成功', site_url('bbs'), 1000);		
		}	
	}

	public function viewMsg() {
		

    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '回复标题', 'required|trim');
		$this->form_validation->set_rules('name', '称呼', 'required|trim');
		$this->form_validation->set_rules('captcha', '验证码', 'required|trim');
		$this->form_validation->set_rules('content', '回复内容', 'required|min_length[5]|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($this->form_validation->run() == FALSE) { 
			$this->_data['captcha'] = $this->captcha();

			$id = intval($this->input->get_post('id'));
			$this->_data['row'] = $this->base->get_data('msg', array('id'=>$id))->row_array();

			$this->db->query("UPDATE lz_msg SET hits=hits+1 WHERE id=".$id);

			//分页配置
			$this->load->library('gpagination');
			$total_num = $this->base->get_data("reply")->num_rows();
			$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
			$limit = 20;
			$offset = ($page - 1) * $limit;

			$this->gpagination->currentPage($page);
			$this->gpagination->items($total_num);
			$this->gpagination->limit($limit);
			$this->gpagination->target(site_url('bbs/viewMsg?id='.$id));

			$this->_data['pagination'] = $this->gpagination->getOutput();

			$this->_data['replyList'] = $this->base->get_data('reply', array('mid'=>$id), '*', $limit, $offset, 'ctime ASC')->result_array();

			$this->load->view(THEME.'/header');
			$this->load->view(THEME.'/reply', $this->_data);
			$this->load->view(THEME.'/footer');			
		} else {
			$mid = intval($this->input->post('id'));
			$deal_data = array(
				'content'	=> $this->input->post('content'),
				'mid'		=> $mid,
				'title'		=> $this->input->post('title'),
				'name'		=> $this->input->post('name'),
				'ctime'		=> time()
			);

			// 首先删除旧的验证码
			$expiration = time()-600; // 2小时限制
			$this->db->query("DELETE FROM lz_captcha WHERE captcha_time < ".$expiration); 

			// 然后再看是否有验证码存在:
			$sql = "SELECT COUNT(*) AS count FROM lz_captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
			$binds = array($_POST['captcha'], $this->input->ip_address(), $expiration);
			$row = $this->db->query($sql, $binds)->row();

			if ($row->count == 0) $this->msg->showmessage('验证码错误', site_url('bbs/viewMsg?id='.$mid), 1);	
			
			if($this->base->insert_data('reply', $deal_data)) {
				$this->db->query('UPDATE lz_msg SET replynum = replynum+1 WHERE id='.$mid);
			}
			$this->msg->showmessage('添加成功', site_url('bbs/viewMsg?id='.$mid), 1);		
		}	

		

	}

	public function getCaptcha() {
		echo $this->captcha();
		exit();
	}

	public function captcha() {
		$this->load->helper('captcha');

		$vals = array(
			'word'		=> mt_rand(1000, 9999),
		    'img_path' 	=> './data/captcha/',
		    'img_url' 	=> base_url('data/captcha/').'/',
		    'expiration'=> 600

		);

		$cap = create_captcha($vals);

		$data = array(
		    'captcha_time' 	=> $cap['time'],
		    'ip_address' 	=> $this->input->ip_address(),
		    'word' 			=> $cap['word']
		);

		$query = $this->db->insert_string('captcha', $data);
		$this->db->query($query);
		return $cap['image'];		
	}

}