<div class="box" style="margin-top:20px;">
	<?=$slider?>

	<div class="contentList">
		<h3><a href="<?=site_url()?>">首页</a> > <a href="<?=site_url('index/lists?ctype='.$ctype)?>"><?=$ctypeList[$ctype]?></a></h3>
		<div class="detail">
			<h2><?=$row['title']?></h2>
			<div class="other">发布时间：<?=date('Y-m-d', $row['ctime'])?>&nbsp;&nbsp;&nbsp;阅读人数：<?=$row['hits']?></div>
			<div class="d"><?=$row['content']?></div>
		</div>
	</div>
</div>