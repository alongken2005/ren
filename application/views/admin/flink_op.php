<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>友情链接<?=intval($this->input->get('fid')) ? '修改' : '添加'?><div class="operate"><a href="<?=site_url('admin/flink/lists')?>">管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/flink/op'.(intval($this->input->get('fid')) ? '?fid='.intval($this->input->get('fid')) : ''))?>" method="POST" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th><b>*</b> 链接名称：</th>
			<td>
				<input type="text" name="name" value="<?=set_value('name', isset($flink['name']) ? $flink['name'] : '')?>" class="input2"/>
				<?php if(form_error('name')) { echo form_error('name'); } ?>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 链接地址：</th>
			<td>
				<input type="text" name="url" value="<?=set_value('url', isset($flink['url']) ? $flink['url'] : '')?>" class="input2"/>
				<?php if(form_error('url')) { echo form_error('url'); } ?>
				<span class="red">必须以 http:// 开头</span>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 链接排序：</th>
			<td>
				<input type="text" name="order" value="<?=set_value('order', isset($flink['order']) ? $flink['order'] : 0)?>" class="input4"/>
				<?php if(form_error('order')) { echo form_error('order'); } ?>
			</td>
		</tr>
		<tr style="display: none">
			<th><b>*</b> 链接图片：</th>
			<td>
				<input type="file" name="userfile"/>
				<?php if(isset($upload_err)):?><span class="err"><?=$upload_err?></span><?php endif;?>
			</td>
		</tr>
		<tr>
			<th></th>
			<td><input type="submit" name="submit" value="提 交" class="but2"/></td>
		</tr>
	</table>
	</form>
</div>

<?php $this->load->view('admin/footer');?>