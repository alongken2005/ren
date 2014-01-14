<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//客服设置
$config['service']['worktime']	= '9:00-22:00';
$config['service']['servtime']	= '周四上午9点-10点';
$config['service']['weekendtime']	= '周日';
$config['service']['tel']		= '0571-85379260';
$config['service']['weixin']		= 'weixinhao';
$config['service']['qqgroup'] = array(
	array('name'=>'312312312', 'full'=>false),
	array('name'=>'312318312', 'full'=>false),
	array('name'=>'312345312', 'full'=>false),
	array('name'=>'312312312', 'full'=>false),
	array('name'=>'312315552', 'full'=>true),
	array('name'=>'456632312', 'full'=>true),
);

//文章内容分类设置
$config['ctype'] = array(
	'depart' 	=> '科室简介',
	'tese' 		=> '特色医疗',
	'dongt' 	=> '科室动态',
	'jibing' 	=> '疾病介绍',
	'bingli' 	=> '成功病例',
	'kepu' 		=> '健康科普',
	'zhinan' 	=> '就医指南',
);
?>