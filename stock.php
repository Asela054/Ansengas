<?php 
include "include/header.php";  

$sqlstock="SELECT `tbl_product`.`product_name`,`tbl_product`.`orderlevel`, `tbl_stock`.`fullqty`, `tbl_stock`.`emptyqty` FROM `tbl_stock` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_stock`.`tbl_product_idtbl_product` WHERE `tbl_stock`.`status`=1 AND `tbl_product`.`status`=1 ORDER BY `tbl_product`.`orderlevel`";
$resultstock =$conn-> query($sqlstock); 

$sqltruststock="SELECT `tbl_product`.`product_name`,`tbl_product`.`orderlevel`, `tbl_stock_trust`.`trustqty`, `tbl_stock_trust`.`returnqty` FROM `tbl_stock_trust` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_stock_trust`.`tbl_product_idtbl_product` WHERE `tbl_stock_trust`.`status`=1 AND `tbl_product`.`status`=1 ORDER BY `tbl_product`.`orderlevel`";
$resulttruststock =$conn-> query($sqltruststock); 

$sqlsaftystock="SELECT `tbl_product`.`product_name`,`tbl_product`.`orderlevel`, `tbl_stock_trust`.`saftyqty`, `tbl_stock_trust`.`saftyreturnqty` FROM `tbl_stock_trust` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_stock_trust`.`tbl_product_idtbl_product` WHERE `tbl_stock_trust`.`status`=1 AND `tbl_product`.`status`=1 ORDER BY `tbl_product`.`orderlevel`";
$resultsaftystock =$conn-> query($sqlsaftystock);

// $sqltrustcusstock="SELECT `tbl_product`.`product_name`,`tbl_product`.`orderlevel`, SUM(`tbl_cutomer_trustreturn`.`trustqty`) AS `trustqty`, SUM(`tbl_cutomer_trustreturn`.`returnqty`) AS `returnqty` FROM `tbl_cutomer_trustreturn` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_cutomer_trustreturn`.`tbl_product_idtbl_product` WHERE `tbl_cutomer_trustreturn`.`status`=1 AND `tbl_product`.`status`=1 GROUP BY `tbl_cutomer_trustreturn`.`tbl_product_idtbl_product` ORDER BY `tbl_product`.`orderlevel`";
$sqltrustcusstock="SELECT 
    p.product_name AS 'Product',
    SUM(d.trustqty) AS 'Trust_Stock',
    SUM(d.trustreturnqty) AS 'Return_Stock',
    (SUM(d.trustqty) - SUM(d.trustreturnqty)) AS 'Balance'
FROM 
    tbl_invoice i
INNER JOIN 
    tbl_invoice_detail d ON i.idtbl_invoice = d.tbl_invoice_idtbl_invoice
LEFT JOIN 
    tbl_product p ON p.idtbl_product = d.tbl_product_idtbl_product
WHERE 
    i.status = 1
    AND d.tbl_product_idtbl_product IN (1, 2, 4, 6)
    AND (d.trustqty > 0 OR d.trustreturnqty > 0)
    AND d.trustqty != d.trustreturnqty
GROUP BY 
    d.tbl_product_idtbl_product
HAVING 
    (SUM(d.trustqty) - SUM(d.trustreturnqty)) != 0
ORDER BY 
    p.orderlevel ASC";
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
                                            <td><?php echo $rowtrustcusstock['Product'] ?></td>
                                            <td class="text-center"><?php echo $rowtrustcusstock['Trust_Stock'] ?></td>
                                            <td class="text-center"><?php echo $rowtrustcusstock['Return_Stock'] ?></td>
                                            <td class="text-center"><?php echo $rowtrustcusstock['Balance'] ?></td>
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
