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
	$MapArr = array('cateName'=>'名称');
	foreach ($MapArr as $index => $val){		
		if (empty($_POST[$index])){
			$isOK = FALSE;
			$warning .= $val.'，';
		}
	}
	
	if($_POST['safeCode'] != md5($_POST['cid'].Security_Code)){
		$isOK = FALSE;
		$warning .= '表单数据被篡改，';
	}
	
	//验证不通过
	if(!$isOK){
		$message = mb_substr($warning, 0, -1,'utf-8') . '！';
	}else{		
		$db->update('category', array(
			'name'	=> $_POST['cateName'],
			'order'	=> $_POST['order']
		),"cid='$_POST[cid]'");
		header('Location: category_list.php');
	}
}

$cateInfo = $db->get_one("SELECT name,`order` FROM category WHERE cid='$_GET[cid]' LIMIT 1");
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
								<td>名称：</td>
								<td><input type="text" name="cateName" value="<?=$cateInfo['name']?>" /></td>
							</tr>
							<tr>
								<td>顺序：</td>
								<td><input type="text" name="order" value="<?=$cateInfo['order']?>" /></td>
							</tr>
							<tr>
								<td></td>
								<td>
									<input type="hidden" name="cid" value="<?=$_GET['cid']?>" />
									<input type="hidden" name="safeCode" value="<?=md5($_GET['cid'].Security_Code)?>" />
									<input type="submit" value="更新" />
								</td>
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