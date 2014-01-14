<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>名医管理<div class="operate"><a href="<?=site_url('admin/doctor/op')?>">添加</a></div></h2>
<table cellpadding="0" cellspacing="0" border="0" class="table2">
	<tr>
		<th>姓名</th>
		<th width="150">科室</th>
		<th width="150">学历</th>
		<th width="150"职称</th>
		<th width="150">操作</th>
	</tr>
<?php if($lists): foreach($lists as $v):?>
	<tr>
		<td><?=$v['name']?></td>
		<td><?=$v['depart']?></td>
		<td><?=$v['edu']?></td>
		<td><?=$v['position']?></td>
		<td>
			<a href="<?=site_url('admin/doctor/op?id='.$v['id'])?>">修改</a>
			<a href="<?=site_url('admin/doctor/del?id='.$v['id'])?>" class="del">删除</a>
		</td>
	</tr>
<?php endforeach; endif;?>
</table>
<script type="text/javascript">
$(function() {
	$('.del').click(function() {
		if(confirm('确认删除？')) {
			var po = $(this).parent().parent();
			$.get($(this).attr('href'), '', function(data) {
				if(data == 'ok') {
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