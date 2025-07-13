<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_main_area` WHERE `status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlproduct="SELECT * FROM `tbl_product` WHERE `status`=1";
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
                            <div class="page-header-icon"><i data-feather="settings"></i></div>
                            <span>Main Area</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/mainareaprocess.php" method="post" autocomplete="off">
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">Main Area*</label>
                                        <input type="text" class="form-control form-control-sm" name="area" id="area" required>
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
                                            <th>Main Area</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_main_area'] ?></td>
                                            <td><?php echo $row['main_area'] ?></td>
                                            <td class="text-right">
                                                <!-- <button class="btn btn-outline-secondary btn-sm btnAddprice <?php //if($editcheck==0){echo 'd-none';} ?>" id="<?php //echo $row['idtbl_main_area'] ?>"><i class="fas fa-plus"></i></button>
                                                <button class="btn btn-outline-dark btn-sm btnView <?php //if($editcheck==0){echo 'd-none';} ?>" id="<?php //echo $row['idtbl_main_area'] ?>"><i class="fas fa-eye"></i></button> -->
                                                <button class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_main_area'] ?>"><i data-feather="edit-2"></i></button>
                                                <?php if($row['status']==1){ ?>
                                                <a href="process/statusmainarea.php?record=<?php echo $row['idtbl_main_area'] ?>&type=2" onclick="return confirm('Are you sure you want to deactive this?');" target="_self" class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="check"></i></a>
                                                <?php }else{ ?>
                                                <a href="process/statusmainarea.php?record=<?php echo $row['idtbl_main_area'] ?>&type=1" onclick="return confirm('Are you sure you want to active this?');" target="_self" class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="x-square"></i></a>
                                                <?php } ?>
                                                <a href="process/statusmainarea.php?record=<?php echo $row['idtbl_main_area'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm <?php if($deletecheck==0){echo 'd-none';} ?>"><i data-feather="trash-2"></i></a>
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
<!-- modal add price -->
<div class="modal fade" id="modaladdprice" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">ADD PRODUCT PRICE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="process/addpriceprocess.php" method="post" autocomplete="off">
                    <div class="form-group mb-1">
                    <input type="hidden" class="form-control form-control-sm" name="hiddenID" id="hiddenID">
                        <label class="small font-weight-bold text-dark">Product*</label>
                        <select class="form-control form-control-sm" name="product" id="product" required>
                            <option value="">Select</option>
                            <?php if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                            <option value="<?php echo $rowproduct['idtbl_product'] ?>">
                                <?php echo $rowproduct['product_name'] ?></option>
                            <?php }} ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-dark">New Sale Price</label>
                        <input type="text" class="form-control form-control-sm" name="newprice" id="newprice">
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-dark">Refill Sale Price</label>
                        <input type="text" class="form-control form-control-sm" name="refillprice" id="refillprice">
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-dark">Empty Sale Price</label>
                        <input type="text" class="form-control form-control-sm" name="emptyprice" id="emptyprice">
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-dark">Discounted Price</label>
                        <input type="text" class="form-control form-control-sm" name="discountprice" id="discountprice">
                    </div>
                    <div class="form-group mt-2">
                        <button type="submit" id="submitBtn" class="btn btn-danger btn-sm fa-pull-right"
                            <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                    </div>
                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                    <input type="hidden" name="recordID" id="recordID" value="">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modal add price -->
<div class="modal fade" id="modalupdateprice" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">UPDATE PRODUCT PRICE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="process/updatepriceprocess.php" method="post" autocomplete="off">
                    <div class="form-group mb-1">
                    <input type="hidden" class="form-control form-control-sm" name="hiddenID2" id="hiddenID2">
                        <label class="small font-weight-bold text-dark">Product*</label>
                        <input type="text" class="form-control form-control-sm" name="product2" id="product2" readonly>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-dark">New Sale Price</label>
                        <input type="text" class="form-control form-control-sm" name="newprice2" id="newprice2">
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-dark">Refill Sale Price</label>
                        <input type="text" class="form-control form-control-sm" name="refillprice2" id="refillprice2">
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-dark">Empty Sale Price</label>
                        <input type="text" class="form-control form-control-sm" name="emptyprice2" id="emptyprice2">
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-dark">Discounted Price</label>
                        <input type="text" class="form-control form-control-sm" name="discountprice2" id="discountprice2">
                    </div>
                    <div class="form-group mt-2">
                        <button type="submit" id="submitBtn2" class="btn btn-danger btn-sm fa-pull-right"
                            <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modal add price -->
<div class="modal fade" id="modalviewprice" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">VIEW PRODUCT PRICE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-sm nowrap" id="pricedataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>New Sale Price</th>
                            <th>Refill Sale Price</th>
                            <th>Empty Sale Price</th>
                            <th>Discounted Price</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="viewhtml">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>

<script>
    $(document).ready(function () {
        $('#pricedataTable tbody').on('click', '.btnEditproduct', function () {
            var recordID = $(this).attr('id');
            $("#modalupdateprice").modal('show');
            $("#modalviewprice").modal('hide');

            $.ajax({
                type: 'POST',
                url: 'getprocess/get_productprices.php',
                data: {
                    recordID: recordID
                },
                success: function (response) { //alert(response);
                    var data = JSON.parse(response);

                    $("#hiddenID2").val(data.idtbl_areawise_product);
                    $("#product2").val(data.product_name);
                    $("#newprice2").val(data.newsaleprice);
                    $("#refillprice2").val(data.refillsaleprice);
                    $("#emptyprice2").val(data.emptysaleprice);
                    $("#discountprice2").val(data.discountedprice);
                },
            });
        });
    });
    $(document).ready(function() {
        $('#dataTable').DataTable();
        $('#pricedataTable').DataTable();
        $('#dataTable tbody').on('click', '.btnEdit', function() {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getmainarea.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#area').val(obj.area);                       

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });

        $(".btnAddprice").click(function () {
            var id = $(this).attr('id');
            $('#hiddenID').val(id);
            $("#modaladdprice").modal('show');
        });

        $(".btnView").click(function () {
            var id = $(this).attr('id');
            $("#modalviewprice").modal('show');

            $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getproductprice.php',
                    success: function(result) { //alert(result);
                        $('#viewhtml').html(result);
                        
                    }
                });
        });


    });

</script>
<?php include "include/footer.php"; ?>
