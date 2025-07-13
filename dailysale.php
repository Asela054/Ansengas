<?php 
include "include/header.php";  

$sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `status`=1 AND `type`=0 ORDER BY `vehicleno` ASC";
$resultvehicle =$conn-> query($sqlvehicle);

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
                            <span>Daily Sale Report</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-8">
                                <form id="searchform">
                                    <div class="form-row">
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Date*</label>
                                            <input type="date" class="form-control form-control-sm" placeholder=""
                                                name="fromdate" id="fromdate">
                                        </div>
                                        <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Lorry*</label><br>
                                            <select class="form-control form-control-sm rounded-1" name="lorry" id="lorry" required>
                                                <option value="0">All Vehicles</option>
                                                <?php if($resultvehicle->num_rows > 0) {while ($rowvehicle = $resultvehicle-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowvehicle['idtbl_vehicle'] ?>"><?php echo $rowvehicle['vehicleno']; ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <div class="custom-control custom-checkbox">
                                                <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                                <input type="checkbox" class="custom-control-input" id="advance">
                                                <label class="custom-control-label font-weight-bold"
                                                    for="advance">Summery Report</label>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                            <button class="btn btn-outline-dark btn-sm px-4" type="button"
                                                id="formSearchBtn"><i class="fas fa-search"></i>&nbsp;Search</button>
                                            <!-- <button type="button" class="btn btn-outline-danger btn-sm" id="btnprint"><i
                                                    class="fas fa-print"></i>&nbsp;Print Report</button> -->
                                        </div>
                                    </div>
                                    <input type="submit" class="d-none" id="hidesubmit">
                                </form>
                            </div>
                            <div class="col-12">
                                <hr class="border-dark">
                                <div class="scrollbar pb-3" id="style-2">
                                    <form action="export/exportdailysale.php" method="post" id="convert_form">
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
        $('#advance').change(function() {
            if(this.checked) {
                $('#todate').prop('readonly', true);
            }
            else{
                $('#todate').prop('readonly', false);
            }     
        });

        $('#formSearchBtn').click(function(){
            $('#btnconvert').prop('disabled', true);
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {   
                if ($('#advance').is(':checked')) { var advance = '1'; } else { var advance = '0'; }
                var validfrom = $('#fromdate').val();
                var lorry = $('#lorry').val();

                $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        validfrom: validfrom,
                        lorry: lorry,
                        advance: advance
                    },
                    url: 'getprocess/getdailyreportreport.php',
                    success: function(result) {//alert(result);
                        $('#targetviewdetail').html(result);
                        $('#btnconvert').prop('disabled', false);
                        $('#btnpdfconvert').prop('disabled', false);
                        invoiceviewoption();
                    }
                });
            }
        });

        // document.getElementById('btnprint').addEventListener ("click", print);

        $('#btnconvert').click(function(){
            var table_content = '<table>';
            table_content += $('#table_content').html();
            table_content += '</table>';
            $('#file_content').val(table_content); 
            $('#convert_form').submit();
        });

        $('#btnpdfconvert').click(function(){
            if ($('#advance').is(':checked')) { var advance = '1'; } else { var advance = '0'; }
            var type = $('#lorry').val();
            var fromdate = $('#fromdate').val();
            var cusexname = $('#lorry option:selected').text();            
            var accedatalist = $('#accedatalist').val();
            var objfirst = JSON.parse(accedatalist);

            const datacollist = ['New', 'Refill', 'Empty', 'Trust', 'New', 'Refill', 'Empty', 'Trust', 'New', 'Refill', 'Empty', 'Trust', 'New', 'Refill', 'Empty', 'Trust']

            $.each(objfirst, function (i, item) {
                datacollist.push(objfirst[i].access);
            });

            datacollist.push('Amount');

            var { jsPDF } = window.jspdf;
            var doc = new jsPDF('l', 'pt', 'legal');

            if(type==''){var title = 'Ansen Gas Distributor PVT Ltd all vehicle day sale information';}
            else{var title = 'Ansen Gas Distributor PVT Ltd '+cusexname+' sale summery '+fromdate;}
            doc.setFontSize(12);
            doc.text(title, 40, 30);

            // Define table content
            var table = document.getElementById("table_content");
            var rows = [];
            if(advance==0){
                for (var i = 0, row; row = table.rows[i]; i++) {
                    var rowData = [];
                    for (var j = 0, col; col = row.cells[j]; j++) {
                        if(j<7){
                            rowData.push(col.innerText);
                            if(col.innerText=='Net invoice Total'){
                                rowData.push('');
                                rowData.push('');
                                rowData.push('');
                                rowData.push('');
                                rowData.push('');
                            }
                        }
                    }
                    rows.push(rowData);
                }

                var head = [rows[0]];
                var data = rows.slice(1);

                doc.autoTable({
                    head: head,
                    body: data,
                    startY: 40,
                    theme: 'grid',
                    headStyles: { fillColor: [41, 128, 185], fontSize: 8, halign: 'center' }, 
                    styles: { cellPadding: 5, halign: 'left', fontSize: 8 }, 
                    columnStyles: {
                        6: { halign: 'right' }, 
                    }
                });
            }
            else{
                for (var i = 2, row; row = table.rows[i]; i++) {
                    var rowData = [];
                    for (var j = 0, col; col = row.cells[j]; j++) {
                        rowData.push(col.innerText);
                    }
                    rows.push(rowData);
                }

                let head = [
                    [
                        {content: 'Invoice', rowSpan: 2, styles: {halign: 'center', fillColor: [41, 128, 185]}}, 
                        {content: 'Customer', rowSpan: 2, styles: {halign: 'center', fillColor: [41, 128, 185]}}, 
                        {content: '2 KG', colSpan: 4, styles: {halign: 'center', fillColor: [41, 128, 185]}}, 
                        {content: '5 KG', colSpan: 4, styles: {halign: 'center', fillColor: [41, 128, 185]}}, 
                        {content: '12.5 KG', colSpan: 4, styles: {halign: 'center', fillColor: [41, 128, 185]}}, 
                        {content: '37.5 KG', colSpan: 4, styles: {halign: 'center', fillColor: [41, 128, 185]}},
                        {content: 'Accessories', colSpan: objfirst.length, styles: {halign: 'center', fillColor: [41, 128, 185]}}, 
                        {content: 'Cash', rowSpan: 2, styles: {halign: 'center', fillColor: [41, 128, 185]}}, 
                        {content: 'Cheque', colSpan: 0, styles: {halign: 'center', fillColor: [41, 128, 185]}},
                        {content: 'Credit', rowSpan: 2, styles: {halign: 'center', fillColor: [41, 128, 185]}}, 
                        {content: 'Discount Amount', rowSpan: 2, styles: {halign: 'center', fillColor: [41, 128, 185]}}, 
                    ],
                    datacollist,
                ];
                var data = rows;

                doc.autoTable({
                    head: head,
                    body: data,
                    startY: 40,
                    theme: 'grid',
                    headStyles: { fillColor: [41, 128, 185], fontSize: 8, halign: 'center' }, 
                    styles: { cellPadding: 5, halign: 'left', fontSize: 8 }, 
                    columnStyles: {
                        2: { halign: 'center' }, 
                        3: { halign: 'center' }, 
                        4: { halign: 'center' }, 
                        5: { halign: 'center' }, 
                        6: { halign: 'center' }, 
                        7: { halign: 'center' }, 
                        8: { halign: 'center' }, 
                        9: { halign: 'center' }, 
                        10: { halign: 'center' }, 
                        11: { halign: 'center' }, 
                        12: { halign: 'center' }, 
                        13: { halign: 'center' }, 
                        14: { halign: 'center' }, 
                        15: { halign: 'center' }, 
                        16: { halign: 'center' }, 
                        17: { halign: 'center' }, 
                        18: { halign: 'right' }, 
                        19: { halign: 'right' }, 
                        20: { halign: 'right' }, 
                        21: { halign: 'right' },
                    }
                });
            }

            doc.save("dailyinformation.pdf");
        });
    });

    function invoiceviewoption(){
        $('#table_content tbody').on('click', '.viewbtninv', function() {
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

    function print() {
        printJS({
            printable: 'targetviewdetail',
            type: 'html',
            style: '@page { size: legal landscape; margin:0.25cm; }',
            targetStyles: ['*']
        })
    }

</script>
<?php include "include/footer.php"; ?>
