<?php
require_once '../require/initialization.php';
require_once './require/checkuser.php';
define('INFRAME', 'yes');
$message = '';

require_once Root_Path.'/require/class/DB.php';
$db = new DB();

$customInfo = $db->get_one("SELECT * FROM custom WHERE id='$_GET[id]' LIMIT 1");

//新增新闻
if(isset($_POST['submit'])){
	
	//验证必填项
	$warning = '请填写：';
	$isOK = TRUE;
	$MapArr = array('title'=>'标题',);
	foreach ($MapArr as $index => $val){		
		if (empty($_POST[$index])){
			$isOK = FALSE;
			$warning .= $val.'，';
		}
	}
	
	if($_POST['safeCode'] != md5($_POST['id'].Security_Code)){
		$isOK = FALSE;
		$warning .= '表单数据被篡改，';
	}
	
	//验证不通过
	if(!$isOK){
		$message = mb_substr($warning, 0, -1,'utf-8') . '！';
	}else{
		
		//查看是否有图片上传
		$img = $customInfo['img'];
		if(!empty($_FILES['img']['name'])){
			require_once Root_Path.'/require/class/upload.php';
			$uploadImg = new Upload(array('uploadPath'=>Root_Path.'/resource/custom/'.date('Ymd')));
			$uploadImg->fileUpload($_FILES['img']);
			$result = $uploadImg->getStatus();
			if(0 == $result['error']){
				@unlink(Root_Path.'/resource/custom/'.$img);
				$img = date('Ymd').'/'.$uploadImg->fileName;
			}else{
				echo $message .= '图片文件失败原因：'.$result['message'];
				die();
			}
		}
		
		$file = $customInfo['file'];
		if(!empty($_FILES['file']['name'])){
			require_once Root_Path.'/require/class/upload.php';
			$uploadFile = new Upload(array('uploadPath'=>Root_Path.'/resource/custom/'.date('Ymd'),'allowTypes'=>array('xls','rar','zip','doc','docx','txt','pdf')));
			$uploadFile->fileUpload($_FILES['file']);
			$result = $uploadFile->getStatus();
			if(0 == $result['error']){
				@unlink(Root_Path.'/resource/custom/'.$file);
				$file = date('Ymd').'/'.$uploadFile->fileName;
			}else{
				echo $message .= '附件文件失败原因：'.$result['message'].'！';
				die();
			}
		}
		$updateTime = date('Y-m-d H:i:s');
				
		$dataArr = array(
			'title'			=> $_POST['title'],
			'author'		=> $_POST['author'],
			'img'			=> $img,
			'file'			=> $file,
			'content'		=> $_POST['content'],
			'keyword'		=> $_POST['keyword'],
			'description'	=> $_POST['description'],
			'order'			=> $_POST['order'],
			'updateTime'	=> $updateTime
		);
		
		$result = $db->update('custom', $dataArr,"id='$_POST[id]'");
		
		if($result){
			header('Location: customs_list.php');
		}else{
			$message .= '数据库插入失败！';
		}
	}
}

//获取新闻类别
$cateArr = $db->get_all("SELECT *,CONCAT(path,'',cid) AS abspath FROM category WHERE path LIKE '0,".CUSTOM.",%' ORDER BY abspath");
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
					<form action="" method="POST" enctype="multipart/form-data">
						<table>
							<tr>
								<td>标题</td>
								<td><input type="text" name="title" value="<?=$customInfo['title']?>" /></td>
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
										<option value="<?=$one['cid']?>" <?=($customInfo['cid']==$one['cid'])?"selected='selected'":''?> ><?=$space.$one['name']?></option>
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
								<td>作者</td>
								<td><input type="text" name="author" value="<?=$customInfo['author']?>" /></td>
							</tr>
							<tr>
								<td>关键词</td>
								<td><input type="text" name="keyword" value="<?=$customInfo['keyword']?>" /></td>
							</tr>
							<tr>
								<td>说明</td>
								<td><input type="text" name="description" value="<?=$customInfo['description']?>" /></td>
							</tr>
							<tr>
								<td>内容</td>
								<td>
									<textarea name="content" style="width:800px;height:400px;visibility:hidden;">
									<?=$customInfo['content']?>
									</textarea>
								</td>
							</tr>
							<tr>
								<td>顺序</td>
								<td><input type="text" name="order" value="<?=$customInfo['order']?>" /></td>
							</tr>
							<tr>
								<td></td>
								<td>
									<input type="hidden" name="id" value="<?=$customInfo['id']?>" />
									<input type="hidden" name=safeCode value="<?=md5($customInfo['id'].Security_Code)?>" />
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