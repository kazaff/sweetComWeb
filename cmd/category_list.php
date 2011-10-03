<?php
require_once '../require/initialization.php';
require_once './require/checkuser.php';
define('INFRAME', 'yes');

require_once Root_Path.'/require/class/DB.php';
$db = new DB();
$message = '';

//删除类别
if(isset($_GET['act']) && $_GET['act'] == 'del'){
	$isAllow = TRUE;
	//没有子类
	$result = $db->get_one("SELECT COUNT(cid) AS num FROM category WHERE pid='$_GET[cid]'");
	if($result['num']!=0){
		$isAllow = FALSE;
		$message = '删除的类别存在子类；';
	}
	
	if($isAllow){
		$db->delete('category',"cid='$_GET[cid]'");
		$message = '删除成功！';
	}
}

//获取类别列表
$cateArr = $db->get_all("SELECT *,CONCAT(path,'',cid) AS abspath FROM category ORDER BY abspath,`order`");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>类别管理-后台</title>
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
					<a href="category_add.php" title="新增类别">新增</a>
				</div>
				<div id="list">
					<div id="message">
						<?=$message?>
					</div>
					<form action="">
						<table>
							<tr>
								<td>编号</td>
								<td>名称</td>
								<td>顺序</td>
								<td>操作</td>
							</tr>
							<?php 
								foreach ($cateArr as $one){
									$space = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',count(explode(',',$one['abspath']))-1);
							?>
							<tr>
								<td><?=$one['cid']?></td>
								<td><?=$space.$one['name']?></td>
								<td><?=$space.$one['order']?></td>
								<td>
									<?php 
										if($one['pid'] != 0){
									?>
									<a href="category_edit.php?cid=<?=$one['cid']?>">编辑</a>
									<a href="?act=del&cid=<?=$one['cid']?>">删除</a>
									<?php 
										}
									?>
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