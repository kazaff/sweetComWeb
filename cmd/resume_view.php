<?php
require_once '../require/initialization.php';
require_once './require/checkuser.php';
define('INFRAME', 'yes');
$message = '';

require_once Root_Path.'/require/class/DB.php';
$db = new DB();

//获取内容
$resumeInfo = $db->get_one("SELECT * FROM resume WHERE rid='$_GET[rid]' LIMIT 1");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script charset="utf-8" src="../require/library/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="../require/library/kindeditor/lang/zh_CN.js"></script>
<script>
	
</script>
<title>招聘管理-后台</title>
</head>
<body>
	<div id="bigBox">
		<div id="headerBox">
			<? include 'header.php';?>
		</div>
		<div id="mainBox">
			<div id="navBox">
				<? include 'navigation.php';?>
			</div>
			<div id="dataBox">
				<div id="toolBar">
					<a href="hunters_add.php" title="新增招聘">新增</a>
				</div>
				<div id="list">
					<div id="message">
						<?=$message?>
					</div>
					<table>
						<tr>
							<td>姓名</td>
							<td><?=$resumeInfo['name']?></td>
						</tr>
						<tr>
							<td>年龄</td>
							<td><?=$resumeInfo['age']?></td>
						</tr>
						<tr>
							<td>性别</td>
							<td><?=$resumeInfo['sex']?></td>
						</tr>
						<tr>
							<td>联系方式</td>
							<td><?=$resumeInfo['phone']?></td>
						</tr>
						<tr>
							<td>邮箱</td>
							<td><?=$resumeInfo['email']?></td>
						</tr>
						<tr>
							<td>学历</td>
							<td><?=$resumeInfo['education']?></td>
						</tr>
						<tr>
							<td>民族</td>
							<td><?=$resumeInfo['nation']?></td>
						</tr>
						<tr>
							<td>籍贯</td>
							<td><?=$resumeInfo['birthplace']?></td>
						</tr>
						<tr>
							<td>毕业院校</td>
							<td><?=$resumeInfo['school']?></td>
						</tr>
						<tr>
							<td>个人简介及工作经验</td>
							<td><?=$resumeInfo['content']?></td>
						</tr>
						<tr>
							<td>投递时间</td>
							<td><?=$resumeInfo['updateTime']?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div id="footerBox">
			<? include 'footer.php';?>
		</div>
	</div>
</body>
</html>