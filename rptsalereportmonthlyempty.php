<?php 
include "include/header.php";  

$sqlcustomer="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1";
$resultcustomer =$conn-> query($sqlcustomer);

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
                            <span>Sale Report Empty Monthly</span>
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
                                            <input type="month" class="form-control form-control-sm" placeholder=""
                                                name="fromdate" id="fromdate" max="<?php echo date('Y-m', strtotime('last month')); ?>" required>
                                        </div>
                                        <div class="col-2">
                                            <label class="small font-weight-bold text-dark">To*</label>
                                            <input type="month" class="form-control form-control-sm" placeholder=""
                                                name="todate" id="todate" max="<?php echo date('Y-m', strtotime('last month')); ?>" required>
                                        </div>
                                        <div class="col-2">
                                            <label class="small font-weight-bold text-dark">Report Type</label><br>
                                            <select class="form-control form-control-sm rounded-1" name="type" id="typeSelector">
                                                <option value="">Select Type</option>
                                                <option value="1">Customer</option>
                                                <option value="2">Sales Executive</option>
                                                <option value="3">Lorry</option>
                                                <option value="4">Driver</option>
                                                <option value="5">Area</option>
                                            </select>
                                        </div>

                                        <div id="customerDropdown" class="col-3">
                                            <label class="small font-weight-bold text-dark">Customer | Sales Excutive | Lorry | Driver | Area</label>
                                            <select class="form-control form-control-sm" name="dataselector" id="dataselector" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>

                                        <div id="customerTypeDiv" class="col-1" style="display: none;">
                                            <label class="small font-weight-bold text-dark">Customer Type</label>
                                            <select name="cusType" id="cusType" class="form-control form-control-sm">
                                                <option value="">Select</option>
                                                <option value="1">Commercial</option>
                                                <option value="2">Dealer</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-sm btn-outline-dark px-4" type="button" id="formSearchBtn" style="margin-top:30px;"><i class="fas fa-search"></i>&nbsp;Search</button>
                                        </div>
                                    </div>
                                    <input type="submit" class="d-none" id="hidesubmit">
                                </form>
                            </div>
                            <div class="col-12">
                                <form action="export/exportsalesreportemptymonthly.php" method="post" id="convert_form">
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            <input type="hidden" name="file_content" id="file_content">
                                            <button type="button" id="btnconvert" class="btn btn-success btn-sm px-4 mb-3" disabled><i class="fas fa-file-excel mr-2"></i>Excel</button>  
                                        </div>
                                    </div>   
                                    <hr class="border-dark">
                                    <div class="scrollbar pb-3" id="style-2">
                                        <div id="targetviewdetail"></div>   
                                    </div>
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
        $('.dpd1a').datepicker('remove');
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

        $('#dataselector').change(function() {
            var type = $('#typeSelector').val();
            if (type == 2) {
                $('#customerTypeDiv').show();
            } else {
                $('#customerTypeDiv').hide();
            }
        });

        $('#typeSelector').change(function(){
            $('#dataselector').val(null).trigger('change');
            if($(this).val()==1){$('#dataselector').prop('required', false);}
            else{$('#dataselector').prop('required', true);}
        });
        $("#dataselector").select2({
            ajax: {
                url: "getprocess/fetch_data.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                        type: $('#typeSelector').val()
                    };
                },
                processResults: function (response) {//console.log(response);
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('#formSearchBtn').click(function(){
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {   
                var validfrom = $('#fromdate').val();
                var validto = $('#todate').val();
                var typeSelector = $('#typeSelector').val();
                var dataselector = $('#dataselector').val();
                var cusType = $('#cusType').val();

                $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        validfrom: validfrom,
                        validto: validto,
                        typeSelector: typeSelector,
                        dataselector: dataselector,
                        cusType: cusType
                    },
                    url: 'getprocess/getmonthlysaleemptyreport.php',
                    success: function(result) {//alert(result);
                        // var obj = JSON.parse(result);
                        $('#btnconvert').prop('disabled', false);
                        $('#targetviewdetail').html(result);
                    }
                });
            }
        });

        $('#btnconvert').click(function(){
            var table_content = '<table>';
            table_content += $('#customerProductReport').html();
            table_content += '</table>';
            $('#file_content').val(table_content); 
            $('#convert_form').submit();
        });
    });
</script>
<?php include "include/footer.php"; ?>
