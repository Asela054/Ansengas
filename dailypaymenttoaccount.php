<?php 
include "include/header.php";  

$sqlcustomer="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 ORDER BY `name` ASC";
$resultcustomer =$conn-> query($sqlcustomer);

$sqlcompany="SELECT `idtbl_company`, `name`, `code` FROM `tbl_company` WHERE `status`=1";
$resultcompany =$conn-> query($sqlcompany); 

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
                            <div class="page-header-icon"><i data-feather="server"></i></div>
                            <span>Receipt To Account</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form id="searchform">
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Date*</label>
                                            <div class="input-group input-group-sm">
                                                <input type="date" class="form-control" id="fromdate" name="fromdate" value="<?php echo date('Y-m-d') ?>" required>
                                                <input type="date" class="form-control" id="todate" name="todate" value="<?php echo date('Y-m-d') ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Debit Account</label><br>
                                            <select class="form-control form-control-sm" name="debitaccount" id="debitaccount" style="width: 100%;" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row mt-3">
                                        <div class="col">
                                            <button class="btn btn-outline-dark btn-sm fa-pull-right px-4" type="button" id="formSearchBtn"><i class="fas fa-search"></i>&nbsp;Search</button>
                                        </div>
                                    </div>
                                    <input type="submit" class="d-none" id="hidesubmit">
                                </form>
                            </div>
                            <div class="col-9">
                                <h6 class="small title-style mt-1"><span>Receipt Information</span></h6>
                                <div id="viewpayment"></div>                                
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
<div class="modal fade" id="modaltransferreceiptinfo" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="exampleModalLabel">Transfer Receipt List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row mb-0">
                    <div class="col-3">
                        <label class="small font-weight-bold text-dark">Credit Account</label>
                        <h6 class="mb-0" id="titlecredit"></h6>
                    </div>
                    <div class="col-3">
                        <label class="small font-weight-bold text-dark">Debit Account</label>
                        <h6 class="mb-0" id="titledebit"></h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <hr class="border-dark">
                        <table class="table table-striped table-bordered table-sm" id="confirmtable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="d-none">PaymentinfoID</th>
                                    <th>Pay Type</th>
                                    <th>Customer</th>
                                    <th class="d-none">Amounthide</th>
                                    <th class="text-right">Amount</th>
                                    <th>Cheque No</th>
                                    <th>Cheque Date</th>
                                    <th>Bank</th>
                                    <th>Branch</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <input type="text" name="hidecreditaccount" id="hidecreditaccount">
                        <input type="hidden" name="hidedebitaccount" id="hidedebitaccount">
                        <hr class="border-dark">
                        <button class="btn btn-outline-primary btn-sm fa-pull-right" id="creditdebitbtn">Credit & Debit To Accounts</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Alert -->
<div class="modal fade" id="warningmodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content bg-danger">
            <div class="modal-body text-white text-center">
                <div class="row">
                    <div class="col text-center" id="bodyAlert"></div>
                </div>
                <button type="button" class="btn btn-outline-light btn-sm px-5" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Account Set -->
<div class="modal fade" id="modalaccount" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form id="formsetaccount">
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Receipt Type</label>
                            <select class="form-control form-control-sm" name="receipttype" id="receipttype" required>
                                <option value="">Select</option>
                                <option value="1">Cash</option>
                                <option value="2">Cheque</option>
                                <option value="3">Credit</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Company</label>
                            <select class="form-control form-control-sm" name="company" id="company" required>
                                <option value="">Select</option>
                                <?php if($resultcompany->num_rows > 0) {while ($rowcompany = $resultcompany-> fetch_assoc()) { ?>
                                <option value="<?php echo $rowcompany['idtbl_company'] ?>"><?php echo $rowcompany['name'] ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Branch</label>
                            <select class="form-control form-control-sm" name="companybranch" id="companybranch" required>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Credit Account</label><br>
                            <select class="form-control form-control-sm" style="width: 100%;" name="creditaccount" id="creditaccount" required>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="col">
                            <button class="btn btn-outline-dark btn-sm fa-pull-right px-4" type="button" id="formsetaccountsubmit"><i class="fas fa-tasks mr-2"></i>Submit</button>
                            <input type="submit" id="hideformsetaccountsubmit" class="d-none">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        $('#modalaccount').modal('show');
        $('#creditaccount').select2({
            dropdownParent: $('#modalaccount')
        });
        $('#debitaccount').select2();

        $('#receipttype').change(function(){
            var typeID = $(this).val();
            var company = $('#company').val();
            var companybranch = $('#companybranch').val();

            accountlist(typeID, company, companybranch);
        });
        $('#company').change(function(){
            var company = $(this).val();
            var typeID = $('#receipttype').val();
            var companybranch = $('#companybranch').val();

            $.ajax({
                type: "POST",
                data: {
                    company: company
                },
                url: 'getprocess/getcompanybranchaccocompany.php',
                success: function(result) { //alert(result);
                    var objfirst = JSON.parse(result);

                    var html = '';
                    html += '<option value="">Select</option>';
                    $.each(objfirst, function(i, item) {
                        //alert(objfirst[i].id);
                        html += '<option value="' + objfirst[i].id + '">';
                        html += objfirst[i].branch;
                        html += '</option>';
                    });

                    $('#companybranch').empty().append(html);
                }
            });

            accountlist(typeID, company, companybranch);
        });
        $('#companybranch').change(function(){
            var company = $('#company').val();
            var typeID = $('#receipttype').val();
            var companybranch = $(this).val();

            accountlist(typeID, company, companybranch);
        });
        $('#formSearchBtn').click(function(){
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {   
                var validfrom = $('#fromdate').val();
                var validto = $('#todate').val();
                var receipttype = $('#receipttype').val();

                $('#viewpayment').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                searchresult(validfrom, validto, receipttype);
            }
        });

        $('#creditdebitbtn').click(function(){
            var validfrom = $('#fromdate').val();
            var validto = $('#todate').val();
            var creditaccount = $('#hidecreditaccount').val();
            var debitaccount = $('#hidedebitaccount').val();
            var receipttype = $('#receipttype').val();
            var company = $('#company').val();
            var companybranch = $('#companybranch').val();

            jsonObj = [];
            $("#confirmtable tbody tr").each(function() {
                item = {}
                $(this).find('td').each(function(col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });
            // console.log(jsonObj);

            $.ajax({
                type: "POST",
                data: {
                    creditaccount: creditaccount,
                    debitaccount: debitaccount,
                    receipttype: receipttype,
                    company: company,
                    companybranch: companybranch,
                    tableData: jsonObj
                },
                url: 'process/dailypaymenttoaccountprocess.php',
                success: function(result) {//alert(result);
                    action(result);

                    $('#modaltransferreceiptinfo').modal('hide');
                    $('#m ').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');
                    $('#creditaccount').val('').trigger();
                    $('#hidecreditaccount').val('');
                    searchresult(validfrom, validto, receipttype);
                }
            });
        });

        $('#formsetaccountsubmit').click(function(){
            if (!$("#formsetaccount")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hideformsetaccountsubmit").click();
            } else { 
                $('#modalaccount').modal('hide');
            }
        })
    });

    function addpaymentoption(){
        $('#btnaddtolist').click(function(){
            var creditaccount = $('#creditaccount').val();
            var debitaccount = $('#debitaccount').val();
            var tablelist = $("#paymentlisttable tbody input[type=checkbox]:checked");

            if(tablelist.length>0){
                $('#confirmtable tbody').empty();

                tablelist.each(function() {
                    var row = $(this).closest("tr");
                    var col_1 = row.find('td:eq(0)').text();
                    var col_2 = row.find('td:eq(1)').text();
                    var col_3 = row.find('td:eq(2)').text();
                    var col_4 = row.find('td:eq(3)').text();
                    var col_5 = row.find('td:eq(4)').text();
                    var col_6 = row.find('td:eq(5)').text();
                    var col_7 = row.find('td:eq(6)').text();
                    var col_8 = row.find('td:eq(7)').text();
                    var col_9 = row.find('td:eq(8)').text();
                    var col_10 = row.find('td:eq(9)').text();

                    $('#confirmtable > tbody:last').append('<tr><td>' + col_1 + '</td><td class="d-none">' + col_2 + '</td><td>' + col_3 + '</td><td>' + col_4 + '</td><td class="d-none">' + col_5 + '</td><td class="text-right">' + col_6 + '</td><td>' + col_7 + '</td><td>' + col_8 + '</td><td>' + col_9 + '</td><td>' + col_10 + '</td></tr>');
                });

                $('#titlecredit').html(creditaccount);
                $('#titledebit').html(debitaccount);
                $('#hidecreditaccount').val(creditaccount);
                $('#hidedebitaccount').val(debitaccount);

                $('#modaltransferreceiptinfo').modal('show');
            }
            else{
                $('#bodyAlert').html('<i class="fas fa-exclamation-triangle fa-3x"></i><p>Please select receipt detail.</p>');
                $('#warningmodal').modal('show');
            }
        });
        $('#selectAll').click(function (e) {
            $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
        });
    }

    function accountlist(typeID, company, companybranch){
        if(typeID!='' && company!='' && companybranch!=''){
            $.ajax({
                type: "POST",
                data: {
                    typeID: typeID,
                    company: company,
                    companybranch: companybranch
                },
                url: 'getprocess/getaccountlistaccoreceipttype.php',
                success: function(result) {//alert(result);
                    var objfirst = JSON.parse(result);

                    var html = '';
                    html += '<option value="">Select</option>';
                    $.each(objfirst, function(i, item) {
                        //alert(objfirst[i].id);
                        html += '<option value="' + objfirst[i].subaccount + '">';
                        html += objfirst[i].subaccount+' '+objfirst[i].subaccountname;
                        html += '</option>';
                    });

                    $('#creditaccount').empty().append(html);
                    $('#debitaccount').empty().append(html);
                }
            });
        }
    }

    function searchresult(validfrom, validto, receipttype){
        $.ajax({
            type: "POST",
            data: {
                validfrom: validfrom,
                validto: validto,
                receipttype: receipttype
            },
            url: 'getprocess/getpaymentinfoaccopaymenttype.php',
            success: function(result) {//alert(result);
                $('#viewpayment').html(result);
                addpaymentoption();
            }
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
