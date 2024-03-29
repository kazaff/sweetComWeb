<?php
require_once '../require/initialization.php';
require_once './require/checkuser.php';
define('INFRAME', 'yes');
$message = '';

require_once Root_Path.'/require/class/DB.php';
$db = new DB();

//新增
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
	
	//验证不通过
	if(!$isOK){
		$message = mb_substr($warning, 0, -1,'utf-8') . '！';
	}else{
				
		$updateTime = date('Y-m-d H:i:s');
		$sql = "INSERT INTO hunter VALUES(NULL,'$_POST[office]','$_POST[beginTime]','$_POST[endTime]','$_POST[num]','$_POST[content]')";
		if($db->query($sql)){
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
<link rel="stylesheet" href="../require/library/date/ui.datepicker.css" type="text/css" media="screen" title="core css file" charset="utf-8" />
<script charset="utf-8" src="../require/library/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="../require/library/kindeditor/lang/zh_CN.js"></script>
<script src="../require/library/jquery.js" type="text/javascript" charset="utf-8"></script>
<script src="../require/library/date/ui.datepicker.js" type="text/javascript" charset="utf-8"></script>
<script>
	var editor;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="content"]', {
			allowFileManager : true
		});			
	});

	jQuery(function($){
		$.datepicker.regional['zh-CN'] = {clearText: '清除', clearStatus: '清除已选日期',
			closeText: '关闭', closeStatus: '不改变当前选择',
			prevText: '&lt;上月', prevStatus: '显示上月',
			nextText: '下月&gt;', nextStatus: '显示下月',
			currentText: '今天', currentStatus: '显示本月',
			monthNames: ['一月','二月','三月','四月','五月','六月',
			'七月','八月','九月','十月','十一月','十二月'],
			monthNamesShort: ['一','二','三','四','五','六',
			'七','八','九','十','十一','十二'],
			monthStatus: '选择月份', yearStatus: '选择年份',
			weekHeader: '周', weekStatus: '年内周次',
			dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
			dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
			dayNamesMin: ['日','一','二','三','四','五','六'],
			dayStatus: '设置 DD 为一周起始', dateStatus: '选择 m月 d日, DD',
			dateFormat: 'yy-mm-dd', firstDay: 1, 
			initStatus: '请选择日期', isRTL: false};
			$.datepicker.setDefaults($.datepicker.regional['zh-CN']);
		$(".dateinput").datepicker();	
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
								<td><input type="text" name="office" /></td>
							</tr>							
							<tr>
								<td>开始时间</td>
								<td><input type="text" name="beginTime" class="dateinput" /></td>
							</tr>
							<tr>
								<td>结束时间</td>
								<td><input type="text" name="endTime" class="dateinput" /></td>
							</tr>
							<tr>
								<td>招聘人数</td>
								<td><input type="text" name="num" /></td>
							</tr>
							<tr>
								<td>内容</td>
								<td>
									<textarea name="content" style="width:800px;height:400px;visibility:hidden;"></textarea>
								</td>
							</tr>
							<tr>
								<td></td>
								<td><input type="submit" name="submit" value="发布" /></td>
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