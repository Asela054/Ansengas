<?php
include "include/header.php";  

$sqlbank="SELECT `idtbl_bank`, `bankname`, `code` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`>1";
$resultbank =$conn-> query($sqlbank); 

$sqlmainclass="SELECT `code`, `class` FROM `tbl_mainclass` WHERE `status`=1";
$resultmainclass =$conn-> query($sqlmainclass); 

$sqlmainaccount="SELECT `code`, `accountname` FROM `tbl_mainaccount` WHERE `status`=1";
$resultmainaccount =$conn-> query($sqlmainaccount); 

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
                    <div class="page-header-content d-flex align-items-center justify-content-between py-3">
                        <div class="d-inline">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="server"></i></div>
                                <span>Bank Account Allocation</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form id="unallocated">
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Bank</label>
                                            <select class="form-control form-control-sm" name="bank" id="bank" required>
                                                <option value="">select</option>
                                                <?php if($resultbank->num_rows > 0) {while ($rowbank = $resultbank-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowbank['idtbl_bank'] ?>"><?php echo $rowbank['bankname'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Bank Branch</label>
                                            <select class="form-control form-control-sm" name="bankbranch" id="bankbranch" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row mt-3">
                                        <div class="col">
                                            <h5 class="title-style small"><span>Search Criteria</span></h5>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Main Class</label>
                                            <select class="form-control form-control-sm" name="mainclass" id="mainclass">
                                                <option value="">select</option>
                                                <?php while ($rowmainclass = $resultmainclass-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowmainclass['code'] ?>"><?php echo $rowmainclass['class'].'-'.$rowmainclass['code'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Main Account</label>
                                            <select class="form-control form-control-sm" name="mainaccount" id="mainaccount">
                                                <option value="">select</option>
                                                <?php while ($rowmainaccount = $resultmainaccount-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowmainaccount['code'] ?>"><?php echo $rowmainaccount['accountname'].'-'.$rowmainaccount['code'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <input type="submit" id="hidebtnsubmit" class="d-none">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-9">
                                <div class="card shadow-none">
                                    <div class="card-header">
                                        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                                    aria-controls="home" aria-selected="true">Unallocate Accounts</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                                    aria-controls="profile" aria-selected="false">Allocated Accounts</a>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                                <table class="table table-sm table-bordered table-striped" id="unallocatedtable">
                                                    <thead>
                                                        <tr>
                                                            <th>Account</th>
                                                            <th>Account Name</th>
                                                            <th class="d-none">BankID</th>
                                                            <th class="d-none">BankbranchID</th>
                                                            <th class="d-none">SubaccountID</th>
                                                            <th>Bank</th>
                                                            <th>Bank Branch</th>
                                                            <th class="text-center">Allocated</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                                <div class="w-100 text-right">
                                                    <button class="btn btn-primary btn-sm px-4" id="allocatedaccountbtn"><i class="far fa-save"></i>&nbsp;Allocated</button>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                                <table class="table table-sm table-bordered table-striped" id="allocatedtable">
                                                    <thead>
                                                        <tr>
                                                            <th>Account</th>
                                                            <th>Account Name</th>
                                                            <th>Bank</th>
                                                            <th>Bank Branch</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
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
<!-- Modal Alert -->
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content bg-danger">
            <div class="modal-body text-white">
                <div class="row">
                    <div class="col" id="bodyAlert"></div>
                </div>
                <button type="button" class="btn btn-outline-light btn-sm fa-pull-right pl-4 pr-4" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        $('#bank').change(function(){
            var bank = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    bank: bank
                },
                url: 'getprocess/getbankbranchaccobank.php',
                success: function(result) { //alert(result);
                    var objfirst = JSON.parse(result);

                    var html = '';
                    html += '<option value="">Select</option>';
                    $.each(objfirst, function(i, item) {
                        //alert(objfirst[i].id);
                        html += '<option value="' + objfirst[i].branchid + '">';
                        html += objfirst[i].branch;
                        html += '</option>';
                    });

                    $('#bankbranch').empty().append(html);
                }
            });
        });
        $('#bankbranch').change(function(){
            $('#mainclass').val('');
            $('#mainaccount').val('');
        });
        $('#mainclass').change(function(){
            if (!$("#unallocated")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidebtnsubmit").click();
            } else { 
                var mainclass = $('#mainclass').val();
                var mainaccount = $('#mainaccount').val();
                var bank = $('#bank').val();
                var bankbranch = $('#bankbranch').val();

                getaccounts(mainclass, mainaccount, bank, bankbranch);
                getallocatedaccounts(mainclass, mainaccount, bank, bankbranch)
            }
        });
        $('#mainaccount').change(function(){
            if (!$("#unallocated")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidebtnsubmit").click();
            } else { 
                var mainclass = $('#mainclass').val();
                var mainaccount = $('#mainaccount').val();
                var bank = $('#bank').val();
                var bankbranch = $('#bankbranch').val();

                getaccounts(mainclass, mainaccount, bank, bankbranch);
                getallocatedaccounts(mainclass, mainaccount, bank, bankbranch)
            }
        });
    });

    function getaccounts(mainclass, mainaccount, bank, bankbranch){
        if(mainclass!='' | mainaccount!=''){
            $.ajax({
                type: "POST",
                data: {
                    mainclass: mainclass,
                    mainaccount: mainaccount,
                    bank: bank,
                    bankbranch: bankbranch
                },
                url: 'getprocess/getunalocatedbankaccount.php',
                success: function(result) { //alert(result);
                    $('#unallocatedtable > tbody').empty().append(result);
                    allocateoption();
                }
            });
        }
    }
    function allocateoption(){
        $('#allocatedaccountbtn').click(function(){
            jsonObj = [];
            $("#unallocatedtable tbody tr").each(function() {
                if($(this).find('input[type=checkbox]').is(':checked')){
                    item = {}
                    $(this).find('td').each(function(col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                }
            });
            // console.log(jsonObj);
            if(jsonObj==''){
                $('#bodyAlert').html('<i class="fas fa-exclamation-triangle fa-pull-left fa-3x"></i><p>Please select the account numbers and then click the "Allocate" button</p>');
                $('#alertModal').modal({
                    keyboard: false,
                    backdrop: 'static'
                });
            }
            else{
                $.ajax({
                    type: "POST",
                    data: {
                        tableData: jsonObj
                    },
                    url: 'process/bankaccountallocationprocess.php',
                    success: function(result) { //alert(result);
                        action(result);
                        location.reload();
                    }
                });
            }
        });
    }
    function getallocatedaccounts(mainclass, mainaccount, bank, bankbranch){
        if(mainclass!='' | mainaccount!=''){
            $.ajax({
                type: "POST",
                data: {
                    mainclass: mainclass,
                    mainaccount: mainaccount,
                    bank: bank,
                    bankbranch: bankbranch
                },
                url: 'getprocess/getalocatedbankaccount.php',
                success: function(result) { //alert(result);
                    $('#allocatedtable > tbody').empty().append(result);
                }
            });
        }
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
<?php include "include/footer.php";?>
