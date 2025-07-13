<?php
include "include/header.php";  

$sqlcompany="SELECT `idtbl_company`, `name`, `code` FROM `tbl_company` WHERE `status`=1";
$resultcompany =$conn-> query($sqlcompany); 

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
                                <span>Account Block</span>
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
                                            <label class="small font-weight-bold text-dark">Company</label>
                                            <select class="form-control form-control-sm" name="company" id="company" required>
                                                <option value="">select</option>
                                                <?php if($resultcompany->num_rows > 0) {while ($rowcompany = $resultcompany-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowcompany['idtbl_company'] ?>"><?php echo $rowcompany['name'] ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Company Branch</label>
                                            <select class="form-control form-control-sm" name="companybranch" id="companybranch" required>
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
                                                <table class="table table-sm table-bordered table-striped" id="unblocktable">
                                                    <thead>
                                                        <tr>
                                                            <th>AccountID</th>
                                                            <th>Account</th>
                                                            <th>Account Name</th>
                                                            <th>Company</th>
                                                            <th>Company Branch</th>
                                                            <th class="text-center">Blocked</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                                <div class="w-100 text-right">
                                                    <button class="btn btn-primary btn-sm px-4" id="accountblockbtn"><i class="far fa-save"></i>&nbsp;Blocked Accounts</button>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                                <table class="table table-sm table-bordered table-striped" id="blocktable">
                                                    <thead>
                                                        <tr>
                                                            <th>AccountID</th>
                                                            <th>Account</th>
                                                            <th>Account Name</th>
                                                            <th>Company</th>
                                                            <th>Company Branch</th>
                                                            <th class="text-center">Unblocked</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                                <div class="w-100 text-right">
                                                    <button class="btn btn-primary btn-sm px-4" id="accountunblockbtn"><i class="far fa-save"></i>&nbsp;Unblocked Accounts</button>
                                                </div>
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
                var company = $('#company').val();
                var companybranch = $('#companybranch').val();

                getblockaccounts(mainclass, mainaccount, company, companybranch);
                getunblockaccounts(mainclass, mainaccount, company, companybranch)
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
                var company = $('#company').val();
                var companybranch = $('#companybranch').val();

                getblockaccounts(mainclass, mainaccount, company, companybranch);
                getunblockaccounts(mainclass, mainaccount, company, companybranch)
            }
        });
        $('#accountunblockbtn').click(function(){
            jsonObj = [];
            $("#blocktable tbody tr").each(function() {
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
                        tableData: jsonObj,
                        type: '1'
                    },
                    url: 'process/accountblockunblockprocess.php',
                    success: function(result) { //alert(result);
                        action(result);
                        location.reload();
                    }
                });
            }
        });
        $('#accountblockbtn').click(function(){
            jsonObj = [];
            $("#unblocktable tbody tr").each(function() {
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
                        tableData: jsonObj,
                        type: '2'
                    },
                    url: 'process/accountblockunblockprocess.php',
                    success: function(result) { //alert(result);
                        action(result);
                        location.reload();
                    }
                });
            }
        });
    });

    function getblockaccounts(mainclass, mainaccount, company, companybranch){
        if(mainclass!='' | mainaccount!=''){
            $.ajax({
                type: "POST",
                data: {
                    mainclass: mainclass,
                    mainaccount: mainaccount,
                    company: company,
                    companybranch: companybranch
                },
                url: 'getprocess/getunblockaccount.php',
                success: function(result) { //alert(result);
                    $('#unblocktable > tbody').empty().append(result);
                }
            });
        }
    }
    function getunblockaccounts(mainclass, mainaccount, company, companybranch){
        if(mainclass!='' | mainaccount!=''){
            $.ajax({
                type: "POST",
                data: {
                    mainclass: mainclass,
                    mainaccount: mainaccount,
                    company: company,
                    companybranch: companybranch
                },
                url: 'getprocess/getablockaccount.php',
                success: function(result) { //alert(result);
                    $('#blocktable > tbody').empty().append(result);
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
