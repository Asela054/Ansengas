<?php 
include "include/header.php";  

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 

$sqlsalesrep="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=7";
$resultsalesrep =$conn-> query($sqlsalesrep); 

$sqlarea="SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1";
$resultarea =$conn-> query($sqlarea); 

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
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            <span>Customerwise Executive</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/customerwiserepprocess.php" method="post" autocomplete="off">
                                <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Area*</label>
                                        <select class="form-control form-control-sm" name="area" id="area" required>
                                            <option value="">Select</option>
                                            <?php if($resultarea->num_rows > 0) {while ($rowarea = $resultarea-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowarea['idtbl_area'] ?>"><?php echo $rowarea['area'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Customer*</label>
                                        <select type="text" class="form-control form-control-sm" name="customer[]" id="customer" required multiple>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Product*</label>
                                        <select class="form-control form-control-sm" name="product[]" id="product" required multiple>
                                            <option value="">Select</option>
                                            <?php if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowproduct['idtbl_product'] ?>"><?php echo $rowproduct['product_name'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Executive Name*</label>
                                        <select class="form-control form-control-sm" name="salesrep" id="salesrep" required>
                                            <option value="">Select</option>
                                            <?php if($resultsalesrep->num_rows > 0) {while ($rowsalesrep = $resultsalesrep-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowsalesrep['idtbl_employee'] ?>"><?php echo $rowsalesrep['name'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group mt-2">
                                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm w-50 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
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
                                            <th>Product</th>
                                            <th>Executive Name</th>
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

        $("#product").select2();
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
        $('#area').change(function () {
            var areaID = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    areaID: areaID
                },
                url: 'getprocess/getcustomeraccoarea.php',
                success: function(result) {
                    var obj = JSON.parse(result);
                    
                    var customerlist = obj.customer;
                    var customerlistoption = [];

                    $.each(customerlist, function(i, item) {
                        customerlistoption.push({
                            id: customerlist[i].customerID,
                            text: customerlist[i].customerName
                        });
                    });
                    $('#customer').empty().select2({
                        data: customerlistoption
                    }).val(customerlistoption.map(option => option.id)).trigger('change');
                }
            });
        });

        $('#dataTable').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/customerwisereplist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_customerwise_salesrep"
                },
                {
                    "data": "customer_name"
                },
                {
                    "data": "product_name"
                },
                {
                    "data": "employee_name"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-outline-primary btn-sm btnEdit mr-1 ';if(editcheck==0){button+='d-none';}button+='" id="'+full['idtbl_customerwise_salesrep']+'"><i class="fas fa-pen"></i></button>';
                        if(full['status']==1){
                        button+='<a href="process/statuscustomerwiserep.php?record='+full['idtbl_customerwise_salesrep']+'&type=2" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-check"></i></a>';
                        }else{
                        button+='<a href="process/statuscustomerwiserep.php?record='+full['idtbl_customerwise_salesrep']+'&type=1" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';
                        }
                        button+='<a href="process/statuscustomerwiserep.php?record='+full['idtbl_customerwise_salesrep']+'&type=3" onclick="return delete_confirm()" target="_self" class="btn btn-outline-danger btn-sm ';if(deletecheck==0){button+='d-none';}button+='"><i class="far fa-trash-alt"></i></a>';
                        return button;
                    }
                }
            ]
        } );

        $('#dataTable tbody').on('click', '.btnEdit', function () {
            var r = confirm("Are you sure, You want to Edit this?");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getcustomerwiserep.php',
                    success: function (result) {
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);

                        if (obj.customer) {
                            var cust = obj.customer;

                            if ($("#customer option[value='" + cust.id + "']").length === 0) {
                                var newOption = new Option(cust.text, cust.id, true, true);
                                $("#customer").append(newOption).trigger('change');
                            }

                            $("#customer").val(cust.id).trigger('change');
                        }
                        
                        if (obj.product) {
                            var productValues = Array.isArray(obj.product) ? obj.product : [obj.product];
                            $('#product').val(productValues).trigger('change.select2');
                        }

                        $('#salesrep').val(obj.employee);
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
