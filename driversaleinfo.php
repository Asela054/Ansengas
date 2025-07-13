<?php 
include "include/header.php";  

$sqldriver="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=4 ORDER BY `name` ASC";
$resultdriver =$conn-> query($sqldriver);


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
                            <span>Driver Sale Report</span>
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
                                            <label class="small font-weight-bold text-dark">Driver</label><br>
                                            <select class="form-control form-control-sm rounded-1" name="driver"
                                                    id="driver" required>
                                                    <option value="">Select driver</option>
                                                    <?php if($resultdriver->num_rows > 0) {while ($rowdriver = $resultdriver-> fetch_assoc()) { ?>
                                                    <option value="<?php echo $rowdriver['idtbl_employee'] ?>">
                                                        <?php echo $rowdriver['name']; ?></option>
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

        $('#formSearchBtn').click(function(){
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {   
                var validfrom = $('#fromdate').val();
                var driver = $('#driver').val();

                $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        driver: driver,
                        validfrom: validfrom
                    },
                    url: 'getprocess/getsalereportaccodriver.php',
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
