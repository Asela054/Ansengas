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
                            <span>Trust Break Report</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                    <div class="row">
                            <div class="col-10">
                            <form id="searchform">
                                <div class="form-row">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Customer*</label>
                                        <select class="form-control form-control-sm" name="customer" id="customer">
                                            <option value="">All Customer</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                        <button class="btn btn-outline-dark btn-sm px-4" type="button" id="formSearchBtn" onclick="bufferreport();"><i class="fas fa-search"></i>&nbsp;Search</button>
                                    </div>
                                </div>
                                <input type="submit" class="d-none" id="hidesubmit">
                            </form>
                            </div>
                            <div class="col-12" id="printtable">
                                <hr class="border-dark">
                                <form action="export/exportcustomerbufferstock.php" method="post" id="convert_form">
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            <input type="hidden" name="file_content" id="file_content">
                                            <button type="button" id="btnconvert" class="btn btn-success btn-sm px-4 mb-3" disabled><i class="fas fa-file-excel mr-2"></i>Excel</button>  
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
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
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
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

        $('#formSearchBtn').click(function () {
            $('#btnconvert').prop('disabled', true);
            // Check if the form is valid
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it to display HTML5 error messages
                $("#hidesubmit").click();
            } else {
                var customer = $('#customer').val();

                $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        customer: customer
                    },
                    url: 'getprocess/getrustbreakdownreport.php',
                    success: function (result) {
                        $('#targetviewdetail').html(result);
                        $('#btnconvert').prop('disabled', false);
                    }
                });
            }
        });

        $('#btnconvert').click(function(){
            var table_content = '<table>';
            table_content += $('#trustbreakdownreport').html();
            table_content += '</table>';
            $('#file_content').val(table_content); 
            $('#convert_form').submit();
        });
    });

    // function bufferreport() {
    //     $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

    //     const date = $('#date').val();
    //     const customer = $('#customer').val();

    //     $.ajax({
    //         type: "POST",
    //         data: {
    //             date: date,
    //             customer: customer
    //         },
    //         url: 'getprocess/getbufferreportaccoperiod.php',
    //         success: function (result) {
    //             $('#targetviewdetail').html(result);
    //             $('#btnconvert').prop('disabled', false);
    //         }
    //     });
    // }
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
