<div class="box" style="margin-top:20px;">
	<div class="writeMsg">
		<h3>在线咨询</h3>
		<form action="<?=site_url('bbs')?>" method="post">
			<table cellpadding="0" cellspacing="8" class="form">
				<tr>
					<th>咨询标题：</th>
					<td>
						<input type="text" name="title" class="input6 left" value="<?=set_value('title')?>"/>
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
					<th>邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;箱：</th>
					<td>
						<input type="text" name="email" class="input3 left" value="<?=set_value('email')?>"//>
						<?php if(form_error('email')) { echo form_error('email'); } ?>
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
					<th>咨询内容：</th>
					<td>
						<textarea name="content" id="content"><?=set_value('content')?></textarea><br>
						<?php if(form_error('content')) { echo form_error('content'); } ?>
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<input type="submit" value=" " class="subMsg"/>
					</td>
				</tr>
			</table>
		</form>
	</div>

	<div class="msgList">
		<form action="<?=site_url('bbs/index')?>" class="top">
			<input type="submit" value=" " class="searchbtn"/>
			<input type="text" name="keyword" class="keyword" value="<?=$this->input->get('keyword')?>"/>
		</form>
		<table cellpadding="0" cellspacing="0" class="msgTable">
			<tr>
				<th>主题</th>
				<th>作者</th>
				<th>回复/人气</th>
				<th>回复时间</th>
			</tr>
		<?php foreach($lists as $key=>$value) {  ?>	
			<tr <?= $key%2 == 0 ? 'class="row"' : ''?>>
				<td><a href="<?=site_url('bbs/viewMsg?id='.$value['id'])?>"><?=$value['title']?></a></td>
				<td><?=$value['name']?><br><?=date('Y-m-d', $value['ctime'])?></td>
				<td><?=$value['replynum']?>/<?=$value['hits']?></td>
				<td><?=date('Y-m-d H:i', $value['replytime'])?></td>
			</tr>
		<?php } ?>
		</table>
		<?=$pagination?>
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
</div>