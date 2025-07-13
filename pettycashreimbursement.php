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
                            <span>Petty Cash Voucher</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" data-toggle="modal" data-target="#modalcreatepettycash"><i class="fas fa-plus"></i>&nbsp;Create Petty Cash Voucher</button>
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
                                                <th>Desc</th>
                                                <th>Company</th>
                                                <th>Branch</th>
                                                <th>Debit Account</th>
                                                <th>Credit Account</th>
                                                <th>Cheque No</th>
                                                <th>Amount</th>
                                                <th>Status</th>
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
<!-- Modal Petty Voucher Create -->
<div class="modal fade" id="modalcreatepettycash" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Create Petty Cash Voucher</h5>
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
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Bank Account</label>
                                    <select type="text" name="bankaccount" id="bankaccount" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-8">
                        <table class="table table-striped table-bordered table-sm small" id="tablepettycash">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Post No</th>
                                    <th>Date</th>
                                    <th>Ref. Code</th>
                                    <th>Description</th>
                                    <th class="text-center">C/D</th>
                                    <th class="d-none">AmountHide</th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input checkallocate" id="selectAll">
                                            <label class="custom-control-label" for="selectAll"></label>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tbodyvoucherlist"></tbody>
                        </table>
                        <div class="row">
                            <div class="col-12 text-right">
                                <h4 id="totalhtml">Total : 0.00</h4>
                                <input type="hidden" name="totalamount" id="totalamount">
                                <hr>
                            </div>
                        </div>
                        <form id="formdesc">
                            <div class="form-row">
                                <div class="col">
                                    &nbsp;
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Cheque No</label>
                                    <input type="text" name="chequeno" id="chequeno" class="form-control form-control-sm" required>
                                    <label class="small font-weight-bold text-dark">Description</label>
                                    <textarea name="descvoucher" id="descvoucher" class="form-control form-control-sm" required></textarea>
                                </div>
                            </div>
                            <input type="submit" class="d-none" id="hidesubmitbtn">
                        </form>                        
                        <div class="form-group mt-2">
                            <hr>
                            <button type="button" id="btncreatepettycash" class="btn btn-outline-primary btn-sm fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Save Petty Cash Voucher</button>
                            <button type="button" class="btn btn-outline-dark btn-sm fa-pull-right mr-1" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Payment Voucher View -->
<div class="modal fade" id="modalvoucherview" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="mx-2">View Voucher information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewvoucherinfo"></div>
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
                url: 'getprocess/getbankaccountlistaccocompany.php',
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

                    $('#bankaccount').empty().append(html);
                }
            });
        });
        $('#pettycashaccount').change(function(){
            var accountno = $(this).val();
            var company = $('#company').val();
            var companybranch = $('#companybranch').val();

            $.ajax({
                type: "POST",
                data: {
                    accountno: accountno,
                    company: company,
                    companybranch: companybranch
                },
                url: 'getprocess/getpettycashinfoaccoaccount.php',
                success: function(result) { //alert(result);
                    $('#tbodyvoucherlist').html(result);
                    pettycashoption();
                }
            });
        });

        $('#btncreatepettycash').click(function(){
            var tbody = $("#tablepettycash tbody");

            if (tbody.children().length > 0) {
                if (!$("#formdesc")[0].checkValidity()) {
                    // If the form is invalid, submit it. The form won't actually submit;
                    // this will just cause the browser to display the native HTML5 error messages.
                    $("#hidesubmitbtn").click();
                } else {
                    jsonObj = [];
                    $("#tablepettycash tbody tr").each(function() {
                        item = {}
                        $(this).find('td').each(function(col_idx) {
                            item["col_" + (col_idx + 1)] = $(this).text();
                        });
                        jsonObj.push(item);
                    });
                    // console.log(jsonObj);

                    var pettycashaccount = $('#pettycashaccount').val();
                    var bankaccount = $('#bankaccount').val();
                    var company = $('#company').val();
                    var companybranch = $('#companybranch').val();
                    var totalamount = $('#totalamount').val();
                    var chequeno = $('#chequeno').val();
                    var descvoucher = $('#descvoucher').val();

                    $.ajax({
                        type: "POST",
                        data: {
                            tableData: jsonObj,
                            pettycashaccount: pettycashaccount,
                            bankaccount: bankaccount,
                            company: company,
                            companybranch: companybranch,
                            totalamount: totalamount,
                            chequeno: chequeno,
                            descvoucher: descvoucher
                        },
                        url: 'process/pettycashvoucherprocess.php',
                        success: function(result) { //alert(result);
                            $('#modalcreatepettycash').modal('hide');
                            action(result);
                            location.reload();
                        }
                    });
                }
            }
        });

        $('#selectAll').click(function (e) {
            $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
            totalcal();
        });

        $('#dataTable').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/pettcashreimbursmentlist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_pettycash_voucher"
                },
                {
                    "data": "date"
                },
                {
                    "data": "desc"
                },
                {
                    "data": "name"
                },
                {
                    "data": "branch"
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        return full['debitaccount']+' - '+full['debitaccountname']
                    }
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        return full['creditaccount']+' - '+full['creditaccountname']
                    }
                },
                {
                    "data": "chequeno"
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
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['approvestatus']==1){return '<span class="text-success"><i class="fas fa-check"></i>&nbsp;Approved</span>';}
                        else{return '<span class="text-danger"><i class="fas fa-times"></i>&nbsp;Not Approve</span>';}
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';

                        button+='<button class="btn btn-outline-dark btn-sm mr-1 btnview" data-toggle="tooltip" data-placement="bottom" title="Print Order" id="'+full['idtbl_pettycash_voucher']+'"><i class="fas fa-eye"></i></button>';

                        if(statuscheck==1 && full['approvestatus']==1){
                            button+='<button class="btn btn-outline-success btn-sm mr-1"><i class="fas fa-check-double"></i></button>';
                        }
                        else if(statuscheck==1 && full['approvestatus']==0){
                            button+='<a href="process/statuspettycashreimbursment.php?record='+full['idtbl_pettycash_voucher']+'&type=4" onclick="return approve_confirm()" target="_self" class="btn btn-outline-danger btn-sm mr-1"><i class="fas fa-times"></i></a>';
                        }

                        button+='<button class="btn btn-outline-primary btn-sm mr-1 btnprint" data-toggle="tooltip" data-placement="bottom" title="Print Order" id="'+full['idtbl_pettycash_voucher']+'" ';if(full['approvestatus']==0){button+='disabled';}button+='><i class="fas fa-print"></i></button>';

                        button+='<a href="process/statuspettycashreimbursment.php?record='+full['idtbl_pettycash_voucher']+'&type=3" onclick="return delete_confirm()" target="_self" class="btn btn-outline-danger btn-sm mr-1 ';if(full['approvestatus']==1){button+='d-none';}button+='"><i class="fas fa-trash-alt"></i></a>';
                        
                        return button;
                    }
                }
            ]
        } );
        $('#dataTable tbody').on('click', '.btnprint', function() {
            var pettyvoucherID = $(this).attr('id');
            
            $('#modalvoucher').modal('hide');

            $.ajax({
                type: "POST",
                data: {
                    pettyvoucherID: pettyvoucherID
                },
                url: 'getprocess/getpettycashvoucherprint.php',
                success: function(result) { //alert(result);
                    $('#viewvoucher').html(result);

                    $('#modalvoucherprint').modal('show');
                }
            });
        });
        $('#dataTable tbody').on('click', '.btnview', function() {
            var pettyID = $(this).attr('id');

            $.ajax({
                type: "POST",
                data: {
                    pettyvoucher: pettyID
                },
                url: 'getprocess/getpettycashvoucherinfoaccovoucher.php',
                success: function(result) { //alert(result);
                    $('#viewvoucherinfo').html(result);
                    $('#modalvoucherview').modal('show');
                }
            });
        });

        document.getElementById('btnpettyprint').addEventListener ("click", printvoucher);
    });

    function totalcal(){
        var sum = 0;
        var tablelist = $("#tablepettycash tbody input[type=checkbox]:checked").closest("tr").find('td:eq(6)');

        tablelist.each(function() {
            sum += parseFloat($(this).text());
        });
        $('#totalamount').val(sum);
        $('#totalhtml').html('Rs '+addCommas(parseFloat(sum).toFixed(2)));
    }

    function pettycashoption(){
        $(".checkallocate").change(function(){
            totalcal();
        });
    }

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

    function approve_confirm() {
        return confirm("Are you sure you want to approve this?");
    }

    function notapprove_confirm() {
        return confirm("Are you sure you want to cancel this?");
    }
</script>
<?php include "include/footer.php"; ?>
