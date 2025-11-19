<?php 
include "include/header.php";  

$sql = "SELECT c.`idtbl_customer`, c.`name` FROM `tbl_customer` c INNER JOIN `tbl_customer_product_special` cs ON c.`idtbl_customer` = cs.`tbl_customer_idtbl_customer` WHERE c.`status` = 1 GROUP BY c.`idtbl_customer`, c.`name` ORDER BY c.`name` ASC";

$resultcustomer = $conn->query($sql);

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
                            <span>Invoice Special Discount</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="form-row">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Date</label>
                                        <input type="date" class="form-control form-control-sm" id="invoicedate">
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Customer</label>
                                        <select class="form-control form-control-sm" style="width: 100%;" name="customer" id="customer">
                                            <option value="">Select</option>
                                            <?php if($resultcustomer->num_rows > 0) {while ($rowcustomer = $resultcustomer-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowcustomer['idtbl_customer'] ?>"><?php echo $rowcustomer['name']; ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="selectAll">
                                    <label class="custom-control-label font-weight-bold" for="selectAll">Select
                                        All</label>
                                </div>
                                <table class="table table-striped table-bordered table-sm small" id="reimTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Customer</th>
                                            <th>Invoice No</th>
                                            <th>Date</th>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th class="text-right">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right">
                                <button class="btn btn-primary btn-sm" id="btnIssueDiscount" disabled><i
                                        class="fas fa-plus"></i>&nbsp;Create Special Discount</button>
                                <input type="hidden" name="discounttotal" id="discounttotal" value="0">
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
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var payment=addCommas(parseFloat(full['netamount']).toFixed(2));
                        return payment;
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button += '<button class="btn btn-dark btn-sm btnView mr-1" id="' + full['idtbl_invoice_reimbursement'] + '"><i class="fas fa-eye"></i></button>';
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
        $('#dataTable tbody').on('click', '.btnView', function() {
            var id = $(this).attr('id');

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
                            reimbursementid : id,
                        },
                        url: 'getprocess/getreimbursementdata.php',
                        success: function(result) {
                            Swal.close();
                            
                            $('#divview').html(result);
                            $('#reimbursementviewmodal').modal('show');
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
        });

        // Filtor part start
        $("#customer").select2();
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
        $('#btnIssueDiscount').click(function(){
            var tablelist = $("#reimTable tbody input[type=checkbox]:checked");
                
            if(tablelist.length>0){
                jsonObj = [];
                tablelist.each(function() {
                    item = {}
                    var row = $(this).closest("tr");
                    item["invoiceid"] = $(this).data('invoiceid');
                    item["productid"] = $(this).data('productid');
                    item["customerid"] = $(this).data('customer');
                    item["price"] = $(this).data('price');
                    item["total"] = $(this).data('total');
                    item["qty"] = row.find('td:eq(5)').text(); 
                    jsonObj.push(item);
                });
                var myJSON = JSON.stringify(jsonObj);
                var totalreimbursement=$('#discounttotal').val();

                Swal.fire({
                    title: '',
                    html: '<div class="div-spinner"><div class="custom-loader"></div></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
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
                                totalreimbursement : totalreimbursement
                            },
                            url: 'process/invoicespecialdiscountprocess.php',
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
                                Swal.close();
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
                    text: 'Please select at least one record.'
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
                    url: 'getprocess/getinvoiceinfo.php',
                    success: function(result) {
                        Swal.close();
                        
                        $('#reimTable > tbody').html(result);
                        $('#reimTable tbody input[type="checkbox"]').on('change', calculateTotal);
                        $('#btnIssueDiscount').prop('disabled', false);
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
        let checkedCount = 0;
        
        $('#reimTable tbody input[type="checkbox"]:checked').each(function() {
            const rowTotal = parseFloat($(this).data('total')) || 0;
            total += rowTotal;
            checkedCount++;
        });
        
        $('#discounttotal').val(total.toFixed(2));
        
        // Enable/disable button based on checked items
        if(checkedCount > 0) {
            $('#btnIssueDiscount').prop('disabled', false);
        } else {
            $('#btnIssueDiscount').prop('disabled', true);
        }
    }

    // Initialize the event listener when page loads
    $(document).ready(function() {
        $('#reimTable tbody').on('change', 'input[type="checkbox"]', calculateTotal);
        
        // Select all functionality
        $('#selectAll').change(function() {
            const isChecked = $(this).prop('checked');
            $('#reimTable tbody input[type="checkbox"]').prop('checked', isChecked).trigger('change');
        });
    });
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
