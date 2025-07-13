<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_vehicle` WHERE `status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`!=1";
$resultbank =$conn-> query($sqlbank); 

$sqlbank2="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`!=1";
$resultbank2 =$conn-> query($sqlbank2); 

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
                            <span>Invoice Reimbursement</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12 text-right">
                            <button type="button" class="btn btn-primary btn-sm px-3" data-toggle="modal" data-target="#reimbursementmodal">Create Reimbursement</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap small" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Reimbursement No</th>
                                                <th>Customer</th>
                                                <th>Invoice No</th>
                                                <th class="text-right">Amount</th>
                                                <th class="text-right">Actions</th>
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
<!--Issue Payment Receipt Modal-->
<div class="modal fade" id="reimbursementmodal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header p-0 p-2">
                <h5 class="modal-title" id="oLevelTitle">Create Reimbursement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="form-row">
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Date</label>
                                <input type="date" class="form-control form-control-sm" id="invoicedate">
                            </div>
                            <div class="col-4">
                                <label class="small font-weight-bold text-dark">Customer</label>
                                <select class="form-control form-control-sm" style="width: 100%;" name="customer" id="customer">
                                    <option value="">Select</option>
                                    <?php if($resultcustomer->num_rows > 0) {while ($rowcustomer = $resultcustomer-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowcustomer['idtbl_customer'] ?>"><?php echo $rowcustomer['name']; ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="small font-weight-bold text-dark">Reimbursement Doc No</label>
                                <input type="text" class="form-control form-control-sm" id="reimno">
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="selectAll">
                            <label class="custom-control-label font-weight-bold" for="selectAll">Select All</label>
                        </div>
                        <table class="table table-striped table-bordered table-sm small" id="reimTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Customer</th>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th class="text-right">Invoice Amount</th>
                                    <th class="text-right">Reimbursement Amount</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5"></th>
                                    <th class="text-right">0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer px-0">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right">
                        <button class="btn btn-primary btn-sm" id="btnIssueReimbursement" disabled><i class="fas fa-plus"></i>&nbsp;Create Reimbursement</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#dataTable').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/invoicereimbursementlist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_invoice_reimbursement"
                },
                {
                    "data": "date"
                },
                {
                    "data": "reimdocno"
                },
                {
                    "data": "customer_name"
                },
                {
                    "data": "invoiceno"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var payment=addCommas(parseFloat(full['amount']).toFixed(2));
                        return payment;
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        if(deletecheck==1){
                            button+='<button type="button" data-url="process/statusinvoicereimbursement.php?record='+full['idtbl_invoice_reimbursement']+'&type=3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
                        }
                        
                        return button;
                    }
                }
            ],
            "rowCallback": function(row, data) {
                // Highlight the entire row with background danger if status is 3
                if (data.status == 3) {
                    $(row).addClass('bg-danger text-white');
                }
            }
        } );

        // Filtor part start
        $("#customer").select2({
            dropdownParent: $('#reimbursementmodal'),
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
        $('#invoicedate').change(function(){
            if($(this).val()!=''){
                loadDiscountInvoice();
            }
        });
        $('#customer').change(function(){
            if($(this).val()!=''){
                loadDiscountInvoice();
            }
        });
        // Filtor part end

        $('#selectAll').click(function (e) {
            $('#reimTable').closest('table').find('td input:checkbox').prop('checked', this.checked);
            calculateTotal();
        });
        $('#btnIssueReimbursement').click(function(){
            var tablelist = $("#reimTable tbody input[type=checkbox]:checked");
            var reimno =  $('#reimno').val();
                
            if(tablelist.length>0 && reimno!=''){
                jsonObj = [];
                tablelist.each(function() {
                    item = {}
                    var row = $(this).closest("tr");
                    item["invoiceid"] = $(this).data('invoiceid');
                    item["discountamount"] = $(this).data('discountamount');
                    item["customerid"] = $(this).data('customer');
                    jsonObj.push(item);
                });
                var myJSON = JSON.stringify(jsonObj);

                Swal.fire({
                    title: '',
                    html: '<div class="div-spinner"><div class="custom-loader"></div></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false, // Hide the OK button
                    backdrop: `
                        rgba(255, 255, 255, 0.5) 
                    `,
                    customClass: {
                        popup: 'fullscreen-swal'
                    },
                    didOpen: () => {
                        document.body.style.overflow = 'hidden';

                        $.ajax({
                            type: "POST",
                            data: {
                                invoicelist : myJSON,
                                reimno : reimno
                            },
                            url: 'process/invoicereimbursementprocess.php',
                            success: function(result) {
                                Swal.close();
                                var obj = JSON.parse(result);
                                if (obj.status == 1) {
                                    actionreload(obj.action);
                                }
                                else{
                                    action(obj.action);
                                }
                            },
                            error: function(error) {
                                // Close the SweetAlert on error
                                Swal.close();
                                
                                // Show an error alert
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Something went wrong. Please try again later.'
                                });
                            }
                        });

                        document.body.style.overflow = 'visible';
                    }
                });
            }
            else{
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please fill the reimbursement document no.'
                });
            }
        });
    });

    function loadDiscountInvoice(){
        var invoicedate = $('#invoicedate').val();
        var customerID = $('#customer').val();

        Swal.fire({
            title: '',
            html: '<div class="div-spinner"><div class="custom-loader"></div></div>',
            allowOutsideClick: false,
            showConfirmButton: false, // Hide the OK button
            backdrop: `
                rgba(255, 255, 255, 0.5) 
            `,
            customClass: {
                popup: 'fullscreen-swal'
            },
            didOpen: () => {
                document.body.style.overflow = 'hidden';

                $.ajax({
                    type: "POST",
                    data: {
                        invoicedate : invoicedate,
                        customerID : customerID
                    },
                    url: 'getprocess/getreimbursementinfo.php',
                    success: function(result) {
                        Swal.close();
                        
                        $('#reimTable > tbody').html(result);
                        $('#reimTable tbody input[type="checkbox"]').on('change', calculateTotal);
                        $('#btnIssueReimbursement').prop('disabled', false);
                    },
                    error: function(error) {
                        // Close the SweetAlert on error
                        Swal.close();
                        
                        // Show an error alert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again later.'
                        });
                    }
                });

                document.body.style.overflow = 'visible';
            }
        });
    }
    function calculateTotal() {
        let total = 0;

        $('#reimTable tbody input[type="checkbox"]:checked').each(function () {
            let amountText = $(this).closest('tr').find('td:last').text().trim();
            let amount = parseFloat(amountText.replace(/,/g, '')) || 0;
            total += amount;
        });
        
        $('#reimTable > tfoot th:last').text(addCommas(total.toFixed(2)));
    }
    function addCommas(nStr) {
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
</script>
<?php include "include/footer.php"; ?>
