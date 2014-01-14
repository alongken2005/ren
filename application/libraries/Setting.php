<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting {

    private $_ci;

    private $settings_autoloaded;
    private $settings = array();
    private $settings_group = array();
    private $settings_db;

    public function __construct() {
        $this->_ci = &get_instance();
        $this->settings_db = $this->_ci->config->item('settings_table');
        
        $this->autoload();
    }

    // ------------------------------------------------------------------------
    // 华丽的分割线 正式开始
    // ------------------------------------------------------------------------

    /**
     * 从数据库获取所有自动加载的设置
     */
    public function autoload() {
        //如果存在则直接返回
        if (!empty($this->settings)) {
            return $this->settings;
        }

        //如果系统不存在数据表则返回false
        if (!$this->_ci->db->table_exists($this->settings_db)) {
            return FALSE;
        }

        //查询标记为自动加载的项
        $this->_ci->db->select('key,value')->from($this->settings_db)->where('autoload', 'yes');

        $query = $this->_ci->db->get();

        if ($query->num_rows() == 0) {
            return FALSE;
        }

        //循环写入系统配置
        foreach ($query->result() as $k => $row) {
            $this->settings[$row->key] = $row->value;
            $this->_ci->config->set_item($row->key, $row->value);
        }

        //标记会话，避免重复读库
        //$this->_ci->session->set_userdata('settings_autoloaded', TRUE);

        return $this->settings;
    }

    // ------------------------------------------------------------------------

    /**
     * 获取单个设定
     *
     * <code>
     * <?php $this->setting->item('config_item');
     ?>
     * </code>
     */
    public function item($key) {
        if (!$key) {
            return FALSE;
        }

        //首先检查是否系统已经自动加载
        if (isset($this->settings[$key])) {
            return $this->settings[$key];
        }

        //查询数据库
        $this->_ci->db->select('value')->from($this->settings_db)->where('key', $key);

        $query = $this->_ci->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $this->settings[$key] = $row->value;

            return $row->value;
        }

        // 查询不到结果则查找系统config，返回值或者false
        return $this->_ci->config->item($key);
    }

    // ------------------------------------------------------------------------

    /**
     * 获取组配置
     */
    public function group($group = '') {
        if (!$group) {
            return FALSE;
        }

        $this->_ci->db->select('key,value')->from($this->settings_db)->where('group', $group);

        $query = $this->_ci->db->get();

        if ($query->num_rows() == 0) {
            return FALSE;
        }

        foreach ($query->result() as $k => $row) {
            $this->settings[$row->key] = $row->value;
            $arr[$row->key] = $row->value;
        }

        return $arr;
    }

    // ------------------------------------------------------------------------

    /**
     * 更改设置
     */
    public function edit($key, $value) {
        $this->_ci->db->where('key', $key);
        $this->_ci->db->update($this->settings_db, array('value' => $value));

        if ($this->_ci->db->affected_rows() == 0) {
            return FALSE;
        }

        return TRUE;
    }

    // ------------------------------------------------------------------------

    /**
     * 新增设置
     */
    public function insert($key, $value = '', $group = 'addon', $autoload = 'no') {
        // 检查是否已经被添加的设置
        $this->_ci->db->select('value')->from($this->settings_db)->where('key', $key);

        $query = $this->_ci->db->get();

        if ($query->num_rows() > 0) {
            return $this->edit($key, $value);
        }

        $data = array('key' => $key, 'value' => $value, 'group' => $group, 'autoload' => $autoload, );

        $this->_ci->db->insert($this->settings_db, $data);

        if ($this->_ci->db->affected_rows() == 0) {
            return FALSE;
        }

        return TRUE;
    }

    // ------------------------------------------------------------------------

    /**
     * 删除设置
     */
    public function delete($key) {
        $this->_ci->db->delete($this->settings_db, array('key' => $key));

        if ($this->_ci->db->affected_rows() == 0) {
            return FALSE;
        }

        return TRUE;
    }

    // ------------------------------------------------------------------------

    /**
     * 删除设置组及成员配置
     */
    public function delete_group($group) {
        $this->_ci->db->delete($this->settings_db, array('group' => $group));

        if ($this->_ci->db->affected_rows() == 0) {
            return FALSE;
        }

        return TRUE;
    }

}