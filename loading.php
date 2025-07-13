<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_dispatch` WHERE `status`=1";
$result=$conn->query($sql);

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 

$sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=0 AND `status`=1";
$resultvehicle =$conn-> query($sqlvehicle); 

$sqldiverlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=4 AND `status`=1";
$resultdiverlist =$conn-> query($sqldiverlist);

$sqlofficerlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=6 AND `status`=1";
$resultofficerlist =$conn-> query($sqlofficerlist);

$sqlreflist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=7 AND `status`=1";
$resultreflist =$conn-> query($sqlreflist);

$sqlarealist="SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1";
$resultarealist =$conn-> query($sqlarealist);

$sqlhelperist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=5 AND `status`=1";
$resulthelperist =$conn-> query($sqlhelperist);

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
                            <div class="page-header-icon"><i class="fas fa-truck-loading"></i></div>
                            <span>Vehicle Loading</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" id="btnordercreate"><i class="fas fa-plus"></i>&nbsp;Create Vehicle Load</button>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped table-bordered table-sm" id="loadview">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Load No</th>
                                            <th>Date</th>
                                            <th>Vehicle</th>
                                            <th>Executive Name</th>
                                            <th>Area</th>
                                            <th>&nbsp;</th>
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
<!-- Modal Dispatch Create -->
<div class="modal fade" id="modalcreatedispatch" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Create Vehicle Dispatch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="formdispatch" autocomplete="off">
                            <div class="form-row mb-1">
                            <div class="col">
                                <label class="small font-weight-bold text-dark">Date*</label>
                                <div class="input-group input-group-sm">
                                    <input type="date" id="date" name="date" class="form-control form-control-sm"
                                        value="<?php echo date('Y-m-d') ?>" required>
                                </div>
                            </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Area*</label>
                                    <select name="area" id="area" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php if($resultarealist->num_rows > 0) {while ($rowarealist = $resultarealist-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowarealist['idtbl_area'] ?>"><?php echo $rowarealist['area'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Lorry No*</label>
                                    <select name="lorrynum" id="lorrynum" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php if($resultvehicle->num_rows > 0) {while ($rowvehicle = $resultvehicle-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowvehicle['idtbl_vehicle'] ?>"><?php echo $rowvehicle['vehicleno'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>                                  
                            </div>
                            <div class="form-row mb-1">
                            <div class="col">
                                    <label class="small font-weight-bold text-dark">Driver*</label>
                                    <select name="drivername" id="drivername" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php if($resultdiverlist->num_rows > 0) {while ($rowdiverlist = $resultdiverlist-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowdiverlist['idtbl_employee'] ?>"><?php echo $rowdiverlist['name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div> 
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Officer*</label>
                                    <select name="officername" id="officername" class="form-control form-control-sm"
                                        required>
                                        <option value="">Select</option>
                                        <?php if($resultofficerlist->num_rows > 0) {while ($rowofficerlist = $resultofficerlist-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowofficerlist['idtbl_employee'] ?>">
                                            <?php echo $rowofficerlist['name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Helper Name*</label><br>
                                    <select name="helpername[]" id="helpername" class="form-control form-control-sm"
                                        style="width:100%;" multiple required>
                                        <?php if($resulthelperist->num_rows > 0) {while ($rowhelperist = $resulthelperist-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowhelperist['idtbl_employee'] ?>">
                                            <?php echo $rowhelperist['name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col-4">
                                    <label class="small font-weight-bold text-dark">Executive Name*</label>
                                    <select name="refname" id="refname" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php if($resultreflist->num_rows > 0) {while ($rowreflist = $resultreflist-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowreflist['idtbl_employee'] ?>">
                                            <?php echo $rowreflist['name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                            </div>
                        </form>

                        <table class="table table-striped table-bordered table-sm small mt-5" id="tableorder">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Product</th>
                                    <th class="d-none" style="width: 100px;">ProductID</th>
                                    <th class="text-center" style="width: 50px;">Qty</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody"></tbody>
                        </table>
                        <div class="form-group mt-2">
                        <div class="row">
                            <div class="input-group input-group-sm ml-3">
                                <input type="checkbox" class="show-accessories-checkbox">&nbsp;Show Accessories
                            </div>
                        </div>
                            <hr>
                            <button type="button" id="btncreatedispatch" class="btn btn-outline-primary btn-sm fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Create Dispatch</button>
                        </div>
                        <div class="form-group mt-3 text-danger small">
                            <span class="badge badge-danger mr-2">&nbsp;&nbsp;</span> Stock quantity warning
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Load -->
<div class="modal fade" id="modaldispatchdetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewdispatchprint"></div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Load print -->
<div class="modal fade" id="modalloadprint" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewloadprint"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm fa-pull-right" id="btnloadprint"><i class="fas fa-print"></i>&nbsp;Print Order</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Warning -->
<div class="modal fade" id="warningModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body bg-danger text-white text-center">
                <div id="warningdesc"></div>
            </div>
            <div class="modal-footer bg-danger rounded-0">
                <button type="button" class="btn btn-outline-light btn-sm w-100" data-dismiss="modal">Close</button>
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
<!-- Modal Load print -->
<div class="modal fade" id="modalspecialcustomer" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="process/vehicleloadspecialcustomer.php" method="post">
                    <!-- <div class="form-group mb-1">
                        <label class="small font-weight-bold text-dark">Invoice Date*</label><br>
                        <input type="date" name="invdate" id="invdate" class="form-control form-control-sm" style="width: 100%;" required />
                    </div> -->
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold text-dark">Customer*</label><br>
                        <select name="specialcustomer[]" id="specialcustomer" class="form-control form-control-sm" style="width: 100%;" multiple required></select>
                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary btn-sm fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Add Special List</button>
                    </div>
                    <input type="hidden" name="vehicleloadid" id="vehicleloadid">
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        checkdayendprocess();
        $("#helpername").select2();

        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#loadview').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/loadinglist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_vehicle_load"
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        return 'VL-'+full['idtbl_vehicle_load'];     
                    }
                },
                {
                    "data": "date"
                },
                {
                    "data": "vehicleno"
                },
                {
                    "data": "name"
                },
                {
                    "data": "area"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-dark btn-sm mr-1 btnspecialcustomer" data-toggle="tooltip" data-placement="bottom" title="Add Special Customer" id="'+full['idtbl_vehicle_load']+'" ';if(full['approvestatus']==0){button+='disabled';}button+='><i class="fas fa-user-check"></i></button>';

                        if(full['veiwallcustomerstatus']==1){button+='<button class="btn btn-outline-success btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-users"></i></button>';}
                        else{button+='<a href="process/statusloading.php?record='+full['idtbl_vehicle_load']+'&type=2" onclick="return allcustomer_confirm()" target="_self" class="btn btn-outline-orange btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-users"></i></a>';}

                        button+='<button class="btn btn-outline-primary btn-sm mr-1 btnprint" data-toggle="tooltip" data-placement="bottom" title="Print Order" id="'+full['idtbl_vehicle_load']+'" ';if(full['approvestatus']==0){button+='disabled';}button+='><i class="fas fa-print"></i></button>';

                        if(full['approvestatus']==1){button+='<button class="btn btn-outline-success btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-check"></i></button>';}
                        else{button+='<a href="process/statusloading.php?record='+full['idtbl_vehicle_load']+'&type=1" onclick="return order_confirm()" target="_self" class="btn btn-outline-orange btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';}

                        button+='<button class="btn btn-outline-dark btn-sm mr-1 btnloadview" data-toggle="tooltip" data-placement="bottom" title="Print Order" id="'+full['idtbl_vehicle_load']+'" ><i class="far fa-eye"></i></button>';
                        
                        return button;
                    }
                }
            ]
        } );
        $('#loadview tbody').on('click', '.btnloadview', function() {
            var loadID=$(this).attr('id');

            $.ajax({
                type: "POST",
                data: {
                    loadID : loadID
                },
                url: 'getprocess/getloaddetail.php',
                success: function(result) {//alert(result);
                    $('#viewdispatchprint').html(result);
                    $('#modaldispatchdetail').modal('show');
                }
            }); 
        });

        $('#btnordercreate').click(function () {
            $('#modalcreatedispatch').modal('show');
            $('#modalcreatedispatch').on('shown.bs.modal', function () {
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

                        $.each(data, function (index, product) {
                            if (product.idtbl_product !== undefined) {

                                var categoryClass = parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'accessory-row' : '';

                                var row = $('<tr class="' + categoryClass + '">' +
                                    '<td>' + product.product_name + '</td>' +
                                    '<td class="d-none">' + product.idtbl_product + '</td>' +
                                    '<td class="text-center"><input type="number" class="form-control form-control-sm custom-width stock-input" name="new_quantity[]" value="0"></td>' +
                                    '</tr>');

                                $('.stock-input').on('blur', function () {
                                    if ($(this).val().trim() === '') {
                                        $(this).val('0');
                                    }
                                });

                                tableBody.append(row);

                                var stockInput = row.find('.stock-input');

                                stockInput.on('keyup', function () {
                                    var enteredQty = $(this).val();
                                    var productId = product.idtbl_product;

                                    $.ajax({
                                        url: 'getprocess/get_available_qty.php',
                                        type: 'GET',
                                        dataType: 'json',
                                        data: {
                                            productId: productId
                                        },
                                        success: function (response) {
                                            if (response.hasOwnProperty('avaqty')) {
                                                var availableQty = response.avaqty;

                                                if (parseInt(enteredQty) > parseInt(availableQty)) {
                                                    stockInput.removeClass('is-valid');
                                                    stockInput.addClass('is-invalid');

                                                    $('#btncreatedispatch').prop('disabled', true);
                                                } else {
                                                    stockInput.removeClass('is-invalid');
                                                    stockInput.addClass('is-valid');
                                                    $('#btncreatedispatch').prop('disabled', false);
                                                }
                                            } else {
                                                console.log('Error: Unexpected response format');
                                            }
                                        },
                                        error: function (error) {
                                            console.log('Error fetching available quantity:', error);
                                        }
                                    });
                                });
                            }
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

        $('#modalcreatedispatch').on('hidden.bs.modal', function (e) {
            $('#tabledispatch > tbody').html('');
            $('#product').val('');
            $('#area').val('');
            $('#lorrynum').val('');
            $('#drivername').val('');
            $('#officername').val('');
            $('#refname').val('');
            $('#qty').val('0');
        });

        $('#btncreatedispatch').click(function () {
            // Collect data from the form and table
            var lorrynum = $('#lorrynum').val();
            var drivername = $('#drivername').val();
            var officername = $('#officername').val();
            var refname = $('#refname').val();
            var area = $('#area').val();
            var date = $('#date').val();
            var helpername = $('#helpername').val();

            var orderDetails = [];
            $('#tableBody tr').each(function () {
                var productId = $(this).find('td:eq(1)').text();
                var newQty = $(this).find('input[name^="new_quantity"]').val();

                orderDetails.push({
                    productId: productId,
                    newQty: newQty
                });
            });

            // Send data to the server
            $.ajax({
                url: 'process/loadingprocess.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    lorryID: lorrynum,
                    driverID: drivername,
                    officerID: officername,
                    refID: refname,
                    areaID: area,
                    date: date,
                    helpername:helpername,
                    orderDetails: orderDetails
                },
                success: function(result) {
                    $('#modalcreatedispatch').modal('hide');
                    action(JSON.stringify(result)); // Convert the object to a JSON-formatted string
                    // Optionally reload the page after a delay or user interaction
                    // setTimeout(function() { location.reload(); }, 2000); // Reload after 2 seconds
                    location.reload();

                }
            });
        });

        $('#tabledispatch').on( 'click', 'tr', function () {
            var r = confirm("Are you sure, You want to remove this product ? ");
            if (r == true) {
                $(this).closest('tr').remove();

                $('#product').focus();
            }
        });
        $('#lorrynum').change(function(){
            var lorryID = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    lorryID : lorryID
                },
                url: 'getprocess/getvehicleavaloadinginfo.php',
                success: function(result) {//alert(result);
                    if(result==1){
                        $('#warningdesc').html("Can't create vehicle loading, please unload the last loading this vehicle.")
                        $('#warningModal').modal('show');
                        $('#formsubmit').prop('disabled', true);
                    }
                }
            }); 
        });
        //Stock check
        $('#qty').keyup(function(){
            if($(this).val()!='0'){
                var qty = parseFloat($(this).val());
            }
            else{
                var qty = parseFloat('0');
            }
            
            var productID = parseFloat($('#product').val());
            var typeID='4';
            var fieldID='qty';

            var stockstatus = checkstock(productID, qty, typeID, fieldID);
        });
        $('#product').change(function(){
            $('#qty').focus();
            $('#qty').select();
        });

        //Print Option
        $('#loadview tbody').on('click', '.btnprint', function() {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    loadID: id
                },
                url: 'getprocess/getvehicleloadingprint.php',
                success: function(result) {
                    $('#viewloadprint').html(result);
                    $('#modalloadprint').modal('show');
                }
            });
        });
        document.getElementById('btnloadprint').addEventListener ("click", print);

        //Special Customer Add to Route
        $('#loadview tbody').on('click', '.btnspecialcustomer', function() {
            var loadID=$(this).attr('id');
            $('#vehicleloadid').val(loadID);
            $('#modalspecialcustomer').modal('show');

            $.ajax({
                url: 'getprocess/getspecialcustomerlistaccoload.php',
                type: 'POST',
                data: {
                    recordID: loadID
                },
                success: function(result) {//console.log(result);
                    var objfirst = JSON.parse(result);
					var html = '';
                    var loadate='';
                    var customerlist = [];
					$.each(objfirst, function(i, item) {
						//alert(objfirst[i].id);
						html += '<option value="' + objfirst[i].idtbl_customer + '">';
						html += objfirst[i].name;
						html += '</option>';

                        customerlist.push(objfirst[i].idtbl_customer);

                        loaddate=objfirst[i].date;
					});
                    $("#specialcustomer").empty().append(html).val(customerlist).trigger('change');
                    // $('#invdate').val(loaddate);
                }
            });
            
            $("#specialcustomer").select2({
                dropdownParent: $('#modalspecialcustomer'),
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
        });
    });

    function tabletotal(){
        var sum = 0;
        $(".total").each(function(){
            sum += parseFloat($(this).text());
        });
        
        var showsum = addCommas(parseFloat(sum).toFixed(2));

        $('#divtotal').html('Rs. '+showsum);
        $('#hidetotalorder').val(sum);
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
    function order_confirm() {
        return confirm("Are you sure you want to Confirm this loading?");
    }
    function allcustomer_confirm() {
        return confirm("Are you sure you want to show all customers this loading?");
    }
    function print() {
        printJS({
            printable: 'viewloadprint',
            type: 'html',
            style: '@page { size: landscape; margin:0.25cm; }',
            targetStyles: ['*']
        })
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
