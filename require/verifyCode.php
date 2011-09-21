<?php
require_once './initialization.php';
require_once Root_Path.'/require/class/ValidateCode.php';

$vc = new Authnum(); 
$vc->ext_num_type=''; 
$vc->ext_pixel = true; //干扰点 
$vc->ext_line = true; //干扰线 
$vc->ext_rand_y= true; //Y轴随机 
//$vc->green = 238; 
$vc->create();