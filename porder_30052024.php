<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_porder` WHERE `confirmstatus` IN (1,0,2)";
$result =$conn-> query($sql); 

$sqlproduct="SELECT `idtbl_product`, `product_name`, `tbl_product_category_idtbl_product_category`, `unitprice`, `refillprice` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 

$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`>1";
$resultbank =$conn-> query($sqlbank); 

$sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=0 AND `status`=1";
$resultvehicle =$conn-> query($sqlvehicle); 

$sqlvehicletrailer="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=1 AND `status`=1";
$resultvehicletrailer =$conn-> query($sqlvehicletrailer); 

$sqldiverlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=4 AND `status`=1";
$resultdiverlist =$conn-> query($sqldiverlist);

$sqlofficerlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=6 AND `status`=1";
$resultofficerlist =$conn-> query($sqlofficerlist);

$sqlhelperlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=5 AND `status`=1";
$resulthelperlist =$conn-> query($sqlhelperlist);

include "include/topnavbar.php"; 
?>
<style>
    .tableprint {
        table-layout: fixed;
    }
    .custom-modal {
        max-width: 1500px;
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
                            <span>Purchase Order</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" id="btnordercreate"><i class="fas fa-plus"></i>&nbsp;Create Purchsing Order</button>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Request</th>
                                            <th class="text-right">Nettotal</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-right">Actions</th>
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
    <div class="modal-dialog modal-dialog-centered modal-xl custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">CREATE PURCHASING ORDER</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <form id="createorderform" autocomplete="off">
                            <div class="form-row mb-1">
                                <div class="col-3">
                                    <label class="small font-weight-bold text-dark">Order Date*</label>
                                    <div class="input-group input-group-sm">
                                        <input type="date" id="orderdate" name="orderdate"
                                            class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>"
                                            required>
                                    </div>
                                </div>
                                <div class="col-3">
                                </div>
                            </div>
                            <input type="hidden" name="unitprice" id="unitprice" value="">
                            <input type="hidden" name="saleprice" id="saleprice" value="">
                            <input type="hidden" name="refillprice" id="refillprice" value="">
                        </form>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <table class="table table-striped table-bordered table-sm small" id="tableorder">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Product</th>
                                    <th class="d-none" style="width: 100px;">ProductID</th>
                                    <th class="d-none" style="width: 100px;">Newprice</th>
                                    <th class="d-none" style="width: 100px;">Refillprice</th>
                                    <th class="d-none" style="width: 100px;">Emptyprice</th>
                                    <th class="d-none" style="width: 100px;">Saleprice VAT</th>
                                    <th class="d-none" style="width: 100px;">Refillprice VAT</th>
                                    <th class="d-none" style="width: 100px;">Emptyprice VAT</th>
                                    <th class="text-center" style="width: 50px;">New Price</th>
                                    <th class="text-center" style="width: 50px;">Refill Price</th>
                                    <th class="text-center" style="width: 50px;">Empty Price</th>
                                    <th class="text-center" style="width: 50px;">New Price +(VAT)</th>
                                    <th class="text-center" style="width: 50px;">Refill Price +(VAT)</th>
                                    <th class="text-center" style="width: 50px;">Empty Price +(VAT)</th>
                                    <th class="text-center" style="width: 50px;">New</th>
                                    <th class="text-center" style="width: 50px;">Refill</th>
                                    <th class="text-center" style="width: 50px;">Empty</th>
                                    <th class="text-center" style="width: 50px;">Trust</th>
                                    <th class="text-center" style="width: 50px;">Trust Return</th>
                                    <th class="text-center" style="width: 50px;">Safty</th>
                                    <th class="text-center" style="width: 50px;">Safty Return</th>
                                    <th class="text-center" style="width: 50px;">VAT</th>
                                    <th class="d-none" style="width: 100px;">HideTotal</th>
                                    <th class="text-right" style="width: 100px;">Total</th>
                                    <th class="d-none" style="width: 100px;">Hide Total Without Vat</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody"></tbody>
                        </table>
                        <div class="row">
                            <div class="input-group input-group-sm mt-4 ml-3">
                                <input type="checkbox" class="show-accessories-checkbox">&nbsp;Show Accessories
                            </div>
                            <div class="col text-right">
                                <h1 class="font-weight-600" id="divtotal">Rs. 0.00</h1>
                            </div>
                            <input type="hidden" id="hidetotalorder" value="0">
                            <input type="hidden" id="hidetotalorderwithoutvat" value="0">

                        </div>
                        <hr>
                        <div class="form-group col-6 ">
                            <label class="small font-weight-bold text-dark">Remark</label>
                            <textarea name="remark" id="remark" class="form-control form-control-sm"></textarea>
                        </div>
                        <div class="form-group mt-2">
                            <button type="button" id="btncreateorder"
                                class="btn btn-outline-primary btn-sm fa-pull-right"
                                <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Create
                                Order</button>
                        </div>
                        <!-- <div class="form-group mt-3 text-danger small">
                            <span class="badge badge-danger mr-2">&nbsp;&nbsp;</span> Stock quantity warning
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal order view -->
<div class="modal fade" id="modalorderview" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6 class="modal-title" id="viewmodaltitle"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-sm small" id="tableorderview">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="d-none">ProductID</th>
                            <th class="text-center">Refill Qty</th>
                            <th class="text-center">New Qty</th>
                            <th class="text-center">Empty Qty</th>
                            <th class="text-center">Trust Qty</th>
                            <th class="text-center">Trust Return Qty</th>
                            <th class="text-center">Safety Qty</th>
                            <th class="text-center">Safety Return Qty</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div class="row">
                    <div class="col-12 text-right"><h1 class="font-weight-600" id="divtotalview">Rs. 0.00</h1></div>
                    <div class="col-12"><h6 class="title-style"><span>Remark Information</span></h6></div>
                    <div class="col-12"><div id="remarkview"></div></div>
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
<!-- Modal cheque view -->
<div class="modal fade" id="modalcheque" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6 class="modal-title" id="">Last week bill Information</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="tablelastbillshow"></div>
                <input type="hidden" name="chequehideorderid" id="chequehideorderid" value="">
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-outline-primary btn-sm px-4 fa-pull-right" id="btnproceedcheque"><i class="fas fa-plus"></i>&nbsp;Proceed to cheque</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal order view -->
<div class="modal fade" id="modalotherinfo" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6 class="modal-title" id="">Vehicle Information</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="otherform" method="post">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Company Lorries*</label><br>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="companylorry1" name="companylorry" class="custom-control-input" value="1">
                                    <label class="custom-control-label font-weight-bold" for="companylorry1">Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="companylorry2" name="companylorry" class="custom-control-input" value="0" checked>
                                    <label class="custom-control-label font-weight-bold" for="companylorry2">No</label>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Distributor Lorries*</label><br>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="distributorlorry1" name="distributorlorry" class="custom-control-input" value="1">
                                    <label class="custom-control-label font-weight-bold" for="distributorlorry1">Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="distributorlorry2" name="distributorlorry" class="custom-control-input" value="0" checked>
                                    <label class="custom-control-label font-weight-bold" for="distributorlorry2">No</label>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Lorry No*</label>
                                <select name="lorrynum" id="lorrynum" class="form-control form-control-sm" required>
                                    <option value="">Select</option>
                                    <?php if($resultvehicle->num_rows > 0) {while ($rowvehicle = $resultvehicle-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowvehicle['idtbl_vehicle'] ?>"><?php echo $rowvehicle['vehicleno'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Trailer*</label>
                                <select name="trailernum" id="trailernum" class="form-control form-control-sm" required>
                                    <option value="">Select</option>
                                    <?php if($resultvehicletrailer->num_rows > 0) {while ($rowvehicletrailer = $resultvehicletrailer-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowvehicletrailer['idtbl_vehicle'] ?>"><?php echo $rowvehicletrailer['vehicleno'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Time*</label>
                                <input type="time" class="form-control form-control-sm" name="scheduletime" id="scheduletime" required>
                            </div>
                            <div class="form-group mt-2">
                                <button type="button" id="btnothersubmit" class="btn btn-outline-primary btn-sm fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add Delivery</button>
                                <input name="othersubmitBtn" type="submit" id="othersubmitBtn" class="d-none">
                                <input name="otherresetBtn" type="reset" id="otherresetBtn" class="d-none">
                            </div>
                            <input type="hidden" name="otherhideorderid" id="otherhideorderid" value="">
                            <input type="hidden" name="recordOption" id="recordOption" value="1">
                            <input type="hidden" name="recordID" id="recordID" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Dispatch Create -->
<div class="modal fade" id="modalcreatedispatch" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Create Dispatch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped table-bordered table-sm small" id="tabledispatch">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="d-none">ProductID</th>
                                    <th class="d-none">UnitPrice</th>
                                    <th class="d-none">Refillprice</th>
                                    <th class="d-none">Emptyprice</th>
                                    <th class="d-none">Newsaleprice</th>
                                    <th class="d-none">Refillsaleprice</th>
                                    <th class="d-none">Emptysaleprice</th>
                                    <th class="text-center">Refill Qty</th>
                                    <th class="text-center">New Qty</th>
                                    <th class="text-center">Empty Qty</th>
                                    <th class="text-center">Return Qty</th>
                                    <th class="text-center">Trust Qty</th>
                                    <th class="text-center">Safty Qty</th>
                                    <th class="text-center">Safty Return</th>
                                    <th class="d-none">HideTotal</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody id="tbodydispatchcreate"></tbody>
                        </table>
                        <div class="row">
                            <div class="col text-right"><h1 class="font-weight-600" id="divtotaldispatch">Rs. 0.00</h1></div>
                            <input type="hidden" id="hidetotalorderdispatch" value="0">
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <form id="formdispatchdriverofficer">
                                    <div class="form-row">
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Driver*</label>
                                            <select name="drivername" id="drivername" class="form-control form-control-sm" required>
                                                <option value="">Select</option>
                                                <?php if($resultdiverlist->num_rows > 0) {while ($rowdiverlist = $resultdiverlist-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowdiverlist['idtbl_employee'] ?>"><?php echo $rowdiverlist['name'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Officer*</label>
                                            <select name="officername" id="officername" class="form-control form-control-sm" required>
                                                <option value="">Select</option>
                                                <?php if($resultofficerlist->num_rows > 0) {while ($rowofficerlist = $resultofficerlist-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowofficerlist['idtbl_employee'] ?>"><?php echo $rowofficerlist['name'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="small font-weight-bold text-dark">Helper*</label><br>
                                            <select name="helpername[]" id="helpername" class="form-control form-control-sm" style="width:100%;" multiple required>
                                                <?php if($resulthelperlist->num_rows > 0) {while ($rowhelperlist = $resulthelperlist-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowhelperlist['idtbl_employee'] ?>"><?php echo $rowhelperlist['name'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                        <input type="submit" class="d-none" id="driverofficersubmitBtn">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <hr>
                            <button type="button" id="btncreatedispatch" class="btn btn-outline-primary btn-sm fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Create Dispatch</button>
                            <input type="hidden" id="hidelorrynum" value=''>
                            <input type="hidden" id="hidetrailernum" value=''>
                            <input type="hidden" id="hideorderid" value=''>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
<!-- Modal Dispatch View -->
<div class="modal fade" id="modaldispatchdetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="dispatchprint"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm fa-pull-right" id="btndispatchprint"><i class="fas fa-print"></i>&nbsp;Print Dispatch</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Day End Warning -->
<div class="modal fade" id="warningDayEndModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body bg-danger text-white text-center">
                <div id="viewmessage"></div>
            </div>
            <div class="modal-footer bg-danger rounded-0">
                <a href="dayend.php" class="btn btn-outline-light btn-sm">Go To Day End</a>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        // var productprice = '<?php //echo $productpricejson; ?>';

        checkdayendprocess();
        $("#helpername").select2();

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

        $('#dataTable').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/porderlist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_porder"
                },
                {
                    "data": "orderdate"
                },
                {
                    "data": "name"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return parseFloat(full['nettotal']).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                },

                {
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function(data, type, full) {
                        var html = '';
                        if(full['confirmstatus']==1){
                            html+='<i class="fas fa-check text-success"></i>&nbsp;Confirm';
                        }
                        else if(full['confirmstatus']==2){
                            html+='<i class="fas fa-times text-danger"></i>&nbsp;Cancelled';
                        }
                        else{
                            html+='<i class="fas fa-times text-danger"></i>&nbsp;Not Confirm';
                        }
                        return html;     
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-outline-primary btn-sm mr-1 btnprint" data-toggle="tooltip" data-placement="bottom" title="Print Order" id="'+full['idtbl_porder']+'" ';if(full['confirmstatus']==0 | full['confirmstatus']==2){button+='disabled';}button+='><i class="fas fa-print"></i></button><button class="btn btn-outline-dark btn-sm mr-1 btncheque" data-toggle="tooltip" data-placement="bottom" title="Previous Bill Info" id="'+full['idtbl_porder']+'" ';if(full['confirmstatus']==0 | full['confirmstatus']==2){button+='disabled';}button+='><i class="fas fa-file-invoice-dollar"></i></button>';
                        if(full['idtbl_dispatch']!= null){button+='<button class="btn btn-outline-purple btn-sm mr-1 btnviewdispatch" data-toggle="tooltip" data-placement="bottom" title="View Dispatch" id="'+full['idtbl_dispatch']+'" ';if(full['confirmstatus']==0 | full['confirmstatus']==2){button+='disabled';}button+='><i class="far fa-file-pdf"></i></button>';}

                        button+='<button class="btn btn-outline-pink btn-sm mr-1 btncreatedispatch" data-toggle="tooltip" data-placement="bottom" title="';if(full['idtbl_dispatch']!= null){button+='Update Dispatch';}else{button+='Create Dispatch';}button+='" id="'+full['idtbl_porder']+'" ';if(full['confirmstatus']==0 | full['confirmstatus']==2){button+='disabled';}button+='><i class="fas fa-truck"></i></button>';

                        button+='<button class="btn btn-outline-primary btn-sm mr-1 btnotherinfo" data-toggle="tooltip" data-placement="bottom" title="Vehicle Info" id="'+full['idtbl_porder']+'" ';if(full['confirmstatus']==0 | full['confirmstatus']==2){button+='disabled';}
                        button+='><i class="fas fa-user-tag"></i></button><button class="btn btn-outline-dark btn-sm mr-1 btnView ';if(editcheck==0){button+='d-none';}button+='" data-toggle="tooltip" data-placement="bottom" title="View Order" id="'+full['idtbl_porder']+'"><i class="far fa-eye"></i></button>'; 
                        
                        if(full['confirmstatus']==1){button+='<button class="btn btn-outline-success btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-check"></i></button>';}
                        else{button+='<a href="process/statusporder.php?record='+full['idtbl_porder']+'&type=1" onclick="return order_confirm()" target="_self" class="btn btn-outline-orange btn-sm mr-1 ';if(statuscheck==0 | full['confirmstatus']==2){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';}

                        button+='<a href="process/statusporder.php?record='+full['idtbl_porder']+'&type=2" onclick="return delete_confirm()" target="_self" class="btn btn-outline-danger btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="far fa-trash-alt"></i></a>';
                        
                        return button;
                    }
                }
            ]
        } );
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm-dd'
        });
        // Prodcut part
        $('#producttype').change(function(){
            var typeID = $(this).val();
            if(typeID==6){
                $('.accessories').prop('readonly', false);
                $('.gasproduct').prop('readonly', true);
            }
            else{
                $('.accessories').prop('readonly', true);
                $('.gasproduct').prop('readonly', false);
            }
        });

        // $('.producttext').keyup(function(){
        //     var prodcut=$(this).attr('id');

        //     var data = JSON.parse(productprice);
        //     var res = data.find(({productid}) => productid == prodcut);
            
        //     var type=$('#producttype').val();

        //     if(type==1){
        //         var newprice=parseFloat(res.newprice);
        //         var qty=parseFloat($(this).val());

        //         var total=newprice*qty;
        //         $('#total'+prodcut).val(total);
        //     }
        // });

        //Trust return validation
        $("#reqty").keyup(function(){
            var qty = parseFloat($(this).val());
            if(qty>0){
                $('#trustqty').prop('readonly', true);
            }
            else{
                $('#trustqty').prop('readonly', false);
            }
        });
        $("#trustqty").keyup(function(){
            var qty = parseFloat($(this).val());
            if(qty>0){
                $('#reqty').prop('readonly', true);
            }
            else{
                $('#reqty').prop('readonly', false);
            }
        });
        //Safty return validation
        $("#saftyqty").keyup(function(){
            var qty = parseFloat($(this).val());
            if(qty>0){
                $('#saftyreturnqty').prop('readonly', true);
            }
            else{
                $('#saftyreturnqty').prop('readonly', false);
            }
        });
        $("#saftyreturnqty").keyup(function(){
            var qty = parseFloat($(this).val());
            if(qty>0){
                $('#saftyqty').prop('readonly', true);
            }
            else{
                $('#saftyqty').prop('readonly', false);
            }
        });

        // Order view part
        $('#dataTable tbody').on('click', '.btnView', function() {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    orderID: id
                },
                url: 'getprocess/getorderlistaccoorderid.php',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);

                    $('#divtotalview').html(obj.nettotalshow);                   
                    $('#remarkview').html(obj.remark);                   
                    $('#viewmodaltitle').html('Order No: PO-'+id);                   

                    var objfirst = obj.tablelist;
                    $.each(objfirst, function(i, item) {
                        //alert(objfirst[i].id);

                        $('#tableorderview > tbody:last').append('<tr><td>' + objfirst[i].productname + '</td><td class="d-none">' + objfirst[i].productid + '</td><td class="text-center">' + objfirst[i].refillqty + '</td><td class="text-center">' + objfirst[i].newqty + '</td><td class="text-center">' + objfirst[i].emptyqty + '</td><td class="text-center">' + objfirst[i].trustqty + '</td><td class="text-center">' + objfirst[i].returnqty + '</td><td class="text-center">' + objfirst[i].safetyqty + '</td><td class="text-center">' + objfirst[i].safetyreturnqty + '</td><td class="text-right total">' + objfirst[i].total + '</td></tr>');
                    });
                    $('#modalorderview').modal('show');
                }
            });
        });
        $('#modalorderview').on('hidden.bs.modal', function (e) {
            $('#tableorderview > tbody').html('');
        });
        // Order print part
        $('#dataTable tbody').on('click', '.btnprint', function() {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    orderID: id
                },
                url: 'getprocess/getorderprint.php',
                success: function(result) {
                    $('#viewdispatchprint').html(result);
                    $('#modalorderprint').modal('show');
                }
            });
        });
        document.getElementById('btnorderprint').addEventListener ("click", print);
        // Order cheque part
        $('#dataTable tbody').on('click', '.btncheque', function() {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {},
                url: 'getprocess/getlastweektotalaccocustomer.php',
                success: function(result) { //alert(result);
                    $('#tablelastbillshow').html(result);
                    $('#modalcheque').modal('show');
                }
            });
            
            $('#chequehideorderid').val(id);
        });

        // Create order part
            $('#btnordercreate').click(function () {
            $('#modalcreateorder').modal('show');
            $('#modalcreateorder').on('shown.bs.modal', function () {
                $('#orderdate').trigger('focus');

                $.ajax({
                    url: 'getprocess/get_products.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        var tableBody = $('#tableBody');
                        tableBody.empty();

                        tableBody.find('tr').each(function () {
                            updateTotalForRow($(this));
                        });

                        updateGrandTotal();

                        // var vatValue = parseFloat(data.vat);
                        var vatValue = (parseFloat(data.vat) || 0) + '%';

                        $.each(data, function (index, product) {

                            if (index === 'vat') return;

                            var newPriceWithVAT = calculatePriceWithVAT(product.newprice, vatValue);
                            var refillPriceWithVAT = calculatePriceWithVAT(product.refillprice, vatValue);
                            var emptyPriceWithVAT = calculatePriceWithVAT(product.emptyprice, vatValue);

                            var categoryClass = parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'accessory-row' : '';

                            tableBody.append('<tr class="' + categoryClass + '">' +
                                '<td>' + product.product_name + '</td>' +
                                '<td class="d-none">' + product.idtbl_product + '</td>' +
                                '<td class="d-none">' + product.newprice + '</td>' +
                                '<td class="d-none">0</td>' +
                                '<td class="d-none">' + product.refillprice + '</td>' +
                                '<td class="d-none">' + product.emptyprice + '</td>' +
                                '<td class="d-none">' + newPriceWithVAT + '</td>' +
                                '<td class="d-none">' + refillPriceWithVAT + '</td>' +
                                '<td class="d-none">' + emptyPriceWithVAT + '</td>' +
                                '<td class="text-center">' + addCommas(parseFloat(product.newprice).toFixed(2)) + '</td>' +
                                '<td class="text-center">' + addCommas(parseFloat(product.refillprice).toFixed(2)) + '</td>'+
                                '<td class="text-center">' + addCommas(parseFloat(product.emptyprice).toFixed(2)) + '</td>'+
                                '<td class="newpricewith_VAT text-center">' + addCommas(parseFloat(newPriceWithVAT).toFixed(2)) + '</td>' +
                                '<td class="refilpricewith_VAT text-center">' + addCommas(parseFloat(refillPriceWithVAT).toFixed(2)) + '</td>' +
                                '<td class="refilpricewith_VAT text-center">' + addCommas(parseFloat(emptyPriceWithVAT).toFixed(2)) + '</td>' +
                                '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width" name="new_quantity[]" value="0"></td>' +
                                '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width" name="refill_quantity[]" value="0" ' + (parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'readonly' : '') + '></td>' +
                                '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width" name="empty_quantity[]" value="0" ' + (parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'readonly' : '') + '></td>' +
                                '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width" name="trust_quantity[]" value="0" ' + (parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'readonly' : '') + '></td>' +
                                '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width" name="return_quantity[]" value="0" ' + (parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'readonly' : '') + '></td>' +
                                '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width" name="safty_quantity[]" value="0" ' + (parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'readonly' : '') + '></td>' +
                                '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width" name="safty_return_quantity[]" value="0" ' + (parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'readonly' : '') + '></td>' +
                                '<td class="text-center"><input type="text" class="form-control form-control-sm custom-width" name="vat_amount[]" id="vat_amount" value="' + vatValue + '" readonly></td>' +
                                '<td class="d-none hide-total-column"><input type="number" class="form-control form-control-sm custom-width" name="hidetotal_quantity[]" value="0"></td>' +
                                '<td class="text-right total-column"><input type="number" class="input-integer-decimal form-control form-control-sm custom-width" name="total_quantity[]" value="0" readonly></td>' +
                                '<td class="d-none hide-total-column-without-vat"><input type="number" class="form-control form-control-sm custom-width" name="hidetotal_quantity_without_VAT[]" value="0"></td>' +
                                '</tr>');

                                $('.input-integer').on('input', function() {
                                    var inputValue = $(this).val().replace(/\D/g, '');
                                    if (inputValue === '' || inputValue === '0') {
                                        $(this).val('');
                                    } else {
                                        $(this).val(inputValue);
                                    }
                                });

                                $('.input-integer').on('blur', function() {
                                    var inputValue = $(this).val().trim();
                                    if (inputValue === '') {
                                        $(this).val('0');
                                    }
                                });

                        });

                        $('.accessory-row').hide();

                        $(document).on('change', '.show-accessories-checkbox', function() {
                            var isChecked = $(this).prop('checked');
                            if (isChecked) {
                                $('.accessory-row').show();
                            } else {
                                $('.accessory-row').hide();
                            }
                        });
                    },
                    error: function (error) {
                        console.log('Error fetching products:', error);
                    }
                });
            });
        });

        $('#tableBody').on('input', 'input[name^="new_quantity"], input[name^="refill_quantity"], input[name^="empty_quantity"], input[name^="trust_quantity"], input[name^="return_quantity"], input[name^="safty_quantity"], input[name^="safty_return_quantity"]', function () {
            var row = $(this).closest('tr');
            updateTotalForRow(row);
            updateGrandTotal();
        });

        // Function to calculate the price with VAT
        function calculatePriceWithVAT(price, vatValue) {
            var vatPercentage = parseFloat(vatValue.replace('%', ''));
            var priceWithoutVAT = parseFloat(price);
            var priceWithVAT = priceWithoutVAT * (1 + vatPercentage / 100);
            return priceWithVAT;
        }

        function updateTotalForRow(row) {
            var newQuantity = parseFloat(row.find('input[name^="new_quantity"]').val()) || 0;
            var refillQuantity = parseFloat(row.find('input[name^="refill_quantity"]').val()) || 0;
            var emptyQuantity = parseFloat(row.find('input[name^="empty_quantity"]').val()) || 0;
            var trustQuantity = parseFloat(row.find('input[name^="trust_quantity"]').val()) || 0;
            var returnQuantity = parseFloat(row.find('input[name^="return_quantity"]').val()) || 0;
            var saftyQuantity = parseFloat(row.find('input[name^="safty_quantity"]').val()) || 0;
            var saftyReturnQuantity = parseFloat(row.find('input[name^="safty_return_quantity"]').val()) || 0;

            var unitPrice = parseFloat(row.find('td:eq(6)').text()) || 0;
            var refillPrice = parseFloat(row.find('td:eq(7)').text()) || 0;
            var emptyPrice = parseFloat(row.find('td:eq(8)').text()) || 0;

            var unitPricewithoutvat = parseFloat(row.find('td:eq(2)').text()) || 0;
            var refillPricewithoutvat = parseFloat(row.find('td:eq(4)').text()) || 0;
            var emptyPricewithoutvat = parseFloat(row.find('td:eq(5)').text()) || 0;


            var newTotal = newQuantity * unitPrice;
            var refillTotal = refillQuantity * refillPrice;
            var emptyTotal = emptyQuantity * emptyPrice;
            var trustTotal = trustQuantity * refillPrice;
            var returnTotal = returnQuantity * 0;
            var saftyTotal = saftyQuantity * refillPrice;
            var saftyReturnTotal = saftyReturnQuantity * 0;

            var newTotalwithoutvat = newQuantity * unitPricewithoutvat;
            var refillTotalwithoutvat = refillQuantity * refillPricewithoutvat;
            var emptyTotalwithoutvat = emptyQuantity * emptyPricewithoutvat;
            var trustTotalwithoutvat = trustQuantity * refillPricewithoutvat;
            var returnTotalwithoutvat = returnQuantity * 0;
            var saftyTotalwithoutvat = saftyQuantity * refillPricewithoutvat;
            var saftyReturnTotalwithoutvat = saftyReturnQuantity * 0;

            var totalColumn = row.find('td:eq(24)');
            var calculatedTotal = newTotal + refillTotal + emptyTotal + trustTotal + returnTotal + saftyTotal + saftyReturnTotal;
            var formattedTotal = calculatedTotal.toFixed(2);
            totalColumn.find('input[name^="total_quantity"]').val(formattedTotal);


            var hideTotalColumn = row.find('.hide-total-column');
            var calculatedTotal = newTotal + refillTotal + emptyTotal + trustTotal + returnTotal + saftyTotal + saftyReturnTotal;
            var formattedTotal = calculatedTotal.toFixed(5);
            hideTotalColumn.find('input[name^="hidetotal_quantity"]').val(formattedTotal);

            var hideTotalColumnwithoutvat = row.find('.hide-total-column-without-vat');
            var calculatedTotalwithoutvat = newTotalwithoutvat + refillTotalwithoutvat + emptyTotalwithoutvat + trustTotalwithoutvat + returnTotalwithoutvat + saftyTotalwithoutvat + saftyReturnTotalwithoutvat;
            var formattedTotalwithoutvat = calculatedTotalwithoutvat.toFixed(5);
            hideTotalColumnwithoutvat.find('input[name^="hidetotal_quantity_without_VAT"]').val(formattedTotalwithoutvat);

        }

        function updateGrandTotal() {
            var grandTotal = 0;
            var grandTotalwithoutvat = 0;

            $('#tableBody').find('input[name^="total_quantity"]').each(function () {
                var total = parseFloat($(this).val().replace(/,/g, '')) || 0;
                grandTotal += total;
            });

            $('#tableBody').find('input[name^="hidetotal_quantity_without_VAT"]').each(function () {
                var total = parseFloat($(this).val().replace(/,/g, '')) || 0;
                grandTotalwithoutvat += total;
            });

            $('#divtotal').text('Rs. ' + addCommas(grandTotal.toFixed(2)));
            $('#hidetotalorder').val(grandTotal);
            $('#hidetotalorderwithoutvat').val(grandTotalwithoutvat);

        }

        $('#btncreateorder').click(function () {
            // Collect data from the form and table
            var orderDate = $('#orderdate').val();
            var remark = $('#remark').val();
            var total = $('#hidetotalorder').val();
            var totalwithoutvat = $('#hidetotalorderwithoutvat').val();

            var orderDetails = [];
            $('#tableBody tr').each(function () {
            var productId = $(this).find('td:eq(1)').text();
            var unitpricewithoutvat = $(this).find('td:eq(2)').text();
            var refillpricewithoutvat = $(this).find('td:eq(4)').text();
            var emptypricewithoutvat = $(this).find('td:eq(5)').text();
            var unitprice = $(this).find('td:eq(6)').text();
            var refillprice = $(this).find('td:eq(7)').text();
            var emptyprice = $(this).find('td:eq(8)').text();
            var newQty = $(this).find('input[name^="new_quantity"]').val();
            var refillQty = $(this).find('input[name^="refill_quantity"]').val();
            var emptyQty = $(this).find('input[name^="empty_quantity"]').val();
            var trustQty = $(this).find('input[name^="trust_quantity"]').val();
            var returnQty = $(this).find('input[name^="return_quantity"]').val();
            var saftyQty = $(this).find('input[name^="safty_quantity"]').val();
            var saftyReturnQty = $(this).find('input[name^="safty_return_quantity"]').val();

            orderDetails.push({
                productId: productId,
                unitPricewithoutvat: unitpricewithoutvat,
                refillPricewithoutvat: refillpricewithoutvat,
                emptyPricewithoutvat: emptypricewithoutvat,
                unitPrice: unitprice,
                refillPrice: refillprice,
                emptyprice: emptyprice,
                newQty: newQty,
                refillQty: refillQty,
                emptyQty: emptyQty,
                trustQty: trustQty,
                returnQty: returnQty,
                saftyQty: saftyQty,
                saftyReturnQty: saftyReturnQty
            });
        });

            // Send data to the server
            $.ajax({
                url: 'process/porderprocess.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    orderdate: orderDate,
                    remark: remark,
                    total: total,
                    totalwithoutvat: totalwithoutvat,
                    orderDetails: orderDetails
                },
                success: function(result) {
                    $('#modalcreateorder').modal('hide');
                    action(JSON.stringify(result)); // Convert the object to a JSON-formatted string
                    // Optionally reload the page after a delay or user interaction
                    // setTimeout(function() { location.reload(); }, 2000); // Reload after 2 seconds
                    location.reload();

                }
            });
        });

       
        // Create cheque part
        $("#btnproceedcheque").click(function() {
            var tbody = $("#lastweektable tbody");

            if (tbody.children().length > 0) { 
                jsonObjDispatch = [];
                $("#lastweektable tbody tr").each(function() {
                    item = {}
                    $(this).find('td').each(function(col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObjDispatch.push(item);
                });
                // console.log(jsonObjDispatch);
                var tableData=jsonObjDispatch;
                var orderID=$('#chequehideorderid').val();
                var billtotal=$('#previousbilltotal').val();

                $.ajax({
                    type: "POST",
                    data: {
                        tableData: tableData,
                        orderID: orderID,
                        billtotal: billtotal
                    },
                    url: 'process/orderchequeprocess.php',
                    success: function(result) { //alert(result);
                        // console.log(result);
                        action(result);
                        $('#modalcheque').modal('hide');
                        location.reload();
                    }
                });
            }
        });   
        $('#modalcheque').on('hidden.bs.modal', function () {
            $('#lastweekbillcheck').prop('checked', false);
            $('.collapse').collapse('hide');
            $('#tablelastbillshow').html('');
        })
        // Vehicle detail part
        $('#dataTable tbody').on('click', '.btnotherinfo', function() {
            var id = $(this).attr('id');
            $('#otherhideorderid').val(id);
            $.ajax({
                type: "POST",
                data: {
                    orderID: id
                },
                url: 'getprocess/getorderdeliveryoption.php',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    
                    if(obj.id>0){
                        $('#recordID').val(obj.id);
                        $('#recordOption').val('2');
                        $('#lorrynum').val(obj.vehicleid);
                        $('#trailernum').val(obj.trailerid);
                        $('#scheduletime').val(obj.scheduletime);
                        if(obj.comlorrystatus==1){$('#companylorry1').prop('checked', true);}
                        else{$('#companylorry2').prop('checked', true)}
                        if(obj.dislorrystatus==1){$('#distributorlorry1').prop('checked', true);}
                        else{$('#distributorlorry2').prop('checked', true)}

                        $('#btnothersubmit').html('<i class="fas fa-plus"></i>&nbsp;Update Delivery');
                    }

                    $('#modalotherinfo').modal('show');
                }
            });
        });    
        $('#btnothersubmit').click(function(){
            if (!$("#otherform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#othersubmitBtn").click();
            } else { 
                var companylorry = $("input[name='companylorry']:checked").val();
                var distributorlorry = $("input[name='distributorlorry']:checked").val();
                var lorrynum = $('#lorrynum').val();
                var trailernum = $('#trailernum').val();
                var scheduletime = $('#scheduletime').val();
                var orderID = $('#otherhideorderid').val();
                var recordOption = $('#recordOption').val();
                var recordID = $('#recordID').val();

                $.ajax({
                    type: "POST",
                    data: {
                        companylorry: companylorry,
                        distributorlorry: distributorlorry,
                        lorrynum: lorrynum,
                        trailernum: trailernum,
                        scheduletime: scheduletime,
                        orderID: orderID,
                        recordOption: recordOption,
                        recordID: recordID
                    },
                    url: 'process/porderdeliveryprocess.php',
                    success: function(result) { //alert(result);
                        $('#modalotherinfo').modal('hide');
                        action(result);
                        $('#otherresetBtn').click();
                    }
                });
            }
        }); 

        // Dispatch part
        $('#dataTable tbody').on('click', '.btncreatedispatch', function() {
            $('#modalcreatedispatch').modal('show');
            $('#tbodydispatchcreate').empty().html('<tr><td colspan="14" class="text-center"><img src="images/spinner.gif"></td></tr>');

            var ponumber = $(this).attr('id');
            $('#hideorderid').val(ponumber);
            //Get Order detail for dispatch
            $.ajax({
                type: "POST",
                data: {
                    ponumber : ponumber
                },
                url: 'getprocess/getporderinfo.php',
                success: function(result) {//alert(result);
                    $('#tbodydispatchcreate').html(result);
                    tabletotal();
                }
            });
            //Check Vehicle information
            $.ajax({
                type: "POST",
                data: {
                    orderID: ponumber
                },
                url: 'getprocess/getorderdeliveryoption.php',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);

                    if(obj.id>0){
                        $('#hidelorrynum').val(obj.vehicleid);
                        $('#hidetrailernum').val(obj.trailerid);
                    }
                    else{
                        $('#hidelorrynum').val('0');
                        $('#hidetrailernum').val('0');
                    }
                }
            });
            //Check driver officer
            $.ajax({
                type: "POST",
                data: {
                    orderID: ponumber
                },
                url: 'getprocess/getdriverofficeraccodispatch.php',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);

                    if(obj.id>0){
                        $('#drivername').val(obj.driverid);
                        $('#officername').val(obj.officerid);

                        var helperlist = obj.helperid;
                        var helperlistoption = [];
                        $.each(helperlist, function(i, item) {
                            helperlistoption.push(helperlist[i].herlperID);
                        });

                        $('#helpername').val(helperlistoption);
                        $('#helpername').trigger('change');

                        $('#btncreatedispatch').html('<i class="fas fa-save"></i>&nbsp;Update Dispatch');
                    }
                }
            });
        });
        $('#btncreatedispatch').click(function(){
            var lorryno = $('#hidelorrynum').val();
            var trailerno = $('#hidetrailernum').val();
            var porderID = $('#hideorderid').val();

            if(lorryno!=0 && trailerno!=0){
                if (!$("#formdispatchdriverofficer")[0].checkValidity()) {
                    // If the form is invalid, submit it. The form won't actually submit;
                    // this will just cause the browser to display the native HTML5 error messages.
                    $("#driverofficersubmitBtn").click();
                } else {
                    jsonObjDispatch = [];
                    $("#tabledispatch tbody tr").each(function() {
                        item = {}
                        $(this).find('td').each(function(col_idx) {
                            item["col_" + (col_idx + 1)] = $(this).text();
                        });
                        jsonObjDispatch.push(item);
                    });
                    // console.log(jsonObjDispatch);

                    var total = $('#hidetotalorder').val();             
                    var lorrynum = $('#hidelorrynum').val();
                    var trailernum = $('#hidetrailernum').val();
                    var drivername = $('#drivername').val();
                    var officername = $('#officername').val();
                    var helpername = $('#helpername').val();

                    $.ajax({
                        type: "POST",
                        data: {
                            tableData: jsonObjDispatch,
                            total: total,
                            porderID: porderID,
                            lorryID: lorrynum,
                            trailerID: trailernum,
                            driverID: drivername,
                            officerID: officername,
                            helperID: helpername
                        },
                        url: 'process/dispatchprocess.php',
                        success: function(result) { //alert(result);
                            $('#modalcreatedispatch').modal('hide');
                            action(result);
                            location.reload();
                        }
                    });
                }
            }
            else{
                $('#warningdesc').html("Can't create dispatch, because firstly enter vehicle information. After create dispatch.")
                $('#warningModal').modal('show');
            }
        });
        $('#dataTable tbody').on('click', '.btnviewdispatch', function() {
            var dispatchID=$(this).attr('id');
            
            $.ajax({
                type: "POST",
                data: {
                    dispatchID : dispatchID
                },
                url: 'getprocess/getdispatchdetail.php',
                success: function(result) {//alert(result);
                    $('#dispatchprint').html(result);
                    $('#modaldispatchdetail').modal('show');
                }
            }); 
        });
        document.getElementById('btndispatchprint').addEventListener ("click", printdispatch);

        //Stock check
        $('#fillqty').keyup(function(){
            if($(this).val()!=''){
                var qty = parseFloat($(this).val());
            }
            else{
                var qty = parseFloat('0');
            }

            var productID = parseFloat($('#product').val());
            var typeID='1';
            var fieldID='fillqty';

            var stockstatus = checkstock(productID, qty, typeID, fieldID);
        });
        $('#reqty').keyup(function(){
            if($(this).val()!=''){
                var qty = parseFloat($(this).val());
            }
            else{
                var qty = parseFloat('0');
            }
            var productID = parseFloat($('#product').val());
            var typeID='2';
            var fieldID='reqty';

            var stockstatus = checkstock(productID, qty, typeID, fieldID);
        });
        $('#saftyreturnqty').keyup(function(){
            if($(this).val()!=''){
                var qty = parseFloat($(this).val());
            }
            else{
                var qty = parseFloat('0');
            }
            var productID = parseFloat($('#product').val());
            var typeID='3';
            var fieldID='saftyreturnqty';

            var stockstatus = checkstock(productID, qty, typeID, fieldID);
        });
    });

    function action(data) { //alert(data);
        var obj = JSON.parse(data);
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

    function print() {
        printJS({
            printable: 'viewdispatchprint',
            type: 'html',
            style: '@page { size: landscape; margin:0.25cm; }',
            targetStyles: ['*']
        })
    }

    function printdispatch() {
        printJS({
            printable: 'dispatchprint',
            type: 'html',
            style: '@page { size: landscape; margin:0.25cm; }',
            targetStyles: ['*']
        })
    }

    function chequeoption(orderID){
        $('#chequeinfotable tbody').on('click', '.btnchequeremove', function() {
            var r = confirm("Are you sure, You want to Remove this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        record: id,
                        type: '3'
                    },
                    url: 'process/statusordercheque.php',
                    success: function(result) { //alert(result);
                        loadcheckinfo(orderID)
                    }
                });
            }
        });
    }

    function tabletotal(){
        var sum = 0;
        $(".totaldispatch").each(function(){
            sum += parseFloat($(this).text());
        });
        
        var showsum = addCommas(parseFloat(sum).toFixed(2));

        $('#divtotaldispatch').html('Rs. '+showsum);
        $('#hidetotalorderdispatch').val(sum);
    }

    function order_confirm() {
        return confirm("Are you sure you want to Confirm this order?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }

    function datepickercloneload(){
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm-dd'
        });
    }

    function checkstock(productID, qty, typeID, fieldID){
        $.ajax({
            type: "POST",
            data: {
                productID: productID,
                qty: qty,
                typeID: typeID
            },
            url: 'getprocess/getstockqtyavailability.php',
            success: function(result) { //alert(result);
                if(result==1){
                    $('#'+fieldID).addClass('bg-danger text-white');
                    $("#formsubmit").prop('disabled', true);
                }
                else{
                    $('#'+fieldID).removeClass('bg-danger text-white');
                    $("#formsubmit").prop('disabled', false);
                }
            }
        });
    }

    function checkdayendprocess(){
        $.ajax({
            type: "POST",
            data: {
                
            },
            url: 'getprocess/getstatuslastdayendinfo.php',
            success: function(result) { //alert(result);
                if(result==1){
                    $('#viewmessage').html("Can't create anything, because today transaction is end");
                    $('#warningDayEndModal').modal('show');
                }
                else if(result==0){
                    $('#viewmessage').html("Can't create anythind, because yesterday day end process end not yet.");
                    $('#warningDayEndModal').modal('show');
                }
            }
        });
    }
</script>
<?php include "include/footer.php"; ?>
