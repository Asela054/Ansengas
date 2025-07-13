<?php 
include "include/header.php";  
$sql="SELECT `tbl_tank_transaction`.*, `tbl_customer`.`name`, `tbl_product`.`product_name` FROM `tbl_tank_transaction` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_tank_transaction`.`tbl_vehicle_load_idtbl_vehicle_load` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_tank_transaction`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_tank_transaction`.`tbl_product_idtbl_product` WHERE `tbl_tank_transaction`.`status` IN (1,2)";
$result =$conn-> query($sql);
$sql2="SELECT `tbl_tank_transaction`.*, `tbl_customer`.`name`, `tbl_product`.`product_name` FROM `tbl_tank_transaction` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_tank_transaction`.`tbl_vehicle_load_idtbl_vehicle_load` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_tank_transaction`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_tank_transaction`.`tbl_product_idtbl_product` WHERE `tbl_tank_transaction`.`status` IN (1,2)";
$result2 =$conn-> query($sql2);
$sqlvehicleload="SELECT `idtbl_vehicle_load` FROM `tbl_vehicle_load` WHERE `status`=1 AND `unloadstatus`=0";
$resultloadlist =$conn-> query($sqlvehicleload);
$sqlvehicleload2="SELECT `idtbl_vehicle_load` FROM `tbl_vehicle_load` WHERE `status`=1 AND `unloadstatus`=0";
$resultloadlist2 =$conn-> query($sqlvehicleload2);
$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct);
$sqlproduct2="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct2 =$conn-> query($sqlproduct2);

$sqltransaction="SELECT `tbl_tank_transaction`.`idtbl_tank_transaction`, `tbl_customer`.`name`  FROM `tbl_tank_transaction` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_tank_transaction`.`tbl_customer_idtbl_customer` WHERE `tbl_tank_transaction`.`status`=1 AND `tbl_tank_transaction`.`issuestatus`=0";
$resulttransaction =$conn-> query($sqltransaction);
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
                        <h1 class="page-header-title font-weight-light">
                            <div class="page-header-icon"><i class="fas fa-exchange-alt"></i></div>
                            <span>Tank Transaction</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active font-weight-bold" id="home-tab" data-toggle="tab"
                                            href="#home" role="tab" aria-controls="home" aria-selected="true">Collect
                                            From Customer</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link font-weight-bold" id="profile-tab" data-toggle="tab"
                                            href="#profile" role="tab" aria-controls="profile"
                                            aria-selected="false">Issue To Customer</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                        aria-labelledby="home-tab">
                                        <br>
                                        <br>
                                        <div class="row">
                                            <div class="col-3">
                                                <form action="process/collecttankprocess.php" method="post"
                                                    autocomplete="off">
                                                    <div class="form-group mb-1">
                                                        <label
                                                            class="small font-weight-bold text-dark">Customer*</label>
                                                        <select class="form-control form-control-sm" name="customer"
                                                            id="customer"  required>
                                                            <option value="">Select</option>
                                                            <?php if($resultcustomer->num_rows > 0) {while ($rowcustomer = $resultcustomer-> fetch_assoc()) { ?>
                                                            <option
                                                                value="<?php echo $rowcustomer['idtbl_customer'] ?>">
                                                                <?php echo $rowcustomer['name'] ?></option>
                                                            <?php }} ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-1">
                                                        <label class="small font-weight-bold">Load List*</label><br>
                                                        <select class="form-control form-control-sm"
                                                            style="width: 100%;" name="loadlist" id="loadlist" required>
                                                            <option value="">Select</option>
                                                            <?php if($resultloadlist->num_rows > 0) {while ($rowloadlist = $resultloadlist-> fetch_assoc()) { ?>
                                                            <option
                                                            value="<?php echo $rowloadlist['idtbl_vehicle_load'] ?>">
                                                                <?php echo 'VL-'.$rowloadlist['idtbl_vehicle_load'] ?></option>
                                                            <?php }} ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-1">
                                                        <label class="small font-weight-bold text-dark">Product*</label>
                                                        <select class="form-control form-control-sm"
                                                            style="width: 100%;" name="product" id="product" required>
                                                            <option value="">Select</option>
                                                            <?php if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                                                            <option
                                                            value="<?php echo $rowproduct['idtbl_product'] ?>">
                                                                <?php echo $rowproduct['product_name'] ?></option>
                                                            <?php }} ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-1">
                                                        <label class="small font-weight-bold text-dark">Qty</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="qty" id="qty">
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <button type="submit" id="submitBtn"
                                                            class="btn btn-outline-primary btn-sm w-50 fa-pull-right"
                                                            <?php if($addcheck==0){echo 'disabled';} ?>><i
                                                                class="far fa-save"></i>&nbsp;Collect</button>
                                                    </div>
                                                    <input type="hidden" name="recordOption" id="recordOption"
                                                        value="1">
                                                    <input type="hidden" name="recordID" id="recordID" value="">
                                                </form>
                                            </div>
                                            <div class="col-9">
                                                <table class="table table-bordered table-striped table-sm nowrap"
                                                    id="dataTable1">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Date</th>
                                                            <th>Customer</th>
                                                            <th>Product</th>
                                                            <th>Qty</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                                        <tr>
                                                            <td><?php echo 'TRNS-'.$row['idtbl_tank_transaction'] ?></td>
                                                            <td><?php echo $row['collectdate'] ?></td>
                                                            <td><?php echo $row['name'] ?></td>
                                                            <td><?php echo $row['product_name'] ?></td>
                                                            <td><?php echo $row['qty'] ?></td>
                                                            <td><?php
                                                                $message = '';
                                                                $messageClass = '';

                                                                if ($row['collectstatus'] == 1 && $row['issuestatus'] == 0) {
                                                                    $message = 'Not Returned';
                                                                    $messageClass = 'text-danger';
                                                                }elseif ($row['collectstatus'] == 1 && $row['issuestatus'] == 1){
                                                                    $message = 'Issued to the Customer';
                                                                    $messageClass = 'text-success';
                                                                }
                                                                echo '<span class="' . $messageClass . '">' . $message . '</span>';
                                                                ?></td>
                                                        </tr>
                                                        <?php }} ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="profile" role="tabpanel"
                                        aria-labelledby="profile-tab">
                                        <br>
                                        <br>
                                        <div class="row">
                                            <div class="col-3">
                                                <form action="process/issuetankprocess.php" method="post"
                                                    autocomplete="off">
                                                    <div class="form-group mb-1">
                                                        <label
                                                            class="small font-weight-bold text-dark">Customer*</label><br> 
                                                        <select class="form-control form-control-sm" name="issuecustomer"
                                                            id="issuecustomer" required style="width:100%;">
                                                            <option value="">Select</option>
                                                            <?php if($resulttransaction->num_rows > 0) {while ($rowtransaction = $resulttransaction-> fetch_assoc()) { ?>
                                                            <option
                                                                value="<?php echo $rowtransaction['idtbl_tank_transaction'] ?>">
                                                                <?php echo 'TRNS-'.$rowtransaction['idtbl_tank_transaction'] .' / '. $rowtransaction['name']?></option>
                                                            <?php }} ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-1">
                                                        <label class="small font-weight-bold text-dark">Product*</label>
                                                        <input type="hidden" class="form-control form-control-sm"
                                                            name="hideproductid" id="hideproductid" readonly>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="issueproduct" id="issueproduct" readonly>
                                                    </div>
                                                    <div class="form-group mb-1">
                                                        <label class="small font-weight-bold text-dark">Collected Qty*</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="collectedqty" id="collectedqty" readonly>
                                                    </div>
                                                    <div class="form-group mb-1">
                                                        <label class="small font-weight-bold text-dark">Qty</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="issueqty" id="issueqty">
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <button type="submit" id="submitBtn2"
                                                            class="btn btn-outline-primary btn-sm w-50 fa-pull-right"
                                                            <?php if($addcheck==0){echo 'disabled';} ?>><i
                                                                class="far fa-save"></i>&nbsp;Issue</button>
                                                    </div>
                                                    <input type="hidden" name="recordOption" id="recordOption"
                                                        value="1">
                                                    <input type="hidden" name="recordID" id="recordID" value="">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#dataTable1').DataTable({
        });
        $('#dataTable2').DataTable({
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

        $('#issuecustomer').change(function(){
            var transID = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    transID: transID
                },
                url: 'getprocess/getproductaccotransaction.php',
                success: function(result) {//alert(result);
                    var obj = JSON.parse(result);
                    $('#hideproductid').val(obj.id);
                    $('#issueproduct').val(obj.product);
                    $('#collectedqty').val(obj.qty);

                    $('#issueqty').focus();

                }
            });   
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
