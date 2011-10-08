<?php
require_once '../require/initialization.php';
require_once './require/checkuser.php';
define('INFRAME', 'yes');

require_once Root_Path.'/require/class/DB.php';
$db = new DB();
$message = '';

//删除
if(isset($_GET['act']) && $_GET['act'] == 'del' && $_GET['rid'] > 0){	
	if($db->delete('resume',"rid='$_GET[rid]'")){
		$message .= '删除成功！';
	}else{
		$message .= '删除失败！';
	}
}

//获取列表
require_once Root_Path.'/require/class/page.php';
$total = $db->get_one("SELECT COUNT(rid) AS total FROM resume WHERE hid='$_GET[hid]'");
$options = array(
	'total_rows' => $total['total'], //总行数
	'list_rows'  => '20',  //每页显示量
	'page_name'	 => 'page',
);
$page = new page($options);

$resumeArr = $db->get_all("SELECT rid,name,age,sex,phone,updateTime FROM resume WHERE hid='$_GET[hid]' LIMIT $page->first_row,$page->list_rows");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
	#page{font:12px/16px arial}
	#page span{float:left;margin:0px 3px;}
	#page a{float:left;margin:0 3px;border:1px solid #ddd;padding:3px 7px; text-decoration:none;color:#666}
	#page a.now_page,#page a:hover{color:#fff;background:#05c}
	#footerBox{clear:both}
</style>
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
					<a href="hunter_add.php" title="新增招聘">新增</a>
				</div>
				<div id="list">
					<div id="message">
						<?=$message?>
					</div>
					<form action="">
						<table>
							<tr>
								<td>编号</td>
								<td>姓名</td>
								<td>年龄</td>
								<td>性别</td>
								<td>联系方式</td>
								<td>投递时间</td>
								<td>操作</td>
							</tr>
							<?php 
								foreach ($resumeArr as $one){
							?>
							<tr>
								<td><?=$one['rid']?></td>
								<td><?=$one['name']?></td>
								<td><?=$one['age']?></td>
								<td><?=$one['sex']?></td>
								<td><?=$one['phone']?></td>
								<td><?=$one['updateTime']?></td>
								<td>
									<a href="resume_view.php?rid=<?=$one['rid']?>">查看</a>
									<a href="?act=del&rid=<?=$one['rid']?>&hid=<?=$_GET['hid']?>">删除</a>
								</td>
							</tr>
							<?php
								}
							?>
						</table>
					</form>
					<div id="page">
						<?=$page->show(1)?>
					</div>
				</div>
			</div>
		</div>
		<div id="footerBox">
			<? include 'footer.php';?>
		</div>
	</div>
</body>
</html>