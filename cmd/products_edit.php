<?php
require_once '../require/initialization.php';
require_once './require/checkuser.php';
define('INFRAME', 'yes');
$message = '';

require_once Root_Path.'/require/class/DB.php';
$db = new DB();

//获取内容
$proInfo = $db->get_one("SELECT * FROM product WHERE pid='$_GET[pid]' LIMIT 1");

//编辑
if(isset($_POST['submit'])){
	
	//验证必填项
	$warning = '请填写：';
	$isOK = TRUE;
	$MapArr = array('name'=>'产品名',);
	foreach ($MapArr as $index => $val){		
		if (empty($_POST[$index])){
			$isOK = FALSE;
			$warning .= $val.'，';
		}
	}
	
	if($_POST['safeCode'] != md5($_POST['pid'].Security_Code)){
		$isOK = FALSE;
		$warning .= '表单数据被篡改，';
	}
	
	//验证不通过
	if(!$isOK){
		$message = mb_substr($warning, 0, -1,'utf-8') . '！';
	}else{
		
		//查看是否有图片上传
		$img = $proInfo['img'];
		if(!empty($_FILES['img']['name'])){
			require_once Root_Path.'/require/class/upload.php';
			$uploadImg = new Upload(array('uploadPath'=>Root_Path.'/resource/product/'.date('Ymd')));
			$uploadImg->fileUpload($_FILES['img']);
			$result = $uploadImg->getStatus();
			if(0 == $result['error']){
				@unlink(Root_Path.'/resource/news/'.$img);
				$img = date('Ymd').'/'.$uploadImg->fileName;
			}else{
				echo $message .= '图片文件失败原因：'.$result['message'];
				die();
			}
		}
		
		$file = $proInfo['file'];
		if(!empty($_FILES['file']['name'])){
			require_once Root_Path.'/require/class/upload.php';
			$uploadFile = new Upload(array('uploadPath'=>Root_Path.'/resource/product/'.date('Ymd'),'allowTypes'=>array('xls','rar','zip','doc','docx','txt','pdf')));
			$uploadFile->fileUpload($_FILES['file']);
			$result = $uploadFile->getStatus();
			if(0 == $result['error']){
				@unlink(Root_Path.'/resource/news/'.$file);
				$file = date('Ymd').'/'.$uploadFile->fileName;
			}else{
				echo $message .= '附件文件失败原因：'.$result['message'].'！';
				die();
			}
		}
		$updateTime = date('Y-m-d H:i:s');
				
		$dataArr = array(
			'name'			=> $_POST['name'],
			'img'			=> $img,
			'file'			=> $file,
			'content'		=> $_POST['content'],
			'keyword'		=> $_POST['keyword'],
			'description'	=> $_POST['description'],
			'order'			=> $_POST['order'],
			'updateTime'	=> $updateTime
		);
		
		$result = $db->update('product', $dataArr,"pid='$_POST[pid]'");
		
		if($result){
			header('Location: products_list.php');
		}else{
			$message .= '数据库插入失败！';
		}
	}
}

//获取产品类别
$cateArr = $db->get_all("SELECT *,CONCAT(path,'',cid) AS abspath FROM category WHERE path LIKE '0,".PRODUCT.",%' ORDER BY abspath");
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
<title>产品管理-后台</title>
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
					<a href="products_add.php" title="新增产品">新增</a>
				</div>
				<div id="list">
					<div id="message">
						<?=$message?>
					</div>
					<form action="" method="POST" enctype="multipart/form-data">
						<table>
							<tr>
								<td>产品名</td>
								<td><input type="text" name="name" value="<?=$proInfo['name']?>" /></td>
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
										<option value="<?=$one['cid']?>" <?=($proInfo['cid']==$one['cid'])?"selected='selected'":''?> ><?=$space.$one['name']?></option>
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
								<td>附件</td>
								<td><input type="file" name="file" /></td>
							</tr>
							<tr>
								<td>关键词</td>
								<td><input type="text" name="keyword" value="<?=$proInfo['keyword']?>" /></td>
							</tr>
							<tr>
								<td>说明</td>
								<td><input type="text" name="description" value="<?=$proInfo['description']?>" /></td>
							</tr>
							<tr>
								<td>内容</td>
								<td>
									<textarea name="content" style="width:800px;height:400px;visibility:hidden;">
									<?=$proInfo['content']?>
									</textarea>
								</td>
							</tr>
							<tr>
								<td>顺序</td>
								<td><input type="text" name="order" value="<?=$proInfo['order']?>" /></td>
							</tr>
							<tr>
								<td></td>
								<td>
									<input type="hidden" name="pid" value="<?=$proInfo['pid']?>" />
									<input type="hidden" name=safeCode value="<?=md5($proInfo['pid'].Security_Code)?>" />
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