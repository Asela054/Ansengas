<?php 
include "include/header.php";  

$sqlarea="SELECT `idtbl_cutomer_target`, `name` FROM `tbl_cutomer_target` WHERE `status`=1";
$resultarea =$conn-> query($sqlarea); 

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 

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
                            <div class="page-header-icon"><i data-feather="target"></i></div>
                            <span>Customer Target</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/customertargetaddprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                            <label class="small font-weight-bold text-dark">Customer</label><br>
                                            <select class="form-control form-control-sm" name="customer" id="customer" style="width:100%">
                                                <option value="">Select Customer</option>
                                                <?php if($resultcustomer->num_rows > 0) {while ($rowcustomer = $resultcustomer-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowcustomer['idtbl_customer'] ?>"><?php echo $rowcustomer['name']; ?></option>
                                                <?php }} ?>
                                            </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Month*</label>
                                        <div class="input-group input-group-sm">
                                            <input type="month" id="targetmonth" name="targetmonth" class="form-control form-control-sm" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Product*</label>
                                        <select class="form-control form-control-sm" name="product" id="product" required>
                                            <option value="">Select</option>
                                            <?php if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowproduct['idtbl_product'] ?>"><?php echo $rowproduct['product_name'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Target Cylinders*</label>
                                        <input type="text" class="form-control form-control-sm" name="target" id="target" required>
                                    </div>
                                    <div class="form-group mt-2">
                                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm px-4 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-9">
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Customer</th>
                                            <th>Month</th>
                                            <th>Product</th>
                                            <th>Target</th>
                                            <!-- <th>Complemployee.phpete Count</th> -->
                                            <th class="text-right">Actions</th>
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
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm',
            viewMode: "months", 
            minViewMode: "months"
        });

        $("#customer").select2({
            ajax: {
                url: 'getprocess/getcustomerlist.php',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term 
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('#dataTable').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/customertargetaddlist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_cutomer_target"
                },
                {
                    "data": "name"
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        var month = full['month'];
                        var res = month.split("-");
                        return res[0]+'/'+res[1];
                    }                    
                },
                {
                    "data": "product_name"
                },
                {
                    "data": "targettank"
                },
                // {
                //     "data": "targetcomplete"
                // },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-outline-primary btn-sm btnEdit mr-1 ';if(editcheck==0){button+='d-none';}button+='" id="'+full['idtbl_cutomer_target']+'"><i class="fas fa-pen"></i></button>';
                        if(full['status']==1){
                        button+='<a href="process/statuscustomertarget.php?record='+full['idtbl_cutomer_target']+'&type=2" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-check"></i></a>';
                        }else{
                        button+='<a href="process/statuscustomertarget.php?record='+full['idtbl_cutomer_target']+'&type=1" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';
                        }
                        button+='<a href="process/statuscustomertarget.php?record='+full['idtbl_cutomer_target']+'&type=3" onclick="return delete_confirm()" target="_self" class="btn btn-outline-danger btn-sm ';if(deletecheck==0){button+='d-none';}button+='"><i class="far fa-trash-alt"></i></a>';
                        return button;
                    }
                }
            ]
        } );
        $('#dataTable tbody').on('click', '.btnEdit', function() {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getcustomertargetadd.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#customer').empty().append($('<option>',{
                            value: obj.customer,
                            text: obj.customerName,
                            selected: true
                        }));      
                        $('#targetmonth').val(obj.month);                       
                        $('#target').val(obj.targettank);                       
                        $('#product').val(obj.product);                       

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });
    
    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }
</script>
<?php include "include/footer.php"; ?>
