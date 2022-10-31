<?php session_start();  
	  include('config.php');

  $sql = mysql_query("SELECT EmpCode,Fname,EmpStatus,DateOfSepration,CompanyId FROM hrm_employee WHERE EmployeeID='".$_REQUEST['ID']."'  AND EmpStatus='A' AND (EmpType='E' OR EmpType='M')", $con2); $res = mysql_fetch_assoc($sql); 

  if(mysql_num_rows($sql)==1)
  { 
  
  /************************************************************************/
  if($res['EmpStatus']=='A')
  {  
    $sqly = mysql_query("SELECT YearId FROM financialyear WHERE Status='Active'",$con); $resy = mysql_fetch_assoc($sqly); 
    
    if($_REQUEST['YI']==3){$yy=1;}
    elseif($_REQUEST['YI']==4){$yy=2;}
    elseif($_REQUEST['YI']==5){$yy=3;}
    elseif($_REQUEST['YI']==6){$yy=4;}
    elseif($_REQUEST['YI']==7){$yy=5;}
    
    //echo $yy; die;
	
    $_SESSION['login']=true.',';
    $_SESSION['EmployeeID']=$_REQUEST['ID'];
    $_SESSION['EmpCode']=$res['EmpCode'];
    $_SESSION['Fname']=$res['Fname'];
    $_SESSION['CompanyId']=$res['CompanyId'];
    //$_SESSION['FYearId']=$resy['YearId'];
    $_SESSION['FYearId']=$yy;
    $_SESSION['CheckLogin']='truev';
    $_SESSION['VsplE']='Y';
    $apprsel=mysql_query("select * from hrm_employee_reporting where AppraiserId='".$_REQUEST['ID']."'",$con2);
    if(mysql_num_rows($apprsel)>0){ $_SESSION['EmpRole']='A'; }else{ $_SESSION['EmpRole']='E'; }
    
   setcookie("login", $_SESSION['login'], time() + (86400 * 10), "/");
   setcookie("EmployeeID", $_SESSION['EmployeeID'], time() + (86400 * 10), "/");
   setcookie("EmpCode", $_SESSION['EmpCode'], time() + (86400 * 10), "/");
   setcookie("Fname", $_SESSION['Fname'], time() + (86400 * 10), "/");
   setcookie("EmpRole", $_SESSION['EmpRole'], time() + (86400 * 10), "/");
   setcookie("FYearId", $_SESSION['FYearId'], time() + (86400 * 10), "/");
   setcookie("CompanyId", $_SESSION['CompanyId'], time() + (86400 * 10), "/");
   setcookie("CheckLogin", $_SESSION['CheckLogin'], time() + (86400 * 10), "/");
   setcookie("VsplE", $_SESSION['VsplE'], time() + (86400 * 10), "/");
   
   $_SESSION['aact']=1;
   
    echo "<script>window.location.href = 'home.php?msg=Login Successfull'</script>";
   
  } //if($res['EmpStatus']=='A')
  else{ echo "<script>window.location.href = 'index.php?msg=Something went wrong&msgcolor=danger'</script>"; }
  /************************************************************************/
  

  }   

?>