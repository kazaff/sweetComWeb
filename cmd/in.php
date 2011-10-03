<?php
require_once '../require/initialization.php';

//登陆动作
$message = '';
if (!empty($_POST)){
	//验证必填项
	$warning = '请填写：';
	$isOK = TRUE;
	$MapArr = array('username'=>'账号','psw'=>'密码','verifyCode'=>'认证');
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
		//验证码是否正确
		if(strtolower($_POST['verifyCode']) != strtolower($_SESSION['verifyCode'])){
			$message = '认证填写有误，请重新填写';
		}else{
						
			//验证登录信息
			require_once Root_Path.'/require/class/DB.php';
			$db = new DB();
			$uinfoArr = $db->get_one("select uid,name from user where account='$_POST[username]' and password='$_POST[psw]' and isAllow=1 limit 1");
			if(empty($uinfoArr)){
				$message = '您的账号或密码有误，也可能账号已被禁用！';
			}else{
				$_SESSION['userid'] = $uinfoArr['uid'];
				$_SESSION['username'] = $uinfoArr['name'];
				header('Location: homepage.php');
			}
		}
	}
}
?>
<div id="message">
	<?=$message?>
</div>
<form method="post">
	<table>
		<tr>
			<td>账号：</td>
			<td><input type="text" name="username" /></td>
		</tr>
		<tr>
			<td>密码：</td>
			<td><input type="password" name="psw" /></td>
		</tr>
		<tr>
			<td>认证：</td>
			<td>
				<input type="text" name="verifyCode" />
				<img src="../require/verifyCode.php" />
			</td>
		</tr>
		<tr>
			<td><input type="submit" value="登录" /></td>
		</tr>
	</table>	
</form>