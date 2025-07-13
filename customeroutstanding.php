<?php 
include "include/header.php";  

// $sqlcustomer="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 ORDER BY `name` ASC";
// $resultcustomer =$conn-> query($sqlcustomer);

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
                            <span>Outstanding Report</span>
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
                                            </select>
                                        </div>

                                        <div id="customerDropdown" class="col-3">
                                            <label class="small font-weight-bold text-dark">Customer | Sales Excutive</label>
                                            <select class="form-control form-control-sm" name="dataselector" id="dataselector" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <!-- <div class="col-5">
                                            <label class="small font-weight-bold text-dark">Customer</label>
                                            <select class="form-control form-control-sm" name="customer" id="customer">
                                                <option value="">All Customer</option>
                                                <?php //if($resultcustomer->num_rows > 0) {while ($rowcustomer = $resultcustomer-> fetch_assoc()) { ?>
                                                <option value="<?php //echo $rowcustomer['idtbl_customer'] ?>"><?php //echo $rowcustomer['name']; ?></option>
                                                <?php //}} ?>
                                            </select>
                                        </div> -->
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                            <button class="btn btn-outline-dark btn-sm" type="button" id="formSearchBtn"><i class="fas fa-search"></i>&nbsp;Search</button>
                                            <button class="btn btn-outline-danger btn-sm" type="button" id="formAllSearchBtn"><i class="fas fa-list"></i>&nbsp;All Outstanding</button>
                                        </div>
                                    </div>
                                    <input type="submit" class="d-none" id="hidesubmit">
                                </form>
                            </div>
                            <div class="col-2">
                                <!-- <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                <button class="btn btn-success btn-sm float-right" onclick="exportToExcel()"><i class="fas fa-file-csv mr-2"></i> Export Excel</button> -->
                            </div>
                            <div class="col-12" id="printtable">
                                <hr class="border-dark">
                                <form action="export/exportcustomeroutstanding.php" method="post" id="convert_form">
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            <input type="hidden" name="file_content" id="file_content">
                                            <button type="button" id="btnconvert" class="btn btn-success btn-sm px-4 mb-3" disabled><i class="fas fa-file-excel mr-2"></i>Excel</button>  
                                            <button type="button" id="btnpdfconvert" class="btn btn-danger btn-sm px-4 mb-3" disabled><i class="fas fa-file-pdf mr-2"></i>PDF</button>  
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
<div class="modal fade" id="modalinvoice" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div id="viewinvoicedetails"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script>
    $(document).ready(function() {
        $('.dpd1a').datepicker('remove');
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

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
            $('#btnconvert').prop('disabled', true);
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {   
                var validfrom = $('#fromdate').val();
                var validto = $('#todate').val();
                var customer = $('#dataselector').val();
                var type = $('#typeSelector').val();

                $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        validfrom: validfrom,
                        validto: validto,
                        customer: customer,
                        type: type
                    },
                    url: 'getprocess/getoutstandingreport.php',
                    success: function(result) {//alert(result);
                        $('#targetviewdetail').html(result);
                        $('#btnconvert').prop('disabled', false);
                        $('#btnpdfconvert').prop('disabled', false);
                        invoiceviewoption();
                    }
                });
            }
        });
        $('#formAllSearchBtn').click(function(){
            $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');
            $('#btnconvert').prop('disabled', true);

            var validfrom='';
            var validto='';
            var customer='';
            var type='';

            $.ajax({
                type: "POST",
                data: {
                    validfrom: validfrom,
                    validto: validto,
                    customer: customer,
                    type: type
                },
                url: 'getprocess/getoutstandingreport.php',
                success: function(result) {//alert(result);
                    $('#targetviewdetail').html(result);
                    $('#btnconvert').prop('disabled', false);
                    $('#btnpdfconvert').prop('disabled', false);
                    invoiceviewoption();
                }
            });
        });

        $('#btnconvert').click(function(){
            var table_content = '<table>';
            table_content += $('#tableoutstanding').html();
            table_content += '</table>';
            $('#file_content').val(table_content); 
            $('#convert_form').submit();
        });

        $('#btnpdfconvert').click(function(){
            var type = $('#typeSelector').val();
            var cusexname = $('#dataselector option:selected').text();

            var { jsPDF } = window.jspdf;
            var doc = new jsPDF('l', 'pt', 'a4');

            // Define table content
            var table = document.getElementById("tableoutstanding");
            var rows = [];
            for (var i = 0, row; row = table.rows[i]; i++) {
                var rowData = [];
                for (var j = 0, col; col = row.cells[j]; j++) {
                    rowData.push(col.innerText);
                    if(col.innerText=='Net Outstanding'){
                        rowData.push('');
                        rowData.push('');
                        rowData.push('');
                        rowData.push('');
                        if(type!=''){
                            rowData.push('');
                            rowData.push('');
                            rowData.push('');
                        }
                    }
                }
                rows.push(rowData);
            }

            var headers = [rows[0]];
            var data = rows.slice(1);
            
            // console.log(data);
            if(type==''){var title = 'Ansen Gas Distributor PVT Ltd all customer outstanding information';}
            else{var title = 'Ansen Gas Distributor PVT Ltd '+cusexname+' outstanding information';}
            doc.setFontSize(12);
            doc.text(title, 40, 30);
            doc.autoTable({
                head: headers,
                body: data,
                startY: 40,
                theme: 'grid',
                headStyles: { fillColor: [41, 128, 185] }, 
                styles: { cellPadding: 5, halign: 'left' }, 
                columnStyles: {
                    2: { halign: 'right' }, 
                    3: { halign: 'right' }, 
                    4: { halign: 'right' }, 
                    5: { halign: 'right' },
                }
            });

            doc.save("customeroutstanding.pdf");
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

        $('table tbody').on('click', 'tr', function(){
            $('table tbody>tr').removeClass('table-warning text-dark');
            $(this).addClass('table-warning text-dark');
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
