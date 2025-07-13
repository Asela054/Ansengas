<?php 
include "include/header.php";  

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

$sqlrpt_head = "SELECT id, head_section_name AS name FROM tbl_gl_report_head_sections WHERE report_id='BAL'";
$resultrpt_head = $conn->query($sqlrpt_head);

$sqlrpt_sub = "SELECT tbl_gl_report_sub_sections.id, tbl_gl_report_sub_sections.sub_section_name as `code`, tbl_gl_report_sub_sections.tbl_gl_report_head_section_id as group_id FROM tbl_gl_report_sub_sections INNER JOIN tbl_gl_report_head_sections ON tbl_gl_report_sub_sections.tbl_gl_report_head_section_id=tbl_gl_report_head_sections.id WHERE report_id='BAL'";
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
<style>
th.text-right.sect_col{
    border-bottom:1px solid black;
}
td.text-right.sect_col{
    border-bottom:1px solid black;
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
                            <div class="page-header-icon"><i data-feather="file"></i></div>
                            <span>Balance Sheet</span>

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
                        <div class="row">
                            <div class="col-3">
                                <form id="searchform" autocomplete="off">
                                    <div class="row">
                                        <div class="col">
                                        <!-- header -->
                                            <div class="form-group mb-2" style="margin-bottom:0px !Important">
                                                <label class="small font-weight-bold text-dark">Report Duration*</label>
                                                <div class="form-row">
                                                    <div class="form-group col" style="margin-bottom:0px;">
                                                        <div class="i-checks" style="line-height:9px;">
                                                            <input type="radio" name="rad_rpt_date" id="rad_date" value="" checked="checked" class="form-control-custom radio-custom" style="width:12px; height:12px;" data-togval="1" />
                                                            <label class="small" for="rad_date">As at Date</label>
                                                        </div>
                                                        <div class="i-checks" style="line-height:10px;">
                                                            <input type="radio" name="rad_rpt_date" id="rad_period" value="" class="form-control-custom radio-custom" style="width:12px; height:12px;" data-togval="0" />
                                                            <label class="small" for="rad_period">Date Range</label>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            
                                            <!--div class="">
                                            	<hr class="border-dark" />
                                            </div-->
                                            
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-dark">Date From</label>
                                                <div class="row">
                                                    <div class="input-group input-group-sm col-md-6">
                                                        <input type="text" class="form-control dpd1a" placeholder="" name="rpt_param_date" id="param_date_fr" required><!--required-->
                                                        <div class="input-group-append">
                                                            <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="input-group input-group-sm col-md-6">
                                                        <input type="text" class="form-control dpd1a" placeholder="" name="rpt_param_date" id="param_date_to" disabled="disabled"><!--required-->
                                                        <div class="input-group-append">
                                                            <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- <hr />
                                            
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
                                            </div> -->
                                        <!-- header -->
                                        </div>
                                        
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="button" id="formsubmitsearch" class="btn btn-outline-primary btn-sm fa-pull-right" <?php // if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;View</button>
                                        <input name="submithideBtn" type="submit" value="Save" id="submithideBtn" class="d-none">
                                    </div>
                                </form>
                            </div>
                            <div class="col-9">
                                <h6 class="title-style small font-weight-bold mt-2"><span>More Info</span></h6>
                                <table class="table table-bordered table-sm table-striped small" id="tableGrnList">
                                    <thead>
                                        <tr>
                                            <th>Account Type</th>
                                            <th>Account No</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodybalancesheet">
                                        <tr>
                                            <td colspan="4" class="text-muted small text-center">No data preview</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <hr class="border-dark">
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right px-5" id="btnSaveGrn"><i class="far fa-save"></i>&nbsp;Save Report</button>
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
                                        <select class="form-control form-control-sm nest_head" data-findnest="debitnest"
                                            id="drp_main" name="drp_main">
                                            <option value="-1">Select</option>
                                            <?php if(count($rpthead)>0){
												foreach($rpthead as $rowmain){?>

                                            <option value="<?php echo $rowmain['id']; ?>">
                                                <?php echo $rowmain['name']; ?></option>
                                            <?php }
											} ?>


                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Sub Section*</label>
                                        <input type="text" id="txt_sub" name="txt_sub"
                                            class="form-control form-control-sm" value="" required>
                                    </div>
                                    <!-- detail -->
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" id="sect_submit"
                                    class="btn btn-outline-primary btn-sm fa-pull-right"
                                    <?php // if($addcheck==0){echo 'disabled';} ?>><i
                                        class="fas fa-plus"></i>&nbsp;Add</button>
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
		
		$('input[name="rad_rpt_date"]').change(function(){
			var obj_act = false;
			var obj_val = $('#param_date_to').val();
			
			if($('#rad_date').is(":checked")){
				obj_act=true;
				obj_val='';//$('#param_date_fr').val();
			}
			
			$('#param_date_to').val(obj_val);
			$('#param_date_to').prop('disabled', obj_act)
		});

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
		
		$('#createorderform').on('submit', function(event){//alert('IN');
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
				success: function(data) {//alert(data);
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

        // Search Balance Part
        $('#formsubmitsearch').click(function(){
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submithideBtn").click();
            } else {
                var param_date_fr = $('#param_date_fr').val();
                var param_date_to = $('#param_date_to').val();

                $.ajax({
                    type: "POST",
                    data: {
                        param_date_fr: param_date_fr,
                        param_date_to: param_date_to
                    },
                    url: 'getprocess/getbalancesheet.php',
                    success: function(result) { //alert(result);
                        $('#tbodybalancesheet').empty().html(result);
                    }
                });
            }
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
</script>
<?php include "include/footer.php"; ?>
