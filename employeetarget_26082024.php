<?php 
include "include/header.php";  

$sqlemployee="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=7 ORDER BY `name` ASC";
$resultemployee =$conn-> query($sqlemployee);

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
                            <span>Executive Target Report</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-6">
                                <form id="searchform">
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">From*</label>
                                            <input type="date" class="form-control form-control-sm" placeholder=""
                                                name="fromdate" id="fromdate">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">To*</label>
                                            <input type="date" class="form-control form-control-sm" placeholder=""
                                                name="todate" id="todate">
                                        </div>
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Executive</label>
                                            <select class="form-control form-control-sm" name="employee" id="employee">
                                                <option value="">All Employee</option>
                                                <?php if($resultemployee->num_rows > 0) {while ($rowemployee = $resultemployee-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowemployee['idtbl_employee'] ?>"><?php echo $rowemployee['name']; ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                            <button class="btn btn-outline-dark btn-sm px-4" type="button" id="formSearchBtn"><i class="fas fa-search"></i>&nbsp;Search</button>
                                        </div>
                                    </div>
                                    <input type="submit" class="d-none" id="hidesubmit">
                                </form>
                            </div>
                            <div class="col-12">
                                <hr class="border-dark">
                                <form action="export/exportemployeetarget.php" method="post" id="convert_form">
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            <input type="hidden" name="file_content" id="file_content">
                                            <button type="button" id="btnconvert" class="btn btn-success btn-sm px-4 mb-3" disabled><i class="fas fa-file-excel mr-2"></i>Excel</button>  
                                        </div>
                                    </div>
                                    <div id="targetviewdetail"></div>    
                                </form>                          
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
        $('#formSearchBtn').click(function(){
            $('#btnconvert').prop('disabled', true);
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {   
                var validfrom = $('#fromdate').val();
                var validto = $('#todate').val();
                var employee = $('#employee').val();

                $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        validfrom: validfrom,
                        validto: validto,
                        employee: employee
                    },
                    url: 'getprocess/getemployeetargetreport.php',
                    success: function(result) {//alert(result);
                        $('#targetviewdetail').html(result);
                        $('#btnconvert').prop('disabled', false);
                    }
                });
            }
        });

        $('#btnconvert').click(function(){
            var table_content = '<table>';
            table_content += $('#table_content').html();
            table_content += '</table>';
            $('#file_content').val(table_content); 
            $('#convert_form').submit();
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

</script>
<?php include "include/footer.php"; ?>
