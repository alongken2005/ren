<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @deprecated 权限
* @see Power_mdl
* @version 1.0.0 (Wed Feb 08 07:53:54 GMT 2012)
* @author ZhangHao
*/
class Power_mdl extends CI_Model {
	
	private $table;
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
    * @deprecated 数据插入
    */
    public function insert_data($insert_data)
    {
    	$this->db->insert($this->db->dbprefix('power'), $insert_data);
    	return $this->db->insert_id();
    }
    
    /**
    * @deprecated 数据更新
    */
    public function update_data ($where = array(), $update_data = array())
    {
    	$this->db->where($where);
        return $this->db->update($this->db->dbprefix('power'), $update_data);
    }
    
    /**
    * @deprecated 获取列表
    */
    public function get_data($where = array())
    {
        $this->db->where($where);
        return $this->db->get($this->db->dbprefix('power'));
    }
    
    /**
    * @deprecated 获取结构列表
    */
    public function get_list ()
    {
        $parents = $this->get_data(array('parent' => 0))->result_array();
    	$powers = $this->get_data(array('parent > ' => 0))->result_array();
    	$lists = array();

    	foreach ($parents as $pt)
    	{
    		$lists[$pt['pid']] = $pt;
    		$lists[$pt['pid']]['child'] = array();
    	}
    	
	    foreach ($powers as $pw)
		{
			$lists[$pw['parent']]['child'][] = $pw;
		}
		
		return $lists;
    }
}