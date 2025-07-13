<?php 
include "include/header.php";  

$sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `status`=1 AND `type`=0";
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
                            <span>Vehicle Target Report</span>
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
                                            <label class="small font-weight-bold text-dark">Vehicle No</label>
                                            <select class="form-control form-control-sm" name="vehicle" id="vehicle">
                                                <option value="">All Vehicle</option>
                                                <?php if($resultvehicle->num_rows > 0) {while ($rowvehicle = $resultvehicle-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowvehicle['idtbl_vehicle'] ?>"><?php echo $rowvehicle['vehicleno']; ?></option>
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
                                <form action="export/exportvehicletarget.php" method="post" id="convert_form">
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
        $('#formSearchBtn').click(function(){
            $('#btnconvert').prop('disabled', true);
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {   
                var validfrom = $('#fromdate').val();
                var validto = $('#todate').val();
                var vehicle = $('#vehicle').val();

                $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        validfrom: validfrom,
                        validto: validto,
                        vehicle: vehicle
                    },
                    url: 'getprocess/getvehicletargetreport.php',
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
            var type = $('#vehicle').val();
            var cusexname = $('#vehicle option:selected').text();

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
            if(type==''){var title = 'Ansen Gas Distributor PVT Ltd all vehicle target information';}
            else{var title = 'Ansen Gas Distributor PVT Ltd '+cusexname+' vehicle target information';}
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
