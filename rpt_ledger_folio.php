<?php 
include "include/header.php";  

$sqlmain="SELECT idtbl_company_branch as id, code, concat(code, ' ', branch) as name FROM `tbl_company_branch`";
$resultmain =$conn-> query($sqlmain); 

$sqlsub="SELECT `tbl_account_allocation`.`idtbl_account_allocation` as id,`tbl_account_allocation`. tbl_company_branch_idtbl_company_branch as group_id, `tbl_account_allocation`.`subaccountno` AS `code`, CONCAT(`tbl_account_allocation`.`subaccountno`, ' ', `tbl_subaccount`.`subaccountname`) as `name` FROM `tbl_account_allocation` INNER JOIN `tbl_subaccount` ON `tbl_account_allocation`.`subaccountno`=`tbl_subaccount`.`subaccount` ORDER BY group_id, id";
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
		$listsub[]=array('id'=>$rowsub['id'], 'code'=>$rowsub['code'], 'name'=>$rowsub['name'], 'group_id'=>$rowsub['group_id']);
	}
}

$totalRows_rsInfo = 0;
$subaccountno='-';
$financial_year='-';
$ac_open_balance=0;//'-';
$crdr_cnt = 0;
$crdr_total = array('D'=>0, 'C'=>0, 'RptSectCnt'=>0);

if(isset($_POST['drp_rpt_branch'], $_POST['drp_rpt_account'])){
	$pre_sql = "SELECT tbl_account_allocation.subaccountno, tbl_finacial_year.`desc` AS financial_year, tbl_master.idtbl_master, IFNULL(drv_open.ac_open_balance, 0) AS ac_open_balance FROM `tbl_account_allocation` INNER JOIN tbl_master ON tbl_account_allocation.`tbl_company_branch_idtbl_company_branch`=tbl_master.`tbl_company_branch_idtbl_company_branch` INNER JOIN tbl_finacial_year ON tbl_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year LEFT OUTER JOIN (SELECT tbl_master_idtbl_master, subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details GROUP BY tbl_master_idtbl_master, subaccount) AS drv_open ON (tbl_master.idtbl_master=drv_open.tbl_master_idtbl_master AND tbl_account_allocation.subaccountno=drv_open.subaccount) WHERE tbl_account_allocation.`tbl_company_branch_idtbl_company_branch`=? AND tbl_account_allocation.idtbl_account_allocation=? AND tbl_master.status=1 LIMIT 1";
	$stmtHead = $conn->prepare($pre_sql);
	$stmtHead->bind_param('ss', $_POST['drp_rpt_branch'], $_POST['drp_rpt_account']);
	$stmtHead->execute();
	$stmtHead->store_result();
	$stmtHead->bind_result($subaccountno, $financial_year, $idtbl_master, $ac_open_balance);
	$row_rsHead = $stmtHead->fetch();
	
	$rpt_sql = "SELECT drv_reg.tradate, drv_reg.narration, drv_reg.accamount*((drv_reg.crdr='C')*IFNULL(NULLIF(1-tbl_mainclass.transactiontype, 0), 1)+(drv_reg.crdr='D')*IFNULL(NULLIF(tbl_mainclass.transactiontype-2, 0), 1)) AS accamount, drv_reg.crdr FROM (SELECT acccode, tradate, narration, accamount, crdr FROM `tbl_account_transaction` WHERE `acccode`=? AND `tbl_master_idtbl_master`=? AND `tradate`<=DATE(NOW())) AS drv_reg INNER JOIN tbl_subaccount ON drv_reg.acccode=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code ORDER BY drv_reg.crdr DESC, drv_reg.tradate ASC";
	$stmtInfo = $conn->prepare($rpt_sql);
	$stmtInfo->bind_param('ss', $subaccountno, $idtbl_master);
	$stmtInfo->execute();
	$stmtInfo->store_result();
	$totalRows_rsInfo = $stmtInfo->num_rows;
	$stmtInfo->bind_result($tradate, $narration, $accamount, $crdr);
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
                            <span>Ledger Folio</span>
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
                                                <label class="small font-weight-bold text-dark">Account*</label>
                                                <select class="form-control form-control-sm" data-nestname="debitnest" id="drp_rpt_account" name="drp_rpt_account">
                                                    <option value="">Select</option>
                                                    <?php if(count($listsub)>0){
                                                        foreach($listsub as $rowsub){?>
                                                    
                                                    <option class="nestopt d-none" value="<?php echo $rowsub['id']; ?>" data-nestcode="<?php echo $rowsub['group_id']; ?>" data-colcode="<?php echo $rowsub['code']; ?>" disabled="disabled"><?php echo $rowsub['name']; ?></option>
                                                    <?php }
                                                    } ?>
                                                    
                                                </select>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-dark">From Date*</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control dpd1a" placeholder="" name="cheque_date" id="" disabled="disabled"><!--required-->
                                                    <div class="input-group-append">
                                                        <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-dark">To Date*</label>
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
                                    	Account Code
                                    </div>
                                    <div class="col text-center">
                                    	Financial Year
                                    </div>
                                    <div class="col text-right">
                                    	Opening Balance
                                    </div>
                                </div>
                                <div class="form-row">
                                	<div class="col">
                                    	<?php echo $subaccountno; ?>
                                    </div>
                                    <div class="col text-center">
                                    	<?php echo $financial_year; ?>
                                    </div>
                                    <div class="col text-right">
                                    	<?php echo (($ac_open_balance==0)?'-':number_format((float)$ac_open_balance, 2, '.', '')); ?>
                                    </div>
                                </div>
                                <!--h6 class="title-style small font-weight-bold mt-2"><span>More Info</span></h6-->
                                <table class="table table-bordered table-sm table-striped" id="" style="margin-top:5px; margin-bottom:25px;">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Particulars</th>
                                            <th class="text-right">Debit</th>
                                            <th class="text-right">Credit</th>
                                            <th class="text-right">Sub Total</th>
                                            <th class="text-right">Net Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="">
                                    <?php if($totalRows_rsInfo>0){
										do{ 
											$debit_amt = '&nbsp;';
											$credit_amt = '&nbsp;';
											
											$dr_close_in_html = '';
											$dr_close_out_html = '';
											$cr_close_html = '';
											$crdr_cnt++;
											
											if($crdr=='D'){
												$debit_amt = number_format((float)$accamount, 2, '.', '');
												$crdr_total['D'] +=  $accamount;
											}else if($crdr=='C'){
												if($crdr_total['C']==0){
													$crdr_total['RptSectCnt']=1;
												}
												
												$credit_amt = number_format((float)$accamount, 2, '.', '');
												$crdr_total['C'] +=  $accamount;
												
												
											}
											
											if($crdr_cnt==$totalRows_rsInfo){
												if($crdr_total['C']==0){
													$crdr_total['RptSectCnt']=2;
												}
												
												$col_txt = number_format((float)$crdr_total['C'], 2, '.', '');
												$cr_close_html = '<tr><td colspan="2"><strong>Total Credit</strong></td>'.
													'<td>&nbsp;</td>'.
													'<td class="text-right"><strong>'.$col_txt.'</strong></td>'.
													'<td class="text-right"><strong>'.$col_txt.'</strong></td>'.
													'<td>&nbsp;</td></tr>';
											}
											
											if($crdr_total['RptSectCnt']>0){
												
												$col_txt=number_format((float)$crdr_total['D'], 2, '.', '');
												
												$dr_close_html = '<tr><td colspan="2"><strong>Total Debit</strong></td>'.
													'<td class="text-right"><strong>'.$col_txt.'</strong></td>'.
													'<td>&nbsp;</td>'.
													'<td class="text-right"><strong>'.$col_txt.'</strong></td>'.
													'<td>&nbsp;</td></tr>';
													
												if($crdr_total['RptSectCnt']==1){
													$dr_close_in_html=$dr_close_html;
												}else if($crdr_total['RptSectCnt']==2){
													$dr_close_out_html=$dr_close_html;
												}
												
												$crdr_total['RptSectCnt']=3;//prevent-repeating-of-debit-total-rows
												
											}
											
											echo $dr_close_in_html;
									?>
                                    	<tr>
                                        	<td><?php echo $tradate; ?></td>
                                            <td><?php echo $narration; ?></td>
                                            <td class="text-right"><?php echo $debit_amt; ?></td>
                                            <td class="text-right"><?php echo $credit_amt; ?></td>
                                            <td class="text-right"><?php echo '&nbsp;'; ?></td>
                                            <td class="text-right"><?php echo '&nbsp;'; ?></td>
                                        </tr>
                                    <?php 
											echo $dr_close_out_html;
											echo $cr_close_html;
											
										}while($stmtInfo->fetch());
									} ?>
                                    
                                    	<tr>
                                        	<td colspan="5"><strong>Closing Balance</strong></td>
                                            <td class="text-right"><strong><?php echo number_format((float)($ac_open_balance+($crdr_total['D']+$crdr_total['C'])), 2, '.', ''); ?></strong></td>
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
    });

    
</script>
<?php include "include/footer.php"; ?>
