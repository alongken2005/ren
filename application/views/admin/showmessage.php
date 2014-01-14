<!DOCTYPE html>
<html>
<head>
	<title>管理后台</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="<?=BASE_VIEW?>admin/css/style.css" type="text/css" />
</head>
<body>
	<div class="showmessage">
		<h2>信息提示</h2>
		<div class="b8_contenti">
			<p><?=$message?></p>
			<p>
			<?php
				if($url_forward):
					echo anchor($url_forward, '页面跳转中...');
				else:
			?>
				<a href="javascript:history.go(-1);">返回上一页</a> |
				<a href="site_url()">返回首页</a>
			<?php endif;?>
			</p>
		</div>
	</div>
</body>
</html>