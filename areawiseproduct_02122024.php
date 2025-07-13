<?php 
include "include/header.php";  

$sql="SELECT `tbl_areawise_product`.*, `tbl_main_area`.`main_area`, `tbl_product`.`product_name` FROM `tbl_areawise_product` LEFT JOIN `tbl_main_area` ON `tbl_main_area`.`idtbl_main_area`=`tbl_areawise_product`.`tbl_main_area_idtbl_main_area` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_areawise_product`.`tbl_product_idtbl_product` WHERE `tbl_areawise_product`.`status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlarea="SELECT `idtbl_main_area`, `main_area` FROM `tbl_main_area` WHERE `status`=1";
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
                            <div class="page-header-icon"><i class="fas fa-list-alt"></i></div>
                            <span>Areawise Product Prices</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/areawiseproductprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Main Area*</label>
                                        <select class="form-control form-control-sm" name="mainarea" id="mainarea"
                                            required>
                                            <option value="">Select</option>
                                            <?php if($resultarea->num_rows > 0) {while ($rowarea = $resultarea-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowarea['idtbl_main_area'] ?>">
                                                <?php echo $rowarea['main_area'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">Product*</label>
                                        <select class="form-control form-control-sm" name="product" id="product"
                                            required>
                                            <option value="">Select</option>
                                            <?php if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowproduct['idtbl_product'] ?>">
                                                <?php echo $rowproduct['product_name'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">New Sale Price</label>
                                            <input type="text" class="form-control form-control-sm" name="newprice"
                                                id="newprice">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Refill Sale Price</label>
                                            <input type="text" class="form-control form-control-sm" name="refillprice"
                                                id="refillprice">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Empty Sale Price</label>
                                            <input type="text" class="form-control form-control-sm" name="emptyprice"
                                                id="emptyprice">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Encustomer New Price</label>
                                            <input type="text" class="form-control form-control-sm"
                                                name="encustomer_newprice" id="encustomer_newprice">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Encustomer Refill Price</label>
                                            <input type="text" class="form-control form-control-sm" name="encustomer_refillprice"
                                                id="encustomer_refillprice">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Encustomer Empty Price</label>
                                            <input type="text" class="form-control form-control-sm"
                                                name="encustomer_emptyprice" id="encustomer_emptyprice">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Discount Price</label>
                                            <input type="text" class="form-control form-control-sm" name="discount_price"
                                                id="discount_price">
                                        </div>
                                    </div>
                                    <div class="form-group mt-2">
                                        <button type="submit" id="submitBtn"
                                            class="btn btn-outline-primary btn-sm w-50 fa-pull-right"
                                            <?php if($addcheck==0){echo 'disabled';} ?>><i
                                                class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-9">
                            <div class="scrollbar pb-3" id="style-2">
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Main Area</th>
                                            <th>Product</th>
                                            <th>New Sale Price</th>
                                            <th>Refill Sale Price</th>
                                            <th>Empty Sale Price</th>
                                            <th>Encustomer New Price</th>
                                            <th>Encustomer Refill Price</th>
                                            <th>Encustomer Empty Price</th>
                                            <th>Discount Price</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_areawise_product'] ?></td>
                                            <td><?php echo $row['main_area'] ?></td>
                                            <td><?php echo $row['product_name'] ?></td>
                                            <td><?php echo $row['newsaleprice'] ?></td>
                                            <td><?php echo $row['refillsaleprice'] ?></td>
                                            <td><?php echo $row['emptysaleprice'] ?></td>
                                            <td><?php echo $row['encustomer_newprice'] ?></td>
                                            <td><?php echo $row['encustomer_refillprice'] ?></td>
                                            <td><?php echo $row['encustomer_emptyprice'] ?></td>
                                            <td><?php echo $row['discount_price'] ?></td>
                                            <td class="text-right">
                                                <button
                                                    class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>"
                                                    id="<?php echo $row['idtbl_areawise_product'] ?>"><i
                                                        data-feather="edit-2"></i></button>
                                                <?php if($row['status']==1){ ?>
                                                <a href="process/statusareawiseproduct.php?record=<?php echo $row['idtbl_areawise_product'] ?>&type=2"
                                                    onclick="return confirm('Are you sure you want to deactive this?');"
                                                    target="_self"
                                                    class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i
                                                        data-feather="check"></i></a>
                                                <?php }else{ ?>
                                                <a href="process/statusareawiseproduct.php?record=<?php echo $row['idtbl_areawise_product'] ?>&type=1"
                                                    onclick="return confirm('Are you sure you want to active this?');"
                                                    target="_self"
                                                    class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i
                                                        data-feather="x-square"></i></a>
                                                <?php } ?>
                                                <a href="process/statusareawiseproduct.php?record=<?php echo $row['idtbl_areawise_product'] ?>&type=3"
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
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
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
                    url: 'getprocess/getareawiseproduct.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#mainarea').val(obj.mainarea);   
                        $('#newprice').val(obj.newprice);  
                        $('#refillprice').val(obj.refillprice);                       
                        $('#emptyprice').val(obj.emptyprice);                       
                        $('#encustomer_newprice').val(obj.encustomer_newprice);    
                        $('#encustomer_refillprice').val(obj.encustomer_refillprice);        
                        $('#encustomer_emptyprice').val(obj.encustomer_emptyprice);     
                        $('#discount_price').val(obj.discount_price);                                                 
                        $('#product').val(obj.product);                       
                     

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });

</script>
<?php include "include/footer.php"; ?>
