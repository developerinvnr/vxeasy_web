<?php
date_default_timezone_set('asia/calcutta');
session_start();
include('codeEncDec.php');
$tm=time();
$dat=date('Y-m-d',$tm);
date_default_timezone_set('Asia/Kolkata');
$con2=mysql_connect('localhost','vnressus_hrims_user','hrims@192') or die(mysql_error());
//$con2=mysql_connect('localhost','root','ajaydbajay') or die(mysql_error());
$empdb=mysql_select_db('vnressus_hrims', $con2) or die(mysql_error());

$empcode=$_POST['empcode'];

 $e = ltrim($empcode,$empcode[0]); 

if($e>0 AND (is_numeric($e)))
{
 $emppass=$_POST['emppass'];

 /***********************/
 $qry=mysql_query("select EmpStatus,DateOfSepration,VCode from hrm_employee where EmpCode='$e' AND VCode ='".$empcode[0]."' AND EmployeeID >100000 and CompanyId=".$_POST['empcompany'],$con2); $rqry=mysql_fetch_assoc($qry);
 $DatAcc=date("Y-m-d",strtotime($rqry['DateOfSepration'].'+15 day'));
 
 //echo $rqry['EmpStatus'].'-'.$rqry['VCode']; die;
 
 if($rqry['EmpStatus']=='A' AND $rqry['VCode']==$empcode[0])
 { 
   
  $run_qry=mysql_query("select * from hrm_employee where EmpCode='$e' AND VCode ='".$empcode[0]."' AND EmployeeID >100000 and CompanyId=".$_POST['empcompany']." and EmpStatus='A'",$con2);
  
  //echo "select * from hrm_employee where EmpCode='$e' AND VCode ='".$empcode[0]."' AND EmployeeID >100000 and CompanyId=".$_POST['empcompany']." and EmpStatus='A'"; die;
  
 }
 elseif($rqry['EmpStatus']=='D' AND $DatAcc!='0000-00-00' && $DatAcc!='1970-01-01')
 {
  
  $run_qry=mysql_query("select * from hrm_employee where EmpCode='$e' and CompanyId=".$_POST['empcompany']." and EmpStatus='D' AND '".date("Y-m-d")."'<='".$DatAcc."'",$con2);  
 }
 else{ $num=0; }
 /**********************/
 
 
 //$run_qry=mysql_query("select * from hrm_employee where EmpCode='$empcode' and CompanyId=".$_POST['empcompany']." and EmpStatus='A'",$con2); 
 
 
 $num=mysql_num_rows($run_qry);
 
 //echo 'aa='.$num.' - '; die;
 
 if($num>0)
 {
  $info=mysql_fetch_array($run_qry);
  $emppass1=decrypt($info['EmpPass']); 
  if($emppass==$emppass1)
  { 
   $_SESSION['login']=true;
   $_SESSION['EmployeeID']=$info['EmployeeID'];
   $_SESSION['EmpCode']=$info['EmpCode'];
   $_SESSION['VCode']=$info['VCode'];
   $_SESSION['Fname']=$info['Fname'];
   $_SESSION['CompanyId']=$info['CompanyId'];
   $_SESSION['FYearId']=$_POST['empyear'];
   $_SESSION['CheckLogin']='truev';
   $apprsel=mysql_query("select * from hrm_employee_reporting where AppraiserId='".$info['EmployeeID']."'",$con2);
   if(mysql_num_rows($apprsel)>0){ $_SESSION['EmpRole']='A'; }else{ $_SESSION['EmpRole']='E'; }
   
   setcookie("login", $_SESSION['login'], time() + (86400 * 10), "/");
   setcookie("EmployeeID", $_SESSION['EmployeeID'], time() + (86400 * 10), "/");
   setcookie("EmpCode", $_SESSION['EmpCode'], time() + (86400 * 10), "/");
   setcookie("Fname", $_SESSION['Fname'], time() + (86400 * 10), "/");
   setcookie("EmpRole", $_SESSION['EmpRole'], time() + (86400 * 10), "/");
   setcookie("FYearId", $_SESSION['FYearId'], time() + (86400 * 10), "/");
   setcookie("CompanyId", $_SESSION['CompanyId'], time() + (86400 * 10), "/");
   setcookie("CheckLogin", $_SESSION['CheckLogin'], time() + (86400 * 10), "/");
   echo "<script>window.location.href = 'home.php?msg=Login Successfull'</script>";
      
  }
  else
  { 
   echo "<script>window.location.href = 'index.php?msg=Something went wrong&msgcolor=danger'</script>";   
  }
  
 } //if($num>0)
 else{ echo "<script>window.location.href = 'index.php?msg=Something went wrong&msgcolor=danger'</script>"; }
 
  
}
else
{
 $emppass=md5($_POST['emppass']);
 
 $string=$empcode;
 //$FirstLet=$string[0];
 $FirstLet=substr($string, 0, 1);
 //if($FirstLet=='T'){ $DbName='vnrseed2_expense_tl'; $ComID=4; }
 //elseif($FirstLet=='N'){ $DbName='vnrseed2_expense_nr'; $ComID=3; }
 //else{ $DbName='vnrseed2_expense'; $ComID=1; }
 
 $DbName='vnressus_expense'; $ComID=11;
  
 $con=mysql_connect('localhost','vnressus_hrims_user','hrims@192',true) or die(mysql_error());
 //$con=mysql_connect('localhost','root','ajaydbajay') or die(mysql_error());
 $DbName=mysql_select_db($DbName,$con) or die(mysql_error());
 
 $rqry=mysql_query("select * from hrm_user where EmpCode='".$empcode."' and EmpPass='".$emppass."' and EmpStatus='A'",$con);
 $num=mysql_num_rows($rqry);
 if($num>0)
 {
  $info=mysql_fetch_array($rqry);
  $_SESSION['login']=true;
  $_SESSION['EmployeeID']=$info['EmployeeID'];
  $_SESSION['EmpCode']=$info['EmpCode'];
  $_SESSION['Fname']=$info['Fname'];
  $_SESSION['EmpRole']=$info['EmpRole'];
  $_SESSION['CompanyId']=$ComID;
  $_SESSION['FYearId']=$_POST['empyear'];
  $_SESSION['CheckLogin']='truev';
  
  setcookie("login", $_SESSION['login'], time() + (86400 * 10), "/");
  setcookie("EmployeeID", $_SESSION['EmployeeID'], time() + (86400 * 10), "/");
  setcookie("EmpCode", $_SESSION['EmpCode'], time() + (86400 * 10), "/");
  setcookie("Fname", $_SESSION['Fname'], time() + (86400 * 10), "/");
  setcookie("EmpRole", $_SESSION['EmpRole'], time() + (86400 * 10), "/");
  setcookie("FYearId", $_SESSION['FYearId'], time() + (86400 * 10), "/");
  setcookie("CompanyId", $_SESSION['CompanyId'], time() + (86400 * 10), "/");
  setcookie("CheckLogin", $_SESSION['CheckLogin'], time() + (86400 * 10), "/");
  
  if(date("m")==1){$m=12;}else{$m=date("m")-1;} $m='';
  echo "<script>window.location.href = 'home.php?msg=Login Successfull&action=displayrec&v=".$m."&chkval=2&ve=0&sts=Submitted'</script>";
  
  /*echo "<script>window.location.href = 'home.php?msg=Login Successfull'</script>";*/
  
  }//if($num>0)
  else
  { 
   echo "<script>window.location.href = 'index.php?msg=Something went wrong&msgcolor=danger'</script>";   
  }
   
}


?>