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
                            <span>Sale Report Customer</span>
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
                                            <input type="date" class="form-control form-control-sm" placeholder=""
                                                name="fromdate" id="fromdate">
                                        </div>
                                        <div class="col-2">
                                            <label class="small font-weight-bold text-dark">To*</label>
                                            <input type="date" class="form-control form-control-sm" placeholder=""
                                                name="todate" id="todate">
                                        </div>
                                        <div class="col-2">
                                            <label class="small font-weight-bold text-dark">Report Type</label><br>
                                            <select class="form-control form-control-sm rounded-1" name="type" id="typeSelector">
                                                <option value="">Select Type</option>
                                                <option value="1">Customer</option>
                                                <option value="2">Sales Executive</option>
                                                <option value="3">Lorry</option>
                                                <option value="4">Driver</option>
                                                <option value="5">Area</option>
                                            </select>
                                        </div>

                                        <div id="customerDropdown" class="col-3">
                                            <label class="small font-weight-bold text-dark">Customer | Sales Excutive | Lorry | Driver | Area</label>
                                            <select class="form-control form-control-sm" name="dataselector" id="dataselector" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div id="customerTypeDiv" class="col-1" style="display: none;">
                                            <label class="small font-weight-bold text-dark">Customer Type</label>
                                            <select name="cusType" id="cusType" class="form-control form-control-sm">
                                                <option value="">Select</option>
                                                <option value="1">Commercial</option>
                                                <option value="2">Dealer</option>
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
                                <!-- <button class="btn btn-success btn-sm float-right mb-3 mr-3" onclick="exportToExcel()">
                                    <i class="fas fa-file-csv mr-2"></i> Export Excel
                                </button>
                                <button class="btn btn-danger btn-sm float-right mb-3 mr-3" onclick="exportToPDF()">
                                    <i class="far fa-file-pdf mr-2"></i> Export PDF
                                </button> -->
                                <form action="export/exportsalereportcustomer.php" method="post" id="convert_form">
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
        $('#typeSelector').change(function(){
            $('#dataselector').val(null).trigger('change');
            if($(this).val()==1){$('#dataselector').prop('required', false);}
            else{$('#dataselector').prop('required', true);}
        });

        $("#dataselector").select2({
            ajax: {
                url: "getprocess/fetch_data.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                        type: $('#typeSelector').val()
                    };
                },
                processResults: function (response) {//console.log(response);
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('#dataselector').change(function() {
            var type = $('#typeSelector').val();
            if (type == 2) {
                $('#customerTypeDiv').show();
            } else {
                $('#customerTypeDiv').hide();
            }
        });

        $('#formSearchBtn').click(function () {
            $('#btnconvert').prop('disabled', true);
            // Check if the form is valid
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it to display HTML5 error messages
                $("#hidesubmit").click();
            } else {
                var validfrom = $('#fromdate').val();
                var validto = $('#todate').val();
                var typeSelector = $('#typeSelector').val();
                var dataselector = $('#dataselector').val();
                var cusType = $('#cusType').val();

                $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        validfrom: validfrom,
                        validto: validto,
                        typeSelector: typeSelector,
                        dataselector: dataselector,
                        cusType: cusType
                    },
                    url: 'getprocess/getcustomersalereportaccoperiod.php',
                    success: function (result) {
                        $('#targetviewdetail').html(result);
                        $('#btnconvert').prop('disabled', false);
                        invoiceviewoption();
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });

        $('#btnconvert').click(function(){
            var table_content = '<table>';
            table_content += $('#tableoutstanding').html();
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

    function exportToExcel() {
        var table = document.getElementById("printtable");
        var ws = XLSX.utils.table_to_sheet(table);
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
        XLSX.writeFile(wb, "Customer_Sales_Report.xlsx");
    }

    function exportToPDF() {
        var pdfContainer = document.createElement('div');
        pdfContainer.innerHTML = document.getElementById("printtable").innerHTML;

        var buttons = pdfContainer.querySelectorAll("button");
        buttons.forEach(function(button) {
            button.remove();
        });

        var hrElements = pdfContainer.querySelectorAll("hr");
        hrElements.forEach(function(hr) {
            hr.remove();
        });

        var opt = {
            margin: 1,
            filename: 'Customer_Sales_Report.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'a1', orientation: 'landscape' }
        };
        html2pdf().from(pdfContainer).set(opt).save();
    }
</script>
<?php include "include/footer.php"; ?>
