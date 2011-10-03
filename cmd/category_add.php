<?php
require_once '../require/initialization.php';
require_once './require/checkuser.php';
define('INFRAME', 'yes');

require_once Root_Path.'/require/class/DB.php';
$db = new DB();

//新增类别
$message = '';
if (!empty($_POST)){
	//验证必填项
	$warning = '请填写：';
	$isOK = TRUE;
	$MapArr = array('pid'=>'所属类别','cateName'=>'名称');
	foreach ($MapArr as $index => $val){		
		if (empty($_POST[$index])){
			$isOK = FALSE;
			$warning .= $val.'，';
		}
	}
	
	//验证不通过
	if(!$isOK){
		$message = mb_substr($warning, 0, -1,'utf-8') . '！';
	}else{
		$result = $db->get_one("SELECT cid,path FROM category WHERE cid='$_POST[pid]' LIMIT 1");
		$path = $result['path'].$result['cid'].',';
		
		$db->insert('category', array(
			'pid'	=> $_POST['pid'],
			'path'	=> $path,
			'name'	=> $_POST['cateName']
		));
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
					<form action="" method="post">
						<table>
							<tr>
								<td>所属：</td>
								<td>
									<select name="pid">
										<option value="0">请选择</option>
									<?php 
										foreach ($cateArr as $one){
											$space = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',count(explode(',',$one['abspath']))-1);
									?>
										<option value="<?=$one['cid']?>"><?=$space.$one['name']?></option>
									<?php
										}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td>名称：</td>
								<td><input type="text" name="cateName" /></td>
							</tr>
							<tr>
								<td></td>
								<td><input type="submit" value="创建" /></td>
							</tr>
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