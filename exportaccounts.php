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
                            <div class="page-header-icon"><i class="far fa-file-excel"></i></div>
                            <span>Accounts Export</span>
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
                                            <label class="small font-weight-bold text-dark">From*</label>
                                            <input type="date" class="form-control form-control-sm" placeholder="" name="fromdate" id="fromdate" required>
                                        </div>
                                        <div class="col-2">
                                            <label class="small font-weight-bold text-dark">To*</label>
                                            <input type="date" class="form-control form-control-sm" placeholder="" name="todate" id="todate" required>
                                        </div>
                                        <div class="col-2">
                                            <label class="small font-weight-bold text-dark">Export Type*</label>
                                            <select class="form-control form-control-sm" name="exporttype" id="exporttype" required>
                                                <option value="">Select</option>
                                                <option value="1">Sales Register VAT</option>
                                                <option value="8">Sales Register NON VAT</option>
                                                <option value="2">Purchase Register</option>
                                                <option value="3">Loading</option>
                                                <option value="4">Unloading</option>
                                                <option value="5">Damage Return Company Send</option>
                                                <option value="6">Damage Return Customer Send</option>
                                                <option value="7">Receipt Register</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-sm btn-outline-dark px-4" type="button" id="formSearchBtn" style="margin-top:30px;"><i class="fas fa-search"></i>&nbsp;Search</button>
                                        </div>
                                    </div>
                                    <input type="submit" class="d-none" id="hidesubmit">
                                </form>
                            </div>
                            <div class="col-12" id="printtable">
                                <hr class="border-dark">
                                <form method="post" id="convert_form">
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            <input type="hidden" name="file_content" id="file_content">
                                            <button type="button" id="btnconvert" class="btn btn-success btn-sm px-4 mb-3" disabled><i class="fas fa-file-excel mr-2"></i>Excel</button>  
                                            <input type="date" class="d-none" name="hidefrom" id="hidefrom">
                                            <input type="date" class="d-none" name="hideto" id="hideto">
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
        $('#formSearchBtn').click(function () {
            $('#btnconvert').prop('disabled', true);
            // Check if the form is valid
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it to display HTML5 error messages
                $("#hidesubmit").click();
            } else {
                var validfrom = $('#fromdate').val();
                var validto = $('#todate').val();
                var exporttype = $('#exporttype').val();

                $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        validfrom: validfrom,
                        validto: validto,
                        exporttype: exporttype
                    },
                    url: 'getprocess/getexportdataaccount.php',
                    success: function (result) {
                        $('#targetviewdetail').html(result);
                        $('#btnconvert').prop('disabled', false);
                        $('#btnpdfconvert').prop('disabled', false);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });

        $('#btnconvert').click(function(){
            var exporttype = $('#exporttype').val();
            if(exporttype==1){var actionurl='export/exportaccountsaleformat.php';}
            if(exporttype==2){var actionurl='export/exportpurchaseregister.php';}
            if(exporttype==3){var actionurl='export/exportloading.php';}
            if(exporttype==4){var actionurl='export/exportunloading.php';}
            if(exporttype==5){var actionurl='export/exportcompanysend.php';}
            if(exporttype==6){var actionurl='export/exportcustomersend.php';}
            if(exporttype==7){var actionurl='export/exportaccountreceiptformat.php';}
            if(exporttype==8){var actionurl='export/exportaccountsaleformatwithoutvat.php';}

            $("#convert_form").attr('action', actionurl);
            $('#hidefrom').val($('#fromdate').val());
            $('#hideto').val($('#todate').val());

            var table_content = '<table>';
            table_content += $('#table_content').html();
            table_content += '</table>';
            $('#file_content').val(table_content); 
            $('#convert_form').submit();
        });
    });
</script>
<?php include "include/footer.php"; ?>
