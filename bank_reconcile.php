<?php 
include "include/header.php";  

$addcheck=0;
$editcheck=0;
$statuscheck=0;
$deletecheck=0;

$bankAccNo = isset($_POST['hbankaccountno'])?$_POST['hbankaccountno']:'';
$statementDateTo = isset($_POST['hstatementdate'])?$_POST['hstatementdate']:'';
$acBalance = isset($_POST['hbankacbalance'])?$_POST['hbankacbalance']:'0.00';
$radMethod = isset($_POST['hcomparemethod'])?$_POST['hcomparemethod']:'B_REC';

$statementRefno = '';

$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`>1";
$resultbank =$conn-> query($sqlbank); 
/*
$sqlsub="SELECT `idtbl_account_allocation` as id, tbl_company_branch_idtbl_company_branch as group_id, `subaccountno` as `code` FROM `tbl_account_allocation` ORDER BY group_id";
*/
$sqlsub="SELECT `tbl_account_allocation`.`idtbl_account_allocation` as id, `tbl_account_allocation`.tbl_company_branch_idtbl_company_branch as group_id, `tbl_account_allocation`.`subaccountno` AS `code`, CONCAT(`tbl_account_allocation`.`subaccountno`, ' ', `tbl_subaccount`.`subaccountname`) as `name` FROM `tbl_account_allocation` INNER JOIN `tbl_subaccount` ON `tbl_account_allocation`.`subaccountno`=`tbl_subaccount`.`subaccount` WHERE `tbl_account_allocation`.`status`=1 ORDER BY group_id, id";
$resultsub =$conn-> query($sqlsub); 

/*
$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 

$sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=0 AND `status`=1";
$resultvehicle =$conn-> query($sqlvehicle); 

$sqlvehicletrailer="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=1 AND `status`=1";
$resultvehicletrailer =$conn-> query($sqlvehicletrailer); 

$sqldiverlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=4 AND `status`=1";
$resultdiverlist =$conn-> query($sqldiverlist);

$sqlofficerlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=6 AND `status`=1";
$resultofficerlist =$conn-> query($sqlofficerlist);
*/

$importmsg = '';
$msgclass = 'msgerror';

if(isset($_POST["Import"])){
	$filename=$_FILES["file"]["tmp_name"];
	
	
	if($_FILES["file"]["size"] > 0){
		$userID=$_SESSION['userid'];
		
		$conn->autocommit(FALSE);
		$flag = true;
		/*
		$insertSQL = "INSERT INTO csvlog(csvname, username) VALUES(%s, %s)";
		
		*/
		
		$presql = "select ifnull(date_add(MAX(statement_date_to), INTERVAL 1 DAY), '') as statement_date_to from tbl_gl_bank_statements where statement_account_id=?";
		$stmt_frDate = $conn->prepare($presql);
		$stmt_frDate->bind_param('s', $bankAccNo);
		$stmt_frDate->execute();
		$stmt_frDate->store_result();
		$stmt_frDate->bind_result($statement_date_to);
		
		$stmt_frDate->fetch();
		
		$adjust_frDate = false;
		
		if($statement_date_to==''){
			$statementDateFr = $statementDateTo;
			$adjust_frDate = true;
		}else{
			$statementDateFr = $statement_date_to;
		}
		
		//echo $statementDateFr.'---'.$statementDateTo;
		
		$presql = "select id as prev_statement_refno, statement_date_to as prev_date_to from tbl_gl_bank_statements where statement_account_id=? AND (? <= statement_date_to) ORDER BY statement_date_to DESC LIMIT 1";//(? BETWEEN statement_date_fr AND statement_date_to)
		$stmt_toDate = $conn->prepare($presql);
		$stmt_toDate->bind_param('ss', $bankAccNo, $statementDateTo);
		$stmt_toDate->execute();
		$stmt_toDate->store_result();
		$totalRows_rsDate = $stmt_toDate->num_rows;
		$stmt_toDate->bind_result($prev_statement_refno, $prev_date_to);
		
		$stmt_toDate->fetch();
		
		if($totalRows_rsDate>0){
			$statementDateTo = $prev_date_to; // ignore-insert
			$statementRefNo = $prev_statement_refno;
		}
		
		$sql = "insert IGNORE into tbl_gl_bank_statements (statement_date_fr, statement_date_to, statement_account_id, statement_code, created_by, created_at) values (?, ?, ?, md5(?), ?, NOW())";
		$stmt = $conn->prepare($sql);
		$statementCode = $statementDateTo.'_'.$bankAccNo;
		$stmt->bind_param('sssss', $statementDateFr, $statementDateTo, $bankAccNo, $statementCode, $userID);
		$ResultOut = $stmt->execute();
		$statementRowId = $stmt->insert_id;
		
		$totjobs = 0; // total-plans-in-csv
		$errjobs = 0; // duplicate-plans-in-csv
		$batchsucc = true; 
		
		$rate_feed = false; // set-player-ranking
		$save_code = 0;
		
		$file = fopen($filename, "r");
			
		while ($batchsucc){
			if(($planData = fgetcsv($file, 10000, ",")) !== FALSE){
				$numcols = count($planData);
				if($numcols==4){
					
					$sql = "insert into tbl_gl_bank_statement_details (tbl_gl_bank_statement_id, transaction_date, cheque_no, transaction_particulars, transaction_amount, crdr, created_by, created_at) values (?, ?, ?, ?, ?, ?, ?, NOW())";
					$stmt = $conn->prepare($sql);
					
					$crdr = ($planData[3]>=0)?'D':'C';
					
					$stmt->bind_param('sssssss', $statementRowId, $planData[0], $planData[1], $planData[2], $planData[3], $crdr, $userID);
					$ResultOut = $stmt->execute();
					$affectedRowId = $stmt->insert_id;
					
					if($affectedRowId>0) {
						/*
						$sql = "insert into tbl (fields) values (params)";
						$stmt = $conn->prepare($sql);
						$stmt->bind_param('ssssss', $userID);
						$ResultOut = $stmt->execute();
						*/
						$affectedRowCnt = $conn->affected_rows;
						
						if($affectedRowCnt==1){
							$totjobs++; // increase-job-count
							$rate_feed = true; // declare-initial-rating-detail
							
						}else{
							$importmsg = 'Something wrong';
							
						}
						
					}else{
						$errjobs = 1; // increase-err-plan-count
					}
					
				}
				
				
			}else{
				$save_code = 1;
			}
			
			if(!$rate_feed){
				if( ($totjobs*$save_code)==0 ){
					$flag = false; // clear-file-import-batch-info-written-to-csvlog
				}
				
				$batchsucc = false; // assuming-end-of-data
				
			}else{
				$rate_feed = false; // reset-value-to-capture-upcoming-errors
				
			}
		}
		
		fclose($file);
		
		if($adjust_frDate){
			$revsql = "UPDATE tbl_gl_bank_statements INNER JOIN (select tbl_gl_bank_statement_id AS id, MIN(transaction_date) AS date_fr FROM tbl_gl_bank_statement_details WHERE tbl_gl_bank_statement_id=?) as drv ON tbl_gl_bank_statements.id=drv.id SET statement_date_fr=drv.date_fr WHERE tbl_gl_bank_statements.id=?";
			$stmt_rev = $conn->prepare($revsql);
			$stmt_rev->bind_param('ss', $statementRowId, $statementRowId);
			$ResultOut = $stmt_rev->execute();
			
			$flag=($conn->affected_rows==1)?$flag:false;
		}
		
		if($flag){
			$conn->commit();
			$statementRefno = $statementRowId;
			$importmsg = $totjobs." record(s) imported successfully";
			$msgclass = "msgsuccess";
		}else{
			$conn->rollback();
			if($importmsg==''){
				if($errjobs==1){
					$importmsg = "Unable to insert line " . ($totjobs+1) . " details.";
					$msgclass = "msginvalid";
				}else{
					$importmsg = "Invalid format. Please check file content";
					$msgclass = "msgerror";
				}
			}
		}
		
		
	}else{
		$importmsg = 'File is empty';
		$msgclass = "msginvalid";
	}
}

include "include/topnavbar.php"; 
?>
<style>
    .tableprint {
        table-layout: fixed;
    }
</style>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="list"></i></div>
                            <span>Bank Reconciliation</span>
                            <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" id="btnordercreate" data-refid="-1" style="position:absolute; right:10px;"><i class="fas fa-file-excel"></i>&nbsp;Upload Statement</button>
                        </h1>
                        <!--h6>&nbsp;</h6-->
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-md-8">
                            	<form id="frm_statement" action="" method="post" autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-row mb-1">
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark" style="margin-left:3px;">Statement Date*</label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control dpd1a" placeholder="Select" name="statement_date" id="statement_date" required value="<?php echo $statementDateTo; ?>">
                                                        <div class="input-group-append">
                                                            <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                                        </div>
                                                    </div> 
                                                </div>  
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark" style="margin-left:3px;">Account No.*</label>
                                                    <select name="account_no" id="account_no" class="form-control form-control-sm">
                                                        <option value="-1">Select</option>
                                                        <?php if($resultsub->num_rows > 0) {while ($rowsub=$resultsub->fetch_assoc()) { ?>
                                                        <option value="<?php echo $rowsub['id'] ?>" data-colcode="<?php echo $rowsub['code']; ?>"<?php if(!(strcmp($bankAccNo, $rowsub['id']))){ echo ' selected="selected"'; } ?>><?php echo ''.$rowsub['name'] ?></option>
                                                        <?php }} ?>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark" style="margin-left:3px;">Statement Balance*</label>
                                                    <input type="text" class="form-control form-control-sm" name="statement_balance" id="statement_balance" required value="<?php echo $acBalance; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="margin-bottom:0px;">
                                            <label class="small font-weight-bold text-dark" style="margin-right:10px;">Compare To</label>
                                                <!--div class="col"-->
                                                    <div class="form-row">
                                                        <div class="form-group col-md-3" style="margin-bottom:0px;"><!--div class="col"-->
                                                            <div class="i-checks" style="line-height:9px;">
                                                                <?php 
																$sel_str='';
																$tog_val=0;
																if(!(strcmp($radMethod, 'B_REC'))){
																	$sel_str=' checked="checked"';
																	$tog_val=1;
																}
																?>
                                                                
                                                                <input type="radio" name="rad_compare_method" id="rad_bank" value="B_REC"<?php  echo $sel_str; ?> data-togval="<?php echo $tog_val; ?>" class="form-control-custom radio-custom" />
                                                                <label class="small" for="rad_bank">Bank</label>
                                                            </div>
                                                        <!--/div>
                                                        <div class="col"-->
                                                            <div class="i-checks" style="line-height:10px;">
                                                                <?php 
																$sel_str='';
																$tog_val=0;
																if(!(strcmp($radMethod, 'G_REC'))){
																	$sel_str=' checked="checked"';
																	$tog_val=1;
																}
																?>
                                                                
                                                                <input type="radio" name="rad_compare_method" id="rad_gl" value="G_REC"<?php  echo $sel_str; ?> data-togval="<?php echo $tog_val; ?>" class="form-control-custom radio-custom" />
                                                                <label class="small" for="rad_gl">GL</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-9" style="margin-bottom:0px;"><!--div class="col"-->
                                                            <div class="i-checks" style="line-height:10px;">
                                                                <?php 
																$sel_str='';
																$tog_val=0;
																if(!(strcmp($radMethod, 'M_REC'))){
																	$sel_str=' checked="checked"';
																	$tog_val=1;
																}
																?>
                                                                
                                                                <input type="radio" name="rad_compare_method" id="rad_manual" value="M_REC"<?php  echo $sel_str; ?> data-togval="<?php echo $tog_val; ?>" class="form-control-custom radio-custom" />
                                                                <label class="small" for="rad_manual">Manual</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <!--/div-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group"><!-- mt-2 -->
                                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm" <?php //if($addcheck==0){echo 'disabled';} ?>><i class="far fa-play-circle"></i>&nbsp;Start</button>
                                    	<input type="hidden" name="statement_id" id="statement_id" value="" />
                                    </div>
                                    <!--input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value=""-->
                                </form>
                            </div>
                            <div class="col-md-4" style="text-align:right;">
                                <div class="form-group mt-2" align="right">
                                	<span id="rec_info_msg">
										<?php echo (($importmsg=='')?'Select all required fields to start process.':$importmsg); ?>
                                    </span>
                                </div>
                                <hr>
                                
                                <div class="form-group mt-2" align="right">
                                    <input type="hidden" name="hrefid" id="hrefid" value="<?php //echo $statementRefno; ?>" />
                                    <button type="button" id="saveBtn" class="btn btn-outline-primary btn-sm" <?php //if($addcheck==0){echo 'disabled';} ?> data-locktype="save"><i class="far fa-save"></i>&nbsp;Save</button>
                                    <button type="button" id="clearBtn" class="btn btn-outline-primary btn-sm" <?php //if($addcheck==0){echo 'disabled';} ?> disabled="disabled"><i class="far fa-trash-alt"></i>&nbsp;Clear</button>
                                    <button type="button" id="copyBtn" class="btn btn-outline-primary btn-sm" <?php //if($addcheck==0){echo 'disabled';} ?> data-locktype="copy"><i class="far fa-copy"></i>&nbsp;Copy</button>
                                    <button type="button" id="insertBtn" class="btn btn-outline-primary btn-sm" <?php //if($addcheck==0){echo 'disabled';} ?> data-locktype="flag"><i class="far fa-check-circle"></i>&nbsp;Insert</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="col-md-6">
                            	<div class="form-group">
                                    <div class="card bg-primary text-white mb-4">
                                        <div class="card-body">GL Credit</div>
                                        <div class="card-footer d-flex align-items-center justify-content-between">
                                            <span class="small text-white stretched-link" href="#">Present Total</span>
                                            <div class="small text-white" id="glcredit_rectot" data-ptsect="gc">0.00</div>
                                            
                                            <span class="small text-white stretched-link" href="#">Unpresent Total</span>
                                            <div class="small text-white" id="glcredit_uptot" data-utsect="gc">0.00</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                	<table class="table table-bordered table-striped table-sm nowrap small" id="glcredit_table">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Trn Date</th>
                                                <th>Chq No</th>
                                                <th>Narration</th>
                                                <th>Amount</th>
                                                
                                            </tr>
                                        </thead>
                                        <!--tbody>
                                        	<tr>
                                            	<td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody-->
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                            	<div class="form-group">
                                    <div class="card bg-secondary text-white mb-4">
                                        <div class="card-body">Bank Debit</div>
                                        <div class="card-footer d-flex align-items-center justify-content-between">
                                            <span class="small text-white stretched-link" href="#">Present Total</span>
                                            <div class="small text-white" id="bankdebit_rectot" data-ptsect="bd">0.00</div>
                                            
                                            <span class="small text-white stretched-link" href="#">Unpresent Total</span>
                                            <div class="small text-white" id="bankdebit_uptot" data-utsect="bd">0.00</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                	<table class="table table-bordered table-striped table-sm nowrap small" id="bankdebit_table">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Trn Date</th>
                                                <th>Chq No</th>
                                                <th>Narration</th>
                                                <th>Amount</th>
                                                
                                            </tr>
                                        </thead>
                                        <!--tbody>
                                        	<tr>
                                            	<td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody-->
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                        	<div class="col-md-6">
                            	<div class="form-group">
                                    <div class="card bg-primary text-white mb-4">
                                        <div class="card-body">GL Debit</div>
                                        <div class="card-footer d-flex align-items-center justify-content-between">
                                            <span class="small text-white stretched-link" href="#">Present Total</span>
                                            <div class="small text-white" id="gldebit_rectot" data-ptsect="gd">0.00</div>
                                            
                                            <span class="small text-white stretched-link" href="#">Unpresent Total</span>
                                            <div class="small text-white" id="gldebit_uptot" data-utsect="gd">0.00</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                	<table class="table table-bordered table-striped table-sm nowrap small"  id="gldebit_table">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Trn Date</th>
                                                <th>Chq No</th>
                                                <th>Narration</th>
                                                <th>Amount</th>
                                                
                                            </tr>
                                        </thead>
                                        <!--tbody>
                                        	<tr>
                                            	<td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody-->
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                            	<div class="form-group">
                                    <div class="card bg-secondary text-white mb-4">
                                        <div class="card-body">Bank Credit</div>
                                        <div class="card-footer d-flex align-items-center justify-content-between">
                                            <span class="small text-white stretched-link" href="#">Present Total</span>
                                            <div class="small text-white" id="bankcredit_rectot" data-ptsect="bc">0.00</div>
                                            
                                            <span class="small text-white stretched-link" href="#">Unpresent Total</span>
                                            <div class="small text-white" id="bankcredit_uptot" data-utsect="bc">0.00</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                	<table class="table table-bordered table-striped table-sm nowrap small"  id="bankcredit_table">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Trn Date</th>
                                                <th>Chq No</th>
                                                <th>Narration</th>
                                                <th>Amount</th>
                                                
                                            </tr>
                                        </thead>
                                        <!--tbody>
                                        	<tr>
                                            	<td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody-->
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<!-- Confirm Modal-->
<div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="custorder_details" action="bank_reconcile.php" method="post" target="_self" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="fileModalLabel">Confirmation</h5>&nbsp;
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <p id="" style="">
                Please select the statement details file for account <span id="modal_date_str"></span> on <span id="modal_acno_str"></span>
            </p>
            
            <p id="row_heading" style="color:blue;"></p>
            
            <p id="lblstatus"></p>
          </div>
          <div class="modal-footer">
            <input class="form-control col" type="file" name="file" id="file" style="padding-bottom:38px;">
            
            <input type="hidden" name="hstatementdate" id="hstatementdate" value="" />
            <input type="hidden" name="hbankaccountno" id="hbankaccountno" value="" /><!-- bank-ac-no -->
            <input type="hidden" name="hbankacbalance" id="hbankacbalance" value="" /><!-- bank-ac-balance -->
            <input type="hidden" name="hcomparemethod" id="hcomparemethod" value="" /><!-- compare-method -->
            <button type="submit" name="Import" class="btn btn-primary" required="required">Upload</button>
          </div>
        </div>
    </form>
  </div>
</div>
<!-- Confirm Modal-->

<!-- Modal order print -->
<div class="modal fade" id="modalorderprint" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewdispatchprint"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm fa-pull-right" id="btnorderprint"><i class="fas fa-print"></i>&nbsp;Print Order</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal order print -->


<!-- Modal Warning -->
<div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body bg-danger text-white text-center">
                <div id="warningdesc"></div>
            </div>
            <div class="modal-footer bg-danger rounded-0">
                <button type="button" class="btn btn-outline-light btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Warning -->


<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        $('body').tooltip({
            selector: '[data-toggle="tooltip"]'
        });
        $('[data-toggle="tooltip"]').tooltip({
            trigger : 'hover'
        });

        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        /*
		var glCredit_rectot = 0;
		var glCredit_uptot = 0;
		var bankDebit_rectot = 0;
		var bankDebit_uptot = 0;
		var glDebit_rectot = 0;
		var glDebit_uptot = 0;
		var bankCredit_rectot = 0;
		var bankCredit_uptot = 0;
		*/
		
		
		var glCredit_table=$('#glcredit_table').DataTable( {
			"info":false,
			//"searching":false,
			"destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/gltransactions.php",
                type: "POST", // you can use GET
				"data":function(data){
					data.set_date=$('#statement_date').val();
					data.set_acnum=$('#account_no').find(":selected").data('colcode');
					data.set_code=$('#hrefid').val();
					data.set_crdr="C";
				}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "detail_id",
                    "className": 'text-center',
					//"orderable":false,
                    "render": function(data, type, full) {
                        var button='';
                        var check_str = (full.detail_cancel==0)?'checked="checked"':'';
						
						if(data==null){
							check_str = '';
							data = '';
						}
						
						var block_str = (full.is_matched=='1')?'disabled="disabled"':'';
                        button+='<input type="checkbox" class="form-control-custom chk_deposit" data-toggle="tooltip" data-placement="right" title="" data-refid="'+full.header_id+'" data-refgroup="GENL" data-sectname="gc" ';button+=check_str+block_str+' value="'+data+'" />'; 
                        
                        return button;
                    }
                },
				{
                    "data": "col_date"
                },
                {
                    "data": "cheque_no"
                },
                {
                    "data": "col_narration"
                },
                {
                    "data": "col_amount",
                    "className": 'text-right'
                }
            ], 
			"createdRow": function( row, data, dataIndex ){
				//$( row ).attr('id', 'pack-'+data.header_id);
			}
		} );
		/*
		var glDebit_table=$('#gldebit_table').DataTable();
		*/
		var glDebit_table=$('#gldebit_table').DataTable( {
			"info":false,
			//"searching":false,
			"destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/gltransactions.php",
                type: "POST", // you can use GET
				"data":function(data){
					data.set_date=$('#statement_date').val();
					data.set_acnum=$('#account_no').find(":selected").data('colcode');
					data.set_code=$('#hrefid').val();
					data.set_crdr="D";
				}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "detail_id",
                    "className": 'text-center',
					//"orderable":false,
                    "render": function(data, type, full) {
                        var button='';
                        var check_str = (full.detail_cancel==0)?'checked="checked"':'';
						
						if(data==null){
							check_str = '';
							data = '';
						}
						
						var block_str = (full.is_matched=='1')?'disabled="disabled"':'';
                        button+='<input type="checkbox" class="form-control-custom chk_deposit" data-toggle="tooltip" data-placement="right" title="" data-refid="'+full.header_id+'" data-refgroup="GENL" data-sectname="gd" ';button+=check_str+block_str+' value="'+data+'" />'; 
                        
                        return button;
                    }
                },
				{
                    "data": "col_date"
                },
                {
                    "data": "cheque_no"
                },
                {
                    "data": "col_narration"
                },
                {
                    "data": "col_amount",
                    "className": 'text-right'
                }
            ], 
			"createdRow": function( row, data, dataIndex ){
				//$( row ).attr('id', 'pack-'+data.header_id);
			}
		} );
		
		
		var bankDebit_table=$('#bankdebit_table').DataTable( {
			"info":false,
			//"searching":false,
			"destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/bstransactions.php",
                type: "POST", // you can use GET
				"data":function(data){
					data.set_bsnum=$('#statement_id').val();
					data.set_code=$('#hrefid').val();
					data.set_crdr="D";
				}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "detail_id",
                    "className": 'text-center',
					//"orderable":false,
                    "render": function(data, type, full) {
                        var button='';
                        var check_str = (full.detail_cancel==0)?'checked="checked"':'';
						
						if(data==null){
							check_str = '';
							data = '';
						}
						
						var block_str = (full.is_matched=='1')?'disabled="disabled"':'';
                        button+='<input type="checkbox" class="form-control-custom chk_deposit" data-toggle="tooltip" data-placement="right" title="" data-refid="'+full.header_id+'" data-refgroup="BANK" data-sectname="bd" ';button+=check_str+block_str+' value="'+data+'" />'; 
                        
                        return button;
                    }
                },
				{
                    "data": "col_date"
                },
                {
                    "data": "cheque_no"
                },
                {
                    "data": "col_narration"
                },
                {
                    "data": "col_amount",
                    "className": 'text-right'
                }
            ], 
			"createdRow": function( row, data, dataIndex ){
				//$( row ).attr('id', 'pack-'+data.header_id);
			}
		} );
		
		/**/
		var bankCredit_table=$('#bankcredit_table').DataTable( {
			"info":false,
			//"searching":false,
			"destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/bstransactions.php",
                type: "POST", // you can use GET
				"data":function(data){
					data.set_bsnum=$('#statement_id').val();
					data.set_code=$('#hrefid').val();
					data.set_crdr="C";
				}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "detail_id",
                    "className": 'text-center',
					//"orderable":false,
                    "render": function(data, type, full) {
                        var button='';
                        var check_str = (full.detail_cancel==0)?'checked="checked"':'';
						
						if(data==null){
							check_str = '';
							data = '';
						}
						
						var block_str = (full.is_matched=='1')?'disabled="disabled"':'';
                        button+='<input type="checkbox" class="form-control-custom chk_deposit" data-toggle="tooltip" data-placement="right" title="" data-refid="'+full.header_id+'" data-refgroup="BANK" data-sectname="bc" ';button+=check_str+block_str+' value="'+data+'" />'; 
                        
                        return button;
                    }
                },
				{
                    "data": "col_date"
                },
                {
                    "data": "cheque_no"
                },
                {
                    "data": "col_narration"
                },
                {
                    "data": "col_amount",
                    "className": 'text-right'
                }
            ], 
			"createdRow": function( row, data, dataIndex ){
				//$( row ).attr('id', 'pack-'+data.header_id);
			}
		} );
		
		
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            endDate: 'today',
            format: 'yyyy-mm-dd'
        });
		
        // Create order part
        $('#btnordercreate').click(function(){
            var date_str = $('#statement_date').val();
			var acno_str = $('#account_no').find(':selected').val();
			var acno_lbl = $('#account_no').find(':selected').data('colcode');
			//
			if(date_str!='' && acno_str!='' && acno_lbl!=''){
				$('#hrefid').val(''); // load-empty-record-number
				
				$('#hstatementdate').val(date_str);
				$('#hbankaccountno').val(acno_str);
				$('#hbankacbalance').val($('#statement_balance').val());
				$('#hcomparemethod').val($('input[name="rad_compare_method"]:checked').val());
				$('#modal_date_str').html(date_str);
				$('#modal_acno_str').html(acno_lbl);
				
				$('#fileModal').modal('show');
			}else{
				action({"icon":"fas fa-info-circle", 
					   "title":"", "message":"Select account and statement date", 
					   "url":"", 
					   "target":"_blank", 
					   "type":"danger"});
			}
        });
		
		$('input[name="rad_compare_method"]').change(function(){
			//var cash_selected=$('#rad_cash').is(":checked");
			//$('#cheque_no').prop('disabled', cash_selected);
			//$('#cheque_date').prop('disabled', cash_selected);
			//$('#drp_bank').prop('disabled', cash_selected);
			
			var auditref_no=$("#hrefid").val();
			if(auditref_no==''){
				//getReceipts();
				//book_table.draw();
				/*
				console.log('b4-ca--'+$("#rad_cash").attr('data-togval'));
				console.log('b4-ch--'+$("#rad_cheque").attr('data-togval'));
				console.log('old-'+$('input[type="radio"][data-togval="1"]').attr('id'));
				*/
				$('input[type="radio"][data-togval="1"]').attr('data-togval', "0");
				
				/*
				console.log('new-'+$(this).attr('id'));
				*/
				$(this).attr('data-togval', "1");
				
				//$('#rec_amount').val('0.00'); // if(cheque_selected)
				
				/*
				console.log('ca--'+$("#rad_cash").attr('data-togval'));
				console.log('ch--'+$("#rad_cheque").attr('data-togval'));
				console.log('0->'+$('input[type="radio"][data-togval="0"]').attr('id'));
				console.log('1->'+$('input[type="radio"][data-togval="1"]').attr('id'));
				*/
			}else{
				//console.log('1--'+ !$('#rad_cash').is(":checked"));
				//console.log('2--'+ !$('#rad_cheque').is(":checked"));
				$('input[type="radio"][data-togval="1"]').prop("checked", true);
				alert('You must restart the reconciliation process to select a different method');
				
			}
		});
		
		$('#frm_statement').on('submit', function(event){
			event.preventDefault();
			
			var check_stmtbal = ($('#hrefid').val()=='')?true:confirm('Are you sure you want to start new reconciliation ?');
			
			if(check_stmtbal){
				var statementdate=$('#statement_date').val();
				var acnum=$('#account_no').find(":selected").val();
				var statementbal=$('#statement_balance').val();
				
				$.ajax({
					type: "POST", 
					data: {
						statement_date:statementdate,
						ac_num:acnum,
						statement_bal:statementbal
					},
					dataType: 'JSON',
					url: 'getprocess/get_bank_ac_balance.php',
					success: function(data){
						$('#rec_info_msg').html(data.infomsg);
						$('#statement_id').val(data.statement_id);
						
						
						/*
						reset-ref-before-table-draw
						*/
						$('#hrefid').val('');
						$("#submitBtn").html('<i class="far fa-play-circle"></i>&nbsp;Start');
						/*
						draw-gl-receive-payment-tables
						set-bank-deposit-withdraw-tables-data-attr
						*/
						glCredit_table.draw();
						glDebit_table.draw();
						bankDebit_table.draw();
						bankCredit_table.draw();
						
						var glCredit_rectot = data.gc_rectot;
						$('#glcredit_rectot').html(parseFloat(glCredit_rectot).toFixed(2));
						var glCredit_uptot = data.gc_uptot;
						$('#glcredit_uptot').html(parseFloat(glCredit_uptot).toFixed(2));
						var bankDebit_rectot = data.bd_rectot;
						$('#bankdebit_rectot').html(parseFloat(bankDebit_rectot).toFixed(2));
						var bankDebit_uptot = data.bd_uptot;
						$('#bankdebit_uptot').html(parseFloat(bankDebit_uptot).toFixed(2));
						var glDebit_rectot = data.gd_rectot;
						$('#gldebit_rectot').html(parseFloat(glDebit_rectot).toFixed(2));
						var glDebit_uptot = data.gd_uptot;
						$('#gldebit_uptot').html(parseFloat(glDebit_uptot).toFixed(2));
						var bankCredit_rectot = data.bc_rectot;
						$('#bankcredit_rectot').html(parseFloat(bankCredit_rectot).toFixed(2));
						var bankCredit_uptot = data.bc_uptot;
						$('#bankcredit_uptot').html(parseFloat(bankCredit_uptot).toFixed(2));
					}
				});
				
			}
			
		});
		
		$(document).on("click", ".chk_deposit", function(){
			var refid=$("#hrefid").val();
			var receiptrefno=$(this).data('refid');
			var depositrefno=$(this).val();
			var detailcancel=$(this).is(":checked")?0:1;
			var groupcode=$(this).data('refgroup');//GENL, BANK
			var docnum=$('#statement_id').val();
			var reconciletype=$('input[type="radio"][name="rad_compare_method"]:checked').val();
			
			var sectname=$(this).data('sectname');
			var par=$(this).parent().parent();
			var keyval=parseFloat($(par).children('td:nth-child(5)').html());
			
			//(detailcancel==0)
			var present_update=keyval;
			var unpresent_update=keyval*-1;
			//(detailcancel==0)
			
			if(detailcancel==1){
				present_update=keyval*-1;
				unpresent_update=keyval;
			}
			
			var objchkdeposit=$(this);
			
			$.ajax({
				type: "POST",
				data: {
					group_code: groupcode, 
					ref_id: refid,
					doc_num: docnum,
					reconcile_type:reconciletype,
					receipt_refno: receiptrefno,
					sub_id: depositrefno,
					detail_cancel: detailcancel
				},
				dataType: 'JSON',
				url: 'process/bank_rec_gl_tally_process.php',
				success: function(data) { //alert(JSON.stringify(data));
					if(data.msgdesc.type=="success"){
						if(refid==''){
							$("#hrefid").val(data.head_k);
							$("#submitBtn").html('<i class="far fa-play-circle"></i>&nbsp;Restart');
						}
						
						if(depositrefno==''){
							$(objchkdeposit).val(data.sub_k);
						}
						
						var present_sect=$('div[data-ptsect="'+sectname+'"]');//pt-sect
						var present_tot=parseFloat(present_sect.html());
						$(present_sect).html(parseFloat(present_tot+present_update).toFixed(2));
						var unpresent_sect=$('div[data-utsect="'+sectname+'"]');//ut-sect
						var unpresent_tot=parseFloat(unpresent_sect.html());
						$(unpresent_sect).html(parseFloat(unpresent_tot+unpresent_update).toFixed(2));
					}else{
						//$(objchkdeposit).prop("disabled", true);
						$(objchkdeposit).prop("checked", !$(objchkdeposit).prop("checked"));
					}
					
					action(data.msgdesc);
					
				}
			});
		});
		
		$("#copyBtn, #insertBtn, #saveBtn").click(function(){
			var refid=$("#hrefid").val();
			var locktype=$(this).data('locktype');//flag, copy
			var docnum=$('#statement_id').val();
			
			$.ajax({
				type: "POST",
				data: {
					lock_type: locktype, 
					ref_id: refid,
					doc_num: docnum
				},
				dataType: 'JSON',
				url: 'process/bank_rec_gl_approve_process.php',
				success: function(data) { //alert(JSON.stringify(data));
					glCredit_table.draw();
					glDebit_table.draw();
					bankDebit_table.draw();
					bankCredit_table.draw();
					action(data.msgdesc);
					
					
				}
			});
		});
		
    });
	
	

    function action(data) { //alert(data);
        var obj = data;//JSON.parse(data);
        $.notify({
            // options
            icon: obj.icon,
            title: obj.title,
            message: obj.message,
            url: obj.url,
            target: obj.target
        }, {
            // settings
            element: 'body',
            position: null,
            type: obj.type,
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "center"
            },
            offset: 100,
            spacing: 10,
            z_index: 1031,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
        });
    }

    function addCommas(nStr){
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    function print_receipt() {
        printJS({
            printable: 'viewdispatchprint',
            type: 'html',
            style: '@page { size: landscape; margin:0.25cm; }',
            targetStyles: ['*']
        })
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }

    
</script>
<?php include "include/footer.php"; ?>
