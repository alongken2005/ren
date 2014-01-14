<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><?=intval($this->input->get('cid')) ? '修改' : '添加'?><div class="operate"><a href="<?=site_url('admin/content/lists')?>">管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/content/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th><b>*</b> 标题：</th>
			<td>
				<input type="text" name="title" value="<?=set_value('title', isset($row['title']) ? $row['title'] : '')?>" class="input2"/>
				<?php if(form_error('title')) { echo form_error('title'); } ?>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 分类：</th>
			<td>
				<select name="ptid" class="ptid">
				<?php foreach($ctype as $k=>$v) { ?>
					<option value="<?=$k?>" <?=isset($selectedType)&&$selectedType==$k ? 'selected' : ''?>><?=$v?></option>
				<?php } ?>
				</select>
				<select name="tid" class="type"></select>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 发布时间：</th>
			<td>
				<input type="text" name="ctime" value="<?=set_value('ctime', isset($row['ctime']) ? date('Y-m-d H:i:s', $row['ctime']) : date('Y-m-d H:i:s', time()))?>" class="input1"/>
				<?php if(form_error('ctime')) { echo form_error('ctime'); } ?>
			</td>
		</tr>
		<tr>
			<th> 排序：</th>
			<td>
				<input type="text" name="dis" value="<?=set_value('dis', isset($row['dis']) ? $row['dis'] : 0)?>" class="input1"/>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 内容：</th>
			<td>
				<textarea name="content" id="content"><?=set_value('content', isset($row['content']) ? $row['content'] : '')?></textarea>
				<?php if(form_error('content')) { echo form_error('content'); } ?>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input type="submit" name="submit" value="提 交" class="but2"/>
			</td>
		</tr>
	</table>
	</form>
</div>


<script type="text/javascript" src="<?=base_url('./common/kindeditor/kindeditor.js')?>"></script>
<script type="text/javascript">
$(function() {
	KindEditor.ready(function(K) {
		K.create('#content', {width : '670', height: '500', newlineTag:'br', filterMode : true});
	});

	getCtype();

	$('.ptid').change(function() {
		getCtype();
	});

	$('.del').click(function() {
		if(confirm('确定删除？')) {
			$.get($('.del').attr('href'), '', function(data) {
				if(data == 'ok') {
					$('.tr_icon').hide();
				} else {
					alert('删除失败');
				}
			})
		}
		return false;
	})
})

function getCtype() {
	var type = $('.ptid').val();

	$.post('<?=site_url("admin/content/getType?tid=".(isset($row["tid"]) ? $row["tid"] : ""))?>&type='+type, {}, function(data) {
		$('.type').html(data);
	});	
}
</script>

<?php $this->load->view('admin/footer');?>