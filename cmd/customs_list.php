<?php
require_once '../require/initialization.php';
require_once './require/checkuser.php';
define('INFRAME', 'yes');

require_once Root_Path.'/require/class/DB.php';
$db = new DB();
$message = '';

//删除
if(isset($_GET['act']) && $_GET['act'] == 'del' && $_GET['id'] > 0){
	$customInfo = $db->get_one("SELECT img,file FROM custom WHERE id='$_GET[id]' LIMIT 1");	
	if($db->delete('custom',"id='$_GET[id]'")){
		//删除图片和附件资源
		@unlink(Root_Path.'/resource/custom/'.$customInfo['img']);
		@unlink(Root_Path.'/resource/custom/'.$customInfo['file']);
		$message .= '删除成功！';
	}else{
		$message .= '删除失败！';
	}
}

//获取列表
require_once Root_Path.'/require/class/page.php';
$total = $db->get_one("SELECT COUNT(id) AS total FROM custom");
$options = array(
	'total_rows' => $total['total'], //总行数
	'list_rows'  => '20',  //每页显示量
	'page_name'	 => 'page',
);
$page = new page($options);

$customArr = $db->get_all("SELECT t.id,t.title,t.img,t.author,t.updateTime,t.`order`,c.name AS className FROM custom AS t LEFT JOIN category c ON t.cid=c.cid LIMIT $page->first_row,$page->list_rows");
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
<title>自助管理-后台</title>
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
					<a href="customs_add.php" title="新增自助">新增</a>
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
								foreach ($customArr as $one){
							?>
							<tr>
								<td><?=$one['id']?></td>
								<td><?=$one['title']?></td>
								<td><?=$one['className']?></td>
								<td><?=$one['img']?></td>
								<td><?=$one['author']?></td>
								<td><?=$one['updateTime']?></td>
								<td><?=$one['order']?></td>
								<td>
									<a href="customs_edit.php?id=<?=$one['id']?>">编辑</a>
									<a href="?act=del&id=<?=$one['id']?>">删除</a>
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