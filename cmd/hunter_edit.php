<?php
require_once '../require/initialization.php';
require_once './require/checkuser.php';
define('INFRAME', 'yes');
$message = '';

require_once Root_Path.'/require/class/DB.php';
$db = new DB();

//获取内容
$hunterInfo = $db->get_one("SELECT * FROM hunter WHERE hid='$_GET[hid]' LIMIT 1");

//新增新闻
if(isset($_POST['submit'])){
	
	//验证必填项
	$warning = '请填写：';
	$isOK = TRUE;
	$MapArr = array('office'=>'招聘职位','num'=>'人数',);
	foreach ($MapArr as $index => $val){		
		if (empty($_POST[$index])){
			$isOK = FALSE;
			$warning .= $val.'，';
		}
	}
	
	if($_POST['safeCode'] != md5($_POST['hid'].Security_Code)){
		$isOK = FALSE;
		$warning .= '表单数据被篡改，';
	}
	
	//验证不通过
	if(!$isOK){
		$message = mb_substr($warning, 0, -1,'utf-8') . '！';
	}else{
		
		$dataArr = array(
			'office'		=> $_POST['office'],
			'num'			=> $_POST['num'],
			'beginTime'		=> $_POST['beginTime'],
			'endTime'		=> $_POST['endTime'],
			'content'		=> $_POST['content']
		);
		
		$result = $db->update('hunter', $dataArr,"hid='$_POST[hid]'");
		
		if($result){
			header('Location: hunters_list.php');
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
	var editor;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="content"]', {
			allowFileManager : true
		});			
	});
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
					<a href="hunter_add.php" title="新增招聘">新增</a>
				</div>
				<div id="list">
					<div id="message">
						<?=$message?>
					</div>
					<form action="" method="POST" enctype="multipart/form-data">
						<table>
							<tr>
								<td>招聘岗位</td>
								<td><input type="text" name="office" value="<?=$hunterInfo['office']?>" /></td>
							</tr>							
							<tr>
								<td>开始时间</td>
								<td><input type="text" name="beginTime" value="<?=$hunterInfo['beginTime']?>" /></td>
							</tr>
							<tr>
								<td>结束时间</td>
								<td><input type="text" name="endTime" value="<?=$hunterInfo['endTime']?>" /></td>
							</tr>
							<tr>
								<td>招聘人数</td>
								<td><input type="text" name="num" value="<?=$hunterInfo['num']?>" /></td>
							</tr>
							<tr>
								<td>内容</td>
								<td>
									<textarea name="content" style="width:800px;height:400px;visibility:hidden;">
										 <?=$hunterInfo['content']?>
									</textarea>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<input type="hidden" name="hid" value="<?=$hunterInfo['hid']?>" />
									<input type="hidden" name=safeCode value="<?=md5($hunterInfo['hid'].Security_Code)?>" />
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