<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>分类<?=intval($this->input->get('id')) ? '修改' : '添加'?><div class="operate"><a href="<?=site_url('admin/type/lists')?>">管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/type/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th> 选择父类：</th>
			<td>
				<select name="type">
				<?php foreach($ctype as $k=>$type) { ?>
					<option value="<?=$k?>" <?=isset($row['type'])&&$row['type']==$k ? 'selected' : ''?>><?=$type?></option>
				<?php } ?>	
				</select>
			</td>
		</tr>
		<tr>
			<th> 分类名称：</th>
			<td>
				<input type="text" name="name" value="<?=set_value('name', isset($row['name']) ? $row['name'] : '')?>" class="input4"/>
				<?php if(form_error('name')) { echo form_error('name'); } ?>
			</td>
		</tr>		
		<tr>
			<th> 链接排序：</th>
			<td>
				<input type="text" name="dis" value="<?=set_value('dis', isset($row['dis']) ? $row['dis'] : 0)?>" class="input4"/>
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