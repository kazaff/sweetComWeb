<?php
require_once '../require/initialization.php';
require_once './require/checkuser.php';
define('INFRAME', 'yes');

require_once Root_Path.'/require/class/DB.php';
$db = new DB();
$message = '';

//删除类别
if(isset($_GET['act']) && $_GET['act'] == 'del'){

}

//获取类别列表
$newsArr = $db->get_all("SELECT n.nid,n.title,n.img,n.author,n.updateTime,n.`order`,c.name AS className FROM news AS n LEFT JOIN category c ON n.cid=c.cid");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>新闻管理-后台</title>
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
					<a href="news_add.php" title="新增新闻">新增</a>
				</div>
				<div id="list">
					<div id="message">
						<?=$message?>
					</div>
					<form action="">
						<table>
							<tr>
								<td>编号</td>
								<td>标题</td>
								<td>类别</td>
								<td>图片</td>
								<td>作者</td>
								<td>更新时间</td>
								<td>顺序</td>
								<td>操作</td>
							</tr>
							<?php 
								foreach ($newsArr as $one){
							?>
							<tr>
								<td><?=$one['nid']?></td>
								<td><?=$one['title']?></td>
								<td><?=$one['className']?></td>
								<td><?=$one['img']?></td>
								<td><?=$one['author']?></td>
								<td><?=$one['updateTime']?></td>
								<td><?=$one['order']?></td>
								<td>
									<a href="news_edit.php?nid=<?=$one['nid']?>">编辑</a>
									<a href="?act=del&nid=<?=$one['nid']?>">删除</a>
								</td>
							</tr>
							<?php
								}
							?>
						</table>
					</form>
				</div>
			</div>
		</div>
		<div id="footerBox">
			<? include 'footer.php';?>
		</div>
	</div>
</body>
</html>