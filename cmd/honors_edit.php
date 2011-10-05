<?php
require_once '../require/initialization.php';
require_once './require/checkuser.php';
define('INFRAME', 'yes');
$message = '';

require_once Root_Path.'/require/class/DB.php';
$db = new DB();

//获取内容
$honorInfo = $db->get_one("SELECT * FROM honor WHERE hid='$_GET[hid]' LIMIT 1");

//新增
if(isset($_POST['submit'])){
	
	//验证必填项
	$warning = '请填写：';
	$isOK = TRUE;
	$MapArr = array('name'=>'荣誉名称',);
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
		
		//查看是否有图片上传
		$img = $honorInfo['img'];
		if(!empty($_FILES['img']['name'])){
			require_once Root_Path.'/require/class/upload.php';
			$uploadImg = new Upload(array('uploadPath'=>Root_Path.'/resource/honor/'.date('Ymd')));
			$uploadImg->fileUpload($_FILES['img']);
			$result = $uploadImg->getStatus();
			if(0 == $result['error']){
				@unlink(Root_Path.'/resource/honor/'.$img);
				$img = date('Ymd').'/'.$uploadImg->fileName;
			}else{
				echo $message .= '图片文件失败原因：'.$result['message'];
				die();
			}
		}
		
		$updateTime = date('Y-m-d H:i:s');
				
		$dataArr = array(
			'name'			=> $_POST['name'],
			'img'			=> $img,
			'content'		=> $_POST['content'],
			'keyword'		=> $_POST['keyword'],
			'description'	=> $_POST['description'],
			'order'			=> $_POST['order'],
			'updateTime'	=> $updateTime
		);
		
		$result = $db->update('honor', $dataArr,"hid='$_POST[hid]'");
		
		if($result){
			header('Location: honors_list.php');
		}else{
			$message .= '数据库插入失败！';
		}
	}
}

//获取类别
$cateArr = $db->get_all("SELECT *,CONCAT(path,'',cid) AS abspath FROM category WHERE path LIKE '0,".HONOR.",%' ORDER BY abspath");
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
<title>荣誉管理-后台</title>
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
					<a href="honors_add.php" title="新增荣誉">新增</a>
				</div>
				<div id="list">
					<div id="message">
						<?=$message?>
					</div>
					<form action="" method="POST" enctype="multipart/form-data">
						<table>
							<tr>
								<td>荣誉名称</td>
								<td><input type="text" name="name" value="<?=$honorInfo['name']?>" /></td>
							</tr>
							<tr>	
								<td>类别</td>
								<td>
									<select	name="cid">
										<option value="0">请选择</option>
									<?php 
										foreach ($cateArr as $one){
											$space = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',count(explode(',',$one['abspath']))-3);
									?>
										<option value="<?=$one['cid']?>" <?=($honorInfo['cid']==$one['cid'])?"selected='selected'":''?> ><?=$space.$one['name']?></option>
									<?php
										}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td>图片</td>
								<td><input type="file" name="img" /></td>
							</tr>
							<tr>
								<td>关键词</td>
								<td><input type="text" name="keyword" value="<?=$honorInfo['keyword']?>" /></td>
							</tr>
							<tr>
								<td>说明</td>
								<td><input type="text" name="description" value="<?=$honorInfo['description']?>" /></td>
							</tr>
							<tr>
								<td>内容</td>
								<td>
									<textarea name="content" style="width:800px;height:400px;visibility:hidden;">
									<?=$honorInfo['content']?>
									</textarea>
								</td>
							</tr>
							<tr>
								<td>顺序</td>
								<td><input type="text" name="order" value="<?=$honorInfo['order']?>" /></td>
							</tr>
							<tr>
								<td></td>
								<td>
									<input type="hidden" name="hid" value="<?=$honorInfo['hid']?>" />
									<input type="hidden" name=safeCode value="<?=md5($honorInfo['hid'].Security_Code)?>" />
									<input type="submit" name="submit" value="保存" />
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