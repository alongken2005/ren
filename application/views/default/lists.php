<div class="box" style="margin-top:20px;">
	<?=$slider?>

	<div class="contentList">
		<h3><a href="<?=site_url()?>">首页</a> > <a href="<?=site_url('index/lists?ctype='.$ctype)?>"><?=$ctypeList[$ctype]?></a></h3>
		<ul>
		<?php foreach($lists as $v) { ?>
			<li><a href="<?=site_url('index/detail?id='.$v['id'])?>"><?=$v['title']?></a> <span><?=date('Y-m-d', $v['ctime'])?></span></li>
		<?php } ?>	
		</ul>
		<div class="clear"></div>
		<?=$pagination?>
	</div>
</div>