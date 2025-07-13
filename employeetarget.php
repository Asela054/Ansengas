<?php 
include "include/header.php";  

$sqlemployee="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 ORDER BY `name` ASC";
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
                            <span>Target Report</span>
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
                                            <label class="small font-weight-bold text-dark">Month*</label>
                                            <input type="month" class="form-control form-control-sm" placeholder=""
                                                name="month" id="month">
                                        </div>
                                        <div class="col-2">
                                            <label class="small font-weight-bold text-dark">Report Type</label>
                                            <select class="form-control form-control-sm" name="reporttype" id="reporttype">
                                                <option value="">Select</option>
                                                <option value="1">Executive</option>
                                                <option value="2">Driver</option>
                                                <option value="3">Area</option>
                                                <option value="4">Customer</option>
                                                <option value="5">Vehicle</option>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Executive | Driver | Area | Customer | Vehicle</label>
                                            <select class="form-control form-control-sm" name="employee" id="employee">
                                                <option value="">Select</option>
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
                                            <!-- <button type="button" id="btnpdfconvert" class="btn btn-danger btn-sm px-4 mb-3" disabled><i class="fas fa-file-pdf mr-2"></i>PDF</button>  -->
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
        $('#reporttype').change(function() {
            $("#employee").val('').trigger('change');
        });
        $("#employee").select2({
            ajax: {
                url: "getprocess/getemployeelistaccotarget.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                        reporttype: $('#reporttype').val() // report type
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
                var month = $('#month').val();
                var employee = $('#employee').val();
                var reporttype = $('#reporttype').val();

                $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        month: month,
                        employee: employee,
                        reporttype: reporttype
                    },
                    url: 'getprocess/getemployeetargetreport.php',
                    success: function(result) {//alert(result);
                        $('#targetviewdetail').html(result);
                        $('#btnconvert').prop('disabled', false);
                        $('#btnpdfconvert').prop('disabled', false);
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

        $('#btnpdfconvert').click(function(){
            var type = $('#employee').val();
            var cusexname = $('#employee option:selected').text();

            var { jsPDF } = window.jspdf;
            var doc = new jsPDF('l', 'pt', 'a4');

            // Define table content
            var table = document.getElementById("table_content");
            var rows = [];
            for (var i = 0, row; row = table.rows[i]; i++) {
                var rowData = [];
                for (var j = 0, col; col = row.cells[j]; j++) {
                    rowData.push(col.innerText);
                    // if(col.innerText=='Net Outstanding'){
                    //     rowData.push('');
                    //     rowData.push('');
                    //     rowData.push('');
                    //     rowData.push('');
                    //     if(type!=''){
                    //         rowData.push('');
                    //         rowData.push('');
                    //         rowData.push('');
                    //     }
                    // }
                }
                rows.push(rowData);
            }

            var headers = [rows[0]];
            var data = rows.slice(1);
            
            // console.log(data);
            if(type==''){var title = 'Ansen Gas Distributor PVT Ltd all employee target information';}
            else{var title = 'Ansen Gas Distributor PVT Ltd '+cusexname+' target information';}
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

            doc.save("targetinformation.pdf");
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
