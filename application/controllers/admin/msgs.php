<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @desc 文字管理
* @see Content
* @version 1.0.0 (Tue Feb 21 08:17:46 GMT 2012)
* @author ZhangHao
*/
class Msgs extends CI_Controller
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
		$total_num = $this->base->get_data('msg')->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('admin/msg/lists'));

		$this->_data['pagination'] = $this->gpagination->getOutput();
		$this->_data['lists'] = $this->base->get_data('msg', array(), '*', $limit, $offset, 'ctime DESC')->result_array();
        $this->load->view('admin/msg_list', $this->_data);
    }

    /**
    * @deprecated 文章处理
    */
    public function op () {
    	//验证表单规则
		$this->load->library('form_validation');
		$this->form_validation->set_rules('reply', '邮件内容', 'required|trim');
		$this->form_validation->set_rules('retitle', '邮件标题', 'required|trim');
		$this->form_validation->set_error_delimiters('<span class="err">', '</span>');

		if ($this->form_validation->run() == FALSE) {
			if ($id = $this->input->get_post('id')) {
				$this->_data['row'] = $this->base->get_data('msg', array('id' => $id))->row_array();
			}
			$this->load->view('admin/msg_op', $this->_data);
		} else {
			$reply = $this->input->post('reply');
			$retitle = $this->input->post('retitle');

			if ($id = $this->input->get('id')) {
				$row = $this->base->get_data('msg', array('id' => $id))->row_array();
				$this->load->config('email');
				$configemail = $this->config->item('smtp');
				$this->load->library('email');

				$this->email->initialize($configemail);
				$this->email->from('hangzhouzetian@gmail.com', '杭州泽天科技有限公司');
				$this->email->to($row['email']);
				$this->email->subject($retitle);
				$message_file = $this->load->view('email_demo', array('reply'=>$reply, 'msg'=>$row['msg']), TRUE);
				$this->email->message($message_file);
				if($this->email->send()) {
					$deal_data = array(
						'reply' 	=> $reply,
						'retitle' 	=> $retitle,
						'status' 	=> 1,
						'rtime'		=> time(),
					);
					$this->base->update_data('msg', array('id' => $id), $deal_data);
					$this->msg->showmessage('发送成功', site_url('admin/msgs/lists'));
				} else {
					$deal_data = array(
						'reply' 	=> $reply,
						'retitle' 	=> $retitle,
						'rtime'		=> time(),
					);
					$this->base->update_data('msg', array('id' => $id), $deal_data);
					$this->msg->showmessage('发送失败', site_url('admin/msgs/lists'));
				}
			}
		}
    }

    /**
    * @deprecated 文章删除
    */
    public function del () {
        $id = intval($this->input->get('id'));
        if($id && $this->base->del_data('msg', array('id' => $id))) {
        	exit('ok');
        } else {
        	exit('no');
        }
    }
}