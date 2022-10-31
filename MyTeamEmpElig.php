<?php include 'config.php'; ?>
<html>
<head>
<title>Eligibility</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link type="text/css" href="css/body.css" rel="stylesheet" />
<link type="text/css" href="css/pro_dropdown_3.css" rel="stylesheet"/>
<style>.font { color:#ffffff; font-family:Georgia; font-size:11px; width:200px;} .font1 { font-family:Georgia; font-size:11px; height:14px; } 
.font2 { font-size:11px;width:260px;height:18px;}.fontButton { background-color:#7a6189; color:#009393; font-family:Georgia; font-size:13px;} .TableHead { font-family:Times New Roman; color:#FFFFFF; font-size:15px; }
.TableHead1 { font-family:Times New Roman; color:#000000; background-color:#FFFFFF; font-size:13px; overflow:hidden; } .InputText {font-family:Times New Roman; font-size:12px; width:100px; height:19px; }
.CalenderButton {background-image:url(images/CalenderBtn.jpeg); width:16px; height:16px; background-color:#E0DBE3; border-color:#FFFFFF;}</style>
</head>
<body class="body">
<?php 
$Sql=mysql_query("SELECT EmpCode,Fname,Sname,Lname,CompanyId,Married,Gender,DR FROM hrm_employee INNER JOIN hrm_employee_general ON hrm_employee.EmployeeID=hrm_employee_general.EmployeeID INNER JOIN hrm_employee_personal ON hrm_employee.EmployeeID=hrm_employee_personal.EmployeeID WHERE hrm_employee.EmployeeID=".$_REQUEST['ei'], $con2); $ResRep=mysql_fetch_array($Sql);

if($ResRep['DR']=='Y'){$MS='Dr.';} elseif($ResRep['Gender']=='M'){$MS='Mr.';} elseif($ResRep['Gender']=='F' AND $ResRep['Married']=='Y'){$MS='Mrs.';} elseif($ResRep['Gender']=='F' AND $ResRep['Married']=='N'){$MS='Miss.';}  $EmpName=$MS.' '.$ResRep['Fname'].' '.$ResRep['Sname'].' '.$ResRep['Lname']; 
?>
<table class="table">
<tr>
 <td>
<?php //*************************************************************************************************************************************************** ?>	   
		     <table border="0" id="Activity">
			  <tr style="height:25px;font-family:Times New Roman;font-size:16px;" valign="middle">
			   <td style="text-align:center; font-size:18px;">
			      <b>[Eligibility]<br>
			      EC:<?php echo $ResRep['EmpCode'].'/ Name:'.$EmpName; ?></b>
			   </td>
			  </tr>
			  <tr>
				 <td align="left" width="700" valign="top">
	     <table border="0" width="700">
		    <tr><td style="height:5px;"></td></tr>
			<tr>
			 <td>
			<table border="1">
<?php $SqlE=mysql_query("SELECT GradeId, EmpAddBenifit_MediInsu_value FROM hrm_employee_general INNER JOIN hrm_employee_ctc ON hrm_employee_general.EmployeeID=hrm_employee_ctc.EmployeeID WHERE hrm_employee_general.EmployeeID=".$_REQUEST['ei'], $con2); $ResE=mysql_fetch_assoc($SqlE); 

$sqlGrade=mysql_query("select GradeValue from hrm_grade where GradeId=".$ResE['GradeId'], $con2); $resGrade=mysql_fetch_assoc($sqlGrade);
      if($resGrade['GradeValue']!='')
	  {
      $sqlLod=mysql_query("select * from hrm_lodentitle where GradeValue='".$resGrade['GradeValue']."'", $con2); $resLod=mysql_fetch_assoc($sqlLod); 
	  $sqlDaily=mysql_query("select * from hrm_dailyallow where GradeValue='".$resGrade['GradeValue']."'", $con2); $resDaily=mysql_fetch_assoc($sqlDaily);
	  $sqlEnt=mysql_query("select * from hrm_travelentitle where GradeValue='".$resGrade['GradeValue']."'", $con2); $resEnt=mysql_fetch_assoc($sqlEnt);
	  $sqlElig=mysql_query("select * from hrm_traveleligibility where GradeValue='".$resGrade['GradeValue']."'", $con2); $resElig=mysql_fetch_assoc($sqlElig); 
	  } 	  
$SqlEligEmp = mysql_query("SELECT * FROM hrm_employee_eligibility WHERE EmployeeID=".$_REQUEST['ei']." AND Status='A'", $con2) or die(mysql_error());  $ResEligEmp=mysql_fetch_assoc($SqlEligEmp); 
?>
<?php $sqlD2=mysql_query("select DepartmentId from hrm_department where DepartmentName='Sales' AND DepartmentCode='SALES'", $con2); $resD2=mysql_fetch_assoc($sqlD2);
      $sqlP2=mysql_query("select DepartmentId from hrm_department where DepartmentName='Production' AND DepartmentCode='PRODUCTION'", $con2); $resP2=mysql_fetch_assoc($sqlP2);
?>
  <input type="hidden" id="D2" class="All_100" value="<?php echo $resD2['DepartmentId']; ?>"/>  
  <input type="hidden" id="P2" class="All_100" value="<?php echo $resP2['DepartmentId']; ?>"/>
  <input type="hidden" id="DeId" class="All_100" value="<?php echo $ResE['DepartmentId']; ?>"/> 	
  		
<tr>
 <td align="center" bgcolor="#7a6189">
   <table border="1" width="685" bgcolor="#ffffff" cellspacing="0">
		  <td style="width:685px;font-size:18px;font-family:Times New Roman;color:#000000;" align="center">
		  
	<?php /***** Start ******/ ?>			
			 <table style="width:685px;" border="0">
			   <tr><td style="height:5px;"></td></tr>
			  <tr><td colspan="2" style="width:685px;"><b>1.&nbsp;&nbsp;</b>&nbsp;Lodging :&nbsp;&nbsp; Actual with upper limit Per day as mentioned in the table.</td></tr>
			  <tr>
			   <td style="width:685px;" colspan="2">
			    <table border="1" style="width:685px;" cellspacing="0">
				<tr>
				<td style="width:175px;font-size:16px;" align="">&nbsp;</td>
				<td style="width:170px;font-size:16px;" align="center">Category A</td>
				<td style="width:170px;font-size:16px;" align="center">Category B</td>
				<td style="width:170px;font-size:16px;" align="center">Category C</td>
				</tr>
                <tr>
                <td style="width:175px;font-size:16px;" align="center">&nbsp;<b>Rs.</b></td>
                 <td style="width:170px;" align="center">
  				<?php if($ResEligEmp['Lodging_CategoryA']>0){echo intval($ResEligEmp['Lodging_CategoryA']);}elseif($ResEligEmp['Lodging_CategoryA']!=''){echo $ResEligEmp['Lodging_CategoryA'];} ?></td>
  				<td style="width:170px;" align="center">
  				<?php if($ResEligEmp['Lodging_CategoryB']>0){echo intval($ResEligEmp['Lodging_CategoryB']);}elseif($ResEligEmp['Lodging_CategoryB']!=''){echo $ResEligEmp['Lodging_CategoryA'];} ?></td>
 			    <td style="width:170px;" align="center">
  				<?php if($ResEligEmp['Lodging_CategoryC']>0){echo intval($ResEligEmp['Lodging_CategoryC']);}elseif($ResEligEmp['Lodging_CategoryC']!=''){echo $ResEligEmp['Lodging_CategoryC'];} ?></td>
				</tr>
				</table>
			   </td>
			  </tr>
			  <tr>
			    <td style="width:485px;font-size:16px;height:22px;" align="left"><b>2.&nbsp;&nbsp;</b>&nbsp;DA Outside H.Q. :</td>
				<td style="width:200px;">: <?php if($ResEligEmp['DA_Outside_Hq']!='' AND $ResEligEmp['DA_Outside_Hq']=='Actual') {echo $ResEligEmp['DA_Outside_Hq'].'&nbsp;&nbsp;Per day';}elseif($ResEligEmp['DA_Outside_Hq']!='' AND $ResEligEmp['DA_Outside_Hq']!='Actual') {echo 'Rs.&nbsp;'.$ResEligEmp['DA_Outside_Hq'].'&nbsp;&nbsp;/-Per day'; }else{echo 'NA';} ?></td>
			  </tr>
			   <tr>
			    <td style="width:485px;font-size:16px;height:22px;" align="left"><b>3.&nbsp;&nbsp;</b>&nbsp;DA @ H.Q. :</td>
				<td style="width:200px;">: <?php if($ResEligEmp['DA_Inside_Hq']!='' AND $ResEligEmp['DA_Inside_Hq']==' '){ echo $ResEligEmp['DA_Inside_Hq'].'&nbsp;&nbsp;Per day';} elseif($ResEligEmp['DA_Inside_Hq']!='' AND $ResEligEmp['DA_Inside_Hq']!=' '){ echo 'Rs.&nbsp;'.$ResEligEmp['DA_Inside_Hq'].'&nbsp;&nbsp;/-Per day';} else {echo 'NA';} ?></td>
			  </tr>
			  
			  
			  
			  <tr>
			    <td style="width:485px;font-size:16px;height:22px;" align="left"><b>4.&nbsp;&nbsp;</b>&nbsp;Travel Eligibility <font size="4"><b>*</b></font> (For Official Purpose Only)</td>
				<td style="width:200px;">&nbsp;</td>
			  </tr>
			  <tr>
			    <td style="width:485px;font-size:16px;height:22px;" align="left">&nbsp;&nbsp;a) 2 Wheeler (<?php echo '<b>Rs&nbsp;'.$ResEligEmp['Travel_TwoWeeKM'].'/KM&nbsp;-&nbsp;Maxi:&nbsp;'.$ResEligEmp['Travel_TwoWeeLimitPerDay'].'/Day&nbsp;-&nbsp;'.$ResEligEmp['Travel_TwoWeeLimitPerMonth'].'/Month</b>'; ?>)</td>
				<td style="width:200px;">: <?php if($ResEligEmp['Travel_TwoWeeKM']!=''){echo 'Applicable';} else {echo 'NA';}?></td>
			  </tr>
			   <tr>
			    <td style="width:485px;font-size:16px;height:22px;" align="left">&nbsp;&nbsp;a) 4 Wheeler (<?php if($ResEligEmp['Travel_FourWeeKM']!='' AND $ResEligEmp['Travel_FourWeeKM']!='NA' AND $ResEligEmp['Travel_FourWeeLimitPerMonth']!=''){ if($ResEligEmp['Travel_FourWeeLimitPerMonth']>0){$PerA=$ResEligEmp['Travel_FourWeeLimitPerMonth']*12; $PerAnnum=$PerA.'&nbsp;KM PA';}elseif($ResEligEmp['Travel_FourWeeLimitPerMonth']=='Actual'){$PerAnnum='Actual&nbsp;KM PA';} else{$PerAnnum='';} echo '<b>Rs&nbsp;'.$ResEligEmp['Travel_FourWeeKM'].'/KM&nbsp;-&nbspMaxi:&nbsp<b>'.$ResEligEmp['Travel_FourWeeLimitPerMonth'].'/Month&nbsp;-&nbsp;'.$PerAnnum;} ?>)</td>
				<td style="width:200px;">: <?php if($ResEligEmp['Travel_FourWeeKM']!='' AND $ResEligEmp['Travel_FourWeeKM']!='NA' AND $ResEligEmp['Travel_FourWeeLimitPerMonth']!='' AND $ResEligEmp['Travel_FourWeeLimitPerMonth']!='NA'){echo 'Applicable';} else {echo 'NA';}?></td>
			  </tr>
			   <tr>
			    <td style="width:485px;font-size:16px;height:22px;" align="left"><b>5.&nbsp;&nbsp;</b>&nbsp;Mode of Travel outside HQ</td>
				<td style="width:200px;">: <?php if($ResEligEmp['Mode_Travel_Outside_Hq']!=''){echo $ResEligEmp['Mode_Travel_Outside_Hq'];}else{echo 'NA';}?></td>
			  </tr>
			   <tr>
			    <td style="width:485px;font-size:16px;height:22px;" align="left"><b>6.&nbsp;&nbsp;</b>&nbsp;Travel Class</td>
				<td style="width:200px;">: <?php if($ResEligEmp['Mode_Travel_Outside_Hq']!=''){echo $ResEligEmp['TravelClass_Outside_Hq'];}else{echo 'NA';}?></td>
			  </tr>
			   <tr>
			    <td style="width:485px;font-size:16px;height:22px;" align="left"><b>7.&nbsp;&nbsp;</b>&nbsp;Mobile expenses Reimbursement :</td>
				<td style="width:200px;">: <?php if($ResEligEmp['Mobile_Exp_Rem_Rs']!=''){ if($ResEligEmp['Mobile_Exp_Rem_Rs']!='Actual'){echo 'Rs.&nbsp;';} echo $ResEligEmp['Mobile_Exp_Rem_Rs'].'/-Month';}else{echo 'NA';}?></td>
			  </tr>
			   <tr>
			    <td style="width:485px;font-size:16px;height:22px;" align="left"><b>8.&nbsp;&nbsp;</b>&nbsp;Mobile Handset Eligibility </td>
				<td style="width:200px;">: <?php if($ResEligEmp['Mobile_Company_Hand']=='Y'){ echo 'Company Handset';} elseif($ResEligEmp['Mobile_Company_Hand']=='N' AND $ResEligEmp['Mobile_Hand_Elig_Rs']!=''){ if($ResEligEmp['Mobile_Hand_Elig_Rs']!='Actual' AND $ResEligEmp['Mobile_Hand_Elig_Rs']!='Actual/ blackberry') {echo 'Rs.&nbsp;';} echo $ResEligEmp['Mobile_Hand_Elig_Rs']; if($ResEligEmp['Mobile_Hand_Elig_Rs']!='Actual' AND $ResEligEmp['Mobile_Hand_Elig_Rs']!='Actual/ blackberry') {echo '/-';}}else{echo 'NA';}?></td>
			  </tr>
			  <tr>
			    <td style="width:485px;font-size:16px;height:22px;" align="left"><b>9.&nbsp;&nbsp;</b>&nbsp;Misc. expenses(like stationery/photocopy/fax/e-mail/etc) </td>
				<td style="width:200px;">: <?php if($ResEligEmp['Misc_Expenses']=='Y'){echo 'Actual';}else{echo 'NA';}?></td>
			  </tr>
			  
			  <tr>
			    <td style="width:485px;font-size:16px;height:22px;" align="left"><b>12.</b>&nbsp;Gratuity </td>
				<td style="width:200px;">: As Per Law</td>
			  </tr>
			  <tr>
			    <td style="width:485px;font-size:16px;height:22px;" align="left"><b>13.</b>&nbsp;Deduction</td>
				<td style="width:200px;">&nbsp;</td>
			  </tr>
			  <tr>
			   <td colspan="2" style="width:685px;">
			    <table>
				 <tr>
				  <td style="width:285px;font-size:16px;height:22px;" align="left">&nbsp;&nbsp;&nbsp;&nbsp;* Deduction - As Per Law</td>
				  <td style="width:400px;">: Provident Fund/ Tax on Employment/ Income Tax</td>
				 </tr>
				 <tr>
				  <td style="width:285px;font-size:16px;height:22px;" align="left">&nbsp;&nbsp;&nbsp;&nbsp;* Deduction - Actual</td>
				  <td style="width:400px;">: Any dues to company(if any)/ Advances</td>
				 </tr>
				</table>
			   </td>
			  </tr>
			  <tr><td colspan="2" style="width:685px;background-color:#000000;height:1px;"></td></tr>
			  <tr><td colspan="2" style="width:685px;"><b style="font-size:18px;text-align:justify;">*</b>&nbsp;The expenses claims on 2 wheeler/4 wheeler is subject to the employee having a valid driving license. </td></tr>
			  <tr><td colspan="2" style="width:685px;">&nbsp;&nbsp;&nbsp;The photocopy should be provided to HR.</td></tr>
			  <tr><td style="height:5px;"></td></tr>
			 </table>
<?php /***** Close ******/ ?>			

</td>
</tr>
</table>
			
<?php //*************************************************************************************************************************************************** ?>
 </td>
</tr>
</table>
</body>
</html>

