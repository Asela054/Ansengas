<?php 
include "include/header.php";  

function calc_stock($opening_stock=false){
	global $conn;
	$sql = "";
	
	if($opening_stock){
		$sql = "SELECT closingstock AS stock_close FROM tbl_stock_closing WHERE `date`='2021-04-30'";
	}else{
		$sql = "SELECT SUM(tbl_stock.fullqty*tbl_product.unitprice) AS stock_close_value FROM tbl_stock INNER JOIN tbl_product ON tbl_stock.tbl_product_idtbl_product=tbl_product.idtbl_product WHERE tbl_stock.status=1 AND tbl_stock.fullqty>0";
	}
	
	$stmt = $conn->prepare($sql);
	//$stmt->bind_param('', '')
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($stock_close_value);
	$row_rs = $stmt->fetch();
	
	return $stock_close_value;
}

$sqlmain="SELECT idtbl_company_branch as id, code, concat(code, ' ', branch) as name FROM `tbl_company_branch`";
$resultmain =$conn-> query($sqlmain); 

$sqlsub="SELECT `idtbl_account_allocation` as id, tbl_company_branch_idtbl_company_branch as group_id, `subaccountno` as `code` FROM `tbl_account_allocation` ORDER BY group_id";
$resultsub =$conn-> query($sqlsub); 

$listmain = array();
$listsub = array();

if($resultmain->num_rows>0){
	while($rowmain=$resultmain->fetch_assoc()){
		$listmain[]=array('id'=>$rowmain['id'], 'code'=>$rowmain['code'], 'name'=>$rowmain['name']);
	}
}

if($resultsub->num_rows>0){
	while($rowsub=$resultsub->fetch_assoc()){
		$listsub[]=array('id'=>$rowsub['id'], 'code'=>$rowsub['code'], 'group_id'=>$rowsub['group_id']);
	}
}


$totalRows_rsInfo = 0;
$financial_year='-';
$crdr_total = array('D'=>0, 'C'=>0, 'RptSectCnt'=>0);

if(isset($_POST['drp_rpt_branch'])){
	$pre_sql = "SELECT tbl_finacial_year.`desc` AS financial_year, tbl_master.idtbl_master FROM tbl_master INNER JOIN tbl_finacial_year ON tbl_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year WHERE tbl_master.tbl_company_branch_idtbl_company_branch=? AND tbl_master.status=1";
	$stmtHead = $conn->prepare($pre_sql);
	$stmtHead->bind_param('s', $_POST['drp_rpt_branch']);
	$stmtHead->execute();
	$stmtHead->store_result();
	$stmtHead->bind_result($financial_year, $idtbl_master);
	$row_rsHead = $stmtHead->fetch();
	
	$rpt_sql = "SELECT accname, (ac_open+dr_accamount+cr_accamount) AS accamount, crdr FROM ";
	
	/*
	$rpt_sql .= "(SELECT CONCAT(drv_acc.subaccountno, ' ', tbl_subaccount.subaccountname) AS accname, (IFNULL(drv_open.ac_open_balance, 0)+(IFNULL(drv_reg.accamount, 0))) AS accamount, tbl_mainclass.transactiontype AS crdr FROM ";
	*/
	$rpt_sql .= "(SELECT CONCAT(drv_acc.subaccountno, ' ', tbl_subaccount.subaccountname) AS accname, IFNULL(drv_open.ac_open_balance, 0) AS ac_open, IFNULL(drv_reg.dr_accamount, 0)*IFNULL(NULLIF(tbl_mainclass.transactiontype-2, 0), 1) AS dr_accamount, IFNULL(drv_reg.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_mainclass.transactiontype, 0), 1) AS cr_accamount, tbl_mainclass.transactiontype AS crdr FROM ";
																																				   
	$rpt_sql .= "(SELECT DISTINCT subaccountno FROM tbl_account_allocation WHERE tbl_company_branch_idtbl_company_branch=?) AS drv_acc INNER JOIN tbl_subaccount ON drv_acc.subaccountno=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master=? GROUP BY subaccount) AS drv_open ON drv_acc.subaccountno=drv_open.subaccount LEFT OUTER JOIN ";
	
	/*
	$rpt_sql .= "(SELECT acccode, SUM((accamount*(crdr='D'))+(accamount*(crdr='C')*-1)) AS accamount, crdr FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`=? AND `tradate`<=DATE(NOW()) GROUP BY acccode) AS drv_reg ON drv_acc.subaccountno=drv_reg.acccode WHERE tbl_subaccount.status=1 ORDER BY crdr DESC, drv_acc.subaccountno) AS drv_rpt ";
	*/
	$rpt_sql .= "(SELECT acccode, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`=? AND `tradate`<=DATE(NOW()) GROUP BY acccode) AS drv_reg ON drv_acc.subaccountno=drv_reg.acccode WHERE tbl_subaccount.status=1 ORDER BY crdr DESC, drv_acc.subaccountno) AS drv_rpt ";
	
	/*
	$rpt_sql .= "WHERE accamount>0";
	*/
	$rpt_sql .= "HAVING accamount>0";
	
	$stmtInfo = $conn->prepare($rpt_sql);
	$stmtInfo->bind_param('sss', $_POST['drp_rpt_branch'], $idtbl_master, $idtbl_master);
	$stmtInfo->execute();
	$stmtInfo->store_result();
	$totalRows_rsInfo = $stmtInfo->num_rows;
	$stmtInfo->bind_result($accname, $accamount, $crdr);
	$row_rsInfo = $stmtInfo->fetch();
	
	
}

include "include/topnavbar.php"; 
?>
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
                            <div class="page-header-icon"><i data-feather="file"></i></div>
                            <span>Trial Balance</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <!--div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                
                            </div>
                        </div-->
                        <div class="row">
                            <div class="col-3">
                                <form id="createorderform" autocomplete="off" method="POST">
                                    <div class="row">
                                        <div class="col">
                                        <!-- header -->
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-dark">Branch*</label>
                                                <select class="form-control form-control-sm nest_head" data-findnest="debitnest" id="drp_rpt_branch" name="drp_rpt_branch">
                                                    <option value="">Select</option>
                                                    <?php if(count($listmain)>0){
                                                        foreach($listmain as $rowmain){?>
                                                    
                                                    <option value="<?php echo $rowmain['id']; ?>" data-colcode="<?php echo $rowmain['code']; ?>"><?php echo $rowmain['name']; ?></option>
                                                    <?php }
                                                    } ?>
                                                    
                                                </select>
                                            </div>
                                            
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-dark">As at Date*</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control dpd1a" placeholder="" name="cheque_date" id="" disabled="disabled"><!--required-->
                                                    <div class="input-group-append">
                                                        <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group mb-2" style="margin-bottom:0px !Important">
                                                <label class="small font-weight-bold text-dark">Accounting Period*</label>
                                                <div class="form-row">
                                                    <div class="form-group col" style="margin-bottom:0px;">
                                                        <div class="i-checks" style="line-height:9px;">
                                                            <input type="radio" name="rad_pay_method" id="rad_cash" value="" checked="checked" class="form-control-custom radio-custom" style="width:12px; height:12px;" data-togval="1" />
                                                            <label class="small" for="rad_cash">Current Financial Year</label>
                                                        </div>
                                                        <div class="i-checks" style="line-height:10px;">
                                                            <input type="radio" name="rad_pay_method" id="rad_cheque" value="" class="form-control-custom radio-custom" style="width:12px; height:12px;" data-togval="0" />
                                                            <label class="small" for="rad_cheque">History Year</label>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        <!-- header -->
                                        </div>
                                        
                                    </div>
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    <div class="form-group mt-3">
                                        <button type="submit" id="formsubmit" class="btn btn-outline-primary btn-sm fa-pull-right" <?php // if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;View</button>
                                        <!--input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none"-->
                                    </div>
                                    
                                    
                                </form>
                            </div>
                            <div class="col-9">
                            	<div class="form-row">
                                    <div class="col">
                                    	Financial Year
                                    </div>
                                	<!--div class="col text-center">
                                    	Account Code
                                    </div>
                                    <div class="col text-right">
                                    	Opening Balance
                                    </div-->
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                    	<?php echo $financial_year; ?>
                                    </div>
                                	<!--div class="col text-center">
                                    	<?php //echo $subaccountno; ?>
                                    </div>
                                    <div class="col text-right">
                                    	<?php //echo $ac_open_balance; ?>
                                    </div-->
                                </div>
                                <!--h6 class="title-style small font-weight-bold mt-2"><span>More Info</span></h6-->
                                <table class="table table-bordered table-sm table-striped" id="" style="margin-top:5px; margin-bottom:25px;">
                                    <thead>
                                        <tr>
                                            <th>Account</th>
                                            <th class="text-right">Debit</th>
                                            <th class="text-right">Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody id="">
                                    	<?php 
										if($totalRows_rsInfo>0){
										
										?>
                                        <tr>
                                        	<td>2021-04-30 Closing</td>
                                            <?php 
											$stock_val = calc_stock(true);
											$crdr_total['D']+=$stock_val;
											?>
                                            <td class="text-right"><?php echo number_format((float)$stock_val, 2, '.', ''); ?></td>
                                            <td class="text-right">&nbsp;</td>
                                        </tr>
										<?php 
											do{ 
												$dr_accamount='';
												$cr_accamount='';
												
												if($crdr==2){
													$dr_accamount=number_format((float)$accamount, 2, '.', '');
													$crdr_total['D']+=$accamount;
												}else if($crdr==1){
													$cr_accamount=number_format((float)$accamount, 2, '.', '');
													$crdr_total['C']+=$accamount;
												}
										?>
                                        <tr>
                                        	<td><?php echo $accname; ?></td>
                                            <td class="text-right"><?php echo $dr_accamount; ?></td>
                                            <td class="text-right"><?php echo $cr_accamount; ?></td>
                                        </tr>
                                        <?php }while($stmtInfo->fetch()); 
										}?>
                                        
                                        <tr>
                                        	<td>&nbsp;</td>
                                            <td class="text-right"><strong><?php echo number_format((float)$crdr_total['D'], 2, '.', ''); ?></strong></td>
                                            <td class="text-right"><strong><?php echo number_format((float)$crdr_total['C'], 2, '.', ''); ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <hr class="border-dark">
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 d-none">
                                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right px-5" id="btnSaveGrn" disabled="disabled"><i class="far fa-save"></i>&nbsp;Save Report</button>
                                    </div>
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

<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            endDate: 'today',
            format: 'yyyy-mm-dd'
        });
        
    });

    
</script>
<?php include "include/footer.php"; ?>
