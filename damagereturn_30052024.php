<?php 
include "include/header.php";  

$sqlarea="SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1 ORDER BY `area` ASC";
$resultarea =$conn-> query($sqlarea);

$sqlemployee="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=7 ORDER BY `name` ASC";
$resultemployee =$conn-> query($sqlemployee);

$sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `status`=1 ORDER BY `vehicleno` ASC";
$resultvehicle =$conn-> query($sqlvehicle);

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct);

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
                            <div class="page-header-icon"><i data-feather="corner-down-left"></i></div>
                            <span>Damage Return</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/damagereturnprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <input type="hidden" class="form-control form-control-sm"
                                            name="hiddencustomerid" id="hiddencustomerid">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="new" name="customerform"
                                                class="custom-control-input" value="1" checked>
                                            <label class="custom-control-label" for="new">New Customer</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline ml-5">
                                            <input type="radio" id="exist" name="customerform"
                                                class="custom-control-input" value="2">
                                            <label class="custom-control-label" for="exist">Exist Customer</label>
                                        </div>
                                    </div>
                                    <div id="existCustomerForm" style="display:none;">
                                        <div class="form-group mb-1 mt-3">
                                            <label class="small font-weight-bold text-dark">Customer*</label><br>
                                            <select name="customer" id="customer" class="form-control form-control-sm"
                                                style="width:100%;">
                                                <option value="">Select</option>
                                                <?php if($resultcustomer->num_rows > 0) {while ($rowcustomer = $resultcustomer-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowcustomer['idtbl_customer'] ?>">
                                                    <?php echo $rowcustomer['name'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="newCustomerForm">
                                        <div class="form-row mb-1 mt-3">
                                            <div class="col">
                                                <label class="small font-weight-bold text-dark">Name</label>
                                                <input type="text" class="form-control form-control-sm" name="name"
                                                    id="name">
                                            </div>
                                            <div class="col">
                                                <label class="small font-weight-bold text-dark">Contact No.</label>
                                                <input type="text" class="form-control form-control-sm" name="phone"
                                                    id="phone">
                                            </div>
                                        </div>
                                        <div class="form-row mb-1">
                                            <div class="col">
                                                <label class="small font-weight-bold text-dark">Area*</label>
                                                <select name="area" id="area" class="form-control form-control-sm">
                                                    <option value="">Select</option>
                                                    <?php if($resultarea->num_rows > 0) {while ($rowarea = $resultarea-> fetch_assoc()) { ?>
                                                    <option value="<?php echo $rowarea['idtbl_area'] ?>">
                                                        <?php echo $rowarea['area'] ?></option>
                                                    <?php }} ?>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label class="small font-weight-bold text-dark">Address</label>
                                                <textarea type="text" class="form-control form-control-sm"
                                                    name="address" id="address"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Executive Name*</label>
                                            <select name="salesrep" id="salesrep" class="form-control form-control-sm"
                                                required>
                                                <option value="">Select</option>
                                                <?php if($resultemployee->num_rows > 0) {while ($rowemployee = $resultemployee-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowemployee['idtbl_employee'] ?>">
                                                    <?php echo $rowemployee['name'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Vehicle*</label>
                                            <select name="vehicle" id="vehicle" class="form-control form-control-sm"
                                                required>
                                                <option value="">Select</option>
                                                <?php if($resultvehicle->num_rows > 0) {while ($rowvehicle = $resultvehicle-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowvehicle['idtbl_vehicle'] ?>">
                                                    <?php echo $rowvehicle['vehicleno'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1 mt-3">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Reference Number</label>
                                            <input type="text" class="form-control form-control-sm" name="ref_num"
                                                id="ref_num">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Serial Number</label>
                                            <input type="text" class="form-control form-control-sm" name="srl_num"
                                                id="srl_num">
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Return Type*</label>
                                        <select name="returntype" id="returntype" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select</option>
                                            <option value="1">Gas Leak</option>
                                            <option value="2">Tank Damage</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Product*</label>
                                        <select name="product" id="product" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select</option>
                                            <?php if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowproduct['idtbl_product'] ?>">
                                                <?php echo $rowproduct['product_name'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Qty*</label>
                                        <input type="text" class="form-control form-control-sm" name="qty" id="qty"
                                            required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="submit" id="submitBtn"
                                            class="btn btn-outline-primary btn-sm px-4 fa-pull-right"
                                            <?php if($addcheck==0){echo 'disabled';} ?>><i
                                                class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-9">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Customer</th>
                                                <th>Product</th>
                                                <th>Qty</th>
                                                <th>Send Company</th>
                                                <th>Back Wharehouse</th>
                                                <th>Return Customer</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
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
<!-- Modal Company Send -->
<div class="modal fade" id="companymodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel"><i class="fas fa-sign-out-alt"></i> SEND TO COMPANY</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="addcompanyform" autocomplete="off">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold">Date</label>
                                <input type="date" class="form-control form-control-sm" name="date_company" id="date_company">
                                <input type="hidden" class="form-control form-control-sm" id="hiddenid1" name="hiddenid1">
                            </div>
                            <div class="form-group mt-2 text-right">
                                <button type="submit" id="submitBtnCompany" class="btn btn-primary btn-sm px-4"><i
                                        class="far fa-save"></i>&nbsp;Add</button>
                                        <input type="submit" class="d-none" id="hidesubmitcompany" value="">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Back Warehouse -->
<div class="modal fade" id="warehousemodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel"><i class="fas fa-warehouse"></i> BACK TO WAREHOUSE</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="addwarehouseform" autocomplete="off">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold">Date</label>
                                <input type="date" class="form-control form-control-sm" name="date_warehouse" id="date_warehouse">
                                <input type="hidden" class="form-control form-control-sm" id="hiddenid2" name="hiddenid2">
                            </div>
                            <div class="form-group mt-2 text-right">
                                <button type="submit" id="submitBtnWhouse" class="btn btn-primary btn-sm px-4"><i
                                        class="far fa-save"></i>&nbsp;Add</button>
                                        <input type="submit" class="d-none" id="hidesubmitwhouse" value="">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Return Customer -->
<div class="modal fade" id="customermodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel"><i class="fas fa-user"></i> RETURN TO CUSTOMER</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="addcustomerform" autocomplete="off">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold">Date</label>
                                <input type="date" class="form-control form-control-sm" name="date_customer" id="date_customer">
                                <input type="hidden" class="form-control form-control-sm" id="hiddenid3" name="hiddenid3">
                            </div>
                            <div class="form-group mt-2 text-right">
                                <button type="submit" id="submitBtnCustomer" class="btn btn-primary btn-sm px-4"><i
                                        class="far fa-save"></i>&nbsp;Add</button>
                                        <input type="submit" class="d-none" id="hidesubmitcustomer" value="">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
        document.addEventListener("DOMContentLoaded", function () {
        var newRadio = document.getElementById("new");
        var existRadio = document.getElementById("exist");
        var newCustomerForm = document.getElementById("newCustomerForm");
        var existCustomerForm = document.getElementById("existCustomerForm");

        newRadio.addEventListener("change", function () {
            if (this.checked) {
                newCustomerForm.style.display = "block";
                existCustomerForm.style.display = "none";
            }
        });

        existRadio.addEventListener("change", function () {
            if (this.checked) {
                newCustomerForm.style.display = "none";
                existCustomerForm.style.display = "block";
            }
        });
    });
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('body').tooltip({
            selector: '[data-toggle="tooltip"]'
        });
        $('[data-toggle="tooltip"]').tooltip({
            trigger : 'hover'
        });

        $('#dataTable').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/damagereturnlist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_damage_return"
                },
                {
                    "data": "returndate"
                },
                {
                    "data": "name"
                },
                {
                    "data": "product_name"
                },
                {
                    "data": "qty"
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['comsendstatus']==1){
                            return full['comsenddate'];
                        }
                        else{
                            return '';
                        }
                    }
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['backstockstatus']==1){
                            return full['backstockdate'];
                        }
                        else{
                            return '';
                        }
                    }
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['returncusstatus']==1){
                            return full['returncusdate'];
                        }
                        else{
                            return '';
                        }
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-outline-orange btn-sm btnsendcompany mr-1" id="'+full['idtbl_damage_return']+'" data-toggle="tooltip" data-placement="bottom" title="Send to company"><i class="fas fa-sign-out-alt"></i></button>';
                        button+='<button class="btn btn-outline-pink btn-sm btnbackwarehouse mr-1" id="'+full['idtbl_damage_return']+'" data-toggle="tooltip" data-placement="bottom" title="Back to warehouse"><i class="fas fa-warehouse"></i></button>';
                        button+='<button class="btn btn-outline-purple btn-sm btnreturncutomer mr-1" id="'+full['idtbl_damage_return']+'" data-toggle="tooltip" data-placement="bottom" title="Return to customer"><i class="fas fa-user"></i></button>';
                        button+='<button class="btn btn-outline-primary btn-sm btnEdit mr-1 ';if(editcheck==0){button+='d-none';}button+='" id="'+full['idtbl_damage_return']+'"><i class="fas fa-pen"></i></button>';
                        if(full['status']==1){
                        button+='<a href="process/statusdamagereturn.php?record='+full['idtbl_damage_return']+'&type=2" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-check"></i></a>';
                        }else{
                        button+='<a href="process/statusdamagereturn.php?record='+full['idtbl_damage_return']+'&type=1" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';
                        }
                        button+='<a href="process/statusdamagereturn.php?record='+full['idtbl_damage_return']+'&type=3" onclick="return delete_confirm()" target="_self" class="btn btn-outline-danger btn-sm ';if(deletecheck==0){button+='d-none';}button+='"><i class="far fa-trash-alt"></i></a>';                   
                        return button;
                    }
                }
            ]
        } );
        $('#dataTable tbody').on('click', '.btnEdit', function() {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getdamagereturn.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#customer').val(obj.customer);                       
                        $('#returntype').val(obj.returntype);                       
                        $('#product').val(obj.product);                       
                        $('#qty').val(obj.qty);        
                        $('#srl_num').val(obj.seriel_no);  
                        $('#ref_num').val(obj.reference_no);                 

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
        $('#dataTable tbody').on('click', '.btnsendcompany', function() {
                var id = $(this).attr('id');
                $("#hiddenid1").val(id);

                $('#companymodal').modal('show');

        });
        $('#dataTable tbody').on('click', '.btnbackwarehouse', function() {
                var id = $(this).attr('id');
                $("#hiddenid2").val(id);

                $('#warehousemodal').modal('show');

        });
        $('#dataTable tbody').on('click', '.btnreturncutomer', function() {
                var id = $(this).attr('id');
                $("#hiddenid3").val(id);

                $('#customermodal').modal('show');

        });
        $('#submitBtnCompany').click(function(){
            if (!$("#addcompanyform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmitcompany").click();
            } else {   
                var date_company = $('#date_company').val();
                var hiddenID = $('#hiddenid1').val();

                $.ajax({
                    type: "POST",
                    data: {
                        date_company: date_company,
                        hiddenID: hiddenID

                    },
                    url: 'process/companysendprocess.php',
                    success: function(result) { //alert(result);
                        action(result);
                    }
                });
            }
        });
        $('#submitBtnWhouse').click(function(){
            if (!$("#addwarehouseform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmitwhouse").click();
            } else {   
                var date_warehouse = $('#date_warehouse').val();
                var hiddenID = $('#hiddenid2').val();


                $.ajax({
                    type: "POST",
                    data: {
                        date_warehouse: date_warehouse,
                        hiddenID: hiddenID

                    },
                    url: 'process/backwarehouseprocess.php',
                    success: function(result) { //alert(result);
                        action(result);
                    }
                });
            }
        });
        $('#submitBtnCustomer').click(function(){
            if (!$("#addcustomerform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmitcustomer").click();
            } else {   
                var date_customer = $('#date_customer').val();
                var hiddenID = $('#hiddenid3').val();


                $.ajax({
                    type: "POST",
                    data: {
                        date_customer: date_customer,
                        hiddenID: hiddenID
                    },
                    url: 'process/returncustomerprocess.php',
                    success: function(result) { //alert(result);
                        action(result);
                    }
                });
            }
        });
        $("#customer").select2({
            ajax: {
                url: 'getprocess/getcustomerlist.php',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term 
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        $('#customer').change(function(){
            var customer = $(this).val();
            $('#hiddencustomerid').val(customer);   

        });
    });

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }

    function company_confirm() {
        return confirm("Are you sure this product send to company?");
    }

    function warehouse_confirm() {
        return confirm("Are you sure this product back to warehouse?");
    }

    function customer_confirm() {
        return confirm("Are you sure this product breturn back to customer?");
    }
</script>
<?php include "include/footer.php"; ?>
