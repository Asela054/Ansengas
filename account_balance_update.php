<?php 
include "include/header.php";  

$addcheck=0;
$editcheck=0;
$statuscheck=0;
$deletecheck=0;


$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`>1";
$resultbank =$conn-> query($sqlbank); 


$sqlmain="SELECT tbl_company_branch.idtbl_company_branch as id, tbl_company_branch.code, concat(tbl_company_branch.code, ' ', tbl_company_branch.branch) as name, drv_master.idtbl_master, drv_master.financial_year, tbl_finacial_year.`desc` AS reg_year FROM `tbl_company_branch` INNER JOIN (SELECT idtbl_master, tbl_finacial_year_idtbl_finacial_year AS financial_year, tbl_company_branch_idtbl_company_branch FROM tbl_master WHERE status=1) AS drv_master ON tbl_company_branch.idtbl_company_branch=drv_master.tbl_company_branch_idtbl_company_branch INNER JOIN tbl_finacial_year ON drv_master.financial_year=tbl_finacial_year.idtbl_finacial_year";
$resultmain =$conn-> query($sqlmain); 
/*
$sqlsub="SELECT `idtbl_account_allocation` as id, tbl_company_branch_idtbl_company_branch as group_id, `subaccountno` as `code` FROM `tbl_account_allocation` ORDER BY group_id";
*/
$sqlsub="SELECT `tbl_account_allocation`.`idtbl_account_allocation` as id,`tbl_account_allocation`. tbl_company_branch_idtbl_company_branch as group_id, `tbl_account_allocation`.`subaccountno` AS `code`, CONCAT(`tbl_account_allocation`.`subaccountno`, ' ', `tbl_subaccount`.`subaccountname`) as `name` FROM `tbl_account_allocation` INNER JOIN `tbl_subaccount` ON `tbl_account_allocation`.`subaccountno`=`tbl_subaccount`.`subaccount` ORDER BY group_id, id";
$resultsub =$conn-> query($sqlsub); 

$idtbl_financial_year = -1;
$idtbl_master = -1;
$reg_year = '---';

$listmain = array();
$listsub = array();

if($resultmain->num_rows>0){
	while($rowmain=$resultmain->fetch_assoc()){
		$listmain[]=array('id'=>$rowmain['id'], 'code'=>$rowmain['code'], 'name'=>$rowmain['name'], 
					'finyear'=>$rowmain['financial_year'], 'fincode'=>$rowmain['idtbl_master'], 
					'lblyear'=>$rowmain['reg_year']);
	}
}

if($resultsub->num_rows>0){
	while($rowsub=$resultsub->fetch_assoc()){
		$listsub[]=array('id'=>$rowsub['id'], 'code'=>$rowsub['code'], 'name'=>$rowsub['name'], 'group_id'=>$rowsub['group_id']);
	}
}

/*
$sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=0 AND `status`=1";
$resultvehicle =$conn-> query($sqlvehicle); 

$sqlvehicletrailer="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=1 AND `status`=1";
$resultvehicletrailer =$conn-> query($sqlvehicletrailer); 

$sqldiverlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=4 AND `status`=1";
$resultdiverlist =$conn-> query($sqldiverlist);

$sqlofficerlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=6 AND `status`=1";
$resultofficerlist =$conn-> query($sqlofficerlist);
*/

$presql = "select idtbl_finacial_year AS opt_code, `desc` AS more_info from tbl_finacial_year ORDER BY startdate DESC";
$stmtOptList = $conn->prepare($presql);
//$stmtOptList->bind_param('s', $col);
$stmtOptList->execute();
$stmtOptList->store_result();
$stmtOptList->bind_result($opt_code, $more_info);

$stmtOptList->fetch();

/*
$presql = "select idtbl_finacial_year, `desc` AS reg_year from tbl_finacial_year where status=1";
$stmtAcYear = $conn->prepare($presql);
//$stmtAcYear->bind_param('s', $col);
$stmtAcYear->execute();
$stmtAcYear->store_result();
$stmtAcYear->bind_result($idtbl_financial_year, $reg_year);

if($stmtAcYear->num_rows==1){
	$stmtAcYear->fetch();
}
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
					$pre_sql="SELECT idtbl_subaccount, tbl_account_category_idtbl_account_category FROM tbl_subaccount WHERE subaccount=?";
					$stmtReg = $conn->prepare($pre_sql);
					$stmtReg->bind_param('s', $planData[0]);
					$stmtReg->execute();
					$stmtReg->store_result();
					$reg_cnt = $stmtReg->num_rows;
					$stmtReg->bind_result($idtbl_subaccount, $idtbl_account_category);
					$row_rsReg = $stmtReg->fetch();
					/*
					$sql = "insert into tbl_gl_account_balance_details (ac_balance_reg_code, idtbl_subaccount, idtbl_account_allocation, idtbl_financial_year, tbl_master_idtbl_master, subaccount, ac_open_balance, created_by, created_at) SELECT md5(CONCAT(tbl_account_allocation.idtbl_account_allocation, '_', ?)) AS ac_balance_reg_code, tbl_subaccount.idtbl_subaccount, tbl_account_allocation.idtbl_account_allocation, ? AS idtbl_financial_year, ? AS idtbl_master, tbl_account_allocation.subaccountno, ? AS ac_open_balance, ? AS created_by, NOW() AS created_at FROM tbl_account_allocation INNER JOIN (SELECT idtbl_subaccount, subaccount FROM tbl_subaccount) AS tbl_subaccount ON tbl_account_allocation.subaccountno=tbl_subaccount.subaccount WHERE tbl_account_allocation.subaccountno=? AND tbl_account_allocation.tbl_company_idtbl_company=? AND tbl_account_allocation.tbl_company_branch_idtbl_company_branch=? ORDER BY idtbl_account_allocation ASC LIMIT 1";
					*/
					$sql = "insert into tbl_gl_account_balance_details (ac_balance_reg_code, idtbl_subaccount, idtbl_account_allocation, idtbl_financial_year, tbl_master_idtbl_master, subaccount, ac_open_balance, created_by, created_at) ";
					
					/*
					$sql .= "SELECT md5(CONCAT(tbl_account_allocation.idtbl_account_allocation, '_', tbl_master.tbl_finacial_year_idtbl_finacial_year)) AS ac_balance_reg_code, tbl_subaccount.idtbl_subaccount, tbl_account_allocation. idtbl_account_allocation, tbl_master.tbl_finacial_year_idtbl_finacial_year AS idtbl_financial_year, tbl_master.idtbl_master AS idtbl_master, tbl_account_allocation.subaccountno, ? AS ac_open_balance, ? AS created_by, NOW() AS created_at FROM tbl_account_allocation INNER JOIN (SELECT idtbl_subaccount, subaccount FROM tbl_subaccount) AS tbl_subaccount ON tbl_account_allocation.subaccountno=tbl_subaccount.subaccount ";
					*/
					$sql .= "SELECT md5(CONCAT(tbl_account_allocation.idtbl_account_allocation, '_', tbl_master.tbl_finacial_year_idtbl_finacial_year)) AS ac_balance_reg_code, ? AS idtbl_subaccount, tbl_account_allocation. idtbl_account_allocation, tbl_master.tbl_finacial_year_idtbl_finacial_year AS idtbl_financial_year, tbl_master.idtbl_master AS idtbl_master, tbl_account_allocation.subaccountno, ? AS ac_open_balance, ? AS created_by, NOW() AS created_at FROM tbl_account_allocation ";
					
					$sql .= "INNER JOIN (SELECT idtbl_master, tbl_company_branch_idtbl_company_branch, tbl_finacial_year_idtbl_finacial_year FROM tbl_master WHERE tbl_company_branch_idtbl_company_branch=? AND status=1) AS tbl_master ON tbl_account_allocation.tbl_company_branch_idtbl_company_branch=tbl_master.tbl_company_branch_idtbl_company_branch ";
					
					$sql .= "WHERE tbl_account_allocation.subaccountno=? AND tbl_account_allocation.tbl_company_idtbl_company=? AND tbl_account_allocation.tbl_company_branch_idtbl_company_branch=? ORDER BY tbl_account_allocation.idtbl_account_allocation ASC LIMIT 1";
					
					$stmt = $conn->prepare($sql);
					/*
					$stmt->bind_param('ssssssss', $idtbl_financial_year, $idtbl_financial_year, $idtbl_master, $planData[3], $userID, $planData[0], $planData[1], $planData[2]);
					*/
					/*
					$stmt->bind_param('ssssss', $planData[3], $userID, $planData[2], $planData[0], $planData[1], $planData[2]);
					*/
					$stmt->bind_param('sssssss', $idtbl_subaccount, $planData[3], $userID, $planData[2], $planData[0], $planData[1], $planData[2]);
					
					$ResultOut = $stmt->execute();
					$affectedRowId = $stmt->insert_id;
					
					if($affectedRowId>0) {
						if($idtbl_account_category==3){
							$pre_sql="SELECT idtbl_account_allocation AS openAccount FROM tbl_gl_account_balance_details WHERE id=?";
							$stmtAcc = $conn->prepare($pre_sql);
							$stmtAcc->bind_param('s', $affectedRowId);
							$stmtAcc->execute();
							$stmtAcc->store_result();
							
							$stmtAcc->bind_result($openAccount);
							$row_rsAcc = $stmtAcc->fetch();
							
							$insertSQL = "INSERT INTO tbl_pettycash_reimburse (`date`, openbal, reimursebal, closebal, accountno, chequeno, chequedate, printstatus, status, insertdatetime, tbl_user_idtbl_user, tbl_subaccount_idtbl_subaccount, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch) SELECT DATE(NOW()) AS `date`, 0 AS openbal, 0 AS reimursebal, ? AS closebal, subaccountno AS accountno, '' AS chequeno, DATE(NOW()) AS chequedate, 1 AS printstatus, 1 AS status, NOW() AS insertdatetime, ? AS tbl_user_idtbl_user, ? AS idtbl_subaccount, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch FROM tbl_account_allocation WHERE idtbl_account_allocation=?";
							$stmt = $conn->prepare($insertSQL);
							$stmt->bind_param("ssss", $planData[3], $userID, $idtbl_subaccount, $openAccount);
							$ResultOut = $stmt->execute();
						}
						
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
		
		if($flag){
			$conn->commit();
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
                            <span>Account Details</span>
                            <select class="form-control-sm fa-pull-right" name="main_filter" id="main_filter" style="position:absolute; right:15px;">
                            	<option value="">All</option>
                                <?php do{ ?>
                                
                                <option value="<?php echo $opt_code; ?>"><?php echo $more_info; ?></option>
                                <?php }while($stmtOptList->fetch()); ?>
                                
                            </select>
                            
                            
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-4">
                            	<form id="frmOtherPayment" autocomplete="off">
                                    <div class="row">
                                        <div class="col">
                                        <!-- header -->
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-dark">Branch*</label>
                                                <select class="form-control form-control-sm nest_head" data-findnest="op_creditnest" id="op_credit_branch" name="op_credit_branch">
                                                    <option value="" data-colcode="" data-finyear="" data-fincode="" data-lblyear="---">Select</option>
                                                    <?php if(count($listmain)>0){
                                                        foreach($listmain as $rowmain){?>
                                                    
                                                    <option value="<?php echo $rowmain['id']; ?>" data-colcode="<?php echo $rowmain['code']; ?>" data-finyear="<?php echo $rowmain['finyear']; ?>" data-fincode="<?php echo $rowmain['fincode']; ?>" data-lblyear="<?php echo $rowmain['lblyear']; ?>"><?php echo $rowmain['name']; ?></option>
                                                    <?php }
                                                    } ?>
                                                    
                                                    
                                                </select>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-dark">Account*</label>
                                                <select class="form-control form-control-sm" data-nestname="op_creditnest" id="op_credit_account" name="op_credit_account">
                                                    <option value="">Select</option>
                                                    <?php if(count($listsub)>0){
                                                        foreach($listsub as $rowsub){?>
                                                    
                                                    <option class="nestopt d-none" value="<?php echo $rowsub['id']; ?>" data-nestcode="<?php echo $rowsub['group_id']; ?>" data-colcode="<?php echo $rowsub['code']; ?>" disabled="disabled"><?php echo $rowsub['name']; ?></option>
                                                    <?php }
                                                    } ?>
                                                    
                                                    
                                                </select>
                                            </div>
                                            
                                            
                                            
                                        <!-- header -->
                                        </div>
                                        
                                    </div>
                                    
                                    
                                    
                                    <div class="row">
                                    	<div class="col-md-6">
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-dark">Financial Year</label>
                                                <input type="text" id="op_pay_year" name="op_pay_year" class="form-control form-control-sm" value="<?php echo $reg_year; ?>" required readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-2" style="margin-bottom:0px !Important">
                                                <label class="small font-weight-bold text-dark">Opening Balance*</label>
                                                <input type="text" id="op_pay_amount" name="op_pay_amount" class="form-control form-control-sm" value="0.00" required style="text-align:right;">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mt-3">
                                    	<a class="" style="margin-left:5px;" id="btnordercreate" data-refid="-1" href="javascript:void(0);"><i class="fas fa-file-excel"></i>&nbsp;Upload From File</a>
                                        <button type="submit" id="op_formsubmit" class="btn btn-outline-primary btn-sm fa-pull-right" <?php // if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add</button>
                                        <!--input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none"-->
                                        
                                    </div>
                                    
                                    <input type="hidden" name="hrefyear" id="hrefyear" value="<?php //echo $idtbl_financial_year; ?>" />
                                    <input type="hidden" name="hrefmaster" id="hrefmaster" value="<?php //echo $idtbl_master; ?>" />
                                    
                                    
                                    
                                </form>
                                
                                <hr />
                                
                                <div class="" aria-labelledby="btnorderacts">
                                    <span style="position:absolute; left:20px;"><?php echo $importmsg; ?></span>
                                </div>
                                
                            </div>
                            <div class="col-8">
                                <!--div class="row">
                                    <div class="col">
                                        
                                    </div>
                                </div>
                                <hr-->
                                <table class="table table-bordered table-striped table-sm nowrap small" id="tableHeaders">
                                    <thead>
                                        <tr>
                                            <th>Sub Account Code</th>
                                            <th>Sub Account Name</th>
                                            <th class="text-right">Opening Balance</th>
                                        </tr>
                                    </thead>
                                </table>
                                
                                
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
    <form id="" action="account_balance_update.php" method="post" target="_self" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="fileModalLabel">Confirmation</h5>&nbsp;
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <p id="" style="">
                Please select the account balance details file
            </p>
            
            <p id="row_heading" style="color:blue;"></p>
            
            <p id="lblstatus"></p>
          </div>
          <div class="modal-footer">
            <input class="form-control col" type="file" name="file" id="file" style="padding-bottom:38px;">
            
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

        var main_table=$('#tableHeaders').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/accbalancelist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "subaccount"
                },
                {
                    "data": "subaccountname"
                },
				{
					"data": "finyear",
                    "className": 'text-right',
                    "render": function(data, type, full) {
                        return full.openbal;
                    }
				}
            ]
        } );
		$("#main_filter").on("change", function(){
			if (main_table.columns(2).search() !== this.value) {
				main_table.columns(2).search(this.value).draw();
			}
		});
		
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm-dd'
        });
		
		$('.nest_head').change(function(){
			prep_nest($(this).data('findnest'), $(this).find(":selected").val(), 0);
		});
		
		function prep_nest(nestname, nestcode, selectedval){
			var childobj=$('select[data-nestname="'+nestname+'"]')
			
			var blockobj=$(childobj).find('option.nestopt');
			$(blockobj).prop('disabled', true);
			$(blockobj).addClass('d-none');
			
			var allowobj=$(childobj).find('option[data-nestcode="'+nestcode+'"]');
			$(allowobj).prop('disabled', false);
			$(allowobj).removeClass('d-none');
			
			selected_val=(selectedval==0)?$(childobj).children('option:first').val():selectedval;
			
			$(childobj).val(selected_val);
		}
		
		$('#op_credit_branch').change(function(){
			$('#op_pay_year').val($('#op_credit_branch').find(":selected").data('lblyear'));
		});
		
        // Create order part
        $('#btnordercreate').click(function(){
            $('#fileModal').modal('show');
			
        });
		
		
		$('#frmOtherPayment').on('submit', function(event){
            event.preventDefault();
			
			var openacc = $('#op_credit_account').find(":selected").val();
			var openacc_colcode = $('#op_credit_account').find(":selected").data('colcode');
			var openamount = $('#op_pay_amount').val();
			var fininfo = $('#op_credit_branch').find(":selected");
			var finyear = $(fininfo).data('finyear');
			var fincode = $(fininfo).data('fincode');
			//if(verifyRecHeader()){
				
				$.ajax({
					type: "POST",
					data: {
						open_acc: openacc,
						open_acc_colcode: openacc_colcode,
						open_amount:openamount, 
						fin_year:finyear,
						fin_code:fincode
					},
					dataType: 'JSON',
					url: 'process/ac_open_balance_reg_process.php',
					success: function(data) { //alert(JSON.stringify(data));
						if(data.msgdesc.type=="success"){
							main_table.draw();
						}
						
						action(data.msgdesc);
						
					}
				});
			//}
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
            z_index: 5031,
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
