<?php
require_once '../require/initialization.php';
require_once './require/checkuser.php';
define('INFRAME', 'yes');

require_once Root_Path.'/require/class/DB.php';
$db = new DB();

$userInfo = $db->get_one("SELECT uid,account,password,name FROM user LIMIT 1");

$message = '';
if (!empty($_POST)){
	//验证必填项
	$warning = '请填写：';
	$isOK = TRUE;
	$MapArr = array('account'=>'账号','name'=>'姓名');
	foreach ($MapArr as $index => $val){		
		if (empty($_POST[$index])){
			$isOK = FALSE;
			$warning .= $val.'，';
		}
	}
	
	$password = $userInfo['password'];
	if(!empty($_POST['password'])){
		if($_POST['password'] != $_POST['psw']){
			$isOK = FALSE;
			$warning .= '两次输入密码不一致，';
		}else{
			$password = $_POST['password'];
		}
	}
	
	if($_POST['safeCode'] != md5($_POST['uid'].Security_Code)){
		$isOK = FALSE;
		$warning .= '表单数据被篡改，';
	}
	
	//验证不通过
	if(!$isOK){
		$message = mb_substr($warning, 0, -1,'utf-8') . '！';
	}else{		
		$db->update('user', array(
			'account'	=> $_POST['account'],
			'password'	=> $password,
			'name'		=> $_POST['name']
		),"uid='$_POST[uid]'");
		header('Location: admin_edit.php');
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>账号管理-后台</title>
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
				</div>
				<div id="list">
					<div id="message">
						<?=$message?>
					</div>
					<form action="" method="post">
						<table>
							<tr>
								<td>姓名：</td>
								<td><input type="text" name="name" value="<?=$userInfo['name']?>" /></td>
							</tr>
							<tr>
								<td>账号：</td>
								<td><input type="text" name="account" value="<?=$userInfo['account']?>" /></td>
							</tr>
							<tr>
								<td>密码：</td>
								<td><input type="password" name="password" value="" /></td>
							</tr>
							<tr>
								<td>再次输入密码：</td>
								<td><input type="password" name="psw" value="" /></td>
							</tr>
							<tr>
								<td></td>
								<td>
									<input type="hidden" name="uid" value="<?=$userInfo['uid']?>" />
									<input type="hidden" name="safeCode" value="<?=md5($userInfo['uid'].Security_Code)?>" />
									<input type="submit" value="修改" />
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