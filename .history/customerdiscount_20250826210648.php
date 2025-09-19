<?php 
include "include/header.php";  

$sql="SELECT `tbl_customer_discount`.`idtbl_customer_discount`,`tbl_customer_discount`.`discount_amount`,`tbl_customer_discount`.`discount_percent`,`tbl_customer_discount`.`status`, `tbl_customer`.`name` AS customer_name, `tbl_product`.`product_name` FROM `tbl_customer_discount` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_customer_discount`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_customer_discount`.`tbl_product_idtbl_product` WHERE `tbl_customer_discount`.`status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultspecialproduct =$conn-> query($sqlproduct); 

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
                            <span>Customerwise Discount</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-3">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/customerwisediscountprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Customer*</label>
                                        <select type="text" class="form-control form-control-sm" name="customer[]"
                                            id="customer" required multiple>
                                            <?php if($resultcustomer->num_rows > 0) { while ($rowcustomer = $resultcustomer->fetch_assoc()) { ?>
                                            <option value="<?php echo $rowcustomer['idtbl_customer'] ?>">
                                                <?php echo $rowcustomer['name'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>

                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Product*</label>
                                        <select class="form-control form-control-sm" name="product[]" id="product"
                                            required multiple>
                                            <option value="">Select</option>
                                            <?php if($resultproduct->num_rows > 0) { while ($rowproduct = $resultproduct->fetch_assoc()) { ?>
                                            <option value="<?php echo $rowproduct['idtbl_product'] ?>">
                                                <?php echo $rowproduct['product_name'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>

                                    <div class="form-group mb-1 mt-2">
                                        <label class="small font-weight-bold text-dark">Discount Type</label><br>
                                        <div class="d-flex align-items-center">
                                            <div class="form-check form-check-inline">
                                                <input type="radio" name="discountType" id="discountPercentageRadio" value="1" checked>
                                                <label class="form-check-label ml-2" for="discountPercentageRadio">Discount Percentage</label>
                                            </div>
                                            <div class="form-check form-check-inline ml-3">
                                                <input type="radio" name="discountType" id="discountAmountRadio" value="2">
                                                <label class="form-check-label ml-2" for="discountAmountRadio">Discount Amount</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-1" id="discountPercentageField">
                                        <label class="small font-weight-bold text-dark">Discount Percentage</label>
                                        <input type="text" step="0.01" class="form-control form-control-sm"
                                            name="discountPercentage" id="discountPercentage"
                                            placeholder="Enter Discount Percentage">
                                    </div>

                                    <div class="form-group mb-1" id="discountAmountField" style="display: none;">
                                        <label class="small font-weight-bold text-dark">Discount Amount</label>
                                        <input type="text" step="0.01" class="form-control form-control-sm"
                                            name="discountAmount" id="discountAmount"
                                            placeholder="Enter Discount Amount">
                                    </div>

                                    <div class="form-group mt-2">
                                        <button type="submit" id="submitBtn"
                                            class="btn btn-outline-primary btn-sm w-50 fa-pull-right"
                                            <?php if($addcheck==0){echo 'disabled';} ?>>
                                            <i class="far fa-save"></i>&nbsp;Add
                                        </button>
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
                                            <th>Discount Amount</th>
                                            <th>Discount Percentage</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_customer_discount'] ?></td>
                                            <td><?php echo $row['customer_name'] ?></td>
                                            <td><?php echo $row['product_name'] ?></td>
                                            <td><?php echo $row['discount_amount'] ?></td>
                                            <td><?php echo $row['discount_percent'] ?></td>
                                            <td class="text-right">
                                                <button
                                                    class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>"
                                                    id="<?php echo $row['idtbl_customer_discount'] ?>"><i
                                                        data-feather="edit-2"></i></button>
                                                <?php if($row['status']==1){ ?>
                                                <a href="process/statuscustomerwisediscount.php?record=<?php echo $row['idtbl_customer_discount'] ?>&type=2"
                                                    onclick="return confirm('Are you sure you want to deactive this?');"
                                                    target="_self"
                                                    class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i
                                                        data-feather="check"></i></a>
                                                <?php }else{ ?>
                                                <a href="process/statuscustomerwisediscount.php?record=<?php echo $row['idtbl_customer_discount'] ?>&type=1"
                                                    onclick="return confirm('Are you sure you want to active this?');"
                                                    target="_self"
                                                    class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i
                                                        data-feather="x-square"></i></a>
                                                <?php } ?>
                                                <a href="process/statuscustomerwisediscount.php?record=<?php echo $row['idtbl_customer_discount'] ?>&type=3"
                                                    onclick="return confirm('Are you sure you want to remove this?');"
                                                    target="_self"
                                                    class="btn btn-outline-danger btn-sm <?php if($deletecheck==0){echo 'd-none';} ?>"><i
                                                        data-feather="trash-2"></i></a>
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

        const percentageRadio = $('#discountPercentageRadio');
        const amountRadio = $('#discountAmountRadio');
        const percentageField = $('#discountPercentageField');
        const amountField = $('#discountAmountField');

        percentageRadio.on('change', function () {
            if (percentageRadio.is(':checked')) {
                percentageField.show();
                amountField.hide();
            }
        });

        amountRadio.on('change', function () {
            if (amountRadio.is(':checked')) {
                amountField.show();
                percentageField.hide();
            }
        });

        $("#product").select2();
        $("#productaddition").select2({
             width: '100%'
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
                    url: 'getprocess/getcustomerwisediscount.php',
                    success: function(result) {  //alert (result);
                        var obj = JSON.parse(result);
                        
                        $('#recordID').val(obj.id);
                        
                        $('#customer').val(obj.customer).trigger('change');
                        $('#product').val(obj.product).trigger('change');
                        
                        $('#discountPercentage').val(obj.percent);     
                        $('#discountAmount').val(obj.amount);          
                        
                        if (obj.type == "1") { 
                            $('#discountPercentageRadio').prop('checked', true);
                        } else if (obj.type == "2") { 
                            $('#discountAmountRadio').prop('checked', true);
                        }

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });

</script>
<?php include "include/footer.php"; ?>
