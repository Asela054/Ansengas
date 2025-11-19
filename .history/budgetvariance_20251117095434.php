<?php 
include "include/header.php";  

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
                            <span>37.5Kg Variance For Budget</span>
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

        // Filtor part start
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
                    url: 'getprocess/getvarianceinfo.php',
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
