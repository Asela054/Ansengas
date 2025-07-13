<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_dispatch` WHERE `status`=1";
$result=$conn->query($sql);

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 

$sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=0 AND `status`=1";
$resultvehicle =$conn-> query($sqlvehicle); 

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
                            <div class="page-header-icon"><i class="fas fa-warehouse"></i></div>
                            <span>Vehicle Unloading</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped table-bordered table-sm" id="loadview">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Load No</th>
                                            <th>Date</th>
                                            <th>Vehicle</th>
                                            <th>Executive Name</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
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
<!-- Modal Load -->
<div class="modal fade" id="modaldispatchdetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewdispatchprint"></div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Load -->
<div class="modal fade" id="modalinvoicelist" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <h6 class="title-style small"><span>Issue Invoice</span></h6>
                        <div id="viewinvoicelist"></div>
                    </div>
                    <div class="col-8">
                        <h6 class="title-style small"><span>View Invoice</span></h6>
                        <div id="viewinvoicedetail"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Load -->
<div class="modal fade" id="modalunloadinfo" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6 class="modal-title" id="viewmodaltitle"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewunloadinglist"></div>
                <div class="row" id="unloadbtnarea">
                    <div class="col">
                        <button class="btn btn-outline-primary btn-sm fa-pull-right" id="btnunload" onclick="disableButton(this)"><i class="fas fa-warehouse"></i>&nbsp;Unload Quantity</button>
                    </div>
                </div>
                <input type="hidden" name="hideloadid" id="hideloadid" value="">
            </div>
        </div>
    </div>
</div>
<!-- Modal Day End Warning -->
<div class="modal fade" id="warningDayEndModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body bg-danger text-white text-center">
                <div id="viewmessage"></div>
            </div>
            <div class="modal-footer bg-danger rounded-0">
                <a href="dayend.php" class="btn btn-outline-light btn-sm">Go To Day End</a>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        checkdayendprocess();
        $('body').tooltip({
            selector: '[data-toggle="tooltip"]'
        });
        $('[data-toggle="tooltip"]').tooltip({
            trigger : 'hover'
        });

        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#loadview').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/loadinglist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_vehicle_load"
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        return 'VL-'+full['idtbl_vehicle_load'];     
                    }
                },
                {
                    "data": "date"
                },
                {
                    "data": "vehicleno"
                },
                {
                    "data": "name"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        if(full['unloadstatus'] == 0) {
                            button += '<button class="btn btn-outline-primary btn-sm mr-1 btnunloading" data-toggle="tooltip" data-placement="bottom" title="Unload" id="' + full['idtbl_vehicle_load'] + '" data-vehicleno="' + full['vehicleno'] + '" ';
                            if(full['approvestatus'] == 0) {
                                button += 'disabled';
                            }
                            button += '><i class="fas fa-download"></i></button>';
                        } else {
                            button += '<button class="btn btn-outline-pink btn-sm mr-1 btnunloadview" data-toggle="tooltip" data-placement="bottom" title="View unload detail" id="' + full['idtbl_vehicle_load'] + '" data-vehicleno="' + full['vehicleno'] + '"><i class="far fa-file"></i></button>';
                        }
                        button+='<button class="btn btn-outline-purple btn-sm mr-1 btninvoicelist" data-toggle="tooltip" data-placement="bottom" title="View issue invoice" id="'+full['idtbl_vehicle_load']+'" ><i class="far fa-file-pdf"></i></button><button class="btn btn-outline-dark btn-sm mr-1 btnloadview" data-toggle="tooltip" data-placement="bottom" title="View load detail" id="'+full['idtbl_vehicle_load']+'" ><i class="far fa-eye"></i></button>';
                        
                        return button;
                    }
                }
            ]
        } );
        $('#loadview tbody').on('click', '.btnloadview', function() {
            var loadID=$(this).attr('id');

            $.ajax({
                type: "POST",
                data: {
                    loadID : loadID
                },
                url: 'getprocess/getloaddetail.php',
                success: function(result) {//alert(result);
                    $('#viewdispatchprint').html(result);
                    $('#modaldispatchdetail').modal('show');
                }
            }); 
        });
        $('#loadview tbody').on('click', '.btninvoicelist', function() {
            var loadID=$(this).attr('id');

            $.ajax({
                type: "POST",
                data: {
                    loadID : loadID
                },
                url: 'getprocess/getissueinvoiceaccoload.php',
                success: function(result) {//alert(result);
                    $('#viewinvoicelist').html(result);
                    $('#viewinvoicedetail').html('');
                    $('#modalinvoicelist').modal('show');
                    invoiceoption();
                }
            }); 
        });
        $('#loadview tbody').on('click', '.btnunloading', function() {
            var loadID=$(this).attr('id');
            var vehicleID=$(this).attr('data-vehicleno');
            $('#hideloadid').val(loadID);

            $.ajax({
                type: "POST",
                data: {
                    loadID : loadID
                },
                url: 'getprocess/getunloadinginfo.php',
                success: function(result) {//alert(result);
                    $('#viewmodaltitle').html('VL-'+loadID+' Unloading Information Of '+vehicleID+'');
                    $('#viewunloadinglist').html(result);
                    $('#modalunloadinfo').modal('show');
                }
            }); 
        });
        $('#loadview tbody').on('click', '.btnunloadview', function() {
            var loadID=$(this).attr('id');
            var vehicleID=$(this).attr('data-vehicleno');
            $('#hideloadid').val(loadID);

            $.ajax({
                type: "POST",
                data: {
                    loadID : loadID
                },
                url: 'getprocess/getunloadinginfo.php',
                success: function(result) {//alert(result);
                    $('#viewmodaltitle').html('VL-'+loadID+' Unloading Information Of '+vehicleID+'');
                    $('#viewunloadinglist').html(result);
                    $('#unloadbtnarea').addClass('d-none');
                    $('#modalunloadinfo').modal('show');
                }
            }); 
        });
        $('#modalunloadinfo').on('hidden.bs.modal', function (event) {
            $('#unloadbtnarea').removeClass('d-none');
        })
        $('#btnunload').click(function(){
            jsonObj = [];
            $("#tableunloading tbody tr").each(function() {
                item = {}
                $(this).find('td').each(function(col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });
            // console.log(jsonObj);

            var loadID = $('#hideloadid').val();

            $.ajax({
                type: "POST",
                data: {
                    tableData: jsonObj,
                    loadID: loadID
                },
                url: 'process/unloadingprocess.php',
                success: function(result) { //alert(result);
                    $('#modalunloadinfo').modal('hide');
                    action(result);
                    location.reload();
                }
            });
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

    function invoiceoption(){
        $('.btninvoiceview').click(function(){
            var invID = $(this).attr('id');

            $('#viewinvoicedetail').html('<div class="text-center"><img src="images/spinner.gif"></div>');

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
    function checkdayendprocess(){
        $.ajax({
            type: "POST",
            data: {
                
            },
            url: 'getprocess/getstatuslastdayendinfo.php',
            success: function(result) { //alert(result);
                if(result==1){
                    $('#viewmessage').html("Can't create anything, because today transaction is end");
                    $('#warningDayEndModal').modal('show');
                }
                else if(result==0){
                    $('#viewmessage').html("Can't create anythind, because yesterday day end process end not yet.");
                    $('#warningDayEndModal').modal('show');
                }
            }
        });
    }
    function disableButton(button) {
        button.disabled = true;
    }
</script>
<?php include "include/footer.php"; ?>
