<?php 
include "include/header.php";  

$sqlemployee="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=7 ORDER BY `name` ASC";
$resultemployee =$conn-> query($sqlemployee);

$sqlarea="SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1 ORDER BY `area` ASC";
$resultarea =$conn-> query($sqlarea);

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
                            <span>Executive Sale Report</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <form id="searchform">
                                <div class="form-row">
                                        <div class="col-2">
                                        <label class="small font-weight-bold text-dark">Date*</label>
                                        <input type="date" id="fromdate" name="fromdate"
                                                    class="form-control form-control-sm"
                                                    value="<?php echo date('Y-m-d') ?>" required>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-checkbox">
                                            <label class="small font-weight-bold text-dark">Executive</label><br>
                                            <select class="form-control form-control-sm rounded-1" name="employee"
                                                    id="employee" required>
                                                    <option value="">Executive</option>
                                                    <?php if($resultemployee->num_rows > 0) {while ($rowemployee = $resultemployee-> fetch_assoc()) { ?>
                                                    <option value="<?php echo $rowemployee['idtbl_employee'] ?>">
                                                        <?php echo $rowemployee['name']; ?></option>
                                                    <?php }} ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-checkbox">
                                            <label class="small font-weight-bold text-dark">Report Type</label><br>
                                            <select class="form-control form-control-sm rounded-1" name="type"
                                                    id="type">
                                                    <option value="">Select Type</option>
                                                    <option value="1">Customerwise</option>
                                                    <option value="2">Areawise</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2" id="customerDropdown" style="display: none;">
                                            <div class="custom-control custom-checkbox">
                                                <label class="small font-weight-bold text-dark">Customer</label><br>
                                                <select class="form-control form-control-sm" name="customer" id="customer" style="width:100%">
                                                    <option value="">Select Customer</option>
                                                    <?php if($resultcustomer->num_rows > 0) {while ($rowcustomer = $resultcustomer-> fetch_assoc()) { ?>
                                                    <option value="<?php echo $rowcustomer['idtbl_customer'] ?>"><?php echo $rowcustomer['name']; ?></option>
                                                    <?php }} ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-2" id="areaDropdown" style="display: none;">
                                            <div class="custom-control custom-checkbox">
                                                <label class="small font-weight-bold text-dark">Area</label><br>
                                                <select class="form-control form-control-sm rounded-1" name="area" id="area">
                                                    <option value="">Select Area</option>
                                                    <?php if($resultarea->num_rows > 0) {while ($rowarea = $resultarea-> fetch_assoc()) { ?>
                                                    <option value="<?php echo $rowarea['idtbl_area'] ?>">
                                                        <?php echo $rowarea['area']; ?></option>
                                                    <?php }} ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                            <button class="btn btn-outline-dark btn-sm rounded-0 px-4" type="button"
                                                id="formSearchBtn"><i class="fas fa-search"></i>&nbsp;Search</button>
                                        </div>
                                    </div>
                                    <input type="submit" class="d-none" id="hidesubmit">
                                </form>
                            </div>
                            <div class="col-12">
                                <hr class="border-dark">
                                <div id="targetviewdetail"></div>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<!-- Modal Invoice detail Load -->
<div class="modal fade" id="modalinvoicelist" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h6 class="title-style small"><span>View Invoice</span></h6>
                        <div id="viewinvoicedetail"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    document.getElementById('type').addEventListener('change', function() {
        var customerDropdown = document.getElementById('customerDropdown');
        var areaDropdown = document.getElementById('areaDropdown');
        
        if (this.value === '1') {
            customerDropdown.style.display = 'block';
            areaDropdown.style.display = 'none';
        } else if (this.value === '2') {
            customerDropdown.style.display = 'none';
            areaDropdown.style.display = 'block';
        } else {
            customerDropdown.style.display = 'none';
            areaDropdown.style.display = 'none';
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('.dpd1a').datepicker('remove');
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            format: 'yyyy-mm',
            endDate: 'today',
            viewMode: "months", 
            minViewMode: "months"
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

        $('#formSearchBtn').click(function(){
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {   
                var validfrom = $('#fromdate').val();
                var employee = $('#employee').val();
                var customer = $('#customer').val();
                var area = $('#area').val();

                $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        employee: employee,
                        customer: customer,
                        area: area,
                        validfrom: validfrom
                    },
                    url: 'getprocess/getsalereportaccosaleref.php',
                    success: function(result) {//alert(result);
                        $('#targetviewdetail').html(result);
                        invoiceviewoption();
                    }
                });
            }
        });
    });

    function invoiceviewoption(){
        $('#tableoutstanding tbody').on('click', '.viewbtninv', function() {
            var invID = $(this).attr('id');

            $('#viewinvoicedetail').html('<div class="text-center"><img src="images/spinner.gif"></div>');
            $('#modalinvoicelist').modal('show');

            $.ajax({
                type: "POST",
                data: {
                    invID : invID
                },
                url: 'getprocess/getissueinvoiceinfo.php',
                success: function(result) {//alert(result);
                    $('#viewinvoicedetail').html(result);
                }
            });
        });
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

</script>
<?php include "include/footer.php"; ?>
