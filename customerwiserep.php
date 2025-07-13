<?php 
include "include/header.php";  

$sql="SELECT `tbl_customerwise_salesrep`.`idtbl_customerwise_salesrep`,`tbl_customerwise_salesrep`.`status` AS status_customerrep, `tbl_customer`.`name` AS customer_name, `tbl_employee`.`name` AS employee_name, `tbl_product`.`product_name` ,`tbl_customerwise_salesrep`.`status` AS table_status FROM `tbl_customerwise_salesrep` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_customerwise_salesrep`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_customerwise_salesrep`.`tbl_employee_idtbl_employee` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_customerwise_salesrep`.`tbl_product_idtbl_product` WHERE `tbl_customerwise_salesrep`.`status` IN (1,2)";
$result =$conn-> query($sql); 

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
                                            <?php if($resultcustomer->num_rows > 0) {while ($rowcustomer = $resultcustomer-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowcustomer['idtbl_customer'] ?>"><?php echo $rowcustomer['name'] ?></option>
                                            <?php }} ?>
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
                                    <tbody>
                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_customerwise_salesrep'] ?></td>
                                            <td><?php echo $row['customer_name'] ?></td>
                                            <td><?php echo $row['product_name'] ?></td>
                                            <td><?php echo $row['employee_name'] ?></td>
                                            <td class="text-right">
                                                <button class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_customerwise_salesrep'] ?>"><i data-feather="edit-2"></i></button>
                                                <?php if($row['status_customerrep']==1){ ?>
                                                <a href="process/statuscustomerwiserep.php?record=<?php echo $row['idtbl_customerwise_salesrep'] ?>&type=2" onclick="return confirm('Are you sure you want to deactive this?');" target="_self" class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="check"></i></a>
                                                <?php }else{ ?>
                                                <a href="process/statuscustomerwiserep.php?record=<?php echo $row['idtbl_customerwise_salesrep'] ?>&type=1" onclick="return confirm('Are you sure you want to active this?');" target="_self" class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="x-square"></i></a>
                                                <?php } ?>
                                                <a href="process/statuscustomerwiserep.php?record=<?php echo $row['idtbl_customerwise_salesrep'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm <?php if($deletecheck==0){echo 'd-none';} ?>"><i data-feather="trash-2"></i></a>
                                            </td>
                                        </tr>
                                        <?php }} ?>
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
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
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
        $('#dataTable').DataTable();
        $('#dataTable tbody').on('click', '.btnEdit', function() {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getcustomerwiserep.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#customer').val(obj.customer).trigger('change.select2');
                        $('#product').val(obj.product);       
                        $('#salesrep').val(obj.employee);                       
                

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });

</script>
<?php include "include/footer.php"; ?>
