<?php 
include "include/header.php";  

$pre_sql = "SELECT idtbl_master FROM tbl_master WHERE tbl_company_branch_idtbl_company_branch=1 AND status=1";
$stmtReg = $conn->prepare($pre_sql);
//$stmtReg->bind_param('', '')
$stmtReg->execute();
$stmtReg->store_result();
$stmtReg->bind_result($idtbl_master);
$row_rs = $stmtReg->fetch();

function add_sect($sect_code, $fig_value_col, $fig_grp_sum=false, $cnt_rev=false){
	global $conn;
	global $idtbl_master;
	/*
	$sql = "SELECT tbl_gl_report_sub_sections.id AS fig_sect_ref, tbl_gl_report_sub_sections.sub_section_name AS sect_name, CONCAT(tbl_gl_report_sub_section_particulars.subaccount, ' ', tbl_subaccount.subaccountname) AS fig_name, ((IFNULL(drv_open.ac_open_balance, 0)*tbl_gl_report_sub_section_particulars.value_ac_open_bal)+(IFNULL(drv_crdr.accamount, 0)*tbl_gl_report_sub_section_particulars.value_ac_cr_dr)) AS fig_value FROM tbl_gl_report_sub_sections ";
	*/
	$sql = "SELECT tbl_gl_report_sub_sections.id AS fig_sect_ref, tbl_gl_report_sub_sections.sub_section_name AS sect_name, CONCAT(tbl_gl_report_sub_section_particulars.subaccount, ' ', tbl_subaccount.subaccountname) AS fig_name, ((IFNULL(drv_open.ac_open_balance, 0)*tbl_gl_report_sub_section_particulars.value_ac_open_bal)+((IFNULL(drv_crdr.dr_accamount, 0)*IFNULL(NULLIF(tbl_mainclass.transactiontype-2, 0), 1)+IFNULL(drv_crdr.cr_accamount, 0)*IFNULL(NULLIF(1-tbl_mainclass.transactiontype, 0), 1))*tbl_gl_report_sub_section_particulars.value_ac_cr_dr)) AS fig_value FROM tbl_gl_report_sub_sections ";
	
	
	$sql .= "INNER JOIN tbl_gl_report_sub_section_particulars ON tbl_gl_report_sub_sections.id=tbl_gl_report_sub_section_particulars.tbl_gl_report_sub_section_id INNER JOIN tbl_subaccount ON tbl_gl_report_sub_section_particulars.subaccount=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code ";
	$sql .= "LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master=? GROUP BY subaccount) AS drv_open ON tbl_gl_report_sub_section_particulars.subaccount=drv_open.subaccount ";
	
	
	/*
	$sql .= "LEFT OUTER JOIN (SELECT acccode, SUM((accamount*(crdr='D'))+((accamount*(crdr='C'))*-1)) AS accamount FROM tbl_account_transaction WHERE reversstatus=0 AND tbl_master_idtbl_master=? GROUP BY acccode) AS drv_crdr ON tbl_gl_report_sub_section_particulars.subaccount=drv_crdr.acccode ";
	*/
	$sql .= "LEFT OUTER JOIN (SELECT acccode, SUM(accamount*(crdr='D')) AS dr_accamount, SUM(accamount*(crdr='C')) AS cr_accamount FROM tbl_account_transaction WHERE reversstatus=0 AND tbl_master_idtbl_master=? GROUP BY acccode) AS drv_crdr ON tbl_gl_report_sub_section_particulars.subaccount=drv_crdr.acccode ";
	
	
	$sql .= "WHERE tbl_gl_report_sub_sections.tbl_gl_report_head_section_id=? AND tbl_gl_report_sub_sections.sect_cancel=0 AND tbl_gl_report_sub_section_particulars.report_part_cancel=0 ORDER BY tbl_gl_report_sub_section_particulars.fig_seq_no, tbl_gl_report_sub_section_particulars.tbl_gl_report_sub_section_id";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('sss', $idtbl_master, $idtbl_master, $sect_code);
	$stmt->execute();
	$stmt->store_result();
	$total_rsFigs=$stmt->num_rows;
	$stmt->bind_result($fig_sect_ref, $sect_name, $fig_name, $fig_value);
	$row_rs=$stmt->fetch();
	
	$row_pos=0;
	$sub_sect_ref='-1';
	
	//$fig_value=0;//10;
	$fig_total=0;
	$sect_total=0;
	
	$col_values = array('l'=>array('', ''), 'm'=>array('', ''), 'r'=>array('', ''));
	$col_grpsum = array('l'=>'m', 'm'=>'r');
	
	
	//$doc_sect_ref='-1';
	$tot_sect_ref=false; // keep-track-of-group-total-allocation-to-be-cleared
	
	if($total_rsFigs>0){
		while($row_pos<$total_rsFigs){
			$col_values[$fig_value_col][0]=number_format((float)$fig_value, 2, '.', '');
			$col_values[$fig_value_col][1]=' class="text-right"';
	
			
			if($tot_sect_ref){
				$col_values[$col_grpsum[$fig_value_col]][0]='';
				$col_values[$col_grpsum[$fig_value_col]][1]='';
				$tot_sect_ref=false;
			}
			
			$fig_grp_name=''; // keep-section-name
			
			$fig_disp_name=$fig_name; // keep-particulars-name-to-be-presented-even-after-fetching-next-record
			
			$fig_bottom_border = '';
			
			$sect_total+=$fig_value;
			
			if($sub_sect_ref!=$fig_sect_ref){
				$fig_grp_name=''.$sect_name.'&nbsp;';//echo '<tr><td colspan="5">'.$sect_name.'</td></tr>';
				$sub_sect_ref=$fig_sect_ref;
				$fig_total=$fig_value;
			}else{
				$fig_total+=$fig_value;
			}
			
			$row_pos++;
			$stmt->fetch();
			
			$col_lm=(($fig_value_col=='l') || (($fig_value_col=='m') && $fig_grp_sum));
			$col_xm=(($fig_value_col=='l') || ($fig_value_col=='m'));
			
			$grp_summary_format='text-right';
			$acc_summary_format='text-right';
			
			if($col_lm || $col_xm){
				if($col_xm){
					if($row_pos==$total_rsFigs){
						$grp_summary_format='text-right sect_col';
						$acc_summary_format='text-right sect_col';
					}
					
				}
				if($col_lm){
					if(($sub_sect_ref!=$fig_sect_ref)||($row_pos==$total_rsFigs)){
						$tot_sect_ref=true;
						
						$grp_summary_format='text-right sect_col';
						
						
						if(($fig_value_col=='l') && ($row_pos==$total_rsFigs)){
							$col_values['r'][0]=number_format((float)$sect_total, 2, '.', '');
							$col_values['r'][1]=' class="text-right sect_col"';
							
						}
						
						
						$col_values[$col_grpsum[$fig_value_col]][0]=number_format((float)$fig_total, 2, '.', '');
						$col_values[$col_grpsum[$fig_value_col]][1]=' class="'.$acc_summary_format.'"';
						
						if($row_pos<$total_rsFigs){
							$fig_bottom_border='<tr><td colspan=5>&nbsp;</td></tr>';
						}
					}
					
					if($fig_grp_name!=''){
						echo '<tr><td colspan="5">'.$fig_grp_name.'</td></tr>';
						$fig_grp_name='';
					}
				}
				
				$col_values[$fig_value_col][1]=' class="'.$grp_summary_format.'"';//' class="text-right sect_col"';
				
			}
			
			echo '<tr><td colspan="2">'.$fig_grp_name.$fig_disp_name.'</td><td'.$col_values['l'][1].'>'.$col_values['l'][0].'</td><td'.$col_values['m'][1].'>'.$col_values['m'][0].'</td><td'.$col_values['r'][1].'>'.$col_values['r'][0].'</td></tr>';
			
			echo $fig_bottom_border;
			
		}
	}else{
		if($cnt_rev){
			$sect_total = -1;
		}
	}
	
	return $sect_total;
}

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

function refine_value($sect_value){
	return (($sect_value==-1)?0:$sect_value);
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

$rpthead = array();
$rptsub = array();

$sqlrpt_head = "SELECT id, head_section_name AS name FROM tbl_gl_report_head_sections WHERE report_id='PNL'";
$resultrpt_head = $conn->query($sqlrpt_head);

$sqlrpt_sub = "SELECT tbl_gl_report_sub_sections.id, tbl_gl_report_sub_sections.sub_section_name as `code`, tbl_gl_report_sub_sections.tbl_gl_report_head_section_id as group_id FROM tbl_gl_report_sub_sections INNER JOIN tbl_gl_report_head_sections ON tbl_gl_report_sub_sections.tbl_gl_report_head_section_id=tbl_gl_report_head_sections.id WHERE report_id='PNL'";
$resultrpt_sub = $conn->query($sqlrpt_sub);

if($resultrpt_head->num_rows>0){
	while($rowrpt_head=$resultrpt_head->fetch_assoc()){
		$rpthead[]=array('id'=>$rowrpt_head['id'], 'name'=>$rowrpt_head['name']);
	}
}

if($resultrpt_sub->num_rows>0){
	while($row_rptsub=$resultrpt_sub->fetch_assoc()){
		$rptsub[]=array('id'=>$row_rptsub['id'], 'code'=>$row_rptsub['code'], 'group_id'=>$row_rptsub['group_id']);
	}
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
                            <span>Profit and Loss</span>
                            
                            <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right dropdown-toggle" id="btnorderacts" style="position:absolute; right:10px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i>&nbsp;Setup</button>
                            <div class="dropdown-menu" aria-labelledby="btnorderacts">
                            	<a class="dropdown-item" id="btnordercreate" data-refid="-1" href="javascript:void(0);">Sections</a>
                            	<a class="dropdown-item" id="btnOtherCreate" data-refid="-1" href="javascript:void(0);">Accounts</a>
                            </div>
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
                                <form id="" autocomplete="off">
                                    <div class="row">
                                        <div class="col">
                                        <!-- header -->
                                            
                                            
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-dark">From Date*</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control dpd1a" placeholder="" name="rpt_fr_date" id="rpt_fr_date"><!--required-->
                                                    <div class="input-group-append">
                                                        <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-dark">To Date*</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control dpd1a" placeholder="" name="rpt_to_date" id="rpt_to_date"><!--required-->
                                                    <div class="input-group-append">
                                                        <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group mb-2" style="margin-bottom:0px !Important">
                                                <label class="small font-weight-bold text-dark">Report Type*</label>
                                                <div class="form-row">
                                                    <div class="form-group col" style="margin-bottom:0px;">
                                                        <div class="i-checks" style="line-height:9px;">
                                                            <input type="radio" name="rad_rpt_type" id="rad_summary" value="" checked="checked" class="form-control-custom radio-custom" style="width:12px; height:12px;" data-togval="1" />
                                                            <label class="small" for="rad_summary">Summarized</label>
                                                        </div>
                                                        <div class="i-checks" style="line-height:10px;">
                                                            <input type="radio" name="rad_rpt_type" id="rad_detail" value="" class="form-control-custom radio-custom" style="width:12px; height:12px;" data-togval="0" />
                                                            <label class="small" for="rad_detail">Detailed</label>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            
                                            
                                        <!-- header -->
                                        </div>
                                        
                                    </div>
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    <div class="form-group mt-3">
                                        <button type="submit" id="rpt_view" class="btn btn-outline-primary btn-sm fa-pull-right" <?php // if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;View</button>
                                        <!--input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none"-->
                                    </div>
                                    
                                    
                                </form>
                            </div>
                            <div class="col-9">
                                <h6 class="title-style small font-weight-bold mt-2"><span>Ansen Gas - Profit or Loss Statement</span></h6>
                                <style type="text/css">
								td.text-right.sect_col{
									border-bottom:1px solid black;
								}
								</style>
                                <table class="table table-bordered table-sm table-striped" id="tableGrnList">
                                    <thead>
                                        <tr>
                                            <th>Account Type</th>
                                            <th>Account No</th>
                                            <th class="text-right">&nbsp;</th>
                                            <th class="text-right">&nbsp;</th>
                                            <th class="text-right">&nbsp;</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody id="">
                                    	<tr><td colspan="5">Sales Revenue</td></tr>
                                        <?php $tot_sale = add_sect('1', 'r'); ?>
                                        <!--tr><td colspan="4">Total Sales Revenue</td><td class="text-right"><?php //echo number_format((float) refine_value($tot_sale), 2, '.', ''); ?></td></tr-->
                                        <tr><td colspan="5">&nbsp;</td></tr>
                                        <tr><td colspan="5">Less: Cost of Sales</td></tr>
                                        <?php $open_stock = calc_stock(true); ?>
                                        <tr><td colspan="3">Opening stock</td><td class="text-right"><?php echo number_format((float)$open_stock, 2, '.', ''); ?></td><td>&nbsp;</td></tr>
										<?php 
										$sale_cost_acc = add_sect('2', 'm');
										$tot_sect = $open_stock+refine_value($sale_cost_acc); 
										?>
                                        <tr><td colspan="3">Cost of Goods to be sold</td><td class="text-right"><?php echo number_format((float)$tot_sect, 2, '.', ''); ?></td><td>&nbsp;</td></tr>
                                        <?php $tot_stock = calc_stock(); ?>
                                        <tr><td colspan="3">Less: Closing stock</td><td class="text-right sect_col"><?php echo number_format((float)$tot_stock, 2, '.', ''); ?></td><td>&nbsp;</td></tr>
                                        <?php $cost_of_sale = $tot_sect-$tot_stock; ?>
                                        <tr><td colspan="4">Cost of Sales</td><td class="text-right sect_col"><?php echo number_format((float)$cost_of_sale, 2, '.', ''); ?></td></tr>
                                        <?php $gross_profit = refine_value($tot_sale)-$cost_of_sale; ?>
                                        <tr><td colspan="4">Gross Profit</td><td class="text-right"><?php echo number_format((float)$gross_profit, 2, '.', ''); ?></td></tr>
                                        <tr><td colspan="5">&nbsp;</td></tr>
                                        <tr><td colspan="5">Add: Other Income</td></tr>
                                        <?php $tot_other_income = add_sect('4', 'm', true, true); ?>
                                        <?php $tot_income = $gross_profit+refine_value($tot_other_income); ?>
                                        
                                        <?php if($tot_other_income>=0){ ?>
                                        <tr><td colspan="4">&nbsp;</td><td class="text-right"><?php echo number_format((float)$tot_income, 2, '.', ''); ?></td></tr>
                                        <?php } ?>
                                        
                                        <tr><td colspan="5">&nbsp;</td></tr>
                                        <tr><td colspan="5">Less: Expenses</td></tr>
                                        <?php $tot_expenses = add_sect('3', 'l'); ?>
                                        <?php $tot_transfer = $tot_income-refine_value($tot_expenses); ?>
                                        <tr><td colspan="4">Net profit transferred to the capital account</td><td class="text-right"><?php echo number_format((float)$tot_transfer, 2, '.', ''); ?></td></tr>
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

<!-- Modal Create Order -->
<div class="modal fade" id="modalcreateorder" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                	<div class="col">
                        <form id="createorderform" autocomplete="off">
                            <div class="row">
                            	<div class="col">
                                <!-- detail -->
                                	<div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Main Section</label>
                                        <select class="form-control form-control-sm nest_head" data-findnest="debitnest" id="drp_main" name="drp_main">
                                        	<option value="-1">Select</option>
                                            <?php if(count($rpthead)>0){
												foreach($rpthead as $rowmain){?>
                                            
                                            <option value="<?php echo $rowmain['id']; ?>"><?php echo $rowmain['name']; ?></option>
                                            <?php }
											} ?>
                                            
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Sub Section*</label>
                                        <input type="text" id="txt_sub" name="txt_sub" class="form-control form-control-sm" value="" required>
                                    </div>
                                    
                                    
                                    
                                    
                                <!-- detail -->
                                </div>
                                
                                
                            </div>
                            
                            
                            
                            
                            
                            
                            
                            <div class="form-group">
                                <button type="submit" id="sect_submit" class="btn btn-outline-primary btn-sm fa-pull-right" <?php // if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add</button>
                                <!--input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none"-->
                            </div>
                            
                            <input type="hidden" name="hrefid" id="hrefid" value="">
                            
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table class="table table-striped table-bordered table-sm small" id="tableDetails" width="100%">
                            <thead>
                                <tr>
                                    <!--th>Main Section</th-->
                                    <th>Sub Section</th>
                                    <th class="text-center">Show/Hide</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        
                        <!--hr-->
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Other -->
<div class="modal fade" id="modalCreateOther" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!--div class="col"-->
                    <!-- detail -->
                        <div class="form-group col mb-2">
                            <label class="small font-weight-bold text-dark">Main Section*</label>
                            <select class="form-control form-control-sm nest_head" data-findnest="op_debitnest" id="drp_grp" name="drp_grp">
                                <option value="">Select</option>
                                <?php if(count($rpthead)>0){
                                    foreach($rpthead as $rowmain){?>
                                
                                <option value="<?php echo $rowmain['id']; ?>"><?php echo $rowmain['name']; ?></option>
                                <?php }
                                } ?>
                                
                                
                            </select>
                        </div>
                        <div class="form-group col mb-2">
                            <label class="small font-weight-bold text-dark">Sub Section*</label>
                            <select class="form-control form-control-sm" data-nestname="op_debitnest" id="drp_sub" name="drp_sub">
                                <option value="-1">Select</option>
                                <?php if(count($rptsub)>0){
                                    foreach($rptsub as $rowsub){?>
                                
                                <option class="nestopt d-none" value="<?php echo $rowsub['id']; ?>" data-nestcode="<?php echo $rowsub['group_id']; ?>" disabled="disabled"><?php echo $rowsub['code']; ?></option>
                                <?php }
                                } ?>
                                
                                
                            </select>
                        </div>
                        
                        
                        
                        
                    <!-- detail -->
                    <!--/div-->
                    
                    
                </div>
                <div class="row">
                    <!--div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <form id="frmOtherPayment" autocomplete="off">
                            
                            
                            <input type="hidden" name="hrefop" id="hrefop" value=""><!-- referring-detail-id-for-other-particulars -//->
                            
                        </form>
                    </div-->
                    <div class="col" style="margin-top:15px;">
                        <table class="table table-striped table-bordered table-sm small" id="optableDetails" width="100%">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th class="text-center" style="width:100px;">Select</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        
                        <!--hr-->
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

        var addcheck='<?php //echo $addcheck; ?>';
        var editcheck='<?php //echo $editcheck; ?>';
        var statuscheck='<?php //echo $statuscheck; ?>';
        var deletecheck='<?php //echo $deletecheck; ?>';

        var main_table=$('#tableDetails').DataTable( {
				"searching":false,
				"info":false,
				"destroy": true,
				"processing": true,
				"serverSide": true,
				ajax: {
					url: "scripts/report_conf_subsectionlist.php",
					type: "POST", // you can use GET
					"data":function(data){
						data.filter_val=$('#drp_main').find(":selected").val();
					}
				},
				"columns": [
					{
						"data": "sub_section_name"
					},
					{
						"data": "header_id",
						"className": 'text-center',
						"orderable":false,
						"render": function(data, type, full) {
							var button='';
							var check_str = (full.detail_cancel==0)?'checked="checked"':'';
							
							var block_str = '';//($('#drp_sub').find(':selected').val()=='-1')?'disabled="disabled"':'';
							button+='<input type="checkbox" class="form-control-custom chk_view" data-toggle="tooltip" data-placement="right" title="" ';button+=check_str+block_str+' value="'+data+'" />'; 
							
							return button;
						}
					}
				]
			} );
		
		$("#drp_main").on("change", function(){
			main_table.draw();
		});
		
		var conf_table=$('#optableDetails').DataTable( {
				"searching":false,
				"info":false,
				"destroy": true,
				"processing": true,
				"serverSide": true,
				ajax: {
					url: "scripts/report_conf_subacclist.php",
					type: "POST", // you can use GET
					"data":function(data){
						data.filter_val=$('#drp_sub').find(":selected").val();
					}
				},
				"columns": [
					{
						"data": "subaccountname"
					},
					{
						"data": "header_id",
						"className": 'text-center',
						//"orderable":false,
						"render": function(data, type, full) {
							var button='';
							var check_str = (full.report_part_cancel==0)?'checked="checked"':'';
							var confid=full.conf_id;
							
							if(confid==null){
								check_str = '';
								confid = '';
							}
							
							var block_str = ($('#drp_sub').find(':selected').val()=='-1')?'disabled="disabled"':'';
							button+='<input type="checkbox" class="form-control-custom chk_sign" data-toggle="tooltip" data-placement="right" title="" data-refid="'+confid+'" data-refacc="'+full.subaccount+'" ';button+=check_str+block_str+' value="'+data+'" />'; 
							
							return button;
						}
					}
				]
			} );
		
		/**/
		$("#drp_grp").on("change", function(){
			$('.chk_sign').prop('disabled', true);
		});
		
		$("#drp_sub").on("change", function(){
			conf_table.draw();
		});
		
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
		
		// Create order part
        $('#btnordercreate').click(function(){
            $('#hrefid').val(''); // load-empty-record-number
			//$('#hrefop').val('');
			$('#modalcreateorder').modal('show');
			
        });
		
		// Create other cheque payments part
        $('#btnOtherCreate').click(function(){
            $('#hrefid').val(''); // load-empty-record-number
			//$('#hrefop').val('');
			$('#modalCreateOther').modal('show');
			
        });
		
		$('#modalcreateorder').on('hidden.bs.modal', function () {
            $('#txt_sub').val('');
			$('#hrefid').val('');
			//$('#hrefop').val('');
            //$('#tableDetails > tbody').html('');
			
			//book_table.clear().draw();
        });
		
		$('#createorderform').on('submit', function(event){
            event.preventDefault();
			
			var grpid=$("#drp_main").find(":selected").val();
			var sectname=$("#txt_sub").val();
			
			$.ajax({
				type:"POST",
				data:{
					grp_id: grpid,
					sect_name: sectname
				},
				dataType: 'JSON',
				url: 'process/report_section_reg_process.php',
				success: function(data) {
					if(data.msgdesc.type=="success"){
						main_table.row.add( {
							"header_id":data.head_k,
							"sub_section_name":sectname, 
							"sect_cancel":0
						}).draw( false ).node();
						
						$('#drp_sub').append('<option class="nestopt d-none" value="'+data.head_k+'" data-nestcode="'+grpid+'" disabled="disabled">'+sectname+'</option>');
					}
					
					action(data.msgdesc);
					
				}
			});
		});
		
		$(document).on('click', '.chk_view', function(event){
            //event.preventDefault();
			
			var confrefid=$(this).val();
			var detailcancel=$(this).is(":checked")?0:1;
			var objchkconf=$(this);
			
			$.ajax({
				type:"POST",
				data:{
					conf_refid: confrefid,
					detail_cancel: detailcancel
				},
				dataType: 'JSON',
				url: 'process/report_section_rev_process.php',
				success: function(data) {
					if(data.msgdesc.type=="success"){
						if(confrefid==''){
							$(objchkconf).data('refid', data.sub_k);
						}
					}else{
						//$(objchkconf).prop("disabled", true);
						$(objchkconf).prop("checked", !$(objchkconf).prop("checked"));
					}
					
					action(data.msgdesc);
					
				}
			});
		});
		
		$(document).on('click', '.chk_sign', function(event){
            //event.preventDefault();
			
			var grpid=$("#drp_grp").find(":selected").val();
			var sectid=$("#drp_sub").find(":selected").val();
			var confrefid=$(this).data('refid');
			var accid=$(this).val();
			var acccode=$(this).data('refacc');
			var detailcancel=$(this).is(":checked")?0:1;
			var objchkconf=$(this);
			
			$.ajax({
				type:"POST",
				data:{
					grp_id: grpid,
					sect_id: sectid,
					conf_refid: confrefid,
					acc_id: accid,
					acc_code: acccode,
					detail_cancel: detailcancel
				},
				dataType: 'JSON',
				url: 'process/report_conf_reg_process.php',
				success: function(data) {
					if(data.msgdesc.type=="success"){
						if(confrefid==''){
							$(objchkconf).data('refid', data.sub_k);
						}
					}else{
						//$(objchkconf).prop("disabled", true);
						$(objchkconf).prop("checked", !$(objchkconf).prop("checked"));
					}
					
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