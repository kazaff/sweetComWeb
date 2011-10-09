<?php
require_once '../require/initialization.php';
require_once './require/checkuser.php';
define('INFRAME', 'yes');
$message = '';

require_once Root_Path.'/require/class/DB.php';
$db = new DB();

//获取
$comInfo = $db->get_one("SELECT * FROM comInfo LIMIT 1");

//新增新闻
if(isset($_POST['submit'])){
	
	//验证必填项
	$warning = '请填写：';
	$isOK = TRUE;
	$MapArr = array('name'=>'公司名称');
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
		
		//查看是否有图片上传
		$img = $comInfo['img'];
		if(!empty($_FILES['img']['name'])){
			require_once Root_Path.'/require/class/upload.php';
			$uploadImg = new Upload(array('uploadPath'=>Root_Path.'/resource/comInfo/'));
			$uploadImg->fileUpload($_FILES['img']);
			$result = $uploadImg->getStatus();
			if(0 == $result['error']){
				@unlink(Root_Path.'/resource/comInfo/'.$img);
				$img = $uploadImg->fileName;
			}else{
				echo $message .= '图片文件失败原因：'.$result['message'];
				die();
			}
		}
				
		$dataArr = array(
			'name'			=> $_POST['name'],
			'img'			=> $img,
			'content'		=> $_POST['content'],
			'keyword'		=> $_POST['keyword'],
			'description'	=> $_POST['description'],
			'email'			=> $_POST['email'],
			'phone'			=> $_POST['phone'],
			'fax'			=> $_POST['fax'],
			'address'		=> $_POST['address'],
			'contactWho'	=> $_POST['contactWho'],
			'xy'			=> $_POST['xy'],
		);
		
		$result = $db->update('comInfo', $dataArr,"cid='$_POST[cid]'");
		
		if($result){
			header('Location: comInfo.php');
		}else{
			$message .= '数据库插入失败！';
		}
	}
}

$mapStr = "经理：{$comInfo['contactWho']}<br/>电话：{$comInfo['phone']}<br/>传真：{$comInfo['fax']}<br/>邮箱：{$comInfo['email']}<br/>地址：{$comInfo['address']}";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="../require/library/jquery.js" type="text/javascript" charset="utf-8"></script>
<script charset="utf-8" src="../require/library/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="../require/library/kindeditor/lang/zh_CN.js"></script>
<script charset="gb2312" type="text/javascript" src="http://api.map.baidu.com/api?v=1.2"></script>
<script>
	var editor;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="content"]', {
			allowFileManager : true
		});			
	});
</script>
<title>网站参数管理-后台</title>
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
								<td>公司名称</td>
								<td><input type="text" name="name" value="<?=$comInfo['name']?>" /></td>
							</tr>
							<tr>
								<td>LOGO图片</td>
								<td><input type="file" name="img" /></td>
							</tr>
							<tr>
								<td>关键词</td>
								<td><input type="text" name="keyword" value="<?=$comInfo['keyword']?>" /></td>
							</tr>
							<tr>
								<td>说明</td>
								<td><input type="text" name="description" value="<?=$comInfo['description']?>" /></td>
							</tr>
							<tr>
								<td>联系人</td>
								<td><input type="text" name="contactWho" value="<?=$comInfo['contactWho']?>" /></td>
							</tr>
							<tr>
								<td>电话</td>
								<td><input type="text" name="phone" value="<?=$comInfo['phone']?>" /></td>
							</tr>
							<tr>
								<td>传真</td>
								<td><input type="text" name="fax" value="<?=$comInfo['fax']?>" /></td>
							</tr>
							<tr>
								<td>邮箱</td>
								<td><input type="text" name="email" value="<?=$comInfo['email']?>" /></td>
							</tr>
							<tr>
								<td>地址</td>
								<td><input type="text" name="address" value="<?=$comInfo['address']?>" /></td>
							</tr>
							<tr>
								<td>内容</td>
								<td>
									<textarea name="content" style="width:800px;height:400px;visibility:hidden;">
									<?=$comInfo['content']?>
									</textarea>
								</td>
							</tr>
							<tr>
								<td>地图</td>
								<td>
									<input type="hidden" name="xy" value="<?=$comInfo['xy']?>" />
									<div style="width:500px;height:400px;border:1px solid gray" id="container"></div>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<input type="hidden" name="cid" value="<?=$comInfo['cid']?>" />
									<input type="hidden" name=safeCode value="<?=md5($comInfo['cid'].Security_Code)?>" />
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
<script type="text/javascript">
	var map = new BMap.Map("container");

	var point = new BMap.Point(<?=$comInfo['xy']?>);
	
	map.centerAndZoom(point, 15);
	map.addControl(new BMap.NavigationControl());
	map.enableScrollWheelZoom();// 启用滚轮放大缩小。
	var marker = new BMap.Marker(point);  // 创建标注
	map.addOverlay(marker);              // 将标注添加到地图中
	
	var contextMenu = new BMap.ContextMenu();
	var txtMenuItem = [
	  {
	   text:'放置到最大级',
	   callback:function(){map.setZoom(18)}
	  },
	  {
	   text:'查看城市',
	   callback:function(){map.setZoom(10)}
	  },
	  {
	   text:'在此添加标注',
	   callback:function(p){
		map.clearOverlays();
	    var marker = new BMap.Marker(p), px = map.pointToPixel(p);
	    $("input[name='xy']").val(p.lng + "," + p.lat);
	    map.addOverlay(marker);
	   }
	  }
	 ];


	 for(var i=0; i < txtMenuItem.length; i++){
	  contextMenu.addItem(new BMap.MenuItem(txtMenuItem[i].text,txtMenuItem[i].callback,100));
	  if(i==1) {
	   contextMenu.addSeparator();
	  }
	 }
	 map.addContextMenu(contextMenu);
	
	/* 前台用
	var sContent =
		"<div style='width: auto; height: auto;'>"+
		"<h4 style=''><?=$comInfo['name']?></h4>" + 
		"<div><img id='imgDemo' src='http://openapi.baidu.com/map/images/tiananmen.jpg' title='<?=$comInfo['name']?>'/></div>" + 
		"<div style='margin-top:10px;'><?=$mapStr?></div>" + 
		"</div>";
	
	var infoWindow = new BMap.InfoWindow(sContent);  // 创建信息窗口对象
	
	marker.addEventListener("click", function(){          
	   this.openInfoWindow(infoWindow); 
	});

	marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
	map.openInfoWindow(infoWindow, map.getCenter());      // 打开信息窗口
	*/
</script>
</body>
</html>