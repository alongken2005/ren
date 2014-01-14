<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Logging Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Logging
 * @author		Zhang Hao
 */
class CI_Log {

	protected $_message		= '';
	protected $_log_path	= '';
	protected $_threshold	= 1;
	protected $_date_fmt	= 'Y-m-d H:i:s';
	protected $_enabled		= TRUE;
	protected $_levels		= array('ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'ALL' => '4');

	/**
	 * Constructor
	 */
	public function __construct() {
		$config =& get_config();

		//析构函数中运行在apache时，当前工作目录会变为apache的，所以用绝对路径
		$this->_log_path = ($config['log_path'] != '') ? $config['log_path'] : FCPATH.APPPATH.'logs/';

		if ( ! is_dir($this->_log_path) OR ! is_really_writable($this->_log_path)) {
			$this->_enabled = FALSE;
		}

		if (is_numeric($config['log_threshold'])) {
			$this->_threshold = $config['log_threshold'];
		}

		if ($config['log_date_format'] != '') {
			$this->_date_fmt = $config['log_date_format'];
		}
	}

	/**
	 * @deprecated 释放实例化时一次性写入，减少IO，提高效率
	 */
	public function __destruct() {
		if($this->_message != '') {
			file_put_contents($this->_log_path.'log-'.date('Y-m-d').'.php', $this->_message, FILE_APPEND);
		}
	}

	/**
	 * @deprecated 追加日志
	 */
	public function write_log($level = 'error', $msg) {
		$level = strtoupper($level);

		if ($this->_enabled === FALSE) {
			return FALSE;
		}

		if ( !isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold)) {
			return FALSE;
		}

		$this->_message .= $level.' - '.date($this->_date_fmt). ' --> '.$msg."\n";
	}
}