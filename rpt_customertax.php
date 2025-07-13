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
                            <span>Customer Tax Report</span>
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
                                        <div class="col">
                                            <button class="btn btn-sm btn-outline-dark px-4" type="button" id="formSearchBtn" style="margin-top:30px;"><i class="fas fa-search"></i>&nbsp;Search</button>
                                        </div>
                                    </div>
                                    <input type="submit" class="d-none" id="hidesubmit">
                                </form>
                            </div>
                            <div class="col-12" id="printtable">
                                <hr class="border-dark">
                                <form action="export/exportrpt_customertax.php" method="post" id="convert_form">
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
        $('#formSearchBtn').click(function () {
            $('#btnconvert').prop('disabled', true);
            // Check if the form is valid
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it to display HTML5 error messages
                $("#hidesubmit").click();
            } else {
                var validfrom = $('#fromdate').val();
                var validto = $('#todate').val();

                $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        validfrom: validfrom,
                        validto: validto
                    },
                    url: 'getprocess/getcustomertaxrpt.php',
                    success: function (result) {
                        $('#targetviewdetail').html(result);
                        $('#btnconvert').prop('disabled', false);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
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
            jsPDF: { unit: 'in', format: 'a3', orientation: 'landscape' }
        };
        html2pdf().from(pdfContainer).set(opt).save();
    }
</script>
<?php include "include/footer.php"; ?>
