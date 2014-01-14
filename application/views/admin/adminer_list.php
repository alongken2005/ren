<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>管理员<div class="operate"><a href="<?=site_url('admin/adminer/op')?>">添加</a></div></h2>
<table cellpadding="0" cellspacing="0" border="0" class="table2">
	<tr>
		<th>用户名</th>
		<th width="150">创建日期</th>
		<th width="150">操作</th>
	</tr>
<?php foreach($lists as $v):?>
	<tr>
		<td><?=$v['username']?></td>
		<td><?=date('Y-m-d H:i', $v['ctime'])?></td>
		<td>
			<a href="<?=site_url('admin/adminer/adminer_op?uid='.$v['uid'])?>">修改</a>
			<a href="<?=site_url('admin/adminer/adminer_del?uid='.$v['uid'])?>" class="del">删除</a>
		</td>
	</tr>
<?php endforeach;?>
</table>
<script type="text/javascript">
$(function() {
	$('.del').click(function() {
		if(confirm('确认删除？')){
			var po = $(this).parent().parent();
			$.get($(this).attr('href'), '', function(data) {
				if(data == 'ok'){
					po.hide();
				} else {
					alert('删除失败！');
				}
			})
		}
		return false;
	})
})
</script>
<?php $this->load->view('admin/footer');?>