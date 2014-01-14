<?php
/**
 * 工具类
 * @version 1.0.0 (Thu Feb 23 13:49:18 GMT 2012)
 * @author ZhangHao
 */
 
class Index extends CI_Controller {
	private $_data;
	
    public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
		$this->_data['ctypeList'] = $this->config->item('ctype');
    }

	public function index() {

		$type = $this->base->get_data('type')->result_array();

		foreach ($type as $v) {
			$tlist[$v['type']][] = $v['id'];
		}


		$this->_data['doctor'] = $this->base->get_data('doctor', array(), '*', 0, 0, 'dis ASC, id DESC')->result_array();

		$this->_data['intro'] = $this->base->get_data('content', array('tid'=>1), '*', 0, 0, 'id ASC')->row_array();
		$this->_data['tese'] = $this->db->query('SELECT * FROM lz_content WHERE tid IN('.implode(',', $tlist['tese']).') LIMIT 0, 5')->result_array();
		$this->_data['dongt'] = $this->db->query('SELECT * FROM lz_content WHERE tid IN('.implode(',', $tlist['dongt']).') LIMIT 0, 5')->result_array();
		$this->_data['bingli'] = $this->db->query('SELECT * FROM lz_content WHERE tid IN('.implode(',', $tlist['bingli']).') LIMIT 0, 5')->result_array();
		$this->load->view(THEME.'/header', $this->_data);
		$this->load->view(THEME.'/index', $this->_data);
		$this->load->view(THEME.'/footer');
	}

	public function lists() {
		$ctype = $this->input->get('ctype');
		$tid = intval($this->input->get('tid'));


		if($ctype) {
			$result = $this->base->get_data('type', array('type'=>$ctype), '*', 0, 0, 'dis ASC, id ASC')->row_array();
			$tid = $result['id'];
		}

		$where = array('tid'=>$tid);

		//分页配置
		$this->load->library('gpagination');
		$total_num = $this->base->get_data('content', $where)->num_rows();
		$page = $this->input->get('page') > 1 ? $this->input->get('page') : '1';
		$limit = 20;
		$offset = ($page - 1) * $limit;

		$this->gpagination->currentPage($page);
		$this->gpagination->items($total_num);
		$this->gpagination->limit($limit);
		$this->gpagination->target(site_url('index/lists?tid='.$tid));

		$this->_data['pagination'] = $this->gpagination->getOutput();

		if($tid) {
			$this->_data['lists'] = $this->base->get_data('content', $where, '*', $limit, $offset, 'dis ASC, id DESC')->result_array();
			$result = $this->base->get_data('type', array('id'=>$tid))->row_array();
			$ctype = $result['type'];
		}

		$ctype = $ctype ? $ctype : 'depart';
		$this->_data['ctype'] = $ctype;
		$this->_data['typeList'] = $this->base->get_data('type', array('type'=>$ctype))->result_array();

		$this->load->view(THEME.'/header');
		$this->_data['slider'] = $this->load->view(THEME.'/slider', $this->_data, true);
		$this->load->view(THEME.'/lists', $this->_data);
		$this->load->view(THEME.'/footer');			

	}

	public function detail() {
		$id = $this->input->get('id');
		$this->_data['row'] = $row = $this->base->get_data('content', array('id'=>$id))->row_array();
		$tid = $row['tid'];

		if($tid) {
			$result = $this->base->get_data('type', array('id'=>$tid))->row_array();
			$ctype = $result['type'];
		}


		$this->db->query('UPDATE lz_content SET hits=hits+1 WHERE id='.$id);

		$ctype = $ctype ? $ctype : 'depart';
		$this->_data['ctype'] = $ctype;
		$this->_data['typeList'] = $this->base->get_data('type', array('type'=>$ctype))->result_array();

		$this->load->view(THEME.'/header');
		$this->_data['slider'] = $this->load->view(THEME.'/slider', $this->_data, true);
		$this->load->view(THEME.'/detail', $this->_data);
		$this->load->view(THEME.'/footer');		
	}

	public function doclist() {
		$this->_data['lists'] = $this->base->get_data('doctor', array(), '*', 0, 0, 'dis ASC, id DESC')->result_array();

		$this->_data['type'] = 'list';
		$this->load->view(THEME.'/header');
		$this->load->view(THEME.'/doctor', $this->_data);
		$this->load->view(THEME.'/footer');			
	}

	public function docdetail() {
		$id = $this->input->get('id');

		$this->_data['lists'] = $this->base->get_data('doctor', array(), '*', 0, 0, 'dis ASC, id DESC')->result_array();
		$this->_data['row'] = $this->base->get_data('doctor', array('id'=>$id))->row_array();

		$this->_data['type'] = 'detail';
		$this->load->view(THEME.'/header');
		$this->load->view(THEME.'/doctor', $this->_data);
		$this->load->view(THEME.'/footer');	
	}
}