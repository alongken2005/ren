<!DOCTYPE html>
<html>
<head>
	<title>后台管理</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link rel="stylesheet" href="<?=BASE_VIEW?>admin/css/style.css" type="text/css" />
</head>
<body style="background: #F7EFDB">
<h1 class="login_h1">后台管理</h1>
<div class="login">
	<?php if(!empty($_POST)):?>
	<ul class="err">
		<?php echo validation_errors(); ?>
		<?php echo isset($loginerr) ? '<li>'.$loginerr.'</li>' : ''; ?>
	</ul>
	<?php endif;?>
	<form name="form" action="<?=site_url('admin/login')?>" method="post">
		<table cellspacing="0" cellpadding="0" border="0">
			<tr>
				<th>账号：</th>
				<td><input type="text" name="username" class="input1"/></td>
			</tr>
			<tr>
				<th>密码：</th>
				<td><input type="password" name="password" class="input1"/></td>
			</tr>
			<tr>
				<th></th>
				<td>
					<input type="submit" name="submit" value="登 录" class="but1"/>
				</td>
			</tr>
		</table>
	</form>
</div>
</body>
</html>