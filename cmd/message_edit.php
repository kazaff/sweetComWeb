<?php
require_once '../require/initialization.php';
require_once './require/checkuser.php';
define('INFRAME', 'yes');
$message = '';

require_once Root_Path.'/require/class/DB.php';
$db = new DB();

//获取
$messageInfo = $db->get_one("SELECT * FROM message WHERE mid='$_GET[mid]' LIMIT 1");

//新增新闻
if(isset($_POST['submit'])){
	
	//验证必填项
	$warning = '请填写：';
	$isOK = TRUE;
	$MapArr = array();
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
		
		$updateTime = date('Y-m-d H:i:s');
				
		$dataArr = array(
			'isAllow'	=> $_POST['isAllow']
		);
		
		$result = $db->update('message', $dataArr,"mid='$_POST[mid]'");
		
		if($result){
			header('Location: messages_list.php');
		}else{
			$message .= '数据库插入失败！';
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script charset="utf-8" src="../require/library/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="../require/library/kindeditor/lang/zh_CN.js"></script>
<script>

</script>
<title>留言管理-后台</title>
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
					<form action="" method="POST" enctype="multipart/form-data">
						<table>
							<tr>
								<td>姓名</td>
								<td><?=$messageInfo['name']?></td>
							</tr>
							<tr>
								<td>电话</td>
								<td><?=$messageInfo['phone']?></td>
							</tr>
							<tr>
								<td>传真</td>
								<td><?=$messageInfo['fax']?></td>
							</tr>
							<tr>
								<td>邮箱</td>
								<td><?=$messageInfo['email']?></td>
							</tr>
							<tr>
								<td>QQ</td>
								<td><?=$messageInfo['qq']?></td>
							</tr>
							<tr>
								<td>公司名称</td>
								<td><?=$messageInfo['company']?></td>
							</tr>
							<tr>
								<td>地址</td>
								<td><?=$messageInfo['address']?></td>
							</tr>
							<tr>
								<td>网址</td>
								<td><?=$messageInfo['website']?></td>
							</tr>
							<tr>
								<td>IP</td>
								<td><?=$messageInfo['ip']?></td>
							</tr>
							<tr>
								<td>内容</td>
								<td><?=$messageInfo['content']?></td>
							</tr>
							<tr>
								<td>时间</td>
								<td><?=$messageInfo['updateTime']?></td>
							</tr>
							<tr>
								<td>状态</td>
								<td>
									<input type="radio" name="isAllow" value="0" <?=(0==$messageInfo['isAllow'])?"checked=checked":''?> />屏蔽
									<input type="radio" name="isAllow" value="1" <?=(1==$messageInfo['isAllow'])?"checked=checked":''?> />显示
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<input type="hidden" name="mid" value="<?=$messageInfo['mid']?>" />
									<input type="submit" name="submit" value="修改" />
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