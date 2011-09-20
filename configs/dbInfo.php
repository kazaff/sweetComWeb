<?php
/**
 * 数据库配置信息
 * @var array $_db
 */
require_once Root_Path.'/../../dbInfo.php';

//请替换成自己的数据库名称
$_db['dbName']	= 'clientManager';

//下面的参数如不设置，一律采用默认值
//$_db['host']		= 'localhost';
//$_db['account']	= 'root';
//$_db['password']	= 'root';
//$_db['port']		= '3306';
//$_db['log']		= 'true';
//$_db['pconnect']	= 'false';

define('Security_Code', '123456');	//安全码，推荐常更换，用于防止篡改提交数据