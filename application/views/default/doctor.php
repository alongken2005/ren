<div class="box" style="margin-top:20px;">
	<div class="slider">
		<h3>专家团队</h3>
		<?php foreach ($lists as $v) { ?>
			<a href="<?=site_url('index/docdetail?id='.$v['id'])?>"><?=$v['name']?></a>
		<?php } ?>
		
	</div>
<?php if($type == 'list') { ?>
	<div class="doctorList">
		<h3><a href="<?=site_url()?>">首页</a> > <a href="<?=site_url('index/doclist')?>">专家团队</a></h3>
		<ul>
		<?php foreach($lists as $v) { ?>
			<li>
				<a href="<?=site_url('index/docdetail?id='.$v['id'])?>"><img src="<?=base_url('./data/uploads/pics/'.$v['header'])?>"/></a>
				<div class="item_title"><a href="<?=site_url('index/docdetail?id='.$v['id'])?>"><?=$v['position'].' '.$v['name']?></a></div>
			</li>
		<?php } ?>	
		</ul>
	</div>
<?php } else if($type == 'detail') { ?>
	<div class="docDetail">
		<img src="<?=base_url('./data/uploads/pics/'.$row['header'])?>" class="icon"/>
		<table cellspacing="0" cellpadding="0" class="intro">
			<tr>
				<th>姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：</th>
				<td><?=$row['name']?></td>
			</tr>
			<tr>
				<th>科&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;室：</th>
				<td><?=$row['depart']?></td>
			</tr>
			<tr>
				<th>学&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;历：</th>
				<td><?=$row['edu']?></td>
			</tr>
			<tr>
				<th>职&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称：</th>
				<td><?=$row['position']?></td>
			</tr>
			<tr>
				<th>学&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;历：</th>
				<td><?=$row['edu']?></td>
			</tr>			
			<tr>
				<th>简&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;历：</th>
				<td><?=$row['description']?></td>
			</tr>
			<tr>
				<th>主攻学科：</th>
				<td><?=$row['zhug']?></td>
			</tr>
			<tr>
				<th>研究方向：</th>
				<td><?=$row['way']?></td>
			</tr>
			<tr>
				<th>医疗成果：</th>
				<td><?=$row['result']?></td>
			</tr>
		</table>
	</div>
<?php } ?>
</div>