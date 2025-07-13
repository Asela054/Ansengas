<?php 
include "include/header.php";  

$sqlorder="SELECT `idtbl_porder` FROM `tbl_porder` WHERE `status`=1 AND `confirmstatus`=1 ORDER BY `idtbl_porder` DESC";
$resultorder =$conn-> query($sqlorder); 

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1 ORDER BY `orderlevel`";
$resultproduct =$conn-> query($sqlproduct); 

include "include/topnavbar.php"; 
?>
<style>
    .tableprint {
        table-layout: fixed;
    }
    .porder-modal {
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
                            <div class="page-header-icon"><i class="fas fa-sticky-note"></i></div>
                            <span>Credit Note</span>
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
                                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" id="btnordercreate"><i class="fas fa-plus"></i>&nbsp;Create Credit Note</button>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Credit Note</th>
                                            <th>Purchasing Order</th>
                                            <th>Date</th>
                                            <th>User</th>
                                            <th class="text-right">Nettotal</th>
                                            <th>Status</th>
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
    <div class="modal-dialog modal-dialog-centered modal-xl porder-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">CREATE CREDIT NOTE</h5>
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
                                    <label class="small font-weight-bold text-dark">Purchase Order*</label>
                                    <select name="ponumber" id="ponumber" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php if($resultorder->num_rows > 0) {while ($roworder = $resultorder-> fetch_assoc()) { ?>
                                        <option value="<?php echo $roworder['idtbl_porder'] ?>"> 
                                            <?php echo 'PO-'.$roworder['idtbl_porder'] ?></option>
                                        <?php }} ?>
                                    </select>
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
                                    <!-- <th class="d-none" style="width: 100px;">Newprice</th> -->
                                    <!-- <th class="d-none" style="width: 100px;">Refillprice</th> -->
                                    <th class="d-none" style="width: 100px;">Emptyprice</th>
                                    <!-- <th class="d-none" style="width: 100px;">Saleprice VAT</th> -->
                                    <!-- <th class="d-none" style="width: 100px;">Refillprice VAT</th> -->
                                    <th class="d-none" style="width: 100px;">Emptyprice VAT</th>
                                    <!-- <th class="text-center" style="width: 50px;">New Price</th> -->
                                    <!-- <th class="text-center" style="width: 50px;">Refill Price</th> -->
                                    <th class="text-center" style="width: 50px;">Empty Price</th>
                                    <!-- <th class="text-center" style="width: 50px;">New Price +(VAT)</th> -->
                                    <!-- <th class="text-center" style="width: 50px;">Refill Price +(VAT)</th> -->
                                    <th class="text-center" style="width: 50px;">Empty Price +(VAT)</th>
                                    <!-- <th class="text-center" style="width: 50px;">New</th>
                                    <th class="text-center" style="width: 50px;">Refill</th> -->
                                    <th class="text-center" style="width: 50px;">Empty</th>
                                    <!-- <th class="text-center" style="width: 50px;">Trust</th>
                                    <th class="text-center" style="width: 50px;">Trust Return</th>
                                    <th class="text-center" style="width: 50px;">Safty</th>
                                    <th class="text-center" style="width: 50px;">Safty Return</th> -->
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
                                Credit Note</button>
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
                            <th class="text-center">Empty Qty</th>
                            <th class="text-center">Balance Qty</th>
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
<!-- Modal Update Stock -->
<div class="modal fade" id="modalupdatestock" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6 class="modal-title" id="viewupdatemodaltitle"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="process/updatestock.php" method="post" autocomplete="off">
                <input type="hidden" class="form-control form-control-sm" name="hiddencreditnoteId" id="hiddencreditnoteId" required>
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold text-dark">Product*</label>
                        <select class="form-control form-control-sm" name="product" id="product" required>
                            <option value="">Select</option>
                            <?php if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                            <option value="<?php echo $rowproduct['idtbl_product'] ?>">
                                <?php echo $rowproduct['product_name'] ?></option>
                            <?php }} ?>
                        </select>
                    </div>
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold text-dark">Qty*</label>
                        <input type="text" class="form-control form-control-sm" name="qty" id="qty" required>
                    </div>
                    <div class="form-group mb-1">
                        <label class="small font-weight-bold text-dark">Balance Qty* <span id="pendingbalanceqty"
                                class="text-danger font-weight-bold"></span></label>
                        <input type="text" class="form-control form-control-sm" name="balanceqty" id="balanceqty" value="0">
                    </div>
                    <div class="form-group mt-2">
                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm px-4 fa-pull-right"
                            <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                    </div>
                </form>
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

        $('#balanceqty').on('focus', function () {
            if ($(this).val() === '0') {
                $(this).select();
            }
        });

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
                url: "scripts/creditnotelist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        return 'CN-'+full['idtbl_credit_note'];     
                    }
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        return 'PO-'+full['tbl_porder_idtbl_porder'];     
                    }
                },
                {
                    "data": "date"
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
                        if(full['sendstatus']==1){
                            html+='<i class="fas fa-check text-success"></i>&nbsp;Transferred to stock';
                        }
                        else{
                        }
                        return html;     
                    }
                },
                {
                    "targets": -1, // Assuming the last column is where you want to generate buttons
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button = '';

                        button += '<button class="btn btn-outline-success btn-sm mr-1 btnUpdateStock ';
                        if (editcheck == 0) {
                            button += 'd-none';
                        }
                        button += '" data-toggle="tooltip" data-placement="bottom" title="Update Stock" id="' + full['idtbl_credit_note'] + '"><i class="fas fa-paper-plane"></i></button>';

                        button += '<button class="btn btn-outline-dark btn-sm mr-1 btnView ';
                        if (editcheck == 0) {
                            button += 'd-none';
                        }
                        button += '" data-toggle="tooltip" data-placement="bottom" title="View Order" id="' + full['idtbl_credit_note'] + '"><i class="far fa-eye"></i></button>';

                        button += '<a href="process/statuscreditnote.php?record=' + full['idtbl_credit_note'] + '&type=3" onclick="return delete_confirm()" target="_self" class="btn btn-outline-danger btn-sm mr-1 ';
                        if (statuscheck == 0) {
                            button += 'd-none';
                        }
                        button += '"><i class="far fa-trash-alt"></i></a>';

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

        $('#dataTable tbody').on('click', '.btnUpdateStock', function() {
            var id = $(this).attr('id');
            var id = $(this).attr('id');
            $('#modalupdatestock').modal('show');
            $('#viewupdatemodaltitle').html('Credit Note: CN-'+id);       
            $('#hiddencreditnoteId').val(id);                               
        });

        $('#product').change(function(){
            var productID = $(this).val();
            var hiddenID = $('#hiddencreditnoteId').val();

            $.ajax({
                type: "POST",
                data: {
                    productID: productID,
                    hiddenID: hiddenID
                },
                url: 'getprocess/getbalanceqty.php',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#pendingbalanceqty').html(obj.balanceqty);
                }
            });   
        });

        // Order view part
        $('#dataTable tbody').on('click', '.btnView', function() {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    orderID: id
                },
                url: 'getprocess/getorderlistaccocreditnote.php',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);

                    $('#divtotalview').html(obj.nettotalshow);                   
                    $('#remarkview').html(obj.remark);                   
                    $('#viewmodaltitle').html('Credit Note: CN-'+id);                   

                    var objfirst = obj.tablelist;
                    $.each(objfirst, function(i, item) {
                        //alert(objfirst[i].id);

                    $('#tableorderview > tbody:last').append('<tr><td>' + objfirst[i].productname + '</td><td class="d-none">' + objfirst[i].productid + '</td><td class="text-center">' + objfirst[i].emptyqty + '</td><td class="text-center">' + objfirst[i].balanceqty + '</td><td class="text-right total">' + objfirst[i].total + '</td></tr>');
                    });
                    $('#modalorderview').modal('show');
                }
            });
        });
        $('#modalorderview').on('hidden.bs.modal', function (e) {
            $('#tableorderview > tbody').html('');
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
                                '<td class="d-none">0</td>' +
                                '<td class="d-none">' + product.emptyprice + '</td>' +
                                '<td class="d-none">' + emptyPriceWithVAT + '</td>' +
                                '<td class="text-center">' + addCommas(parseFloat(product.emptyprice).toFixed(2)) + '</td>'+
                                '<td class="refilpricewith_VAT text-center">' + addCommas(parseFloat(emptyPriceWithVAT).toFixed(2)) + '</td>' +
                                '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width" name="empty_quantity[]" value="0" ' + (parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'readonly' : '') + '></td>' +
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

        $('#tableBody').on('input', 'input[name^="empty_quantity"]', function () {
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
            var emptyQuantity = parseFloat(row.find('input[name^="empty_quantity"]').val()) || 0;

            var emptyPrice = parseFloat(row.find('td:eq(4)').text()) || 0;

            var emptyPricewithoutvat = parseFloat(row.find('td:eq(3)').text()) || 0;


            var emptyTotal = emptyQuantity * emptyPrice;

            var emptyTotalwithoutvat = emptyQuantity * emptyPricewithoutvat;

            var totalColumn = row.find('td:eq(10)');
            var calculatedTotal = emptyTotal;
            var formattedTotal = calculatedTotal.toFixed(2);
            totalColumn.find('input[name^="total_quantity"]').val(formattedTotal);


            var hideTotalColumn = row.find('.hide-total-column');
            var calculatedTotal = emptyTotal;
            var formattedTotal = calculatedTotal.toFixed(5);
            hideTotalColumn.find('input[name^="hidetotal_quantity"]').val(formattedTotal);

            var hideTotalColumnwithoutvat = row.find('.hide-total-column-without-vat');
            var calculatedTotalwithoutvat = emptyTotalwithoutvat;
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
            var porderID = $('#ponumber').val();

            var orderDetails = [];
            $('#tableBody tr').each(function () {
            var productId = $(this).find('td:eq(1)').text();
            var emptypricewithoutvat = $(this).find('td:eq(3)').text();
            var emptyprice = $(this).find('td:eq(4)').text();
            var emptyQty = $(this).find('input[name^="empty_quantity"]').val();

            orderDetails.push({
                productId: productId,
                emptyPricewithoutvat: emptypricewithoutvat,
                emptyprice: emptyprice,
                emptyQty: emptyQty
            });
        });

            // Send data to the server
            $.ajax({
                url: 'process/creditnoteprocess.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    orderdate: orderDate,
                    porderID: porderID,
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


    function order_confirm() {
        return confirm("Are you sure you want to Confirm this order?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }

    function update_confirm() {
        return confirm("Are you sure you want to update stock?");
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
