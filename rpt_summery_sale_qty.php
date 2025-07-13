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
                            <span>Sales Summary Qty</span>
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
                                            <input type="date" class="form-control form-control-sm" placeholder="" name="fromdate" id="fromdate">
                                        </div>
                                        <div class="col-2">
                                            <label class="small font-weight-bold text-dark">To*</label>
                                            <input type="date" class="form-control form-control-sm" placeholder="" name="todate" id="todate">
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
                                <form action="export/exportrpt_summery_sale_qty.php" method="post" id="convert_form">
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
                    url: 'getprocess/getsummerysaleqtyrpt.php',
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
            var table_content = '<table>';
            table_content += $('#table_content').html();
            table_content += '</table>';
            $('#file_content').val(table_content); 
            $('#convert_form').submit();
        });

        $('#btnpdfconvert').click(function(){
            var { jsPDF } = window.jspdf;
            var doc = new jsPDF('p', 'pt', 'A4');

            // Define table content
            var table = document.getElementById("table_content");
            
            var specialborder = 0;
            var totalbold = 0;
            var lastchequetype = 0;
            var trfcredit = 0;
            var cheqbreak = 0;
            var lasttotal = 0;
            var accestatus = 0;

            var rows = [];
            for (var i = 0, row; row = table.rows[i]; i++) {      
                console.log(row);
                          
                var rowData = [];
                var specialthead = 0;                

                for (var j = 0, col; col = row.cells[j]; j++) {
                    if(row.cells.length==1){
                        if(col.innerText=='Refil Cylinders' | col.innerText=='New Cylinders' | col.innerText=='Trust Cylinders' | col.innerText=='Empty Cylinders'){
                            rowData.push({content: col.innerText, colSpan: 6, styles: {halign: 'left', fontStyle: 'bold', lineColor: [0, 0, 0], lineWidth: 0.5}});
                        }
                        else if(col.innerText=='Excess'){
                            rowData.push({content: col.innerText, colSpan: 7, styles: {halign: 'left', fontStyle: 'bold'}});
                            specialborder = 1;
                        }
                        else{  
                            if(col.innerText=='Credit Brekup' | col.innerText=='Cheque Brekup' | col.innerText=='Excess Brekup' | col.innerText=='TRF Brekup' | col.innerText=='Discount Brekup'){
                                if(col.innerText=='Excess Brekup'){lastchequetype=1;}
                                else if(col.innerText=='Cheque Brekup'){trfcredit=0;}
                                else if(col.innerText=='TRF Brekup'){trfcredit=1;}

                                cheqbreak=1;
                                rowData.push({content: col.innerText, colSpan: 7, styles: {halign: 'left', fontStyle: 'bold'}});
                            }
                            else{
                                rowData.push({content: col.innerText, colSpan: 7, styles: {halign: 'right', fontStyle: 'bold'}});
                            }
                        }
                        lasttotal=0;
                        accestatus = 0;
                    }
                    else{
                        if(col.innerText=='Cash' | col.innerText=='Cheque' | col.innerText=='Credit' | col.innerText=='TRF' | col.innerText=='Refund' | col.innerText=='2 Kg' | col.innerText=='5 Kg' | col.innerText=='12.5 Kg' | col.innerText=='37.5 Kg' | col.innerText=='Accessories'){
                            if(lastchequetype==1){
                                rowData.push({content: col.innerText, styles: {halign: 'left'}});
                            }
                            else if(col.innerText=='Accessories'){
                                rowData.push({content: col.innerText, styles: {halign: 'left', fontStyle: 'bold', lineColor: [0, 0, 0], lineWidth: 0.5}});
                                var accestatus = 1;
                            }
                            else{
                                rowData.push({content: col.innerText, styles: {halign: 'center', fontStyle: 'bold', lineColor: [0, 0, 0], lineWidth: 0.5}});
                            }
                        }
                        else if(col.innerText=='Bill No' | col.innerText=='Amount' | col.innerText=='Bank' | col.innerText=='Cheque Date' | col.innerText=='Cheque No' | col.innerText=='Type' | col.innerText=='Customer'){
                            rowData.push({content: col.innerText, styles: {halign: 'left', fontStyle: 'bold'}});
                        }
                        else if (i==0 && j==0) {
                            rowData.push({content: col.innerText, styles: {halign: 'left', lineColor: [0, 0, 0], lineWidth: 0.5, fontStyle: 'bold'}});
                        }
                        else{
                            if (/X/.test(col.innerText)) {
                                rowData.push({content: col.innerText, styles: {halign: 'left', lineColor: [0, 0, 0], lineWidth: 0.5}});
                            }
                            else{
                                if(specialborder==1){
                                    if(j==0){
                                        rowData.push({content: col.innerText, styles: {halign: 'left'}});
                                        if(trfcredit==1 && col.innerText==''){lasttotal=1;}
                                        else if(cheqbreak==1 && col.innerText==''){lasttotal=1;}
                                    }
                                    else if(j==1){
                                        if(lasttotal==1){
                                            rowData.push({content: col.innerText, styles: {halign: 'right', fontStyle: 'bold'}});
                                        }
                                        else{
                                            rowData.push({content: col.innerText, styles: {halign: 'right'}});
                                        }
                                    }
                                    else{
                                        if(cheqbreak==1 && j==5){
                                            rowData.push({content: col.innerText, styles: {halign: 'left', fontStyle: 'bold'}});
                                        }
                                        else if(trfcredit==1){
                                            rowData.push({content: col.innerText, styles: {halign: 'left', fontStyle: 'bold'}});
                                        }
                                        else{
                                            rowData.push({content: col.innerText, styles: {halign: 'left'}});
                                        }
                                    }
                                }
                                else{
                                    if(col.innerText=='' && j==6){
                                        rowData.push({content: col.innerText, styles: {halign: 'right',}});
                                    }
                                    else{
                                        if(accestatus==1){
                                            rowData.push({content: col.innerText, styles: {halign: 'left', lineColor: [0, 0, 0], lineWidth: 0.5}});
                                        }
                                        else{
                                            rowData.push({content: col.innerText, styles: {halign: 'right', lineColor: [0, 0, 0], lineWidth: 0.5}});
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                rows.push(rowData);
            }

            const margin = {
                top: 30,                  
                right: 20,                  
                bottom: 30, 
                left: 20               
            };

            var data = rows;
            
            var title = 'Ansen Gas Distributor PVT Ltd Sales Summary Qty information';
            doc.setFontSize(12);
            // doc.text(title, 15, 25);
            doc.autoTable({
                // head: head,
                body: data,
                margin: margin,
                startY: 40,
                theme: 'plain',
                // headStyles: { fillColor: [41, 128, 185], fontSize: 7, halign: 'center' }, 
                styles: { cellPadding: 5, halign: 'left', fontSize: 7, textColor: [0, 0, 0] }, 
                columnStyles: {
                    // 2: { halign: 'right' }, 
                    // 3: { halign: 'right' }, 
                    // 4: { halign: 'right' }, 
                    // 5: { halign: 'right' },
                },
                // showHead: 'firstPage'
            });

            doc.save("salessummeryqtyinformation.pdf");
        });
    });
</script>
<?php include "include/footer.php"; ?>
