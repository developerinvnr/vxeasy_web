<?php 
// echo "heelll";die();



session_start();
// include 'config.php';
// error_reporting(0);


if(isset($_POST['FYearId'])){
 
    // define('HOST','localhost');
    // define('USER','root'); 
    // define('PASS',''); 
    // define('dbexpro','expense_demo');
    
    $DbName='vnressus_expense';
    //if($_SESSION['CompanyId']==11){ $DbName='vnressus_expense'; }
    //elseif($_SESSION['CompanyId']==22){ $DbName=''; }
    //elseif($_SESSION['CompanyId']==33){ $DbName=''; }

    define('HOST','localhost');
    define('USER','vnressus_hrims_user'); 
    define('PASS','hrims@192'); 
    define('dbexpro',$DbName);
    define('CHARSET','utf8'); 
    $con2=mysql_connect(HOST,USER,PASS) or die(mysql_error());
    $empdb=mysql_select_db(dbexpro, $con2) or die(mysql_error());

    if(isset($_POST['BillDate'])){

    $m=date("n",strtotime($_POST['BillDate']));
    $exp=mysql_query("SELECT CrBy FROM y".$_POST['FYearId']."_expenseclaims WHERE ExpId = '".$_POST['expid']."' ");
    $exp_data=mysql_fetch_assoc($exp);
 

    if($exp_data!=''){

        $c=mysql_query("SELECT * FROM y".$_POST['FYearId']."_monthexpensefinal WHERE EmployeeID = '".$exp_data['CrBy']."' and Month ='".$m."'");

        // print_r("SELECT * FROM y".$_POST['FYearId']."_monthexpensefinal WHERE EmployeeID = '".$exp_data['CrBy']."' and Month ='".$m."'");
        // die();
	    $ct=mysql_fetch_assoc($c);
   
		 if($ct['Status']=='Closed'){

		    echo json_encode(array("status"=>'success'));
		      die();
	      }else{
	      			echo json_encode(array("status"=>'error')); die();
	      }
      }else{
      			echo json_encode(array("status"=>'error')); die();

      }
    }else{
    	echo json_encode(array("status"=>'error')); die();

    }
	
}elseif (isset($_POST['mclaim_date'])) {
    
    $DbName='vnressus_expense';
    //if($_SESSION['CompanyId']==11){ $DbName='vnressus_expense'; }
    //elseif($_SESSION['CompanyId']==22){ $DbName=''; }
    //elseif($_SESSION['CompanyId']==33){ $DbName=''; }

  // $servername = "localhost";
  // $username = "root";
  // $password = "";
  // $dbname = "expense_demo";

    $servername = "localhost";
    $username = "vnressus_hrims_user";
    $password = "hrims@192";
    $dbname = $DbName;


    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    $_POST['mclaim_date'];
    $_POST['mclaimtype'];
    $_POST['mamount'];
    $_POST['mremarks']; 

    $BillDate=date("Y-m-d",strtotime($_POST['mclaim_date']));//this billdate been copied JourneyStartDt because billdate is necessary
    
    if($BillDate>='2022-04-01'){ $yId=2; }else{ $yId=$_SESSION['FYearId']; }

      $sth = $conn->prepare("SELECT * FROM `y".$yId."_expenseclaims` WHERE ClaimId='".$_POST['mclaimtype']."' and BillDate = '". $BillDate."' and ClaimStatus!='Deactivate' and FilledBy='".$_SESSION['EmployeeID']."'");
      $sth->execute();
      $result = $sth->fetchAll();

      // print_r($result);
    if(count($result)==0){

      $stmt = $conn->prepare("INSERT INTO `y".$yId."_expenseclaims`  (`ClaimId`,`BillDate`,`Remark`,`ClaimStatus`,`ClaimAtStep`,`ClaimMonth`,`FilledBy`,`FilledOkay`,`FilledTAmt`,`FilledDate`, `CrBy`, `CrDate`, `ClaimYearId`) VALUES  ('".$_POST['mclaimtype']."', '". $BillDate."', '".$_POST['mremarks']."',  'Filled', 2,  '".date("m",strtotime($_POST['mclaim_date']))."', '".$_SESSION['EmployeeID']."', 1, '".$_POST['mamount']."', '".date("Y-m-d")."', '".$_SESSION['EmployeeID']."', '".date("Y-m-d")."', '".$yId."')");
      $stmt->execute();
      $expid = $conn->lastInsertId();


     $cl = $conn->prepare("INSERT INTO `y".$yId."_g1_expensefilldata` (`ExpId`,`BillDate`) VALUES ('".$expid."','".$BillDate."')");
     $cl->execute();


     $cl2 = $conn->prepare("INSERT INTO `y".$yId."_expenseclaimsdetails`( `ExpId`,`Title`,`Amount`,`Remark`) VALUES ('".$expid."', 'Manual amount', '".$_POST['mamount']."', '".$_POST['mremarks']."')");
     $st = $cl2->execute();

    if($st){
          echo json_encode(array("status"=>'success', "msg"=>"Manual claim added successfully."));
          die();
    }

    }else{

    echo json_encode(array("status"=>'error', "msg"=>"Claim is already exists."));

    }


}
else{
		echo json_encode(array("status"=>'error', "msg"=>"There is some problem in your reuquest."));
}
  



?>