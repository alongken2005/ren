<div class="slider">
	<h3><?=$ctypeList[$ctype]?></h3>
	<?php foreach ($typeList as $v) { ?>
		<a href="<?=site_url('index/lists?tid='.$v['id'])?>"><?=$v['name']?></a>
	<?php } ?>
	
</div>