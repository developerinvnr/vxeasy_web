
<?php
session_start();
if(!isset($_SESSION['login'])){
  session_destroy();
  header('location:index.php');
}
include 'config.php';
// echo 'aaa'.$_SESSION['EmpCode'].'---'.$_SESSION['EmployeeID'];

?>
<?php



/*
====================================================================================================
		setting fare details table visibility settings
====================================================================================================
*/
switch ($_SESSION['EmpRole']) {
    case 'E':
    	$title='readonly';
        $astate='readonly';
        $vastate='readonly';
        $aastate='readonly';
        $fastate='readonly';
		$Amount='';
		$VerifierEditAmount='';
		$ApproverEditAmount='';
		$FinanceEditAmount='';
        break;
    case 'M':
    	$title='readonly required';
        $astate='readonly';
        $vastate='readonly';
        $aastate='readonly';
        $fastate='readonly';
        $Amount='transparent';
		$VerifierEditAmount='';
		$ApproverEditAmount='';
		$FinanceEditAmount='';
        break;
    case 'V':
    	$title='readonly';
        $astate='readonly';
        $vastate='';
        $aastate='readonly';
        $fastate='readonly';
        $Amount='readonly';
		$VerifierEditAmount='transparent';
		$ApproverEditAmount='';
		$FinanceEditAmount='';
        break;
    case 'A':
    	$title='readonly';
        $astate='readonly';
        $vastate='readonly';
        $aastate='';
        $fastate='readonly';
        $Amount='';
		$VerifierEditAmount='';
		$ApproverEditAmount='transparent';
		$FinanceEditAmount='';
        break;
    case 'F':
    	$title='readonly';
        $astate='readonly';
        $vastate='readonly';
        $aastate='readonly';
        $fastate='';
        $Amount='';
		$VerifierEditAmount='';
		$ApproverEditAmount='';
		$FinanceEditAmount='transparent';
        break;
}


if(isset($exp['ClaimStatus'])){
	if($exp['ClaimStatus']=='Filled'){$astate='readonly';$Amount='';$title='readonly';}
	if($exp['ClaimStatus']=='Verified'){$vastate='readonly';$VerifierEditAmount='';}
	if($exp['ClaimStatus']=='Approved'){$aastate='readonly';$ApproverEditAmount='';}
	if($exp['ClaimStatus']=='Financed'){$fastate='readonly';$FinanceEditAmount='';}
}
/*
====================================================================================================
		setting fare details table visibility settings
====================================================================================================
*/

$cg=mysql_query("select cgId from claimtype where ClaimId=".$_REQUEST['claimid']);
$cgd=mysql_fetch_assoc($cg);


$e=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_REQUEST['expid']);
$exp=mysql_fetch_assoc($e);

$ef=mysql_query("select * from y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata where ExpId=".$_REQUEST['expid']);


$expf=mysql_fetch_assoc($ef);



if($_SESSION['EmpRole']=='M'){ $actform='updateclaim.php'; }
elseif($_SESSION['EmpRole']=='V'){ $actform='updateclaimV.php'; }
elseif($_SESSION['EmpRole']=='A'){ $actform='updateclaimA.php'; }
elseif($_SESSION['EmpRole']=='F'){ $actform='updateclaimF.php'; }



if($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==1){ 
	/*
	====================================================================================================
			$_POST['claimid']==1      Lodging form 
	====================================================================================================
	*/


$BillDate = ($expf['BillDate']  != '0000-00-00' && $expf['BillDate']  != '') ? date("d-m-Y",strtotime($expf['BillDate'])) : date("d-m-Y",strtotime($exp['CrDate']));
$arrdate  = ($expf['Arrival']   != '0000-00-00 00:00:00' && $expf['Arrival']   != '') ? date("d-m-Y H:i",strtotime($expf['Arrival'])) : '';
$depdate  = ($expf['Departure'] != '0000-00-00 00:00:00' && $expf['Departure'] != '') ? date("d-m-Y H:i",strtotime($expf['Departure'])) : '';

?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
			
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				<tr >
				<th scope="row">City Category</th>
				<td>
					<select type="text" class="form-control" id="CityCategory" name="CityCategory" readonly>
				    	<option value="A">A</option>
				    	<option value="B">B</option>
				    	<option value="C">C</option>
				    </select>
				</td>
				<script type="text/javascript">
					$("#CityCategory option[value=<?=$expf['CityCategory']?>]").attr('selected', 'selected');
				</script>
				
				</tr>


				<tr >
				<th scope="row">Hotel Name</th>
				<td><input type="text" class="form-control" name="HotelName" value="<?=$expf['HotelName']?>" readonly></td>
				<th scope="row">Hotel Address</th>
				<td><input class="form-control" name="HotelAddress" value="<?=$expf['HotelAddress']?>" readonly></td>
				</tr>
				
				<tr >
				<th scope="row">Billing Person </th>
				<td><input type="text" class="form-control" name="BillingName" value="<?=$expf['BillingName']?>" readonly></td>
				<th scope="row">Billing address</th>
				<td><input class="form-control" name="BillingAddress" value="<?=$expf['BillingAddress']?>" readonly></td>
				</tr>
				
				<tr>
				<th scope="row">Bill No. </th>
				<td><input type="text" class="form-control" name="BillNo" value="<?=$expf['BillNo']?>" readonly></td>
				<th scope="row">Bill date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly></td>
				</tr>
				
				<tr>
				<th scope="row">Arr. date/time</th>
				<td>
					<input id="arrdate" name="arrdate" value="<?=$arrdate?>" placeholder="Arrival" class="form-control" readonly>	
				</td>
				<th scope="row">Dept. date/time</th>
				<td>
					<input id="depdate" name="depdate" value="<?=$depdate?>" placeholder="Departure" class="form-control" readonly>
					
				</td>
				</tr>
				
				<tr>
				<th scope="row">Duration of stay</th>
				<td><input type="text" class="form-control" name="StayDuration" value="<?=$expf['StayDuration']?>" readonly></td>
				<th scope="row">Room rate/type</th>
				<td><input type="text" class="form-control" name="RoomRateType" value="<?=$expf['RoomRateType']?>" readonly></td>
				</tr>
				
				<tr>
				<th scope="row">Meal Plan</th>
				<td><select type="text" class="form-control" name="MealPlan" value="<?=$expf['Plan']?>" readonly>
				    <option value="AP">American Plan</option><option value="MAP">Modify American Plan</option><option value="EP">European plan</option><option value="CP">Continental plan</option></select>
				
				</td>
				<th scope="row">No. of pax</th>
				<td>
					<select type="text" class="form-control" id="NoOfPAX" name="NoOfPAX" readonly>
					    <option value="One">1</option>
					    <option value="Two">2</option>
					    <option value="Three">3</option>
					    <option value="Four">4</option>
					    <option value="Five">5</option>
					    <option value="Six">6</option>
					    <option value="Seven">7</option>
					    <option value="Eight">8</option>
					    <option value="Nine">9</option>
					    <option value="Ten">10</option>
					</select>

				    <script type="text/javascript">
						$("#NoOfPAX option[value=<?=$expf['NoOfPAX']?>]").attr('selected', 'selected');
					</script>

				</td>
				</tr>
				
				<tr>
				<th scope="row">GST/Tax Rate</th>
				<td><input type="text" class="form-control" name="GST" value="<?=$expf['GST']?>" readonly></td>
				
				<th scope="">Billing instruction</th>
				<td>
					<select type="text" class="form-control" id="BillIns" name="BillIns" readonly>
						<option value="Direct">Direct</option>
						<option value="Bill to Company">Bill to Company</option>
					</select>
					<script type="text/javascript">
						$("#BillIns option[value=<?=$expf['BillingInstruction']?>]").attr('selected', 'selected');
					</script>
				</td>
				</tr>

				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								
								<tr>
			<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
			<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
			<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
			<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
			<?php if($_SESSION['EmpRole']!='M'){ ?>
			<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
				<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
			<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
			<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
				<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
			<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
			<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
				<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
			<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
			<?php }?>


			<?php if($_SESSION['EmpRole']=='M'){ ?>
			<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
			<?php } ?>
									
		  </tr>
		  <?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">


						

						

				      	<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftLodging" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>

						<button class="btn btn-sm btn-success" id="Update" name="UpdateLodging" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==2){
	/*
	====================================================================================================
			$_POST['claimid']==2      Air,Rail&Bus Fare Form
	====================================================================================================
	*/



$BookingDate=($expf['BookingDate']!='0000-00-00' && $expf['BookingDate']!='') ? date("d-m-Y",strtotime($expf['BookingDate'])) : '';
$JourneyStartDt=($expf['JourneyStartDt']!='0000-00-00' && $expf['JourneyStartDt']!='') ? date("d-m-Y",strtotime($expf['JourneyStartDt'])) : '';

?>
<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">
				

				<!-- <tr >
				<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
				<td colspan="3">
					<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" readonly value="<?=$exp['ExpenseName']?>">
				</td>
				
				</tr> -->
			
				<tr >
					<th scope="row">Mode&nbsp;<span class="text-danger">*</span></th>
					<td>
						<select class="form-control" id="Mode" name="Mode" readonly onchange="changemode(this.value)">
							<!--<option></option>-->
							<option value="Air" <?php echo $expf['Mode']=='Air'?'selected':'';?>>Air</option>
							<option value="Rail" <?php echo $expf['Mode']=='Rail'?'selected':'';?>>Rail</option>
							<option value="Bus" <?php echo $expf['Mode']=='Bus'?'selected':'';?>>Bus</option>
						</select>
					</td>
					<th scope="row"><span id="modenm">Flight</span> Name&nbsp;<span class="text-danger">*</span></th>
					<td>
						<input type="text" class="form-control" id="TrainBusName" name="TrainBusName" readonly value="<?=$expf['TrainBusName']?>">
					</td>
				</tr>
				
				<tr >
				<th scope="row">Quota&nbsp;<span class="text-danger">*</span></th>
				<td>
					<select class="form-control" id="Quota" readonly name="Quota">
						<!--<option></option>-->
						<option value="GN" <?php echo $expf['Quota']=='GN'?'selected':'';?>>GN</option>
						<option value="Tatkal" <?php echo $expf['Quota']=='Tatkal'?'selected':'';?>>Tatkal</option>
						<option value="Bus" <?php echo $expf['Quota']=='Bus'?'selected':'';?>>Pr. Tatkal</option>
					</select>
				</td>
				<th scope="row">Class&nbsp;<span class="text-danger">*</span></th>
				<td>
					
					<select class="form-control" id="Class" readonly name="Class">
						<!--<option></option>-->
						<option value="CC" <?php echo $expf['Class']=='CC'?'selected':'';?>>CC</option>
						<option value="SL" <?php echo $expf['Class']=='SL'?'selected':'';?>>SL</option>
						<option value="AC" <?php echo $expf['Class']=='AC'?'selected':'';?>>AC</option>
						<option value="Economy" <?php echo $expf['Class']=='Economy'?'selected':'';?>>Economy</option>
						<option value="Economy AC" <?php echo $expf['Class']=='Economy AC'?'selected':'';?>>Economy AC</option>
						<option value="Business" <?php echo $expf['Class']=='Business'?'selected':'';?>>Business</option>
					</select>
				</td>
			
				</tr>
				
				<tr>
				<th scope="row">Booking Date&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="BookingDate" name="BookingDate" readonly autocomplete="off" value="<?=$BookingDate?>"></td>
				<th scope="row">Journey Date&nbsp;<span class="text-danger">*</span></th>
				<td><input  class="form-control dat" id="rbJourneyStartDt" name="JourneyStartDt" readonly autocomplete="off" value="<?=$JourneyStartDt?>"></td>
				</tr>
				
				
				<tr>
				<th scope="row">Journey from&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyFrom" name="JourneyFrom" readonly value="<?=$expf['JourneyFrom']?>"></td>
				<th scope="row">Journey Upto&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyUpto" name="JourneyUpto" readonly value="<?=$expf['JourneyUpto']?>"></td>
				</tr>
				
				<tr>
				<th scope="row">Passenger Detail</th>
				<td><input type="text" class="form-control" id="PassengerDetail" name="PassengerDetail"  value="<?=$expf['PassengerDetail']?>" readonly></td>
				<th scope="row">Booking Status&nbsp;<span class="text-danger">*</span></th>
				<td>
					<select class="form-control" id="BookingStatus" readonly name="BookingStatus">
						<!--<option></option>-->
						<option value="Confirmed" <?php echo $expf['BookingStatus']=='Confirmed'?'selected':'';?>>Confirmed</option>
						<option value="Waiting" <?php echo $expf['BookingStatus']=='Waiting'?'selected':'';?>>Waiting</option>
						
					</select>
				</td>
				</tr>
				
				<tr>
				<th scope="row">Travel Insurance&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="TravelInsurance" name="TravelInsurance" readonly value="<?=$expf['TravelInsurance']?>"></td>
				
				<!-- <th scope="">Total Fare</th>
				<td><input type="text" class="form-control" id="TotalFare" name="TotalFare"  value="<?=$exp['TotalFare']?>" readonly></td>
				</tr> -->
				
				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --></td>	
				</tr>
				
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									
									<td>
										<input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>>
									</td>
									<td>
										<input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>>
									</td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td>
										<?php
										if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';}
										?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>>
									</td>
									
									<td>
										<?php
										if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}
										?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>>
									</td>
									
									<td>
										<?php
										if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}
										?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>>
									</td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td style="width:20px;text-align:center;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button>
									</td>
									<?php } ?>
									
								</tr>
								
								<?php
								$i++;
								}
								?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>
						

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				
				
				</tr><?php */?>

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" id="Remark" name="Remark" value="<?=$exp['Remark']?>">

				      	<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?> 
				      	<button class="btn btn-sm btn-info" id="draft" name="draftRailBusFare" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
                        <?php } ?>
 
						<button class="btn btn-sm btn-success" id="Update" name="UpdateRailBusFare" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>


					</td>
				</tr>
			
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==3){
	/*
	====================================================================================================
			$_POST['claimid']==3      Local Conveyance form 
	====================================================================================================
	*/


$JourneyStartDt=($expf['JourneyStartDt']!='0000-00-00' && $expf['JourneyStartDt']!='') ? date("d-m-Y",strtotime($expf['JourneyStartDt'])) : '';
$JourneyEndDt=($expf['JourneyEndDt']!='0000-00-00' && $expf['JourneyEndDt']!='') ? date("d-m-Y",strtotime($expf['JourneyEndDt'])) : '';


?>
<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				
				<!-- <tr >
					<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
					<td colspan="3">
						<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" readonly value="<?=$exp['ExpenseName']?>">
					</td>
				
				</tr> -->

				<tr>
					<th scope="row">Trip Started On&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control dat" id="JourneyStartDt1" name="JourneyStartDt" value="<?=$JourneyStartDt?>" readonly required></td>
					<th scope="row">Trip Ended On&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="JourneyEndDt" name="JourneyEndDt" value="<?=$JourneyEndDt?>" readonly required></td>
				</tr>

				<tr >
					<th scope="row">Mode&nbsp;<span class="text-danger">*</span></th>
					<td>
						<!-- <input class="form-control" id="Mode" name="Mode" value="<?php $expf['Mode']; ?>" required readonly /> -->
						<select class="form-control" id="Mode" name="Mode" required readonly >
							<option></option>
							<option value="Sharing Taxi / Cab" <?php if($expf['Mode']=='Sharing Taxi / Cab'){echo 'selected';}?>>Sharing Taxi / Cab</option>
							<option value="Auto" <?php if($expf['Mode']=='Auto'){echo 'selected';}?>>Auto</option>
							<option value="Bus" <?php if($expf['Mode']=='Bus'){echo 'selected';}?>>Bus</option>
							
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --></td>	
				</tr>
				
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];
									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									
									<td>
										<input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>>
									</td>
									<td>
										<input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>>
									</td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td>
										<?php
										if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';}
										?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>>
									</td>
									
									<td>
										<?php
										if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}
										?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>>
									</td>
									
									<td>
										<?php
										if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}
										?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>>
									</td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;">
										<button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;">
											<i class="fa fa-times fa-sm" aria-hidden="true"></i>
										</button>
									</td>
									<?php } ?>
									
								</tr>
								
								<?php
								$i++;
								}
								?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet(<?=$exp['ClaimId']?>)">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>
				
				

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" ><?=$exp['Remark']?></textarea></td>
				
				
				</tr><?php */?>

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" id="Remark" name="Remark" value="<?=$exp['Remark']?>">


						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                         <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?> 
				      	<button class="btn btn-sm btn-info" id="draft" name="draftLocalConv" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
                         <?php } ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateLocalConv" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

					</td>
				</tr>
			
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==4){
	/*
	====================================================================================================
			$_POST['claimid']==4      Hired Vehicle form 
	====================================================================================================
	*/



$BillDate = ($expf['BillDate']  != '0000-00-00' && $expf['BillDate']  != '') ? date("d-m-Y",strtotime($expf['BillDate'])) : date("d-m-Y",strtotime($exp['CrDate']));
$JourneyStartDt  = ($expf['JourneyStartDt'] != '0000-00-00' && $expf['JourneyStartDt'] != '') ? date("d-m-Y",strtotime($expf['JourneyStartDt'])) : '';
$JourneyEndDt  = ($expf['JourneyEndDt'] != '0000-00-00' && $expf['JourneyEndDt'] != '') ? date("d-m-Y",strtotime($expf['JourneyEndDt'])) : '';


$DailyBasisCharges = ($expf['DailyBasisCharges']  != '0') ? $expf['DailyBasisCharges'] : '';
$KmBasisCharges = ($expf['KmBasisCharges']  != '0') ? $expf['KmBasisCharges'] : '';
$DriverCharges = ($expf['DriverCharges']  != '0') ? $expf['DriverCharges'] : '';
$OtherCharges = ($expf['OtherCharges']  != '0') ? $expf['OtherCharges'] : '';


?>
<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl " style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				
				<!-- <tr >
					<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
					<td colspan="3">
						<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" readonly required value="<?=$exp['ExpenseName']?>">
					</td>
				
				</tr> -->
				<tr>
					<th scope="row">Agency Name&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="AgencyName" name="AgencyName" value="<?=$expf['AgencyName']?>" readonly required></td>
					<th scope="row">Agency Address&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="AgencyAddress" name="AgencyAddress" value="<?=$expf['AgencyAddress']?>" readonly required></td>
				</tr>
				<tr >
					<th scope="row">Billing Person </th>
					<td><input type="text" class="form-control" name="BillingName" value="<?=$expf['BillingName']?>" readonly></td>
					<th scope="row">Billing address</th>
					<td><input class="form-control" rows="2" name="BillingAddress" value="<?=$expf['BillingAddress']?>" readonly required></td>
				</tr>
				<tr>
					<th scope="row">Invoice No. </th>
					<td><input type="text" class="form-control" name="Invoice" value="<?=$expf['Invoice']?>" readonly></td>
					<th scope="row">Date of Travel</th>
					<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly></td>
				</tr>
				<tr>
					
					<th colspan="2" scope="row">Vehicle class /Registration Number&nbsp;<span class="text-danger">*</span></th>
					<td colspan="2"><input type="text" class="form-control" id="VehicleReg" name="VehicleReg" value="<?=$expf['VehicleReg']?>" required readonly></td>
				</tr>
				<tr>
					<th scope="row">Journey Start Dt&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control dat" id="JourneyStartDt1" name="JourneyStartDt" value="<?=$JourneyStartDt?>" required readonly></td>
					<th scope="row">Journey End Dt&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="JourneyEndDt" name="JourneyEndDt" value="<?=$JourneyEndDt?>" required readonly></td>
				</tr>
				<tr>
					<th scope="row"><!--Distance_Travelled (-->Opening_Reading<!--)-->&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="DistTraOpen" name="DistTraOpen" value="<?=$expf['DistTraOpen']?>" required readonly></td>
					<th scope="row"><!--Distance_Travelled (-->Closing_Reading<!--)-->&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="DistTraClose" name="DistTraClose" value="<?=$expf['DistTraClose']?>" required readonly></td>
				</tr>


				<tr>
					<th scope="row">Charges (Daily basis)&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="DailyBasisCharges" name="DailyBasisCharges" value="<?=$DailyBasisCharges?>" required readonly onkeypress="return isNumber(event)" ></td>
					<th scope="row">(Km Basis)&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="KmBasisCharges" name="KmBasisCharges" value="<?=$KmBasisCharges?>" required readonly onkeypress="return isNumber(event)" ></td>
				</tr>

				<tr>
					<th scope="row">Driver Charges&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="DriverCharges" name="DriverCharges" value="<?=$DriverCharges?>" required readonly placeholder="Fooding/Allowances" onkeypress="return isNumber(event)" ></td>
					<th scope="row">Other Charges&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="OtherCharges" name="OtherCharges" value="<?=$OtherCharges?>" required readonly placeholder="Toll/Parking" onkeypress="return isNumber(event)" ></td>
				</tr>
				
                <tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --></td>	
				</tr>  


				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									
									<td>
										<input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>>
									</td>
									<td>
										<input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>>
									</td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td>
										<?php
										if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';}
										?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>>
									</td>
									
									<td>
										<?php
										if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}
										?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>>
									</td>
									
									<td>
										<?php
										if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}
										?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>>
									</td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;">
										<button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;">
											<i class="fa fa-times fa-sm" aria-hidden="true"></i>
										</button>
									</td>
									<?php } ?>
									
								</tr>
								
								<?php
								$i++;
								}
								?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet(<?=$exp['ClaimId']?>)">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" ><?=$exp['Remark']?></textarea></td>
				
				
				</tr><?php */?>

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
                        <input type="hidden" id="Remark" name="Remark" value="<?=$exp['Remark']?>">
				      	

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftHiredVeh" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
                        <?php } ?>
						
						<button class="btn btn-sm btn-success" id="Update" name="UpdateHiredVeh" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

					</td>
				</tr>
			
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==5){
	/*
	====================================================================================================
			$_POST['claimid']==5      Phone Fax form 
	====================================================================================================
	*/


$BillDate = ($expf['BillDate']  != '0000-00-00' && $expf['BillDate']  != '') ? date("d-m-Y",strtotime($expf['BillDate'])) : date("d-m-Y",strtotime($exp['CrDate']));
// $JourneyStartDt  = ($exp['JourneyStartDt'] != '0000-00-00') ? date("d-m-Y",strtotime($exp['JourneyStartDt'])) : '';
$DueDate  = ($expf['DueDate'] != '0000-00-00' && $expf['DueDate'] != '') ? date("d-m-Y",strtotime($expf['DueDate'])) : '';



?>
<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl " style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				
				<!-- <tr >
					<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
					<td colspan="3">
						<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" readonly required value="<?=$exp['ExpenseName']?>">
					</td>
				
				</tr> -->

				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly></td>
				
				</tr>

				<tr >
					<th scope="row">Billing Person </th>
					<td><input type="text" class="form-control" name="BillingName" value="<?=$expf['BillingName']?>" readonly></td>
					<th scope="row">Billing address</th>
					<td><input class="form-control" rows="2" name="BillingAddress" value="<?=$expf['BillingAddress']?>" readonly></td>
				</tr>

				<tr>
					<th scope="row">Mobile Service&nbsp;<span class="text-danger">*</span></th>
					<td>
						<select class="form-control" id="MobileService" readonly name="MobileService">
							<!--<option></option>-->
							<option value="PREPAID" <?php echo $exp['MobileService']=='PREPAID'?'selected':'';?>>PREPAID</option>
							<option value="POSTPAID" <?php echo $exp['MobileService']=='POSTPAID'?'selected':'';?>>POSTPAID</option>
						</select>
					</td>
					<th scope="row">Mobile Number&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="Mobile" name="Mobile" value="<?php if($expf['Mobile']!=0){echo $expf['Mobile'];}?>" readonly required onkeypress="return isNumber(event)" pattern="[789][0-9]{9}" maxlength="10"></td>
				</tr>

				<tr>
					<th scope="row">Billing Cycle&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="BillingCycle" name="BillingCycle" value="<?=$expf['BillingCycle']?>" readonly required></td>
					<th scope="row">Tariff Plan&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="Plan" name="Plan" value="<?=$expf['Plan']?>" readonly required></td>
				</tr>
				
				
				<tr>
					<th scope="row">Charges&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="OtherCharges" name="OtherCharges" value="<?php if($expf['OtherCharges']!=0){echo $expf['OtherCharges'];}?>" placeholder="other than usage charges" required readonly onkeypress="return isNumber(event)" ></td>
					<th scope="row">Previous balance </th>
					<td><input  class="form-control" id="PrevBalance" name="PrevBalance" value="<?=$expf['PrevBalance']?>" readonly></td>
				</tr>

				<tr>
					<th scope="row" colspan="2">Last Payement Detail&nbsp;<span class="text-danger">*</span></th>
					<td colspan="2"><input type="text" class="form-control" id="LastPayement" name="LastPayement" value="<?=$expf['LastPayement']?>" required readonly></td>
				</tr>
				<tr>
					<th scope="row">Payment Mode&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="PaymentMode" name="PaymentMode" value="<?=$expf['PaymentMode']?>" required readonly></td>
					<th  scope="row">Due Date&nbsp;<span class="text-danger">*</span></th>
					<!-- <td><input  class="form-control" id="BillDate" name="BillDate" value="<?=$BillDate?>" readonly></td> -->
					<td ><input class="form-control" id="DueDate" name="DueDate" value="<?=$DueDate?>" readonly></td>

					<script type="text/javascript">
						$('#DueDate').datetimepicker({format:'d-m-Y'});
					    $('#DueDate').on('change', function(){
					        $(this).datetimepicker('hide');
					    }); //here closing the billdate datetimepicker on date change 
					</script>
					
				</tr>
                <tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --></td>	
				</tr>

				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									
									<td>
										<input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>>
									</td>
									<td>
										<input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>>
									</td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td>
										<?php
										if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';}
										?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>>
									</td>
									
									<td>
										<?php
										if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}
										?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>>
									</td>
									
									<td>
										<?php
										if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}
										?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>>
									</td>
									<?php } ?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;">
										<button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;">
											<i class="fa fa-times fa-sm" aria-hidden="true"></i>
										</button>
									</td>
									<?php } ?>
									
								</tr>
							
								<?php
								$i++;
								}
								?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet(<?=$exp['ClaimId']?>)">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>
				

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" ><?=$exp['Remark']?></textarea></td>
				
				
				</tr><?php */?>

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
                        <input type="hidden" id="Remark" name="Remark" value="<?=$exp['Remark']?>">
                        <?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft2" name="draftMobileBill" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?> 

						<button class="btn btn-sm btn-success" id="Update" name="UpdateMobileBill" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>
				      	

					</td>
				</tr>
			
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==6){
	/*
	====================================================================================================
			$_POST['claimid']==6      Postage Courier form 
	====================================================================================================
	*/


$DocketBookedDt  = ($expf['DocketBookedDt'] != '0000-00-00' && $expf['DocketBookedDt'] != '') ? date("d-m-Y",strtotime($expf['DocketBookedDt'])) : '';

?>
<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl " style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">
				

				<!-- <tr >
					<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
					<td colspan="3">
						<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" readonly required value="<?=$exp['ExpenseName']?>">
					</td>
				
				</tr> -->

				<tr >
					<th scope="row">Provider Name</th>
					<td colspan=""><input type="text" class="form-control" name="ServiceProvider" value="<?=$expf['ServiceProvider']?>" readonly></td>
					<th scope="row">Weight Charged&nbsp;<span class="text-danger">*</span></th>
					<td ><input type="text" class="form-control" name="WeightCharged" value="<?=$expf['WeightCharged']?>" required readonly></td> 
					
				</tr>

				<tr>
					<th scope="row">Sender Name</th>
					<td><input type="text" class="form-control" name="SenderName" value="<?=$expf['SenderName']?>" readonly></td>
					<th scope="row">Sender_Address</th>
					<td><input class="form-control" rows="2" name="SenderAddress" readonly value="<?=$expf['SenderAddress']?>" /></td>
					
				</tr>

				<tr>
					<th scope="row">Receiver name &nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" name="ReceiverName" value="<?=$expf['ReceiverName']?>" readonly required></td>
					
					<th scope="row">Receiver Address</th>
					<td><input class="form-control" rows="2" name="ReceiverAddress" readonly value="<?=$expf['ReceiverAddress']?>" /></td>
					
				</tr>
				

				<tr>
					<th scope="row">Docket No. &nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" name="DocketNumber" value="<?=$expf['DocketNumber']?>" required readonly></td>
					<th scope="row">Booked Date </th>
					
					<td><input  class="form-control dat" id="BillDate2" name="DocketBookedDt" value="<?=$DocketBookedDt?>" readonly></td>
				</tr>

				<tr>
					
				</tr>
                <tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --></td>	
				</tr>
				
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td>
										<input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
										<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>>
										
									</td>
									
									<td>
										<input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>>
									</td>
									<td>
										<input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>>
									</td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td>
										<?php
										if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';}
										?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>>
									</td>
									
									<td>
										<?php
										if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}
										?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>>
									</td>
									
									<td>
										<?php
										if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}
										?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>>
									</td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;">
										<button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;">
											<i class="fa fa-times fa-sm" aria-hidden="true"></i>
										</button>
									</td>
									<?php } ?>
									
								</tr>
								
								<?php
								$i++;
								}
								?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
					<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet(<?=$exp['ClaimId']?>)">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>
				

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" ><?=$exp['Remark']?></textarea></td>
				
				
				</tr><?php */?>

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
                        <input type="hidden" id="Remark" name="Remark" value="<?=$exp['Remark']?>">
				      	

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftPostCour" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>

						<button class="btn btn-sm btn-success" id="Update" name="UpdatePostCour" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

					</td>
				</tr>
			
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==7){ 
	/*
	====================================================================================================
			$_POST['claimid']==7      2/4 Wheeler form 
	====================================================================================================
	*/

if($_SESSION['CompanyId']==1){ $DbName='vnrseed2_expense'; }
elseif($_SESSION['CompanyId']==4){ $DbName='vnrseed2_expense_tl'; }	
	
$servername = "localhost";
$username = "vnrseed2_hr";
$password = "vnrhrims321";
$dbname = $DbName;


    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    if(isset($expf['did'])){
    $stmt = $conn->prepare("select * from 2_4_wheeler_entry where did='".$expf['did']."' and ExpId='".$_REQUEST['expid']."'");
    $stmt->execute();
    $expf_wheeler = $stmt->fetchAll();
    }else{
    $expf_wheeler = array();
    }
// $ef_wheeler=mysql_query("select * from 2_4_wheeler_entry where ExpId=".$_REQUEST['expid']);
// $expf_wheeler=mysql_fetch_assoc($ef_wheeler);

$JourneyStartDt  = ($expf['JourneyStartDt'] != '0000-00-00' && $expf['JourneyStartDt'] != '') ? date("d-m-Y",strtotime($expf['JourneyStartDt'])) : date("d-m-Y h:i",strtotime($exp['CrDate'])) ;
$JourneyEndDt  = ($expf['JourneyEndDt'] != '0000-00-00' && $expf['JourneyEndDt'] != '') ? date("d-m-Y",strtotime($expf['JourneyEndDt'])) : date("d-m-Y h:i",strtotime($exp['CrDate']));

?>
<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl " style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				<tr>
					<th scope="row">Vehicle&nbsp;<span class="text-danger">*</span></th>
					<td >
						<label >
							<input type="radio" class="" id="vehicleType2" name="vehicleType" value="2" readonly required  onclick="vehTypeSel(2)" checked=""><b>2 Wheeler</b>
						</label>&emsp;
					
						
					</td>
					<td>
						<label >
							<input type="radio" class="" id="vehicleType4" name="vehicleType" value="4" readonly required onclick="vehTypeSel(4)"><b>4 Wheeler</b>
						</label>
					</td>

					<th scope="row">Per KM Rs&nbsp;</th>
					<td>
					    
<?php 
$sdept=mysql_query("select DepartmentId,GradeValue from hrm_employee_general g inner join hrm_grade gr on g.GradeId=gr.GradeId where EmployeeID=".$exp['CrBy']."",$con2);
$rdept=mysql_fetch_assoc($sdept);

$li=mysql_query("SELECT Travel_TwoWeeKM,Travel_FourWeeKM,FourWElig,CostOfVehicle,WithDriver,AdvanceCom,DateOfEntryPolicy,LessKm,Plan FROM `hrm_employee_eligibility` where EmployeeID=".$exp['CrBy']." order by EligibilityId desc limit 1",$con2);
$lim=mysql_fetch_assoc($li);

if($rdept['DepartmentId']!=4 AND $rdept['DepartmentId']!=6)
{
 $tpkm=$lim['Travel_TwoWeeKM'];
 $fpkm=$lim['Travel_FourWeeKM'];
}
elseif(($rdept['DepartmentId']==4 OR $rdept['DepartmentId']==6) AND $lim['FourWElig']=='N')
{
 $tpkm=$lim['Travel_TwoWeeKM'];
 $fpkm=$lim['Travel_FourWeeKM'];
}
elseif(($rdept['DepartmentId']==4 OR $rdept['DepartmentId']==6) AND $lim['FourWElig']=='Y')
{
  $sel=mysql_query("select * from emp_trvrate where EmployeeID=".$exp['CrBy']); $rowse=mysql_num_rows($sel);
  if($rowse==0)
  {     
   $ins=mysql_query("insert into emp_trvrate(EmployeeID,crdate,ratefour) values(".$exp['CrBy'].", '".date("Y-m-d")."', 11)");
  }
  $schk=mysql_query("select ratefour from emp_trvrate where EmployeeID=".$exp['CrBy']); $rchk=mysql_fetch_assoc($schk);
  $tpkm=$lim['Travel_TwoWeeKM'];
  $fpkm=$rchk['ratefour'];
}
?>					    
						<?php 
                      
						//$li=mysql_query("SELECT Travel_TwoWeeKM,Travel_FourWeeKM FROM `hrm_employee_eligibility` where EmployeeID=".$exp['CrBy']." order by EligibilityId desc limit 1",$con2);
						//$lim=mysql_fetch_assoc($li);

						 //$tpkm=$lim['Travel_TwoWeeKM'];
						 //$fpkm=$lim['Travel_FourWeeKM'];

						?>
						<input type="text" class="form-control" id="tpkm" name="tpkm" value="<?=$tpkm?>" required readonly>
						<input type="text" class="form-control" id="fpkm" name="fpkm" value="<?=$fpkm?>" required readonly style="display: none;">
					</td>	

					<th>Bill Date</th>
					<td><input type="text" class="form-control dat" id="BillDate1" name="BillDate" value="<?=!empty($expf['BillDate'])?$expf['BillDate']:''?>"  required></td>				
				</tr>
			
  
                <tr><th>Trip Started</th>
                	<th>Trip Ended</th>
                	<th>Vehicle Reg no</th>
                	<th>Dist Trvld Opening</th>
                	<th>Dist Trvld closing</th>
                	<th>Total Km</th>
                	<th>Amount</th>
                	<th></th>
                </tr>
                 
                 <tbody id="d">

                  <?php if(count($expf_wheeler)>0){
                    for ($i=0; $i <count($expf_wheeler); $i++) { 
                    
	               		 $JourneyStartDt=date("d-m-Y H:i:s",strtotime($expf_wheeler[$i]['JourneyStartDt']));
		           		 $JourneyEndDt=date("d-m-Y H:i:s",strtotime($expf_wheeler[$i]['JourneyEndDt']));

                        $k = $i.'_';
                      ?>


                   <tr><td><input type="text" class="form-control DateTime" id="JourneyStartDt<?=$k?>" name="JourneyStartDt[]" value="<?=!empty($expf_wheeler[$i]['JourneyStartDt'])?$JourneyStartDt:''?>" title="<?=!empty($expf_wheeler[$i]['JourneyStartDt'])?$JourneyStartDt:''?>" readonly required></td>
                	<td><input type="text" class="form-control DateTime" id="JourneyEndDt<?=$k?>" name="JourneyEndDt[]" value="<?=!empty($expf_wheeler[$i]['JourneyEndDt'])?$JourneyEndDt:''?>" title="<?=!empty($expf_wheeler[$i]['JourneyEndDt'])?$JourneyEndDt:''?>" readonly required></td>
                	<td><input type="text" class="form-control" id="VehicleReg<?=$k?>" name="VehicleReg[]" value="<?=!empty($expf_wheeler[$i]['VehicleReg'])?$expf_wheeler[$i]['VehicleReg']:''?>" required readonly></td>
                	<td><input type="text" class="form-control" id="DistTraOpen<?=$k?>" name="DistTraOpen[]" value="<?=!empty($expf_wheeler[$i]['DistTraOpen'])?$expf_wheeler[$i]['DistTraOpen']:0?>" required readonly></td>
                	<td><input type="text" class="form-control" id="DistTraClose<?=$k?>" name="DistTraClose[]" value="<?=!empty($expf_wheeler[$i]['DistTraClose'])?$expf_wheeler[$i]['DistTraClose']:0?>" onkeyup="cald('<?=$k?>');"  required readonly></td>
                	<td><input type="text" class="form-control" id="totalkm<?=$k?>" name="totalkm[]" value="<?=!empty($expf_wheeler[$i]['Totalkm'])?$expf_wheeler[$i]['Totalkm']:0?>" required readonly></td>
                	<td><input type="text" class="form-control" id="FilledTAmt<?=$k?>" name="FilledTAmt[]" value="<?=!empty($expf_wheeler[$i]['FilledTAmt'])?$expf_wheeler[$i]['FilledTAmt']:''?>" required readonly></td>

                	   <?php 
                	   if($i==(count($expf_wheeler)-1)){
                	   // if($i==0){
						   	?>

                	<td style="width:5px !important;"><a style="background-color: #36af2e; font-weight:600; padding: 3px 8px 6px 7px; color: white;" class="btn-sm btn-default add-row">+</a>
							
							<input type="hidden" class="form-control" id="WheelId" name="WheelId[]" value="<?=!empty($expf_wheeler[$i]['WheelId'])?$expf_wheeler[$i]['WheelId']:''?>" >
                	</td>
                <?php }else{?>
                	<td style="width:5px !important;">
                	<!-- 	<a style="background-color: #d63434; font-weight:600; padding: 1px 10px 6px 8px; color:white;" onclick="del(<?=$i?>);" class="btn-sm btn-default cut-row">-</a> -->

                		<input type="hidden" class="form-control" id="WheelId" name="WheelId[]" value="<?=!empty($expf_wheeler[$i]['WheelId'])?$expf_wheeler[$i]['WheelId']:''?>" >
                	</td>
                <?php } ?>
                 </tr>


                   <?php }
                   }else{ ?>

  					<tr id="row_data<?=$i?>"><td><input type="text" class="form-control DateTime" id="JourneyStartDt_" name="JourneyStartDt[]" value="" readonly required></td>
                	<td><input type="text" class="form-control DateTime" id="JourneyEndDt_" name="JourneyEndDt[]" value="" readonly required></td>
                	<td><input type="text" class="form-control" id="VehicleReg" name="VehicleReg[]" value="" required readonly></td>
                	<td><input type="text" class="form-control" id="DistTraOpen" name="DistTraOpen[]" value="0" required readonly></td>
                	<td><input type="text" class="form-control" id="DistTraClose" name="DistTraClose[]" value="0" required readonly onkeyup="caldist()" ></td>
                	<td><input type="text" class="form-control" id="totalkm" name="totalkm[]" value="0" required readonly></td>
                	<td><input type="text" class="form-control" id="FilledTAmt" name="FilledTAmt[]" value="0" required readonly></td>
                	<td style="width:5px !important;"><a style="background-color: #36af2e; font-weight:600; padding: 3px 8px 6px 7px; color: white;" class="btn-sm btn-default add-row">+</a>

                      <input type="hidden" class="form-control" id="WheelId" name="WheelId[]" value="" >

                	</td>
                 </tr>

                  <?php } ?>
                
                
                </tbody>
                 
<!---------------------------------------->
<!---------------------------------------->
<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
			<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
			<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
			<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
			<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
			<?php if($_SESSION['EmpRole']!='M'){ ?>
			<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
				<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
			<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
			<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
				<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
			<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
			<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
				<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
			<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
			<?php }?>


			<?php if($_SESSION['EmpRole']=='M'){ ?>
			<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
			<?php } ?>
									
		  </tr>
		  <?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
<!---------------------------------------->
<!---------------------------------------->
				 

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
                        <input type="hidden" id="Remark" name="Remark" value="<?=$exp['Remark']?>">
                        <?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draft24Wheeler" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>

						<button class="btn btn-sm btn-success" id="Update" name="Update24Wheeler" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

				      	

					</td>
				</tr>
				
				<?php
				if($expf['VehicleType']==2){
					?>
					<script type="text/javascript"> $("#vehicleType2").prop('checked','checked'); vehTypeSel(2); caldist();</script>
					<?php
				}elseif($expf['VehicleType']==4){
					?>
					<script type="text/javascript"> $("#vehicleType4").prop('checked','checked'); vehTypeSel(4); caldist();</script>
					<?php
				}else{
					?>
			
					<?php
				}
				?>
			
				<script type="text/javascript">
			
			$(document).ready(function() {

			    jQuery('.DateTime').datetimepicker({
			      format:'d-m-Y H:i:s'
			      });

				var i=0;
				$("#tpkm").prop('readonly',true);
				$("#fpkm").prop('readonly',true);
				$("#totalkm").prop('readonly',true);
				$("#FilledTAmt").prop('readonly',true);


			$(".add-row").click(function(){

			 var markup = '<tr id="row_data'+i+'"><td><input type="text" class="form-control DateTime" id="JourneyStartDt'+i+'" name="JourneyStartDt[]" value=""  required></td><td><input type="text" class="form-control DateTime" id="JourneyEndDt'+i+'" name="JourneyEndDt[]" value=""  required></td><td><input type="text" class="form-control" id="VehicleReg'+i+'" name="VehicleReg[]" value="" required ></td><td><input type="text" class="form-control" id="DistTraOpen'+i+'" name="DistTraOpen[]" value="0" required ></td><td><input type="text" class="form-control" id="DistTraClose'+i+'" name="DistTraClose[]" value="0" required onkeyup="cald('+i+');" ></td><td><input type="text" class="form-control" id="totalkm'+i+'" name="totalkm[]" value="0" required readonly></td><td><input type="text" class="form-control" id="FilledTAmt'+i+'" name="FilledTAmt[]" value="" required readonly></td><td style="width:5px !important;"><a style="background-color: #d63434; font-weight:600; padding: 1px 10px 6px 8px; color:white;" onclick="del('+i+');" class="btn-sm btn-default cut-row">-</a><input type="hidden" class="form-control" id="WheelId" name="WheelId[]" value="" ></td></tr>';

            $("table tbody#d").append(markup);
                 i++;

             jQuery('.DateTime').datetimepicker({
			      format:'d-m-Y H:i:s'
			      });

        });
		});


              function del(id){
                   $("#row_data"+id).remove();
              }


              function cald(id){

              		var opening=parseInt($("#DistTraOpen"+id).val() || 0);
					var closing=parseInt($("#DistTraClose"+id).val() || 0);
					var dist= closing-opening;

					$("#totalkm"+id).val(dist);


					var tChecked = $('#vehicleType2').prop('checked');
					var fChecked = $('#vehicleType4').prop('checked');

					if(tChecked){
						$("#FilledTAmt"+id).val(dist * $("#tpkm").val());
					}else if(fChecked){
						$("#FilledTAmt"+id).val(dist * $("#fpkm").val());
					} 
              }
					</script>
		</table>

	
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==8){
	/*
	====================================================================================================
			$_POST['claimid']==8      Vehicle Maintenance form 
	====================================================================================================
	*/
?>

<?php


$BillDate = ($expf['BillDate']  != '0000-00-00' && $expf['BillDate']  != '') ? date("d-m-Y",strtotime($expf['BillDate'])) : date("d-m-Y",strtotime($exp['CrDate']));




?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly></td>
				
				</tr>
				
				

				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
			<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
			<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
			<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
			<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
			<?php if($_SESSION['EmpRole']!='M'){ ?>
			<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
				<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
			<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
			<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
				<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
			<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
			<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
				<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
			<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
			<?php }?>


			<?php if($_SESSION['EmpRole']=='M'){ ?>
			<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
			<?php } ?>
									
		  </tr>
		  <?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftVehMain" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>

						<button class="btn btn-sm btn-success" id="Update" name="UpdateVehMain" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==9){
	/*
	====================================================================================================
			$_POST['claimid']==9      Vehicle Fuel form 
	====================================================================================================
	*/



$BillDate = ($expf['BillDate']  != '0000-00-00' && $expf['BillDate']  != '') ? date("d-m-Y",strtotime($expf['BillDate'])) : date("d-m-Y",strtotime($exp['CrDate']));




?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly></td>
				
				</tr>
				
				

				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
			<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
			<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
			<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
			<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
			<?php if($_SESSION['EmpRole']!='M'){ ?>
			<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
				<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
			<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
			<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
				<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
			<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
			<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
				<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
			<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
			<?php }?>


			<?php if($_SESSION['EmpRole']=='M'){ ?>
			<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
			<?php } ?>
									
		  </tr>
		  <?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">


						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftVehFuel" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>

						<button class="btn btn-sm btn-success" id="Update" name="UpdateVehFuel" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>


				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==10){
	/*
	====================================================================================================
			$_POST['claimid']==10      RST/OFD form 
	====================================================================================================
	*/



$BillDate = ($expf['BillDate']  != '0000-00-00' && $expf['BillDate']  != '' && $expf['BillDate'] != '') ? date("d-m-Y",strtotime($expf['BillDate'])) : date("d-m-Y",strtotime($exp['CrDate']));
// $arrdate  = ($expf['Arrival']   != '0000-00-00 00:00:00' && $expf['Arrival']   != '') ? date("d-m-Y H:i",strtotime($expf['Arrival'])) : '';
// $depdate  = ($expf['Departure'] != '0000-00-00 00:00:00' && $expf['Departure'] != '') ? date("d-m-Y H:i",strtotime($expf['Departure'])) : '';



?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly></td>

				<th scope="row">Crop</th>
				<td>
					<select  class="form-control" name="Crop">
						<?php 
						if($expf['Crop']!=''){
						?>
							<option value="<?=$expf['Crop']?>" selected><?=$expf['Crop']?></option>
						<?php
						}else{
							?>
							<option value="">--Select--</option>
							<?php
						}
						?>
						<option value="Vegetable" >Vegetable</option> 
						<option value="Field Crops" >Field Crops</option> 
						
					</select>
				</td>
			
				</tr>


				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								if(mysql_num_rows($ed)<=0){
								?>
								<tr> 
									<td>Labour Charge (LC)<input type="hidden" class="form-control" name="fdtitle1" value="Labour Charge (LC)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark1" name="fdremark1" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Input Charge (IC)<input type="hidden" class="form-control" name="fdtitle2" value="Input Charge (IC)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount2" name="fdamount2" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark2" name="fdremark2" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Miscellaneous (MSC)<input type="hidden" class="form-control" name="fdtitle3" value="Miscellaneous (MSC)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount3" name="fdamount3" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark3" name="fdremark3" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>

								<?php
								}
								$i=4; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
									<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
									<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
									<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
									<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
									<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
									<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
									<?php } ?>
															
								</tr>
								<?php $i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftRSTOFD" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>

						<button class="btn btn-sm btn-success" id="Update" name="UpdateRSTOFD" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>


				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==11){
	/*
	====================================================================================================
			$_POST['claimid']==11      FD/FV form 
	====================================================================================================
	*/


$BillDate = ($expf['BillDate']  != '0000-00-00' && $expf['BillDate']  != '' ) ? date("d-m-Y",strtotime($expf['BillDate'])) : date("d-m-Y",strtotime($exp['CrDate']));
// $arrdate  = ($expf['Arrival']   != '0000-00-00 00:00:00' && $expf['Arrival']   != '') ? date("d-m-Y H:i",strtotime($expf['Arrival'])) : '';
// $depdate  = ($expf['Departure'] != '0000-00-00 00:00:00' && $expf['Departure'] != '') ? date("d-m-Y H:i",strtotime($expf['Departure'])) : '';



?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly></td>
				<th scope="row">Vegetable</th>
				<td>
					<!-- <input type="text" class="form-control" name="Item" value="<?=$expf['Item']?>" readonly> -->
					<select  class="form-control" name="Vegetable">
						
						<?php if($expf['Vegetable']!=''){
							?>
							<option value="<?=$expf['Vegetable']?>" selected><?=$expf['Vegetable']?></option>
							<?php
						} ?>
						<option value="">--Select--</option>
						<option value="Chilly">Chilly</option> 
						<option value="Gourds">Gourds</option> 
						<option value="Okra">Okra</option> 
						<option value="Paddy">Paddy</option> 
						<option value="Maize">Maize</option> 
						<option value="Pearl Millet">Pearl Millet</option> 
						<option value="Tomato">Tomato</option> 
						<option value="Brinjal">Brinjal</option>
					</select>
				</td>
				
				</tr>

				<tr> 
					<th scope="row">No. of Farmers (FMS)*<input type="hidden" class="form-control" name="fdtitle5" value="No. of Farmers (FMS)*" readonly></th> 
					<td> <input class="form-control text-right" id="fms" name="fms" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
					<th scope="row">Dealers/Trade Partners (DTP)<input type="hidden" class="form-control" name="fdtitle6" value="Dealers/Trade Partners (DTP)" readonly></th> 
					<td> <input class="form-control text-right" id="dtp" name="dtp" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
					
				</tr>
				

				
				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">


								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								if(mysql_num_rows($ed)<=0){
								?>
								<tr> 
									<td>Hired Vehicle (HVC)<input type="hidden" class="form-control" name="fdtitle1" value="Hired Vehicle (HVC)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark1" name="fdremark1" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>AV Tent (AVT)<input type="hidden" class="form-control" name="fdtitle2" value="AV Tent (AVT)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount2" name="fdamount2" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark2" name="fdremark2" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Snacks (SNK)<input type="hidden" class="form-control" name="fdtitle3" value="Snacks (SNK)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount3" name="fdamount3" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark3" name="fdremark3" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>

								<tr> 
									<td>Other (OTH)<input type="hidden" class="form-control" name="fdtitle4" value="Other (OTH)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount4" name="fdamount4" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark4" name="fdremark4" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								


								<?php
								}
								$i=5; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
									<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
									<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
									<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
									<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
									<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
									<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
									<?php } ?>
															
								</tr>
								<?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftFDFV" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>

						<button class="btn btn-sm btn-success" id="Update" name="UpdateFDFV" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==12){
	/*
	====================================================================================================
			$_POST['claimid']==12      Jeep Campaign form 
	====================================================================================================
	*/


$BillDate = ($expf['BillDate']  != '0000-00-00' && $expf['BillDate']  != '' && $expf['BillDate'] != '') ? date("d-m-Y",strtotime($expf['BillDate'])) : date("d-m-Y",strtotime($exp['CrDate']));
// $arrdate  = ($expf['Arrival']   != '0000-00-00 00:00:00' && $expf['Arrival']   != '') ? date("d-m-Y H:i",strtotime($expf['Arrival'])) : '';
// $depdate  = ($expf['Departure'] != '0000-00-00 00:00:00' && $expf['Departure'] != '') ? date("d-m-Y H:i",strtotime($expf['Departure'])) : '';



?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly></td>

				<th scope="row">Vegetable</th>
				<td>
					<select  class="form-control" name="Vegetable">
						<?php if($expf['Vegetable']!=''){
							?>
							<option value="<?=$expf['Vegetable']?>" selected><?=$expf['Vegetable']?></option>
							<?php
						} ?>
						<option value="">--Select--</option>
						<option value="Chilly">Chilly</option> 
						<option value="Gourds">Gourds</option> 
						<option value="Okra">Okra</option> 
						<option value="Paddy">Paddy</option> 
						<option value="Maize">Maize</option> 
						<option value="Pearl Millet">Pearl Millet</option> 
						<option value="Tomato">Tomato</option> 
						<option value="Brinjal">Brinjal</option>
					</select>
				</td>

				
				</tr>


				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">


								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								if(mysql_num_rows($ed)<=0){
								?>
								<tr> 
									<td>Hired Vehicle (HVC)<input type="hidden" class="form-control" name="fdtitle1" value="Hired Vehicle (HVC)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark1" name="fdremark1" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>AV (AV)<input type="hidden" class="form-control" name="fdtitle2" value="AV (AV)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount2" name="fdamount2" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark2" name="fdremark2" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Other (OTH)<input type="hidden" class="form-control" name="fdtitle3" value="Other (OTH)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount3" name="fdamount3" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark3" name="fdremark3" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>

								


								<?php
								}
								$i=4; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
									<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
									<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
									<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
									<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
									<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
									<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
									<?php } ?>
															
							</tr>
							<?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftJC" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>

						<button class="btn btn-sm btn-success" id="Update" name="UpdateJC" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==13){
	/*
	====================================================================================================
			$_POST['claimid']==13      Dealer Meeting form 
	====================================================================================================
	*/
?>

<?php

$cg=mysql_query("select cgId from claimtype where ClaimId=".$_REQUEST['claimid']);
$cgd=mysql_fetch_assoc($cg);


$e=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_REQUEST['expid']);
$exp=mysql_fetch_assoc($e);

$ef=mysql_query("select * from y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata where ExpId=".$_REQUEST['expid']);
$expf=mysql_fetch_assoc($ef);

$BillDate = ($expf['BillDate']  != '0000-00-00' && $expf['BillDate']  != '' && $expf['BillDate'] != '') ? date("d-m-Y",strtotime($expf['BillDate'])) : date("d-m-Y",strtotime($exp['CrDate']));
// $arrdate  = ($expf['Arrival']   != '0000-00-00 00:00:00' && $expf['Arrival']   != '') ? date("d-m-Y H:i",strtotime($expf['Arrival'])) : '';
// $depdate  = ($expf['Departure'] != '0000-00-00 00:00:00' && $expf['Departure'] != '') ? date("d-m-Y H:i",strtotime($expf['Departure'])) : '';



?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly></td>

				<th scope="row">Crop</th>
				<td>
					<select  class="form-control" name="Crop">
						<?php 
						if($expf['Crop']!=''){
						?>
							<option value="<?=$expf['Crop']?>" selected><?=$expf['Crop']?></option>
						<?php
						}else{
							?>
							<option value="">--Select--</option>
							<?php
						}
						?>
						<option value="Vegetable">Vegetable</option> 
						<option value="Paddy">Paddy</option> 
						
					</select>
				</td>
			
				</tr>

				

				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">

								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								if(mysql_num_rows($ed)<=0){
								?>
								<tr> 
									<td>Meals (MLS)<input type="hidden" class="form-control" name="fdtitle1" value="Meals (MLS)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark1" name="fdremark1" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Hall/Tent (HTN)<input type="hidden" class="form-control" name="fdtitle2" value="Hall/Tent (HTN)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount2" name="fdamount2" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark2" name="fdremark2" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Gift (GFT)<input type="hidden" class="form-control" name="fdtitle3" value="Gift (GFT)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount3" name="fdamount3" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark3" name="fdremark3" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>

								<tr> 
									<td>AV (AV)<input type="hidden" class="form-control" name="fdtitle4" value="AV (AV)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount4" name="fdamount4" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark4" name="fdremark4" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Others (OTH)<input type="hidden" class="form-control" name="fdtitle5" value="Others (OTH)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount5" name="fdamount5" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark5" name="fdremark5" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								


								<?php
								}
								$i=6; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
									<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
									<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
									<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
									<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
									<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
									<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
									<?php } ?>
															
								</tr>
								<?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftDm" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>

						<button class="btn btn-sm btn-success" id="Update" name="UpdateDm" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==14){
	/*
	====================================================================================================
			$_POST['claimid']==13      DHQ form 
	====================================================================================================
	*/
?>

<?php

$cg=mysql_query("select cgId from claimtype where ClaimId=".$_REQUEST['claimid']);
$cgd=mysql_fetch_assoc($cg);


$e=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_REQUEST['expid']);
$exp=mysql_fetch_assoc($e);

$ef=mysql_query("select * from y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata where ExpId=".$_REQUEST['expid']);
$expf=mysql_fetch_assoc($ef);

$BillDate = ($expf['BillDate']  != '0000-00-00' && $expf['BillDate']  != '' && $expf['BillDate'] != '') ? date("d-m-Y",strtotime($expf['BillDate'])) : date("d-m-Y",strtotime($exp['CrDate']));


$seldep=mysql_query("SELECT `DepartmentCode` FROM `hrm_department` hd, `hrm_employee_general` heg where heg.DepartmentId=hd.DepartmentId and heg.EmployeeID=".$exp['CrBy'],$con2);
$depnm=mysql_fetch_assoc($seldep);

if($depnm['DepartmentCode']=='SALES'){
	$column='DA_Inside_HqSal';
}elseif($depnm['DepartmentCode']=='PD'){
	$column='';

}else{
	$column='DA_Inside_Hq';
}



$li=mysql_query("SELECT ".$column." FROM `hrm_employee_eligibility` where EmployeeID=".$exp['CrBy']." order by EligibilityId desc limit 1",$con2);
$lim=mysql_fetch_assoc($li);

$daAmount=$lim[$column];

if($expf['DAAmount']!=0){
	$daAmount=$expf['DAAmount'];
}

?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly></td>

				<th scope="row">Amount</th>
				<td>
					<input class="form-control" id="daAmount" name="daAmount" value="<?=$daAmount?>" readonly>
				</td>
			
				</tr>

				
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftDHQ" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>

						<button class="btn btn-sm btn-success" id="Update" name="UpdateDHQ" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==15){
	/*
	====================================================================================================
			$_POST['claimid']==13      DOS form 
	====================================================================================================
	*/
?>

<?php

$cg=mysql_query("select cgId from claimtype where ClaimId=".$_REQUEST['claimid']);
$cgd=mysql_fetch_assoc($cg);


$e=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_REQUEST['expid']);
$exp=mysql_fetch_assoc($e);

$ef=mysql_query("select * from y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata where ExpId=".$_REQUEST['expid']);
$expf=mysql_fetch_assoc($ef);

$BillDate = ($expf['BillDate']  != '0000-00-00' && $expf['BillDate']  != '' && $expf['BillDate'] != '') ? date("d-m-Y",strtotime($expf['BillDate'])) : date("d-m-Y",strtotime($exp['CrDate']));


$seldep=mysql_query("SELECT `DepartmentCode` FROM `hrm_department` hd, `hrm_employee_general` heg where heg.DepartmentId=hd.DepartmentId and heg.EmployeeID=".$exp['CrBy'],$con2);
$depnm=mysql_fetch_assoc($seldep);

if($depnm['DepartmentCode']=='SALES'){
	$column='DA_Outside_HqSal';
}elseif($depnm['DepartmentCode']=='PD'){
	$column='DA_Outside_HqPD';

}else{
	$column='DA_Outside_Hq';
}



$li=mysql_query("SELECT ".$column." FROM `hrm_employee_eligibility` where EmployeeID=".$exp['CrBy']." order by EligibilityId desc limit 1",$con2);
$lim=mysql_fetch_assoc($li);

$daAmount=$lim[$column];


if($expf['DAAmount']!=0){
	$daAmount=$expf['DAAmount'];
}


?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly></td>

				<th scope="row">Amount</th>
				<td>
					<input class="form-control" id="daAmount" name="daAmount" value="<?=$daAmount?>" readonly>
				</td>
			
				</tr>
			
			

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftDOS" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>

						<button class="btn btn-sm btn-success" id="Update" name="UpdateDOS" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && ($_REQUEST['claimid']==16 || $_REQUEST['claimid']==17)){
	/*
	====================================================================================================
			$_POST['claimid']==13      Miscellaneous form 
	====================================================================================================
	*/
?>

<?php

$cg=mysql_query("select cgId from claimtype where ClaimId=".$_REQUEST['claimid']);
$cgd=mysql_fetch_assoc($cg);


$e=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_REQUEST['expid']);
$exp=mysql_fetch_assoc($e);

$ef=mysql_query("select * from y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata where ExpId=".$_REQUEST['expid']);
$expf=mysql_fetch_assoc($ef);

$BillDate = ($expf['BillDate']!= '0000-00-00' && $expf['BillDate']!= '' && $expf['BillDate'] != '') ? date("d-m-Y",strtotime($expf['BillDate'])) : date("d-m-Y",strtotime($exp['CrDate']));
// $arrdate  = ($expf['Arrival']   != '0000-00-00 00:00:00' && $expf['Arrival']   != '') ? date("d-m-Y H:i",strtotime($expf['Arrival'])) : '';
// $depdate  = ($expf['Departure'] != '0000-00-00 00:00:00' && $expf['Departure'] != '') ? date("d-m-Y H:i",strtotime($expf['Departure'])) : '';



?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly></td>

				
			
				</tr>

				

				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								
								<tr>
			<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
			<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
			<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
			<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
			<?php if($_SESSION['EmpRole']!='M'){ 
			
			/* onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" */
			?>
			<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
				<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>"  name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" value="<?=$vamt?>" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" <?php if($_SESSION['EmpRole']=='V'){echo 'required';}?> <?=$vastate?>></td>
			<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
			<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
				<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" value="<?=$aamt?>" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" <?php if($_SESSION['EmpRole']=='A'){echo 'required';}?> <?=$aastate?>></td>
			<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
			<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
				<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" value="<?=$famt?>" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" <?php if($_SESSION['EmpRole']=='F'){echo 'required';}?> <?=$fastate?>></td>
			<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
			<?php }?>


			<?php if($_SESSION['EmpRole']=='M'){ ?>
			<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
			<?php } ?>
									
		  </tr>
		  <?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftMLSaMISC" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>

						<button class="btn btn-sm btn-success" id="Update" name="UpdateMLSaMISC" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
		<br><br>
		<!-- <?php include 'multipleremark.php';?> -->
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==18){
	/*
	====================================================================================================
			$_POST['claimid']==18     Boarding Pass Form
	====================================================================================================
	*/

$JourneyStartDt=($expf['JourneyStartDt']!='0000-00-00' && $expf['JourneyStartDt']!='') ? date("d-m-Y",strtotime($expf['JourneyStartDt'])) : '';
$JourneyEndDt=($expf['JourneyEndDt']!='0000-00-00' && $expf['JourneyEndDt']!='') ? date("d-m-Y",strtotime($expf['JourneyEndDt'])) : '';

?>
<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">
				

				<!-- <tr >
				<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
				<td colspan="3">
					<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" readonly value="<?=$exp['ExpenseName']?>">
				</td>
				
				</tr> -->
			
				
				
				<tr>
					<th scope="row">Trip Started On&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="JourneyStartDt" name="JourneyStartDt" value="<?=$JourneyStartDt?>" readonly required></td>
					<th scope="row">Trip Ended On&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="JourneyEndDt" name="JourneyEndDt" value="<?=$JourneyEndDt?>" readonly required></td>
				</tr>
				
				
				<tr>
				<th scope="row">Journey from&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyFrom" name="JourneyFrom" readonly value="<?=$expf['JourneyFrom']?>"></td>
				<th scope="row">Journey Upto&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyUpto" name="JourneyUpto" readonly value="<?=$expf['JourneyUpto']?>"></td>
				</tr>
				
				
				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --></td>	
				</tr>
				
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									
									<td>
										<input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>>
									</td>
									<td>
										<input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>>
									</td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td>
										<?php
										if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';}
										?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>>
									</td>
									
									<td>
										<?php
										if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}
										?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>>
									</td>
									
									<td>
										<?php
										if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}
										?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>>
									</td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td style="width:20px;text-align:center;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button>
									</td>
									<?php } ?>
									
								</tr>
								
								<?php
								$i++;
								}
								?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>
						

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				
				
				</tr><?php */?>

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" id="Remark" name="Remark" value="<?=$exp['Remark']?>">

				      	<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftBoPa" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>


						<button class="btn btn-sm btn-success" id="Update" name="UpdateBoPa" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>


					</td>
				</tr>
			
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && ($_REQUEST['claimid']==19 || $_REQUEST['claimid']==20 || $_REQUEST['claimid']==21)){

$cg=mysql_query("select cgId from claimtype where ClaimId=".$_REQUEST['claimid']);
$cgd=mysql_fetch_assoc($cg);


$e=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_REQUEST['expid']);
$exp=mysql_fetch_assoc($e);


$ef=mysql_query("select * from y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata where ExpId=".$_REQUEST['expid']);
$expf=mysql_fetch_assoc($ef);

$BillDate = ($expf['BillDate']  != '0000-00-00' && $expf['BillDate']  != '' && $expf['BillDate'] != '') ? date("d-m-Y",strtotime($expf['BillDate'])) : date("d-m-Y",strtotime($exp['CrDate']));
 ?>


<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">
				                    

                    <tr>
                     <td><b>Bill Date</b></td>	
                     <td><b>Amount</b></td>	
                     <td><b>Remarks</b></td>	
                    </tr>


                    <tr>
                    <td>		
					<div class="form-group">
			            <label for="date" class="sr-only">Date</label>
			            <input id="claimdate" class="form-control_manual form-control input-group-lg dat reg_name" type="text" name="mclaim_date" title="Date" placeholder="Date" value="<?=$BillDate?>" required />
			        </div>
                     </td>

                    <td>
				    <div class="form-group">
				        <label for="amount" class="sr-only">Amount</label>
				        <input id="amount" class="form-control_manual form-control input-group-lg" type="text" autocapitalize='off' name="mamount" value="<?=$exp['FilledTAmt']?>" title="Enter Amount" placeholder="Amount" required/>
				    </div></td>


                    <td>
				    <div class="form-group">
				        <label for="remarks" class="sr-only">Remarks</label>
				       <input id="remarks" class="form-control_manual form-control input-group-lg" type="remarks" name="mremarks"  value="<?=$exp['Remark']?>" title="Enter Remarks" placeholder="Remarks"/>
				    </div></td>
                     </tr>


                    <tr>
                  
				  
<!---------------------------------------->
<!---------------------------------------->
<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
			<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
			<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
			<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
			<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
			<?php if($_SESSION['EmpRole']!='M'){ ?>
			<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
				<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
			<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
			<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
				<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
			<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
			<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
				<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
			<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
			<?php }?>


			<?php if($_SESSION['EmpRole']=='M'){ ?>
			<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
			<?php } ?>
									
		  </tr>
		  <?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
<!---------------------------------------->
<!---------------------------------------->				  

                   	<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">		

				      	<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']!='V' && $_SESSION['EmpRole']!='A' && $_SESSION['EmpRole']!='F'){ ?>
				      	<button class="btn btn-sm btn-info draft2" id="draft2" name="draftManual" style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';" disabled>Save as Draft</button>
						<?php } ?>

						<button class="btn btn-sm btn-success" id="Update" name="UpdateManual" style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" disabled>Submit</button>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

					</td>

				</tr>
					
			
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>
<?php
}
?>
<script type="text/javascript" src="js/claim.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
     
    $('.dat').datetimepicker({format:'d-m-Y'});
        $('#JourneyStartDt1').datetimepicker({format:'d-m-Y H:i'});

    //here closing the JourneyStartDt datetimepicker on date change 

});


	$('.dat').on('click', function(){
	    $(this).datetimepicker('hide');
         
           var FYearId = '<?=$_SESSION['FYearId']?>';
           var expid= '<?=$_REQUEST['expid']?>';
           var BillDate = $(this).val();
           if(BillDate!=''){
           var postdata = {'FYearId':FYearId, 'expid':expid, 'BillDate':BillDate};

            $.ajax({
            url: 'post_ajax.php',	
            type:'post',
            data: postdata,
            dataType: 'json',
            success:function(data){
               if(data.status=='success'){
                  	alert("This Month Entry has been submitted and closed.");
                         window.location.reload();
                   }
                   
            }    
        });
           }

	}); 
</script>