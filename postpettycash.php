<?php 
include "include/header.php";  

$sqlcompany="SELECT `idtbl_company`, `name`, `code` FROM `tbl_company` WHERE `status`=1";
$resultcompany =$conn-> query($sqlcompany); 

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
                            <span>Post Petty Cash</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form id="formsearch">
                                    <!-- <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Opening Balance</label>
                                            <input type="text" name="" id="" class="form-control form-control-sm" readonly>
                                            <label class="small font-weight-bold text-dark">Amount</label>
                                            <input type="text" name="" id="" class="form-control form-control-sm" readonly>
                                            <label class="small font-weight-bold text-dark">Closing Balance</label>
                                            <input type="text" name="" id="" class="form-control form-control-sm" readonly>
                                        </div>                         
                                    </div>
                                    <div class="form-row">
                                        <div class="col"><hr class="border-dark"></div>
                                    </div> -->
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
                                            <label class="small font-weight-bold text-dark">Date</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control dpd1a" placeholder="" name="pettycashdate" id="pettycashdate" required>
                                                <div class="input-group-append">
                                                    <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                                </div>
                                            </div> 
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
                                    <div class="form-group mt-3">
                                        <button type="button" id="formsubmit" class="btn btn-outline-primary btn-sm fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-eye"></i>&nbsp;View Petty Cash</button>
                                        <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                                    </div>
                                </form>
                            </div>
                            <div class="col-9">
                                <h6 class="title-style small mt-1"><span>List of Petty Cash Expense</span></h6>
                                <table class="table table-striped table-bordered table-sm small" id="posttable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Account No</th>
                                            <th>Desc</th>
                                            <th>AmountHide</th>
                                            <th>Amount</th>
                                            <th class="text-center">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input checkallocate" id="selectAll">
                                                    <label class="custom-control-label" for="selectAll"></label>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyexpenses"></tbody>
                                </table>
                                <hr>
                                <button type="button" id="btnpostpettycash" class="btn btn-outline-secondary btn-sm fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Post Petty Cash</button>
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
        $("#helpername").select2();

        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
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
        });

        $('#formsubmit').click(function(){
            if (!$("#formsearch")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {
                var pettycashaccount = $('#pettycashaccount').val();
                var pettycashdate = $('#pettycashdate').val();

                $.ajax({
                    type: "POST",
                    data: {
                        pettycashaccount: pettycashaccount,
                        pettycashdate: pettycashdate
                    },
                    url: 'getprocess/getpettycashexpensesaccoaccount.php',
                    success: function(result) { //alert(result);
                        $('#tbodyexpenses').html(result);
                    }
                });
            }
        });

        $('#selectAll').click(function (e) {
            $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
        });

        $('#btnpostpettycash').click(function(){
            var tablelist = $("#posttable tbody input[type=checkbox]:checked");

            if (tablelist.length > 0) {
                jsonObj = [];
                tablelist.each(function() {
                    item = {}
                    $(this).closest("tr").find('td').each(function(col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });
                // console.log(jsonObj);

                var company = $('#company').val();
                var companybranch = $('#companybranch').val();

                $.ajax({
                    type: "POST",
                    data: {
                        tableData: jsonObj,
                        company: company,
                        companybranch: companybranch
                    },
                    url: 'process/pettycashpostprocess.php',
                    success: function(result) { //alert(result);
                        action(result);
                        location.reload();
                    }
                });
            }
        });
    });


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
</script>
<?php include "include/footer.php"; ?>
