<?php 

session_start();

//if($_SESSION['CompanyId']==1){ $DbName='vnrseed2_expense'; }
//elseif($_SESSION['CompanyId']==3){ $DbName='vnrseed2_expense_nr'; }
//elseif($_SESSION['CompanyId']==4){ $DbName='vnrseed2_expense_tl'; }

$DbName='vnressus_expense';
       
define('HOST','localhost');
define('USER','vnressus_hrims_user'); 
define('PASS','hrims@192'); 
define('dbexpro',$DbName);
define('dbemp','vnressus_hrims');  
define('CHARSET','utf8'); 
$con2=mysql_connect(HOST,USER,PASS) or die(mysql_error());
$empdb=mysql_select_db(dbemp, $con2) or die(mysql_error());
$con=mysql_connect(HOST,USER,PASS,true) or die(mysql_error());
$exprodb=mysql_select_db(dbexpro,$con) or die(mysql_error());
mysql_query("SET NAMES utf8");
date_default_timezone_set('Asia/Kolkata');



?>
