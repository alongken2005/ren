	<div class="msgMenu box">在线咨询</div>
	<div class="box msgTop">
		<h3>主题：<?=$row['title']?></h3>
		<div class="info">
			<?=$row['name']?> 发表于 <?=date("Y-m-d H:i", $row['ctime'])?>
		</div>
		<div class="replynum" title="回复数量" alt="回复数量"><?=$row['replynum']?></div>
		<div class="hitsnum" title="浏览数量" alt="浏览数量"><?=$row['hits']?></div>
		<div class="clear"></div>
		<div class="content"><?=$row['content']?></div>
	</div>

	<?php if($replyList) {?>
	<div class="box reply">
	<?php foreach($replyList as $v) { ?>
		<h3>回复：<?=$v['title']?></h3>
		<div class="info"><?=$v['name']?> 回复于 <?=date('Y-m-d H:i', $v['ctime'])?></div>
		<div class="content"><?=$v['content']?></div>
	<?php } ?>
	</div>
	<?php } ?>

	<div class="box"><?=$pagination?></div>
	<div class="writeMsg box" style="margin-top:20px;">
		<h3>回复咨询</h3>
		<form action="<?=site_url('bbs/viewMsg')?>" method="post">
			<table cellpadding="0" cellspacing="8" class="form">
				<tr>
					<th>回复标题：</th>
					<td>
						<input type="text" name="title" class="input6 left" value="<?=set_value('title', "Re:".$row['title'])?>"/>
						<?php if(form_error('title')) { echo form_error('title'); } ?>
					</td>
				</tr>
				<tr>
					<th>称&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;呼：</th>
					<td>
						<input type="text" name="name" class="input3 left" value="<?=set_value('name')?>"/>
						<?php if(form_error('name')) { echo form_error('name'); } ?>
					</td>
				</tr>
				<tr>
					<th>验 证 码：</th>
					<td>
						<input type="text" name="captcha" class="input1 left" /> 
						<div id="captcha"><?=$captcha?></div>
						<div class="capinfo">如果验证码少于4位，请点击刷新</div>
						<?php if(form_error('captcha')) { echo form_error('captcha'); } ?>
					</td>
				</tr>
				<tr>
					<th>回复内容：</th>
					<td>
						<textarea name="content" id="content"><?=set_value('content')?></textarea><br>
						<?php if(form_error('content')) { echo form_error('content'); } ?>
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<input type="hidden" value="<?=$row['id']?>" name="id"/>
						<input type="submit" value=" " class="subMsg"/>
					</td>
				</tr>
			</table>
		</form>
	</div>	
<script type="text/javascript" src="<?=base_url('common/kindeditor/kindeditor.js')?>"></script>
<script type="text/javascript">
$(function() {
	KindEditor.ready(function(K) {
		K.create('#content',  {
			width : '670', 
			height: '200', 
			newlineTag:'br', 
			filterMode : true,
			items: ['fontsize', 'bold', 'removeformat', 'image', 'emoticons', 'link', 'unlink']
		});
	});


	$('#captcha').click(function() {
		$.post("<?=site_url('bbs/getCaptcha')?>", {}, function(data) {
			$('#captcha').html(data);
		})
	})
})
</script>
