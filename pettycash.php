<?php 
include "include/header.php";  

$sqlcompany="SELECT `idtbl_company`, `name`, `code` FROM `tbl_company` WHERE `status`=1";
$resultcompany =$conn-> query($sqlcompany); 

$sqlpettyref="SELECT COUNT(*) AS `count` FROM `tbl_pettycash`";
$resultpettyref=$conn->query($sqlpettyref);
$rowpettyref = $resultpettyref-> fetch_assoc();

if($rowpettyref['count']==0){
    $pettyrefcode='PTC0001';
}
else{
    $pettyrefcode='PTC000'.($rowpettyref['count']+1);
}

include "include/topnavbar.php"; 
?>
<style>
    .tableprint {
        table-layout: fixed;
    }
</style>
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
                            <span>Petty Cash Expense</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" data-toggle="modal" data-target="#modalcreatepettycash"><i class="fas fa-plus"></i>&nbsp;Create Petty Cash</button>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-striped table-bordered table-sm small nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Trans. Code</th>
                                                <th>Ref. Code</th>
                                                <th>Petty Cash Account</th>
                                                <th>Account No</th>
                                                <th>Amount</th>
                                                <th>Description</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                    </table>
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
<!-- Modal Petty Create -->
<div class="modal fade" id="modalcreatepettycash" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Create Petty Cash</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <form id="formpettycash" autocomplete="off">
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
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Branch</label>
                                    <select class="form-control form-control-sm" name="companybranch" id="companybranch" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Petty Cash Account</label>
                                    <select type="text" name="pettycashaccount" id="pettycashaccount" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col"><hr class="border-dark"></div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Opening Balance</label>
                                    <input type="text" class="form-control form-control-sm text-right font-weight-bold" id="showopen" value="" readonly>
                                    <input type="hidden" name="hideopen" id="hideopen" value="">
                                    <label class="small font-weight-bold text-dark">Not Posted Balance</label>
                                    <input type="text" class="form-control form-control-sm text-right font-weight-bold" id="shownotpost" value="" readonly>
                                    <input type="hidden" name="hidenonpost" id="hidenonpost" value="">
                                    <label class="small font-weight-bold text-dark">Closing Balance</label>
                                    <input type="text" class="form-control form-control-sm text-right font-weight-bold" id="closeshow" value="" readonly>
                                    <input type="hidden" name="hideclose" id="hideclose" value="">
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Credit</label>
                                    <input type="text" name="creditamount" id="creditamount" class="form-control form-control-sm text-right font-weight-bold" readonly>
                                    <input type="hidden" name="hidecredit" id="hidecredit" value="0">
                                    <label class="small font-weight-bold text-dark">Debit</label>
                                    <input type="text" name="debitamount" id="debitamount" class="form-control form-control-sm text-right font-weight-bold" value="0.00" readonly>
                                    <input type="hidden" name="hidedebit" id="hidedebit" value="0">
                                    <label class="small font-weight-bold text-dark">Balance</label>
                                    <input type="text" name="balanceamount" id="balanceamount" class="form-control form-control-sm text-right font-weight-bold" value="0.00" readonly>
                                    <input type="hidden" name="hidebal" id="hidebal" value="">
                                </div>                                
                            </div>
                            <div class="form-row">
                                <div class="col"><hr class="border-dark"></div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Ref. Code</label>
                                    <input type="text" name="pettyrefcode" id="pettyrefcode" class="form-control form-control-sm" value="<?php echo $pettyrefcode; ?>" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Date</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control dpd1a" placeholder="" name="pettydate" id="pettydate" required>
                                        <div class="input-group-append">
                                            <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Amount</label>
                                    <input type="text" name="pettyamount" id="pettyamount" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Expense Account</label>
                                    <select type="text" name="transaccount" id="transaccount" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Amount</label>
                                    <input type="text" name="expenseamount" id="expenseamount" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Description</label>
                                    <textarea type="text" name="textnarration" id="textnarration" class="form-control form-control-sm" required></textarea>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="button" id="formsubmit" class="btn btn-outline-primary btn-sm fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Continue</button>
                                <input name="submitpettyBtn" type="submit" value="Save" id="submitpettyBtn" class="d-none">
                            </div>
                        </form>
                    </div>
                    <div class="col-8">
                        <table class="table table-striped table-bordered table-sm small" id="tablepettycash">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Account Name</th>
                                    <th>AmountHide</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="form-group mt-2">
                            <hr>
                            <button type="button" id="btncreatepettycash" class="btn btn-outline-primary btn-sm fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Save Petty Cash</button>
                            <button type="button" class="btn btn-outline-dark btn-sm fa-pull-right mr-1" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Warning -->
<div class="modal fade" id="warningModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body bg-danger text-white text-center">
                <div id="warningdesc"></div>
            </div>
            <div class="modal-footer bg-danger rounded-0">
                <button type="button" class="btn btn-outline-light btn-sm w-100" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Payment Desc -->
<div class="modal fade" id="modalvoucher" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formpaymentvoucher">
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Enter the payment voucher description</label>
                            <textarea type="text" name="voucherdesc" id="voucherdesc" class="form-control form-control-sm" required></textarea>
                        </div>
                        <input type="hidden" id="hidepettycashid" value="">
                    </div>
                    <div class="form-group mt-3">
                        <button type="button" id="formvouchersubmit" class="btn btn-outline-primary btn-sm fa-pull-right px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-eye"></i>&nbsp;Voucher View</button>
                        <input name="submitvoucherBtn" type="submit" id="submitvoucherBtn" class="d-none">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Payment Voucher Print -->
<div class="modal fade" id="modalvoucherprint" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewvoucher"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm fa-pull-right" id="btnpettyprint"><i class="fas fa-print"></i>&nbsp;Print Voucher</button>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        $("#helpername").select2();

        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm-dd'
        });

        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#company').change(function(){
            var company = $(this).val();

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
        });
        $('#companybranch').change(function(){
            var company = $('#company').val();
            var companybranch = $(this).val();

            // Petty Cash Account GET
            $.ajax({
                type: "POST",
                data: {
                    company: company,
                    companybranch: companybranch
                },
                url: 'getprocess/getpettycashaccountaccocompany.php',
                success: function(result) { //alert(result);
                    var objfirst = JSON.parse(result);

                    var html = '';
                    html += '<option value="">Select</option>';
                    $.each(objfirst, function(i, item) {
                        //alert(objfirst[i].id);
                        html += '<option value="' + objfirst[i].subaccount + '">';
                        html += objfirst[i].subaccount+' - '+objfirst[i].subaccountname;
                        html += '</option>';
                    });

                    $('#pettycashaccount').empty().append(html);
                }
            });
            // Account List GET
            $.ajax({
                type: "POST",
                data: {
                    company: company,
                    companybranch: companybranch
                },
                url: 'getprocess/getaccountlistaccocompany.php',
                success: function(result) { //alert(result);
                    var objfirst = JSON.parse(result);

                    var html = '';
                    html += '<option value="">Select</option>';
                    $.each(objfirst, function(i, item) {
                        //alert(objfirst[i].id);
                        html += '<option value="' + objfirst[i].subaccount + '">';
                        html += objfirst[i].subaccount+' - '+objfirst[i].subaccountname;
                        html += '</option>';
                    });

                    $('#transaccount').empty().append(html);
                }
            });
        });
        $('#pettycashaccount').change(function(){
            var pettyaccount = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    pettyaccount: pettyaccount
                },
                url: 'getprocess/getopencloasebalancepettycash.php',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#showopen').val(obj.openbalshow);
                    $('#hideopen').val(obj.openbal);
                    $('#shownotpost').val(obj.nonpostshow);
                    $('#hidenonpost').val(obj.nonpost);
                    $('#closeshow').val(obj.closebalshow);
                    $('#hideclose').val(obj.closebal);
                }
            });
        });
        $("#pettyamount").keyup(function(){
            var value = $(this).val();
            var valueshow = addCommas($(this).val());

            var closebal = parseFloat($('#hideclose').val());

            $("#creditamount").val(valueshow);
            $("#hidecredit").val(value);

            var checkval = parseFloat(value);

            if(closebal<checkval){ 
                $("#pettyamount").addClass('bg-danger-soft');
                $('#formsubmit').attr('disabled', true);
            }
            else{
                $("#pettyamount").removeClass('bg-danger-soft');
                $('#formsubmit').attr('disabled', false);
            }
        });
        $("#expenseamount").keyup(function(){
            var valueex = $('#expenseamount').val();

            var pettyamount = parseFloat($('#pettyamount').val());
            var checkval = parseFloat(valueex);

            if(pettyamount<checkval){ 
                $("#expenseamount").addClass('bg-danger-soft');
                $('#formsubmit').attr('disabled', true);
            }
            else{
                $("#expenseamount").removeClass('bg-danger-soft');
                $('#formsubmit').attr('disabled', false);
            }
        });
        $('#formsubmit').click(function(){
            if (!$("#formpettycash")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitpettyBtn").click();
            } else {
                var pettyamount = parseFloat($("#pettyamount").val());
                var expensamount = parseFloat($("#expenseamount").val());
                var transaccount = $("#transaccount").val();
                var accountname = $("#transaccount option:selected").text();
                var textnarration = $("#textnarration").val();

                var hidedebit = parseFloat($('#hidedebit').val());

                var debitamount = parseFloat(expensamount+hidedebit);
                var debitamountshow = addCommas(debitamount);

                var balance = (pettyamount-debitamount);
                var balanceshow = addCommas(balance);

                var expenshow = addCommas(expensamount);

                $('#hidedebit').val(debitamount);
                $('#debitamount').val(debitamountshow);

                $('#hidebal').val(balance);
                $('#balanceamount').val(balanceshow);

                $('#tablepettycash > tbody:last').append('<tr class="pointer"><td>' + transaccount + '</td><td>' + accountname + '</td><td class="">' + expensamount + '</td><td class="text-right">' + expenshow + '</td><td>' + textnarration + '</td></tr>');

                $("#pettyamount").attr('readonly', true);
                if(balance>0){
                    $('#transaccount').val('').focus();
                    $('#btncreatepettycash').attr('disabled', true);
                }
                else{
                    $('#transaccount').val('');
                    $('#formsubmit').attr('disabled', true);
                    $('#btncreatepettycash').attr('disabled', false);
                }
                $('#textnarration').val('');
                $('#expenseamount').val('');
            }
        });
        $('#btncreatepettycash').click(function(){
            var tbody = $("#tablepettycash tbody");

            if (tbody.children().length > 0) {
                jsonObj = [];
                $("#tablepettycash tbody tr").each(function() {
                    item = {}
                    $(this).find('td').each(function(col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });
                // console.log(jsonObj);

                var pettydate = $('#pettydate').val();
                var openbal = $('#hideclose').val();
                var pettycashaccount = $('#pettycashaccount').val();
                var company = $('#company').val();
                var companybranch = $('#companybranch').val();

                $.ajax({
                    type: "POST",
                    data: {
                        tableData: jsonObj,
                        pettydate: pettydate,
                        openbal: openbal,
                        pettycashaccount: pettycashaccount,
                        company: company,
                        companybranch: companybranch
                    },
                    url: 'process/pettycashprocess.php',
                    success: function(result) { //alert(result);
                        $('#modalcreatepettycash').modal('hide');
                        action(result);
                        location.reload();
                    }
                });
            }
        });

        $('#dataTable').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/pettcashlist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_pettycash"
                },
                {
                    "data": "date"
                },
                {
                    "data": "transcode"
                },
                {
                    "data": "refcode"
                },
                {
                    "data": "pettyaccount"
                },
                {
                    "data": "debitaccount"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var amount = parseFloat(full['amount']).toFixed(2);
                        return addCommas(amount);
                    }
                },
                {
                    "data": "desc"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';

                        button+='<button class="btn btn-outline-primary btn-sm mr-1 btnprint" data-toggle="tooltip" data-placement="bottom" title="Print Order" id="'+full['idtbl_pettycash']+'" ';if(full['poststatus']==0){button+='disabled';}button+='><i class="fas fa-print"></i></button>';

                        button+='<a href="process/statuspettycashvoucher.php?record='+full['idtbl_pettycash']+'&type=3" onclick="return delete_confirm()" target="_self" class="btn btn-outline-danger btn-sm mr-1 ';if(full['poststatus']==1){button+='d-none';}button+='"><i class="fas fa-trash-alt"></i></a>';
                        
                        return button;
                    }
                }
            ]
        } );
        $('#dataTable tbody').on('click', '.btnprint', function() {
            var pettyID = $(this).attr('id');
            $('#modalvoucher').modal('show');
            $('#hidepettycashid').val(pettyID);
        });

        $('#formvouchersubmit').click(function(){
            if (!$("#formpaymentvoucher")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitvoucherBtn").click();
            } else {
                var pettycashID = $('#hidepettycashid').val();
                var voucherdesc = $('#voucherdesc').val();
                $('#modalvoucher').modal('hide');

                $.ajax({
                    type: "POST",
                    data: {
                        pettycashID: pettycashID,
                        voucherdesc: voucherdesc
                    },
                    url: 'getprocess/getpettycashvoucheraccopettycash.php',
                    success: function(result) { //alert(result);
                        $('#viewvoucher').html(result);

                        $('#modalvoucherprint').modal('show');
                    }
                });
            }
        });
        document.getElementById('btnpettyprint').addEventListener ("click", printvoucher);
    });

    function tabletotal(){
        var sum = 0;
        $(".total").each(function(){
            sum += parseFloat($(this).text());
        });
        
        var showsum = addCommas(parseFloat(sum).toFixed(2));

        $('#divtotal').html('Rs. '+showsum);
        $('#hidetotalorder').val(sum);
    }

    function addCommas(nStr){
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
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

    function printvoucher() {
        printJS({
            printable: 'viewvoucher',
            type: 'html',
            style: '@page { size: A4 portrait; margin:0.25cm; margin-left:0.50cm; }',
            targetStyles: ['*']
        })
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }
</script>
<?php include "include/footer.php"; ?>
