<div class="box" >
	<div class="bh">
		<a href="<?=site_url('index/doclist')?>" class="name">名医风采</a>
		<a href="<?=site_url('index/doclist')?>" class="more">更多>></a>
	</div>
	<div class="mingyi">
		<ul class="linklist">
		<?php foreach($doctor as $k=>$v):?>
			<li>
				<a href="<?=site_url('index/docdetail?id='.$v['id'])?>"><img src="<?=base_url('./data/uploads/pics/'.$v['header'])?>"/></a>
				<div class="item_title"><a href="<?=site_url('index/docdetail?id='.$v['id'])?>"><?=$v['position'].' '.$v['name']?></a></div>
			</li>
		<?php endforeach;?>
		</ul>
	</div>

	<div class="clear"></div>

	<div class="box1">
		<div class="bh">
			<a href="<?=site_url('index/lists?ctype=depart')?>" class="name">科室简介</a>
		</div>		
		<div class="intro">
			<?=cutstr($intro['content'], 300)?>&nbsp;&nbsp;<a href="<?=site_url('index/detail?id='.$intro['id'])?>" style="color:#007EE6">>详情</a>
		</div>
	</div>

	<div class="box2">
		<div class="bh">
			<a href="<?=site_url('index/lists?ctype=tese')?>" class="name">特色医疗</a>
			<a href="<?=site_url('index/lists?ctype=tese')?>" class="more">更多>></a>
		</div>		
		<ul>
		<?php foreach ($tese as $v) { ?>
			<li><a href="<?=site_url('index/detail?id='.$v['id'])?>"><?=$v['title']?></a><span><?=date('Y-m-d', $v['ctime'])?></span></li>
		<?php } ?>
		</ul>
	</div>

	<div class="clear"></div>

	<div class="box1">
		<div class="bh">
			<a href="<?=site_url('index/lists?ctype=dongt')?>" class="name">科室动态</a>
			<a href="<?=site_url('index/lists?ctype=dongt')?>" class="more">更多>></a>
		</div>		
		<ul>
		<?php foreach ($dongt as $v) { ?>
			<li><a href="<?=site_url('index/detail?id='.$v['id'])?>"><?=$v['title']?></a><span><?=date('Y-m-d', $v['ctime'])?></span></li>
		<?php } ?>
		</ul>
	</div>	

	<div class="box2">
		<div class="bh">
			<a href="<?=site_url('index/lists?ctype=bingli')?>" class="name">成功病例</a>
			<a href="<?=site_url('index/lists?ctype=bingli')?>" class="more">更多>></a>
		</div>		
		<ul>
		<?php foreach ($bingli as $v) { ?>
			<li><a href="<?=site_url('index/detail?id='.$v['id'])?>"><?=$v['title']?></a><span><?=date('Y-m-d', $v['ctime'])?></span></li>
		<?php } ?>
		</ul>
	</div>	

	<div class="clear"></div>

</div>

<script type="text/javascript">

	$(function() {
		$('.linklist').width($('.linklist li').length*$('.linklist li').width());
		var sa = new move_list($('.linklist'), 'li', 30, 'left');
		sa.start();

		slidshow($('.foucs'));
	})

	function move_list(listObj, listElem, speed, direction) {	//listObj为需要滚动的列表，  speed为滚动速度
		var pos, nowpos, firstsize;
		var id = '';  //记录setInterval的标记id

		pos = listObj.position();
		if(direction == 'top') {
			nowpos = pos.top;
			firstsize = listObj.children().eq(0).height();
		} else if(direction == 'left') {
			nowpos = pos.left;
			firstsize = listObj.children().eq(0).width();
		}
		var scrollUp = function() {
			nowpos--;

			if(nowpos == -firstsize) {	//连续滚动
				var firstItem = '<' + listElem +'>' + listObj.children().eq(0).html() + '</' + listElem +'>';
				listObj.children().eq(0).remove();
				listObj.append(firstItem);
				nowpos = 0;
			};
			listObj.css(direction, nowpos + 'px');
		};

		var hover = function(id) {
			listObj.hover(function() {
				clearInterval(id);
			}, function() {
				id = setInterval(scrollUp, speed);
			});
		};

		this.start = function() {
			id = setInterval(scrollUp, speed);
			hover(id);
		};
	};	

</script>