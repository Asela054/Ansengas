<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$company=addslashes($_POST['company']);
$companybranch=addslashes($_POST['companybranch']);
$fianncialyear=addslashes($_POST['fianncialyear']);
$updatedatetime=date('Y-m-d h:i:s');


$conn->autocommit(FALSE);
$flag = true;
$msg_num = 5; // error-code

if($recordOption==1){
    /*
	prevent-multiple-allocation-for-a-specified-branch
	*/
	$pre_sql = "SELECT idtbl_master FROM tbl_master WHERE tbl_company_branch_idtbl_company_branch=?";
	$stmt = $conn->prepare($pre_sql);
	$stmt->bind_param('s', $companybranch);
	$stmt->execute();
	$stmt->store_result();
	
	if(!($stmt->num_rows==0)){
		$flag = false;
		header("Location:../financialyearbranch.php?action=5");
		die();
	}
	
	$insert="INSERT INTO `tbl_master`(`status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_company_branch_idtbl_company_branch`, `tbl_finacial_year_idtbl_finacial_year`) VALUES ('1','$updatedatetime','$userID','$companybranch','$fianncialyear')";
    if($conn->query($insert)==true){        
        $msg_num = 4; //insert-code //header("Location:../financialyearbranch.php?action=4");
    }
    //else{header("Location:../financialyearbranch.php?action=5");}
}
else{
    /*
	prevent-multiple-allocation-of-same-financial-year-for-a-specified-branch
	*/
	$pre_sql = "SELECT idtbl_master FROM tbl_master WHERE tbl_company_branch_idtbl_company_branch=? AND tbl_finacial_year_idtbl_finacial_year=?";
	$stmt = $conn->prepare($pre_sql);
	$stmt->bind_param('ss', $companybranch, $fianncialyear);
	$stmt->execute();
	$stmt->store_result();
	
	if(!($stmt->num_rows==0)){
		$flag = false;
		header("Location:../financialyearbranch.php?action=5");
		die();
	}
	
	/*
	$update="UPDATE `tbl_master` SET `updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_company_branch_idtbl_company_branch`='$companybranch',`tbl_finacial_year_idtbl_finacial_year`='$fianncialyear' WHERE `idtbl_master`='$recordID'";
    if($conn->query($update)==true){     
        $msg_num = 6; //update-code //header("Location:../financialyearbranch.php?action=6");
    }
    //else{header("Location:../financialyearbranch.php?action=5");}
	*/
	
	/*
	cancel-current-accounting-year
	*/
	$updateSQL = "UPDATE `tbl_master` SET `status`=3, `updatedatetime`=?, `tbl_user_idtbl_user`=? WHERE `idtbl_master`=? AND `status`=1";
	$stmtLock = $conn->prepare($updateSQL);
	$stmtLock->bind_param('sss', $updatedatetime, $userID, $recordID);
	$ResultOut = $stmtLock->execute();
	$affectedRowCnt = $conn->affected_rows;
	
	if(!($affectedRowCnt==1)){
		$flag = false;
	}
	
	/*
	create-new-accounting-year
	*/
	$insertSQL = "INSERT INTO `tbl_master`(`status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_company_branch_idtbl_company_branch`, `tbl_finacial_year_idtbl_finacial_year`) VALUES ('1', ?, ?, ?, ?)";
	$stmtReg = $conn->prepare($insertSQL);
	$stmtReg->bind_param('ssss', $updatedatetime, $userID, $companybranch, $fianncialyear);
	$ResultOut = $stmtReg->execute();
	$regNum = $conn->insert_id;
	
	if(!($regNum>0)){
		$flag = false;
	}
	
	/*
	calculate-opening-balance-of-new-accounting-year
	*/
	/*
	branch-allocated-accounts
	-------------------------
	SELECT `idtbl_account_allocation`, `subaccountno` FROM `tbl_account_allocation` WHERE `tbl_company_branch_idtbl_company_branch`=1 GROUP BY subaccountno
	
	closed-year-transaction-sums
	----------------------------
	SELECT `acccode`, SUM(`accamount`*(`crdr`='C')) AS cr_sum, SUM(`accamount`*(`crdr`='D')) AS dr_sum FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`=1 GROUP BY `acccode`
	
	closed-year-opening-sums
	------------------------
	SELECT `subaccount`, SUM(`ac_open_balance`) AS ac_open_balance FROM `tbl_gl_account_balance_details` WHERE `tbl_master_idtbl_master`=1 GROUP BY `subaccount`
	
	new-opening-balance-calc
	------------------------
	SELECT drv_alloclist.idtbl_account_allocation, drv_alloclist.subaccountno, IFNULL(drv_openinfo.ac_open_balance, 0) AS ac_open_balance, IFNULL(drv_crdrinfo.cr_sum, 0) AS cr_sum, IFNULL(drv_crdrinfo.dr_sum, 0) AS dr_sum FROM (
	SELECT `idtbl_account_allocation`, `subaccountno` FROM `tbl_account_allocation` WHERE `tbl_company_branch_idtbl_company_branch`=1 GROUP BY subaccountno) AS drv_alloclist LEFT OUTER JOIN (
	SELECT `subaccount`, SUM(`ac_open_balance`) AS ac_open_balance FROM `tbl_gl_account_balance_details` WHERE `tbl_master_idtbl_master`=1 GROUP BY `subaccount`
	) AS drv_openinfo ON drv_alloclist.subaccountno=drv_openinfo.subaccount LEFT OUTER JOIN (
	SELECT `acccode`, SUM(`accamount`*(`crdr`='C')) AS cr_sum, SUM(`accamount`*(`crdr`='D')) AS dr_sum FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`=1 GROUP BY `acccode`
	) AS drv_crdrinfo ON drv_alloclist.subaccountno=drv_crdrinfo.acccode
	
	*/
	$insertSQL = "INSERT INTO tbl_gl_account_balance_details (ac_balance_reg_code, idtbl_subaccount, idtbl_account_allocation, idtbl_financial_year, tbl_master_idtbl_master, subaccount, ac_open_balance, created_by, created_at) SELECT md5(CONCAT(drv_alloclist.idtbl_account_allocation, ?)) AS ac_balance_reg_code, tbl_subaccount.idtbl_subaccount, drv_alloclist.idtbl_account_allocation, ? AS idtbl_financial_year, ? AS tbl_master_idtbl_master, drv_alloclist.subaccountno AS subaccount, (IFNULL(drv_openinfo.ac_open_balance, 0)+(IFNULL(drv_crdrinfo.dr_sum, 0)*IFNULL(NULLIF(tbl_mainclass.transactiontype-2, 0), 1))+(IFNULL(drv_crdrinfo.cr_sum, 0)*IFNULL(NULLIF(1-tbl_mainclass.transactiontype, 0), 1))) AS ac_open_balance, ? AS created_by, ? AS created_at FROM (";
	$insertSQL .= "SELECT `idtbl_account_allocation`, `subaccountno` FROM `tbl_account_allocation` WHERE `tbl_company_branch_idtbl_company_branch`=? GROUP BY subaccountno) AS drv_alloclist INNER JOIN tbl_subaccount ON drv_alloclist.subaccountno=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code LEFT OUTER JOIN (";
	$insertSQL .= "SELECT `subaccount`, SUM(`ac_open_balance`) AS ac_open_balance FROM `tbl_gl_account_balance_details` WHERE `tbl_master_idtbl_master`=? GROUP BY `subaccount`";
	$insertSQL .= ") AS drv_openinfo ON drv_alloclist.subaccountno=drv_openinfo.subaccount LEFT OUTER JOIN (";
	$insertSQL .= "SELECT `acccode`, SUM(`accamount`*(`crdr`='C')) AS cr_sum, SUM(`accamount`*(`crdr`='D')) AS dr_sum FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`=? GROUP BY `acccode`";
	$insertSQL .= ") AS drv_crdrinfo ON drv_alloclist.subaccountno=drv_crdrinfo.acccode";
	
	$stmtOpen = $conn->prepare($insertSQL);
	$stmtOpen->bind_param('ssssssss', $fianncialyear, $fianncialyear, $regNum, $userID, $updatedatetime, $companybranch, $recordID, $recordID);
	$ResultOut = $stmtOpen->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if(!($affectedRowCnt>0)){
		$flag = false;
	}
	
	/*
	set-msg-num-as-updated
	*/
	if($flag){
		$msg_num = 6;
	}
}


if ($flag) {
	$conn->commit();
	
} else {
	$conn->rollback();
	
}


header("Location:../financialyearbranch.php?action=".$msg_num);



?>