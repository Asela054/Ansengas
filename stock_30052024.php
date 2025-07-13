<?php 
include "include/header.php";  

$sqlstock="SELECT `tbl_product`.`product_name`, `tbl_stock`.`fullqty`, `tbl_stock`.`emptyqty` FROM `tbl_stock` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_stock`.`tbl_product_idtbl_product` WHERE `tbl_stock`.`status`=1 AND `tbl_product`.`status`=1";
$resultstock =$conn-> query($sqlstock); 

$sqltruststock="SELECT `tbl_product`.`product_name`, `tbl_stock_trust`.`trustqty`, `tbl_stock_trust`.`returnqty` FROM `tbl_stock_trust` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_stock_trust`.`tbl_product_idtbl_product` WHERE `tbl_stock_trust`.`status`=1 AND `tbl_product`.`status`=1";
$resulttruststock =$conn-> query($sqltruststock); 

$sqlsaftystock="SELECT `tbl_product`.`product_name`, `tbl_stock_trust`.`saftyqty`, `tbl_stock_trust`.`saftyreturnqty` FROM `tbl_stock_trust` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_stock_trust`.`tbl_product_idtbl_product` WHERE `tbl_stock_trust`.`status`=1 AND `tbl_product`.`status`=1";
$resultsaftystock =$conn-> query($sqlsaftystock);

$sqltrustcusstock="SELECT `tbl_product`.`product_name`, SUM(`tbl_cutomer_trustreturn`.`trustqty`) AS `trustqty`, SUM(`tbl_cutomer_trustreturn`.`returnqty`) AS `returnqty` FROM `tbl_cutomer_trustreturn` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_cutomer_trustreturn`.`tbl_product_idtbl_product` WHERE `tbl_cutomer_trustreturn`.`status`=1 AND `tbl_product`.`status`=1 GROUP BY `tbl_cutomer_trustreturn`.`tbl_product_idtbl_product`";
$resulttrustcusstock =$conn-> query($sqltrustcusstock);

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
                            <span>Stock Report</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row row-cols-1 row-cols-md-2" id="printarea">
                            <div class="col">
                                <h6 class="small title-style"><span>Main stock</span></h6>
                                <table class="table table-bordered table-striped table-sm nowrap">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Full Stock</th>
                                            <th class="text-center">Empty Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($resultstock->num_rows > 0) {while ($rowstock = $resultstock-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $rowstock['product_name'] ?></td>
                                            <td class="text-center"><?php echo $rowstock['fullqty'] ?></td>
                                            <td class="text-center"><?php echo $rowstock['emptyqty'] ?></td>
                                        </tr>
                                        <?php }} ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col">
                                <h6 class="small title-style"><span>Trust received & return stock in Laugfs</span></h6>
                                <table class="table table-bordered table-striped table-sm nowrap">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Trust Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($resulttruststock->num_rows > 0) {while ($rowtruststock = $resulttruststock-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $rowtruststock['product_name'] ?></td>
                                            <td class="text-center"><?php echo $rowtruststock['trustqty'] ?></td>
                                        </tr>
                                        <?php }} ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col">
                                <h6 class="small title-style"><span>Safty stock</span></h6>
                                <table class="table table-bordered table-striped table-sm nowrap">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Safty Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($resultsaftystock->num_rows > 0) {while ($rowsaftystock = $resultsaftystock-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $rowsaftystock['product_name'] ?></td>
                                            <td class="text-center"><?php echo $rowsaftystock['saftyqty'] ?></td>
                                        </tr>
                                        <?php }} ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col">
                                <h6 class="small title-style"><span>Trust customer stock</span></h6>
                                <table class="table table-bordered table-striped table-sm nowrap">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Trust Stock</th>
                                            <th class="text-center">Return Stock</th>
                                            <th class="text-center">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($resulttrustcusstock->num_rows > 0) {while ($rowtrustcusstock = $resulttrustcusstock-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $rowtrustcusstock['product_name'] ?></td>
                                            <td class="text-center"><?php echo $rowtrustcusstock['trustqty'] ?></td>
                                            <td class="text-center"><?php echo $rowtrustcusstock['returnqty'] ?></td>
                                            <td class="text-center"><?php echo ($rowtrustcusstock['trustqty']-$rowtrustcusstock['returnqty']) ?></td>
                                        </tr>
                                        <?php }} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-outline-danger btn-sm fa-pull-right" id="btnprint"><i class="fas fa-print"></i>&nbsp;Print Report</button>
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
        document.getElementById('btnprint').addEventListener ("click", print);
    });

    function print() {
        printJS({
            printable: 'printarea',
            type: 'html',
            // style: '@page { size: landscape; }',
            targetStyles: ['*']
        })
    }
</script>
<?php include "include/footer.php"; ?>
