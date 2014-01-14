<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>名医<?=intval($this->input->get('id')) ? '修改' : '添加'?><div class="operate"><a href="<?=site_url('admin/doctor/lists')?>">管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/doctor/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th><b>*</b> 姓名：</th>
			<td>
				<input type="text" name="name" value="<?=set_value('name', isset($row['name']) ? $row['name'] : '')?>" class="input4"/>
				<?php if(form_error('name')) { echo form_error('name'); } ?>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 名医照片：</th>
			<td>
				<input type="file" name="userfile"/>
				<?php if(isset($upload_err)):?><span class="err"><?=$upload_err?></span><?php endif;?>
			</td>
		</tr>	
	<?php if(isset($row['header'])):?>
		<tr>
			<th></th>
			<td>
				<img src="<?=base_url('data/uploads/pics/'.$row['header'])?>" style="width:120px"/>
			</td>
		</tr>
	<?php endif;?>			
		<tr>
			<th> 科室：</th>
			<td>
				<input type="text" name="depart" value="<?=set_value('depart', isset($row['depart']) ? $row['depart'] : '')?>" class="input4"/>
			</td>
		</tr>
		<tr>
			<th> 学历：</th>
			<td>
				<input type="text" name="edu" value="<?=set_value('edu', isset($row['edu']) ? $row['edu'] : '')?>" class="input4"/>
			</td>
		</tr>		
		<tr>
			<th> 职称：</th>
			<td>
				<input type="text" name="position" value="<?=set_value('position', isset($row['position']) ? $row['position'] : '')?>" class="input4"/>
			</td>
		</tr>		
		<tr>
			<th> 简历：</th>
			<td>
				<textarea name="description" style="margin-top:5px; margin-bottom: 10px;"><?=set_value('description', isset($row['description']) ? $row['description'] : '')?></textarea>
			</td>
		</tr>		
		<tr>
			<th> 主攻学科：</th>
			<td>
				<textarea name="zhug" style="margin-bottom: 10px;"><?=set_value('zhug', isset($row['zhug']) ? $row['zhug'] : '')?></textarea>
			</td>
		</tr>		
		<tr>
			<th> 研究方向：</th>
			<td>
				<textarea name="way" style="margin-bottom: 10px;"><?=set_value('way', isset($row['way']) ? $row['way'] : '')?></textarea>
			</td>
		</tr>
		<tr>
			<th> 医疗成果：</th>
			<td>
				<textarea name="result" style="margin-bottom: 10px;"><?=set_value('result', isset($row['result']) ? $row['result'] : '')?></textarea>
			</td>
		</tr>		
		<tr>
			<th><b>*</b> 链接排序：</th>
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