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
                            <span>Reverse Transaction</span>
                            <!--button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" id="btnordercreate" data-refid="-1" style="position:absolute; right:10px;"><i class="fas fa-plus"></i>&nbsp;Create</button-->
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                        	<div class="col-md-8">
                            	<div class="form-row mb-1">
                                	<div class="col">
                                    	<label class="small font-weight-bold text-dark" style="margin-left:3px;">Trn. No</label>
                                        <input type="text" class="form-control form-control-sm" name="trnno_filter" id="trnno_filter" value="<?php echo ''; ?>">
                                    </div>
                                    <div class="col">
                                    	<label class="small font-weight-bold text-dark" style="margin-left:3px;">Trn. Date</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control dpd1a" placeholder="Select" name="trndate_filter" id="trndate_filter" value="<?php echo ''; ?>">
                                            <div class="input-group-append">
                                                <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                            	<div class="form-row mb-1">
                                	<div class="col" align="right">
                                    	<!--label class="small font-weight-bold text-dark" style="margin-left:3px;" id="rec_info_msg"><?php //echo '0 Transaction(s) selected.'; ?></label-->
                                        <span class="small text-dark" style="margin-left:3px; margin-bottom:0.5rem; display:inline-block;" id="rec_info_msg">
											<span id="rec_info_cnt">0</span><?php echo ' Transaction(s) selected.'; ?>
                                        </span>
                                        <div class="form-group" align="right">
                                            <button type="button" id="saveBtn" class="btn btn-outline-primary btn-sm" <?php //if($addcheck==0){echo 'disabled';} ?> ><i class="far fa-save"></i>&nbsp;Save</button>
                                            <button type="button" id="clearBtn" class="btn btn-outline-primary btn-sm" <?php //if($addcheck==0){echo 'disabled';} ?> disabled="disabled"><i class="far fa-trash-alt"></i>&nbsp;Clear</button>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <!--hr /-->
                        <div class="row">
                            <div class="col-12">
                                <!--div class="row">
                                    <div class="col">
                                        
                                    </div>
                                </div>
                                <hr-->
                                <style type="text/css">
									.dataTables_filter{
										display:none;
									}
								</style>
                                <table class="table table-bordered table-striped table-sm nowrap" id="tableHeaders" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Trn. No</th>
                                            <th>Trn. ID</th>
                                            <th>DrCr</th>
                                            <th>Narration</th>
                                            <th>Trn. Date</th>
                                            <th>Account</th>
                                            <th>Ref. No</th>
                                            <th>Branch</th>
                                            <th>Amount</th>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-sm small" id="tableDetails">
                            <thead>
                                <tr>
                                    <th>Trn. No</th>
                                    <th>Trn. ID</th>
                                    <th>Trn. Date</th>
                                    <th class="text-right">Tot Amount</th>
                                    
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        
                        <!--hr-->
                        
                        <div style="position: relative; bottom: 0px; margin-top: auto; margin-bottom: 0px; right: 0px;" class="form-group mt-3">
                        	<button type="button" class="btn btn-secondary btn-sm fa-pull-right" id="btn_complete"><i class="fas fa-save"></i>&nbsp;Reverse All Transactions</button>
                            <input type="hidden" name="hrefid" id="hrefid" value="">
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

        var main_table=$('#tableHeaders').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
			//"searching": false,
            ajax: {
                url: "scripts/revtransactionlist.php",
                type: "POST", // you can use GET
				"data":function(data){
					data.set_code=$('#hrefid').val();
				}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "trn_num"
                },
                {
                    "data": "trn_type"
                },
				{
					"data": "dr_cr"
				},
				{
					"data": "trn_narration"
				},
				{
					"data": "trn_date"
				},
				{
					"data": "trn_accnum"
				},
				{
					"data": "trn_refnum"
				},
				{
					"data": "trn_branch"
				},
				{
					"data": "trn_amount"
				},
                {
                    "data": "detail_id",
                    "className": 'text-center',
					"orderable":false,
                    "render": function(data, type, full) {
                        var button='';
                        var check_str = (full.detail_cancel==0)?'checked="checked"':'';
						
						if(data==null){
							check_str = '';
							data = '';
						}
						
						var block_str = '';//(full.is_matched=='1')?'disabled="disabled"':'';
                        button+='<input type="checkbox" class="form-control-custom chk_rev" data-toggle="tooltip" data-placement="right" title="" data-refid="'+full.header_id+'" data-trnnum="'+full.trn_num+'" ';button+=check_str+block_str+' value="'+data+'" />'; 
                        
                        return button;
                    }
                }
            ]
        } );
		$('#trnno_filter').on('keyup change', function () {
			if (main_table.columns(0).search() !== this.value) {
				main_table.columns(0).search(this.value).draw();
			}
		});
		$('#trndate_filter').on('keyup change', function () {
			if (main_table.columns(4).search() !== this.value) {
				main_table.columns(4).search(this.value).draw();
			}
		});
		
		var book_table=$('#tableDetails').DataTable( {
				"info":false,
				"searching":false,
				"paging":false,
				"columns": [{data:'trn_num'},
							{data:'trn_type'}, 
							{data:'trn_date'}, 
							{data:'tot_amount', "className":"text-right"}], 
				/*
				"columnDefs": [{
						"targets":4,
						"orderable":false, 
						render: function( data, type, row ){
							return '<a class="act_delitem" '+
								'data-refid="'+data+'" href="#" >'+
									'<i class="fas fa-window-close"></i>'+
								'</a>';
						}
					}], 
				*/
				"createdRow": function( row, data, dataIndex ){
					$( row ).attr('id', 'pack-'+data.detail_id);
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
        /*
		$('#btnordercreate').click(function(){
            $('#hrefid').val(''); // load-empty-record-number
			$('#receipt_complete').val(0);
			$('#modalcreateorder').modal('show');
        });
		*/
		
		$('#saveBtn').click(function(){
            $('#modalcreateorder').modal('show');
        });
		
		$('#modalcreateorder').on('shown.bs.modal', function () {
            var id = $('#hrefid').val();/*if($('.showdata').length>0){$('.showdata').remove();}if(id!=''){$('<a class="showdata" style="margin-right:15px; line-height:30px;" href="show_data.php?refno='+id+'&refch=R" target="_blank">show-data</a>').insertBefore('#btn_complete');}*/
            $.ajax({
                type: "POST",
                data: {
                    refID: id
                },
				dataType:'JSON', 
                url: 'getprocess/get_rev_request_list.php',
                success: function(data) { //alert(result);
                    book_table.clear();
					book_table.rows.add(data.table_data);
					book_table.draw();
					
                }
            });
        });
		
        $('#modalcreateorder').on('hidden.bs.modal', function () {
            book_table.clear();
        });
		
        $(document).on('click', '.chk_rev', function(){
			var refid=$("#hrefid").val();
			var receiptrefno=$(this).data('refid');//receipt-ref-no
			var revtrnrefno=$(this).val();//rev-trn-refno
			var detailcancel=$(this).is(":checked")?0:1;
			
			var trnnum=$(this).data('trnnum');
			var objtrnlist=$('input[type="checkbox"][data-trnnum="'+trnnum+'"]');//obj-trn-list
			$(objtrnlist).prop('checked', $(this).is(':checked'));
			
			var rec_info_update=(detailcancel==0)?1:-1;
			var selected_recs=parseInt($('#rec_info_cnt').html())+rec_info_update;
			
			var objchktrnrev=$(this);//obj-chk-trn-rev
			
			$.ajax({
				type: "POST",
				data: {
					ref_id: refid,
					receipt_refno: receiptrefno,
					sub_id: revtrnrefno,
					detail_cancel: detailcancel,
					trn_num: trnnum
				},
				dataType: 'JSON',
				url: 'process/reverse_transaction_reg_process.php',
				success: function(data) { //alert(JSON.stringify(data));
					if(data.msgdesc.type=="success"){
						if(refid==''){
							$("#hrefid").val(data.head_k);
							
						}
						
						if(revtrnrefno==''){
							$(objtrnlist).attr('value', data.sub_k);
						}
						
						$('#rec_info_cnt').html(selected_recs);
						
					}else{
						//$(objtrnlist).prop("disabled", true);
						$(objtrnlist).prop("checked", !$(objchktrnrev).prop("checked"));
					}
					
					action(data.msgdesc);
					
				}
			});
		});
		
		$('#btn_complete').click(function(){
			$.ajax({
				type: "POST",
				data: {
					ref_id: $("#hrefid").val()
				},
				dataType: "JSON",
				url: "process/transaction_revoke_complete_process.php",
				success: function(data){
					var resmsg_data = {'icon':"fas fa-exclamation-triangle", 
							   'title':"", 
							   'message':"", 
							   'url':"", 
							   'target':"_blank", 
							   'type':"danger"};
					
					if(data.msgdesc.type=="success"){
						//$('#receipt_complete').val(data.rec_complete);
						resmsg_data = data.msgdesc;
					}else{
						if($('#hrefid').val()==''){
							resmsg_data.message="No transactions selected";
						}else{
							resmsg_data.message="Unable to complete the process";
						}
					}
					
					action(resmsg_data);
					 
				}
			});
			
			
		});
		
        
		
    });

    function action(data) { //alert(JSON.stringify(data));
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
