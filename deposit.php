<?php 
include "include/header.php";  

$addcheck=0;
$editcheck=0;
$statuscheck=0;
$deletecheck=0;


$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`>1";
$resultbank =$conn-> query($sqlbank); 


$sqlmain="SELECT idtbl_company_branch as id, code, concat(code, ' ', branch) as name FROM `tbl_company_branch`";
$resultmain =$conn-> query($sqlmain); 

$sqlsub="SELECT `tbl_account_allocation`.`idtbl_account_allocation` as id, `tbl_account_allocation`.tbl_company_branch_idtbl_company_branch as group_id, `tbl_account_allocation`.`subaccountno` AS `code`, CONCAT(`tbl_account_allocation`.`subaccountno`, ' ', `tbl_subaccount`.`subaccountname`) as `name` FROM `tbl_account_allocation` INNER JOIN `tbl_subaccount` ON `tbl_account_allocation`.`subaccountno`=`tbl_subaccount`.`subaccount` WHERE `tbl_account_allocation`.`status`=1 ORDER BY group_id, id";
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
                            <span>Cash/Cheque Deposit</span>
                            <select class="form-control-sm fa-pull-right" name="main_filter" id="main_filter" style="position:absolute; right:100px;">
                            	<option value="">All</option>
                                <option value="1">Cash</option>
                                <option value="0">Cheque</option>
                            </select>
                            <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" id="btnordercreate" data-refid="-1" style="position:absolute; right:10px;"><i class="fas fa-plus"></i>&nbsp;Create</button>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <!--div class="row">
                                    <div class="col">
                                        
                                    </div>
                                </div>
                                <hr-->
                                <table class="table table-bordered table-striped table-sm nowrap small" id="tableHeaders">
                                    <thead>
                                        <tr>
                                            <th>Receipt No.</th>
                                            <th>Cheque No.</th>
                                            <th>Bank</th>
                                            <th>Branch</th>
                                            <th>Narration</th>
                                            <th>Paid By</th>
                                            <th>Date</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-center">Actions</th>
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
<!-- Modal Create Order -->
<div class="modal fade" id="modalcreateorder" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <form id="createorderform" autocomplete="off">
                            <div class="row">
                            	<div class="col">
                                <!-- header -->
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select class="form-control form-control-sm" id="drp_deposit_branch" name="drp_deposit_branch">
                                        	<option value="">Select</option>
											<?php if(count($listmain)>0){
												foreach($listmain as $rowmain){?>
                                            
                                            <option value="<?php echo $rowmain['id']; ?>" data-colcode=<?php echo $rowmain['code']; ?>><?php echo $rowmain['name']; ?></option>
                                            <?php }
											} ?>
                                            
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Date*</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control dpd1a" placeholder="" name="deposit_date" id="deposit_date" required><!-- -->
                                            <div class="input-group-append">
                                                <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Narration*</label>
                                        <input type="text" id="deposit_narration" name="deposit_narration" class="form-control form-control-sm" value="" required>
                                    </div>
                                    
                                <!-- header >
                                </div-->
                                <!--div class="col-md-6">
                                < detail -->
                                	<div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Transfer Branch*</label>
                                        <select class="form-control form-control-sm nest_head" data-findnest="accnest" id="drp_transfer_branch" name="drp_transfer_branch">
                                        	<option value="">Select</option>
                                            <?php if(count($listmain)>0){
												foreach($listmain as $rowmain){?>
                                            
                                            <option value="<?php echo $rowmain['id']; ?>" data-colcode="<?php echo $rowmain['code']; ?>"><?php echo $rowmain['name']; ?></option>
                                            <?php }
											} ?>
                                            
                                            
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Transfer Account*</label>
                                        <select class="form-control form-control-sm" data-nestname="accnest" id="drp_transfer_account" name="drp_transfer_account">
                                        	<option value="">Select</option>
                                            <?php if(count($listsub)>0){
												foreach($listsub as $rowsub){?>
                                            
                                            <option class="nestopt d-none" value="<?php echo $rowsub['id']; ?>" data-nestcode="<?php echo $rowsub['group_id']; ?>" data-colcode="<?php echo $rowsub['code']; ?>" disabled="disabled"><?php echo $rowsub['name']; ?></option>
                                            <?php }
											} ?>
                                            
                                            
                                        </select>
                                    </div>
                                    
                                    <div class="form-group mb-2" style="margin-bottom:0px !Important">
                                        <label class="small font-weight-bold text-dark">Amount*</label>
                                        <div class="form-row">
                                        	<div class="form-group col-md-6" style="margin-bottom:0px;">
                                            	<div class="i-checks" style="line-height:9px;">
                                                	<input type="radio" name="rad_pay_method" id="rad_cash" value="" checked="checked" class="form-control-custom radio-custom" style="width:12px; height:12px;" data-togval="1" />
                                                    <label class="small" for="rad_cash">Cash</label>
                                                </div>
                                                <div class="i-checks" style="line-height:10px;">
                                                	<input type="radio" name="rad_pay_method" id="rad_cheque" value="" class="form-control-custom radio-custom" style="width:12px; height:12px;" data-togval="0" />
                                                    <label class="small" for="rad_cheque">Cheque</label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6" style="margin-bottom:0px;">
                                            	<input type="text" id="rec_amount" name="rec_amount" class="form-control form-control-sm" value="0.00" required style="text-align:right;">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                <!-- detail -->
                                </div>
                                
                                
                            </div>
                            
                            
                            
                            
                            
                            
                            
                            <div class="form-group mt-3">
                                <button type="submit" id="formsubmit" class="btn btn-outline-primary btn-sm fa-pull-right" <?php // if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Save</button>
                                <!--input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none"-->
                            </div>
                            
                            <input type="hidden" name="hrefid" id="hrefid" value="">
                            <!--input type="hidden" name="hsubid" id="hsubid" value=""--><!-- sub-id -->
                            <input type="hidden" name="receipt_complete" id="receipt_complete" value="">
                            
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <table class="table table-striped table-bordered table-sm small" id="tableDetails" width="100%">
                            <thead>
                                <tr>
                                    <th>Receipt No.</th>
                                    <th>Cheque No.</th>
                                    <th>Bank</th>
                                    <th>Narration</th>
                                    <th>Paid By<!-- Customer --></th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<!--tr>
                                	<td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr-->
                            </tbody>
                        </table>
                        
                        <hr />
                        <div class="row">
                            <div class="col" style="text-align:justify;">
                                Please <strong>complete and save the account details</strong> in order to select relevant receipts from the transactions list
                            </div>
                            
                        </div>
                        <div style="position: absolute; bottom: 0px; margin-top: 0px !important; margin-bottom: 0px; right: 15px;" class="form-group mt-3">
                            
                                <button type="button" class="btn btn-secondary btn-sm fa-pull-right" id="btn_complete"><i class="fas fa-save"></i>&nbsp;Save All</button>
                                
                        </div>
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

        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        /**/
		var main_table=$('#tableHeaders').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/depositlist.php",
                type: "POST", // you can use GET
				"data":function(data){
					data.filter_val=$("#main_filter").find(":selected").val();
				}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": 'receipt_no'
                },
                {
                    "data": "cheque_no"
                },
				{
                    "data": "col_bank"
                },
                {
                    "data": "col_branch"
                },
				{
                    "data": "col_narration"
                },
                {
                    "data": "col_customer"
                },
				{
                    "data": "col_date"
                },
                {
                    "data": "deposit_amount",
                    "className": 'text-right'
                },
                {
                    "data": "header_id",
                    "className": 'text-center',
					"orderable":false,
                    "render": function(data, type, full) {
                        var button='';
                        
                        button+='<button class="btn btn-outline-primary btn-sm mr-1 btnedit" data-toggle="tooltip" data-placement="bottom" title="Edit" data-refid="'+data+'" ';button+='><i class="fas fa-edit"></i></button>'; 
                        
                        return button;
                    }
                }
            ]
        } );
		
		$("#main_filter").on("change", function(){
			main_table.draw();
		});
		
		var book_table=$('#tableDetails').DataTable({
			"info":false,
			"searching":false,
			"destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/chequedepositlist.php",
                type: "POST", // you can use GET
				"data":function(data){
					data.set_cash=($('#rad_cash').is(":checked"))?1:0;
					data.set_code=$('#hrefid').val();
				}
            },
            "order": [[ 6, "desc" ], [ 0, "asc" ]],
            "columns": [
                {
                    "data": "receipt_no"
                },
                {
                    "data": "cheque_no"
                },
				{
                    "data": "col_bank"
                },
                {
                    "data": "col_narration"
                },
				{
                    "data": "col_customer"
                },
                {
                    "data": "deposit_amount",
                    "className": 'text-right'
                },
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
						
						var block_str = ($('#hrefid').val()=='')?'disabled="disabled"':'';
                        button+='<input type="checkbox" class="form-control-custom chk_deposit" data-toggle="tooltip" data-placement="right" title="Deposit" data-refid="'+full.header_id+'" data-refacc="'+full.header_acc+'" data-refloc="'+full.header_loc+'" ';button+=check_str+block_str+' value="'+data+'" />'; 
                        
                        return button;
                    }
                }
            ], 
			"createdRow": function( row, data, dataIndex ){
				$( row ).attr('id', 'pack-'+data.header_id);
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
		
		$('#createorderform').on('submit', function(event){
            event.preventDefault();
			
			var refid = $("#hrefid").val();
            var depositdate = $('#deposit_date').val();
			var depositnarration = $('#deposit_narration').val();
			var depositbranch = $('#drp_deposit_branch').find(":selected").val();
			var depositbranch_colcode = $('#drp_deposit_branch').find(":selected").data('colcode');
			var transferbranch = $('#drp_transfer_branch').find(":selected").val();
			var transferbranch_colcode = $('#drp_transfer_branch').find(":selected").data('colcode');
			var transferacc = $('#drp_transfer_account').find(":selected").val();
			var transferacc_colcode = $('#drp_transfer_account').find(":selected").data('colcode');
			
            var setcash = $('#rad_cash').is(':checked')?1:0;
            var subid = '';//$("#hsubid").val();
			var recamount = $('#rec_amount').val();

            $.ajax({
                type: "POST",
                data: {
                    ref_id: refid,
                    deposit_date: depositdate,
					deposit_narration: depositnarration, 
					deposit_branch: depositbranch, 
					deposit_branch_colcode: depositbranch_colcode, 
					transfer_branch: transferbranch,
					transfer_branch_colcode: transferbranch_colcode,
					transfer_acc: transferacc,
					transfer_acc_colcode: transferacc_colcode,
                    set_cash: setcash,
					sub_id: subid,
                    rec_amount:recamount
                },
				dataType: 'JSON',
                url: 'process/bank_deposit_process.php',
                success: function(data) { //alert(JSON.stringify(data));
                    if(data.msgdesc.type=="success"){
						if($("#hrefid").val()==''){
							$("#hrefid").val(data.head_k);
							$('.chk_deposit').prop("disabled", false);
							
							if(setcash==1){
								/*main_table.row.add( {
									"header_id":data.head_k,
									"receipt_debit_branch":'-',
									"receipt_debit_account":'-',
									"receipt_head_narration":'-',
									"receipt_customer":reccustomer,
									"receipt_category":reccategory
								}).draw( false ).node();*/
							}
						}
						
						
					}
					
                    action(data.msgdesc);
                    
                }
            });
        });
		
		$(document).on("click", ".chk_deposit", function(){
			var refid=$("#hrefid").val();
			var receiptrefno=$(this).data('refid');
			var accref=$(this).data('refacc');
			var branchref=$(this).data('refloc');
			
			var depositrefno=$(this).val();
			
			var detailcancel=$(this).is(":checked")?0:1;
			
			var cal_opt=(detailcancel==0)?1:-1;
			var selected_tr=book_table.row('#pack-'+receiptrefno+'');
			
			var d=selected_tr.data();
			var detailval=d.deposit_amount*cal_opt;
			var setcash = $('#rad_cash').is(':checked')?1:0;
			
			var objchkdeposit=$(this);
			
			$.ajax({
                type: "POST",
                data: {
                    ref_id: refid,
					receipt_refno: receiptrefno,
					acc_ref: accref,
					branch_ref: branchref, 
					sub_id: depositrefno,
                    detail_cancel: detailcancel,
					detail_val: detailval,
					set_cash: setcash
                },
				dataType: 'JSON',
                url: 'process/bank_deposit_detail_process.php',
                success: function(data) { //alert(JSON.stringify(data));
                    if(data.msgdesc.type=="success"){
						if(depositrefno==''){
							$(objchkdeposit).val(data.sub_k);
							
							
						}
						
						if(setcash==0){
							var tot_amount = parseFloat($("#rec_amount").val())+detailval;
							$("#rec_amount").val(tot_amount.toFixed(2)); //update total-deposit
						}
						
					}else{
						//$(objchkdeposit).prop("disabled", true);
						$(objchkdeposit).prop("checked", !$(objchkdeposit).prop("checked"));
					}
					
                    action(data.msgdesc);
                    
                }
            });
		});
		
        // Create order part
        $('#btnordercreate').click(function(){
            $('#hrefid').val(''); // load-empty-record-number
			$('#receipt_complete').val(0);
			/*
			$('#hsubid').val('');
			*/
			$('#modalcreateorder').modal('show');
        });
		
		$(document).on("click", '.btnedit', function(){
			$('#hrefid').val($(this).data('refid')); // load-existing-record-number
			$('#modalcreateorder').modal('show');
		});
		
		$('#btn_complete').click(function(){
			$.ajax({
				type: "POST",
				data: {
					ref_id: $("#hrefid").val()
				},
				dataType: "JSON",
				url: "process/bank_deposit_complete_process.php",
				success: function(data){
					var resmsg_data = {'icon':"fas fa-exclamation-triangle", 
							   'title':"", 
							   'message':"", 
							   'url':"", 
							   'target':"_blank", 
							   'type':"danger"};
					
					if(data.msgdesc.type=="success"){
						$('#receipt_complete').val(data.rec_complete);
						resmsg_data = data.msgdesc;
					}else{
						if($('#hrefid').val()==''){
							resmsg_data.message="No receipt details available";
						}else if($('#receipt_complete').val()==1){
							resmsg_data.message="Receipt details have already been processed";
						}else{
							resmsg_data.message=data.msgdesc.message;
						}
					}
					
					action(resmsg_data);
					 
				}
			});
			
			
		});
		
		$('input[name="rad_pay_method"]').change(function(){
			var deposit_no=$("#hrefid").val();
			if(deposit_no==''){
				getReceipts();
				book_table.draw();
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
				
				$('#rec_amount').val('0.00'); // if(cheque_selected)
				
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
				alert('Changes to receipt type is not allowed');
				
			}
		});
		
		$('#modalcreateorder').on('shown.bs.modal', function () {
			if($('#hrefid').val()==''){
				if($('.chk_deposit:checked').length>0){
					book_table.draw();
				}else{
					$('.chk_deposit').prop("disabled", true);
				}
				
				$('#rec_amount').val('0.00');
				$('#deposit_date').val('');
					
				getReceipts();
				
			}else{
				var id = $('#hrefid').val();if($('.showdata').length>0){$('.showdata').remove();}$('<a class="showdata" style="margin-right:15px; line-height:30px;" href="show_data.php?refno='+id+'&refch=D" target="_blank">show-data</a>').insertBefore('#btn_complete');
				$.ajax({
					type: "POST",
					data: {
						refID: id
					},
					dataType:'JSON', 
					url: 'getprocess/get_bank_deposit_slip.php',
					success: function(data) { //alert(result);
						$('#deposit_date').val(data.deposit_date);
						$('#deposit_narration').val(data.deposit_narration);
						$('#drp_deposit_branch').val(data.branch_id);
						$('#rec_amount').val(parseFloat(data.rec_amount).toFixed(2));                   
						
						$('input[type="radio"][data-togval="1"]').attr('data-togval', "0");
						$('#rad_cash').prop('checked', (data.set_cash==1));
						$('#rad_cheque').prop('checked', (data.set_cheque==1));
						//console.log($('input[type="radio"]:checked').attr('id'));
						$('input[type="radio"]:checked').attr('data-togval', "1");
						
						$('#drp_transfer_branch').val(data.transfer_branch_id);
						$('#receipt_complete').val(data.rec_complete);
						
						prep_nest($('#drp_transfer_branch').data('findnest'), data.transfer_branch_id, data.transfer_acc_id);
						
						getReceipts();
						
						book_table.draw();
					}
				});
			}
			
		});
		
		function getReceipts(){
			var cheque_selected=$('#rad_cheque').is(":checked");
			$('#rec_amount').prop('disabled', cheque_selected);
			
			
			
		}
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
