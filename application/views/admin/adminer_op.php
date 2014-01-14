<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>管理员<div class="operate"><a href="<?=site_url('admin/adminer/lists')?>">管理</a></div></h2>
<form method="POST" action="<?=site_url('admin/adminer/adminer_op'.($this->input->get('uid') ? '?uid='.$this->input->get('uid') : ''))?>">
	<table cellpadding="0" cellspacing="0" border="0" class="table1">
		<tr>
			<th>用户名：</th>
			<td>
				<input type="text" name="username" class="input1" value="<?php echo set_value('username', (isset($adminer['username']) ? $adminer['username'] : '')); ?>"/>
				</td>
			<td class="errinfo"><?php if(form_error('username')) { echo form_error('username'); } ?></td>
		</tr>
		<tr>
			<th>密码：</th>
			<td><input type="password" name="password" class="input1"/></td>
			<td class="errinfo"><?php if(form_error('password')) { echo form_error('password'); } ?></td>
		</tr>
		<tr>
			<th></th>
			<td><input type="submit" name="submit" value="提交" class="but2"/></td>
		</tr>
	</table>
</form>
<?php $this->load->view('admin/footer');?>