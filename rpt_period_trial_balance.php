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
                                <form id="createorderform" autocomplete="off">
                                    <div class="row">
                                        <div class="col">
                                        <!-- header -->
                                            
                                            
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-dark">From Date*</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control dpd1a" placeholder="" name="cheque_date" id=""><!--required-->
                                                    <div class="input-group-append">
                                                        <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-dark">To Date*</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control dpd1a" placeholder="" name="cheque_date" id=""><!--required-->
                                                    <div class="input-group-append">
                                                        <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
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
                                <h6 class="title-style small font-weight-bold mt-2"><span>More Info</span></h6>
                                <table class="table table-bordered table-sm table-striped" id="tableGrnList">
                                    <thead>
                                        <tr>
                                            <th>Account Type</th>
                                            <th>Account No</th>
                                            <th>Account Name</th>
                                            <th class="text-right">Opening Bal</th>
                                            <th>C/D</th>
                                            <th class="text-center">Debit</th>
                                            <th class="text-center">Credit</th>
                                            <th class="text-right">Closing Bal</th>
                                            <th>C/D</th>
                                        </tr>
                                    </thead>
                                    <tbody id=""><td colspan="9">&nbsp;</td></tbody>
                                    <tfoot>
                                    	<td colspan="4">Total</td>
                                        <td>...</td>
                                        <td class="text-center">Dr Total</td>
                                        <td class="text-center">Cr Total</td>
                                        <td>&nbsp;</td>
                                        <td>...</td>
                                    </tfoot>
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
