<?php 
include "include/header.php";  

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
                            <div class="page-header-icon"><i class="fas fa-tasks"></i></div>
                            <span>Trust Confirmation</span>
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
                                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" id="btnordercreate"><i class="fas fa-plus"></i>&nbsp;Create Trust Confirmation</button>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Remark</th>
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
                <h5 class="modal-title" id="exampleModalLabel">CREATE TRUST CONFIRMATION</h5>
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
                                    <label class="small font-weight-bold text-dark">Date*</label>
                                    <div class="input-group input-group-sm">
                                        <input type="date" id="confirmationdate" name="confirmationdate"
                                            class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row mb-1" id="existing-customer-section">
                                <div class="col-3">
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
                                    <th class="text-center" style="width: 50px;">Qty</th>
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
                                Local Purchase</button>
                        </div>
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

<!-- Modal -->
<div id="purchaseview">
	<div class="modal fade" id="porderviewmodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
		aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">

			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="staticBackdropLabel">View Local Purchase Order</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div id="viewhtml"></div>
                    <div class="col-12 text-right">
                        <hr>
                        <button id="btnapprovereject" class="btn btn-primary btn-sm px-3 mb-2"><i class="fas fa-check mr-2"></i>Approve or Reject</button>
                        <input type="hidden" name="porderid" id="porderid">
                    </div>
                    <div class="col-12 text-center">
                        <div id="alertdiv"></div>
                    </div> 
				</div>
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

        $("#customer").select2({
            dropdownParent: $('#modalcreateorder'),

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

        checkdayendprocess();

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
                url: "scripts/localpurchaselist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        return 'LP-'+full['idtbl_local_purchase'];     
                    }
                },
                {
                    "data": "customer_name"
                },
                {
                    "data": "date"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return parseFloat(full['total']).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function(data, type, full) {
                        var html = '';
                        if(full['approvestatus']==1){
                            html+='<i class="fas fa-check text-success"></i>&nbsp;Approved';
                        }else if(full['approvestatus']==2){
                            html+='<i class="fas fa-times text-danger"></i>&nbsp;Rejected';
                        }
                        else{
                        }
                        return html;     
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button = '';

                        button += '<button data-toggle="tooltip" data-placement="bottom" title="View Local Purchase" class="btn btn-outline-dark btn-sm btnview mr-1" id="' + full[
                        'idtbl_local_purchase'] + '" aproval_id="' + full[
                        'approvestatus'] + '"><i class="fas fa-eye"></i></button>';

                        button += '<a href="process/statuslocalpurchase.php?record=' + full['idtbl_local_purchase'] + '&type=3" onclick="return delete_confirm()" target="_self" class="btn btn-outline-danger btn-sm mr-1 ';
                        if (full['approvestatus'] != 0) {
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

        $('#dataTable tbody').on('click', '.btnview', function () {
            var id = $(this).attr('id');
            $('#porderid').val(id);

            var approvestatus = $(this).attr('aproval_id');

            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: 'getprocess/localpurchaseview.php',
                success: function (result) { //alert(result);

                    $('#porderviewmodal').modal('show');
                    $('#viewhtml').html(result);

                    if (approvestatus > 0) {
                        $('#btnapprovereject').addClass('d-none').prop('disabled', true);
                        if (approvestatus == 1) {
                            $('#alertdiv').html('<div class="alert alert-success" role="alert"><i class="fas fa-check-circle mr-2"></i> Purchase Order approved</div>');
                        } else if (approvestatus == 2) {
                            $('#alertdiv').html('<div class="alert alert-danger" role="alert"><i class="fas fa-times-circle mr-2"></i> Purchase Order rejected</div>');
                        }
                    }
                }
            });

            $('#porderviewmodal').on('hidden.bs.modal', function (event) {
                $('#alertdiv').html('');
                $('#btnapprovereject').removeClass('d-none').prop('disabled', false);
            });
        });
        $('#btnapprovereject').click(function () {
            Swal.fire({
                title: "Do you want to approve this Purchase Order?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Approve",
                denyButtonText: `Reject`
            }).then((result) => {
                if (result.isConfirmed) {
                    var confirmnot = 1;
                    approvejob(confirmnot);
                } else if (result.isDenied) {
                    var confirmnot = 2;
                    approvejob(confirmnot);
                }
            });
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

                    $.each(data, function (index, product) {
                        if (index === 'vat') return;

                        var categoryClass = parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'accessory-row' : '';

                        tableBody.append('<tr class="' + categoryClass + '">' +
                            '<td>' + product.product_name + '</td>' +
                            '<td class="d-none">' + product.idtbl_product + '</td>' +
                            '<td><input type="text" class="input-integer-decimal form-control form-control-sm custom-width full-price-input" name="full_price[]" value="0"></td>' +
                            '<td><input type="text" class="input-integer-decimal form-control form-control-sm custom-width empty-price-input" name="empty_price[]" value="0" ' + (parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'readonly' : '') + '></td>' +
                            '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width full-qty-input" name="full_quantity[]" value="0"></td>' +
                            '<td class="text-center"><input type="text" class="input-integer form-control form-control-sm custom-width empty-qty-input" name="empty_quantity[]" value="0" ' + (parseInt(product.tbl_product_category_idtbl_product_category) === 2 ? 'readonly' : '') + '></td>' +
                            '<td class="text-right d-none"><input type="number" class="input-integer-decimal form-control form-control-sm custom-width" name="total_quantity[]" value="0" readonly></td>' +
                            '<td class="text-right total-display">0.00</td>' +
                            '</tr>');
                    });

                    initializeInputHandlers();
                    $('.accessory-row').hide();
                },
                error: function (error) {
                    console.log('Error fetching products:', error);
                }
            });
        });
    });

    function initializeInputHandlers() {
        $('.input-integer').on('input', function() {
            var inputValue = $(this).val().replace(/\D/g, '');
            $(this).val(inputValue === '' ? '' : inputValue);
        }).on('blur', function() {
            if ($(this).val() === '') $(this).val('0');
        });

        $('.input-integer-decimal').on('input', function() {
            var inputValue = $(this).val().replace(/[^0-9.]/g, '');
            inputValue = inputValue.replace(/(\..*)\./g, '$1');
            $(this).val(inputValue);
        }).on('blur', function() {
            if ($(this).val() === '') $(this).val('0');
        });

        $('#tableBody').on('input', '.full-price-input, .empty-price-input, .full-qty-input, .empty-qty-input', function() {
            updateRowTotals($(this).closest('tr'));
        });
    }

    function updateRowTotals(row) {
        var fullPrice = parseFloat(row.find('.full-price-input').val()) || 0;
        var fullQty = parseFloat(row.find('.full-qty-input').val()) || 0;
        
        var emptyPrice = parseFloat(row.find('.empty-price-input').val()) || 0;
        var emptyQty = parseFloat(row.find('.empty-qty-input').val()) || 0;

        var fullTotal = fullPrice * fullQty;
        var emptyTotal = emptyPrice * emptyQty;
        var rowTotal = fullTotal + emptyTotal;

        // Update hidden numeric field (for form submission)
        row.find('input[name^="total_quantity"]').val(rowTotal.toFixed(2));
        
        // Update visible display field with commas
        row.find('.total-display').text(addCommas(rowTotal.toFixed(2)));

        updateGrandTotals();
    }

    function updateGrandTotals() {
        var grandTotal = 0;

        $('#tableBody').find('input[name^="total_quantity"]').each(function () {
            grandTotal += parseFloat($(this).val()) || 0;
        });

        $('#divtotal').text('Rs. ' + addCommas(grandTotal.toFixed(2)));
        $('#hidetotalorder').val(grandTotal.toFixed(2));
    }

        $('#btncreateorder').click(function () {
            var confirmationdate = $('#confirmationdate').val();
            var customerType = $('input[name="customer_type"]:checked').val();
            var remark = $('#remark').val();
            var total = $('#hidetotalorder').val();

            var orderDetails = [];
            $('#tableBody tr').each(function () {
                var row = $(this);
                var productId = row.find('td:eq(1)').text();
                var fullpricewithoutvat = row.find('.full-price-input').val();
                var emptypricewithoutvat = row.find('.empty-price-input').val();
                var fullQty = row.find('.full-qty-input').val();
                var emptyQty = row.find('.empty-qty-input').val();

                orderDetails.push({
                    productId: productId,
                    fullpricewithoutvat: fullpricewithoutvat,
                    emptypricewithoutvat: emptypricewithoutvat,
                    fullQty: fullQty,
                    emptyQty: emptyQty
                });
            });

            // Prepare the data object
            var data = {
                confirmationdate: confirmationdate,
                remark: remark,
                total: total,
                orderDetails: JSON.stringify(orderDetails),
                customer_type: customerType
            };

            // Add customer data based on type
            if (customerType === 'existing') {
                data.customer = $('#customer').val();
            } else {
                data.new_customer_name = $('#new_customer_name').val();
                data.new_customer_phone = $('#new_customer_phone').val();
                data.new_customer_address = $('#new_customer_address').val();
            }

            $.ajax({
                url: 'process/localpurchaseprocess.php',
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function (result) {
                    $('#modalcreateorder').modal('hide');
                    action(JSON.stringify(result));
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    alert("Error saving order. Please check console for details.");
                }
            });
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

    function approvejob(confirmnot) {
        Swal.fire({
            title: '',
            html: '<div class="div-spinner"><div class="custom-loader"></div></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            backdrop: 'rgba(255, 255, 255, 0.5)',
            customClass: {
                popup: 'fullscreen-swal'
            },
            didOpen: () => {
                document.body.style.overflow = 'hidden';

                $.ajax({
                    type: "POST",
                    data: {
                        porderid: $('#porderid').val(),
                        confirmnot: confirmnot
                    },
                    url: 'process/localpurchaseapproveprocess.php',
                    dataType: 'json', // Expect JSON response
                    success: function (response) {
                        Swal.close();
                        document.body.style.overflow = 'auto';

                        if (response.status == 1) {
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: response.action.title,
                                text: response.action.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload the page or update UI as needed
                                location.reload();
                            });
                        } else {
                            // Show error message
                            Swal.fire({
                                icon: 'error',
                                title: response.action.title,
                                text: response.action.message
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.close();
                        document.body.style.overflow = 'auto';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again later.'
                        });
                    }
                });
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
