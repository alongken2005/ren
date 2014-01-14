<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * 基本数据库操作
 * @see Base_mdl
 * @version 1.0.0 (Thu Feb 16 07:12:49 GMT 2012)
 * @author ZhangHao
 */
class Base_mdl extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 数据插入
	 */
	public function insert_data($table, $insert_data) {
		$this->db->insert($this->db->dbprefix($table), $insert_data);
		return $this->db->insert_id();
	}

	/**
	 * 数据更新
	 */
	public function update_data($table, $where = array(), $update_data = array()) {
		$this->db->where($where);
		return $this->db->update($this->db->dbprefix($table), $update_data);
	}

	/**
	 * 数据删除
	 */
	public function del_data($table, $where = array()) {
		return $this->db->delete($this->db->dbprefix($table), $where);
	}

	/**
	 * 获取列表
	 */
	public function get_data($table, $where = array(), $select = '*', $limit = 0, $offset = 0, $order = '') {
		$this->db->select($select);
		$this->db->where($where);
		if ($limit) {
			$this->db->limit($limit, $offset);
		}
		if ($order) {
			$this->db->order_by($order);
		}
		return $this->db->get($this->db->dbprefix($table));
	}

}