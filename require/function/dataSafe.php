<?php
/* 
	函数名称：inject_check() 
	函数作用：检测提交的值是不是含有SQL注射的字符，防止注射，保护服务器安全 
	参　　数：$sql_str: 提交的变量 
	返 回 值：返回检测结果，ture or false 
*/ 
function inject_check($sql_str)
{
     return preg_match('/select|insert|update|delete|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/', $sql_str); // 进行过滤 
}

function sql_injection($content)
	{
		if (!get_magic_quotes_gpc()) {
		if (is_array($content)) {
			foreach ($content as $key=>$value) {
				$content[$key] = addslashes($value);
			}
		} else {
			addslashes($content);
		}
	}
	return $content;
}