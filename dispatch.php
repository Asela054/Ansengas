<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_dispatch` WHERE `status`=1";
$result=$conn->query($sql);

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 

$sqlorder="SELECT `idtbl_porder` FROM `tbl_porder` WHERE `status`=1 AND `confirmstatus`=1 AND `dispatchissue`=0";
$resultorder =$conn-> query($sqlorder); 

$sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=0 AND `status`=1";
$resultvehicle =$conn-> query($sqlvehicle); 

$sqlvehicletrailer="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=1 AND `status`=1";
$resultvehicletrailer =$conn-> query($sqlvehicletrailer);

$sqldiverlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=4 AND `status`=1";
$resultdiverlist =$conn-> query($sqldiverlist);

$sqlofficerlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=6 AND `status`=1";
$resultofficerlist =$conn-> query($sqlofficerlist);

$sqlreflist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=7 AND `status`=1";
$resultreflist =$conn-> query($sqlreflist);

$sqlarealist="SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1";
$resultarealist =$conn-> query($sqlarealist);

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
                            <div class="page-header-icon"><i data-feather="list"></i></div>
                            <span>Dispatch</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" data-toggle="modal" data-target="#modalcreatedispatch"><i class="fas fa-plus"></i>&nbsp;Create Dispatch</button>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped table-bordered table-sm" id="dispatchview">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Dispatch No</th>
                                            <th>Type</th>
                                            <th>Date</th>
                                            <th>Vehicle</th>
                                            <th class="text-right">Net Total</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row=$result->fetch_assoc()){ ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_dispatch']; ?></td>
                                            <td><?php echo 'DIS-'.$row['idtbl_dispatch']; ?></td>
                                            <td><?php if($row['distype']==1){echo "Laugfs Dispatch";}else{echo "Other";} ?></td>
                                            <td><?php echo $row['date']; ?></td>
                                            <td><?php $vehID=$row['vehicle_id']; $sqlveh="SELECT `vehicleno` FROM `tbl_vehicle` WHERE `idtbl_vehicle`='$vehID'"; $resultveh=$conn->query($sqlveh); $rowveh=$resultveh->fetch_assoc(); echo $rowveh['vehicleno']; ?></td>
                                            <td class="text-right"><?php echo number_format($row['nettotal'],2); ?></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-outline-dark btn-sm btnviewdispatch" id="<?php echo $row['idtbl_dispatch']; ?>"><i class="fas fa-eye"></i></button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<!-- Modal Dispatch Create -->
<div class="modal fade" id="modalcreatedispatch" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Create Dispatch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-3">
                        <form id="formdispatch" autocomplete="off">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Dispatch Type</label>
                                <select name="dispatchtype" id="dispatchtype" class="form-control form-control-sm" required>
                                    <option value="">Select</option>
                                    <option value="1">Laugfs Dispatch</option>
                                    <option value="2">Other</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Purches Order</label>
                                <select name="ponumber" id="ponumber" class="form-control form-control-sm" disabled>
                                    <option value="">Select</option>
                                    <?php if($resultorder->num_rows > 0) {while ($roworder = $resultorder-> fetch_assoc()) { ?>
                                    <option value="<?php echo $roworder['idtbl_porder'] ?>"><?php echo 'PO-'.$roworder['idtbl_porder'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Product*</label>
                                <select class="form-control form-control-sm" name="product" id="product" required>
                                    <option value="">Select</option>
                                    <?php if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowproduct['idtbl_product'] ?>"><?php echo $rowproduct['product_name'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Refill*</label>
                                    <input type="text" id="fillqty" name="fillqty" class="form-control form-control-sm" value="0" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Return</label>
                                    <input type="text" id="reqty" name="reqty" class="form-control form-control-sm" value="0" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">New</label>
                                    <input type="text" id="newqty" name="newqty" class="form-control form-control-sm" value="0" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="button" id="formsubmit" class="btn btn-outline-primary btn-sm fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add Product</button>
                                <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                            </div>
                            <input type="hidden" name="unitprice" id="unitprice" value="">
                            <input type="hidden" name="newsaleprice" id="newsaleprice" value="">
                            <input type="hidden" name="refillsaleprice" id="refillsaleprice" value="">
                            <input type="hidden" name="refillprice" id="refillprice" value="">
                        </form>
                    </div>
                    <div class="col-9">
                        <table class="table table-striped table-bordered table-sm small" id="tabledispatch">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="d-none">ProductID</th>
                                    <th class="d-none">UnitPrice</th>
                                    <th class="d-none">Newsaleprice</th>
                                    <th class="d-none">Refillsaleprice</th>
                                    <th class="d-none">Refillprice</th>
                                    <th class="text-center">Refill Qty</th>
                                    <th class="text-center">Return Qty</th>
                                    <th class="text-center">New Qty</th>
                                    <th class="d-none">HideTotal</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody id="tbodydispatchcreate"></tbody>
                        </table>
                        <div class="row">
                            <div class="col text-right"><h1 class="font-weight-600" id="divtotal">Rs. 0.00</h1></div>
                            <input type="hidden" id="hidetotalorder" value="0">
                        </div>
                        <div class="form-group mt-2">
                            <hr>
                            <button type="button" id="btncreatedispatch" class="btn btn-outline-primary btn-sm fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Create Dispatch</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Dispatch -->
<div class="modal fade" id="modaldispatchdetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewdispatchprint"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm fa-pull-right" id="btndispatchprint"><i class="fas fa-print"></i>&nbsp;Print Dispatch</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Dispatch -->
<div class="modal fade" id="modaltransportdetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transport Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form id="otherform" method="post">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Lorry No*</label>
                                <select name="lorrynum" id="lorrynum" class="form-control form-control-sm" required>
                                    <option value="">Select</option>
                                    <?php if($resultvehicle->num_rows > 0) {while ($rowvehicle = $resultvehicle-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowvehicle['idtbl_vehicle'] ?>"><?php echo $rowvehicle['vehicleno'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Trailer*</label>
                                <select name="trailernum" id="trailernum" class="form-control form-control-sm" required>
                                    <option value="">Select</option>
                                    <?php if($resultvehicletrailer->num_rows > 0) {while ($rowvehicletrailer = $resultvehicletrailer-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowvehicletrailer['idtbl_vehicle'] ?>"><?php echo $rowvehicletrailer['vehicleno'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Driver*</label>
                                <select name="drivername" id="drivername" class="form-control form-control-sm" required>
                                    <option value="">Select</option>
                                    <?php if($resultdiverlist->num_rows > 0) {while ($rowdiverlist = $resultdiverlist-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowdiverlist['idtbl_employee'] ?>"><?php echo $rowdiverlist['name'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Officer*</label>
                                <select name="officername" id="officername" class="form-control form-control-sm" required>
                                    <option value="">Select</option>
                                    <?php if($resultofficerlist->num_rows > 0) {while ($rowofficerlist = $resultofficerlist-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowofficerlist['idtbl_employee'] ?>"><?php echo $rowofficerlist['name'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Ref Name*</label>
                                <select name="refname" id="refname" class="form-control form-control-sm" required>
                                    <option value="">Select</option>
                                    <?php if($resultreflist->num_rows > 0) {while ($rowreflist = $resultreflist-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowreflist['idtbl_employee'] ?>"><?php echo $rowreflist['name'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Area*</label>
                                <select name="area" id="area" class="form-control form-control-sm" required>
                                    <option value="">Select</option>
                                    <?php if($resultarealist->num_rows > 0) {while ($rowarealist = $resultarealist-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowarealist['idtbl_area'] ?>"><?php echo $rowarealist['area'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <button type="button" id="btnothersubmit" class="btn btn-outline-primary btn-sm fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Issue Dispatch</button>
                                <input name="othersubmitBtn" type="submit" id="othersubmitBtn" class="d-none">
                                <input name="otherresetBtn" type="reset" id="otherresetBtn" class="d-none">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        $('#dispatchview').DataTable();
        $('#dispatchview tbody').on('click', '.btnviewdispatch', function() {
            var dispatchID=$(this).attr('id');

            $.ajax({
                type: "POST",
                data: {
                    dispatchID : dispatchID
                },
                url: 'getprocess/getdispatchdetail.php',
                success: function(result) {//alert(result);
                    $('#viewdispatchprint').html(result);
                    $('#modaldispatchdetail').modal('show');
                }
            }); 
        });
        // Dispatch print part
        document.getElementById('btndispatchprint').addEventListener ("click", print);
        // Dispatch type part
        $('#dispatchtype').change(function(){
            var distype = $(this).val();

            if(distype==1){
                $('#ponumber').prop("disabled", false);
                $('#fillqty').prop("readonly", true);
                $('#reqty').prop("readonly", true);
                $('#newqty').prop("readonly", true);
                $('#product').prop("disabled", true);
                $('#formsubmit').prop("disabled", true);
                $('#tbodydispatchcreate').html('');
                tabletotal();
            }
            else{
                $('#ponumber').val('');
                $('#ponumber').prop("disabled", true);
                $('#fillqty').prop("readonly", false);
                $('#reqty').prop("readonly", false);
                $('#newqty').prop("readonly", false);
                $('#product').prop("disabled", false);
                $('#formsubmit').prop("disabled", false);
                $('#tbodydispatchcreate').html('');
                tabletotal();
            }
        });
        $('#ponumber').change(function(){
            var ponumber = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    ponumber : ponumber
                },
                url: 'getprocess/getporderinfo.php',
                success: function(result) {//alert(result);
                    $('#tbodydispatchcreate').html(result);
                    tabletotal();
                }
            });
        });
        // Prodcut part
        $('#product').change(function(){
            var productID = $(this).val();
            var distype = $('#dispatchtype').val();

            $.ajax({
                type: "POST",
                data: {
                    productID: productID,
                    distype: distype
                },
                url: 'getprocess/getqtyaccoproduct.php',
                success: function(result) {//alert(result);
                    var obj = JSON.parse(result);
                    if(distype==2){
                        $('#newsaleprice').val(obj.newsaleprice);
                        $('#refillsaleprice').val(obj.refillsaleprice);
                        $('#unitprice').val(obj.unitprice);
                        $('#refillprice').val(obj.refillprice);
                    }

                    $('#fillqty').focus();
                    $('#fillqty').select();
                }
            });   
        });
        //Create dispatch part
        $("#formsubmit").click(function() {
            if (!$("#formdispatch")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {   
                var distype = $('#dispatchtype').val();
                var productID = $('#product').val();
                var product = $("#product option:selected").text();
                var unitprice = parseFloat($('#unitprice').val());
                var newsaleprice = parseFloat($('#newsaleprice').val());
                var refillsaleprice = parseFloat($('#refillsaleprice').val());
                var refillprice = parseFloat($('#refillprice').val());
                var fillqty = parseFloat($('#fillqty').val());
                var reqty = parseFloat($('#reqty').val());
                var newqty = parseFloat($('#newqty').val());

                var totrefill=parseFloat(fillqty*refillsaleprice);
                var totnew=parseFloat(newqty*newsaleprice);
                var total = parseFloat(totrefill+totnew);
                var showtotal = addCommas(parseFloat(total).toFixed(2));

                $('#tabledispatch > tbody:last').append('<tr><td>' + product + '</td><td class="d-none">' + productID + '</td><td class="d-none">' + unitprice + '</td><td class="d-none">' + newsaleprice + '</td><td class="d-none">' + refillsaleprice + '</td><td class="d-none">' + refillprice + '</td><td class="text-center">' + fillqty + '</td></td><td class="text-center">' + reqty + '</td><td class="text-center">' + newqty + '</td><td class="total d-none">' + total + '</td><td class="text-right">' + showtotal + '</td></tr>');

                $('#product').val('');
                $('#unitprice').val('0');
                $('#newsaleprice').val('0');
                $('#refillsaleprice').val('0');
                $('#refillprice').val('0');
                $('#fillqty').val('0');
                $('#reqty').val('0');
                $('#newqty').val('0');

                var sum = 0;
                $(".total").each(function(){
                    sum += parseFloat($(this).text());
                });
                
                var showsum = addCommas(parseFloat(sum).toFixed(2));

                $('#divtotal').html('Rs. '+showsum);
                $('#hidetotalorder').val(sum);

                $('#product').focus();
            }
        });
        $('#modalcreatedispatch').on('hidden.bs.modal', function (e) {
            $('#tabledispatch > tbody').html('');
            $('#ponumber').val('').prop("disabled", false);
            $('#dispatchtype').val('').prop("disabled", false);
            $('#product').val('');
            $('#newsaleprice').val('0');
            $('#refillsaleprice').val('0');
            $('#refillprice').val('0');
            $('#fillqty').val('0');
            $('#reqty').val('0');
            $('#newqty').val('0');
            $('#divtotal').html('Rs. 0.00');
            $('#hidetotalorder').val('0');
        });
        $('#btncreatedispatch').click(function(){
            var distype = $('#dispatchtype').val();
            if(distype==1){
                var id = $('#ponumber').val();
                $('#refname').removeAttr( "required" );
                $('#area').removeAttr( "required" );
                $.ajax({
                    type: "POST",
                    data: {
                        orderID: id
                    },
                    url: 'getprocess/getorderdeliveryoption.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        
                        if(obj.id>0){
                            $('#lorrynum').val(obj.vehicleid);
                            $('#trailernum').val(obj.trailerid);
                        }

                        $('#modaltransportdetail').modal('show');
                    }
                });
            }
            else{
                $('#refname').prop('required', true);
                $('#area').prop('required', true);
                $('#modaltransportdetail').modal('show');
            }
        });
        $('#btnothersubmit').click(function(){
            if (!$("#otherform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#othersubmitBtn").click();
            } else {
                jsonObj = [];
                $("#tabledispatch tbody tr").each(function() {
                    item = {}
                    $(this).find('td').each(function(col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });
                // console.log(jsonObj);

                var total = $('#hidetotalorder').val();
                var distypeID = $('#dispatchtype').val();
                if(distypeID==1){
                    var porderID = $('#ponumber').val();
                }
                else{
                    var porderID = '0';
                }                
                var lorrynum = $('#lorrynum').val();
                var trailernum = $('#trailernum').val();
                var drivername = $('#drivername').val();
                var officername = $('#officername').val();
                var refname = $('#refname').val();
                var area = $('#area').val();
                var dispatchtype = $('#dispatchtype').val();

                $.ajax({
                    type: "POST",
                    data: {
                        tableData: jsonObj,
                        total: total,
                        distypeID: distypeID,
                        porderID: porderID,
                        lorryID: lorrynum,
                        trailerID: trailernum,
                        driverID: drivername,
                        officerID: officername,
                        refID: refname,
                        areaID: area
                    },
                    url: 'process/dispatchprocess.php',
                    success: function(result) { //alert(result);
                        $('#modalcreatedispatch').modal('hide');
                        $('#modaltransportdetail').modal('hide');
                        action(result);
                        location.reload();
                    }
                });
            }
        });
    });

    function print() {
        printJS({
            printable: 'viewdispatchprint',
            type: 'html',
            targetStyles: ['*']
        })
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

</script>
<?php include "include/footer.php"; ?>
