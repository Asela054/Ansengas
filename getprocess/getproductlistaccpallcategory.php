<?php
require_once('../connection/db.php');

$categoryID=$_POST['categoryID'];
$groupcategoryID=$_POST['groupcategoryID'];

$sql="SELECT `tbl_areawise_product`.`encustomer_newprice`,`tbl_areawise_product`.`encustomer_refillprice`,`tbl_areawise_product`.`encustomer_emptyprice`,`tbl_product`.`idtbl_product`, `tbl_product`.`product_code`, `tbl_product`.`product_name` FROM `tbl_product` LEFT JOIN `tbl_areawise_product` ON `tbl_product`.`idtbl_product`= `tbl_areawise_product`.`tbl_product_idtbl_product` WHERE `tbl_product`.`tbl_product_category_idtbl_product_category`='$categoryID' AND `tbl_areawise_product`.`status`=1 AND `tbl_areawise_product`.`tbl_main_area_idtbl_main_area`=1";
$result=$conn->query($sql);
?>
<div class="row">
    <div class="col-12">
        <table class="table table-bordered table-striped" id="tableproductpricelist">
            <thead>
                <tr>
                    <th>#</th>
                    <th>CODE</th>
                    <th>PRODUCT</th>
                    <th>FULL STOCK</th>
                    <th>EMPTY STOCK</th>
                    <th class="d-none">Group ID</th>
                    <th class="text-right d-none">UNIT PRICE</th>
                    <th class="text-right">RETAIL PRICE</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) { 
                        $productID = $row['idtbl_product'];
                        $sqlstockcheck = "SELECT `fullqty`, `emptyqty` FROM `tbl_stock` WHERE `tbl_product_idtbl_product`='$productID'"; 
                        $resultstockcheck = $conn->query($sqlstockcheck);
                        $rowstockcheck = $resultstockcheck->fetch_assoc();

                        $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
                        $resultvat = $conn->query($sqlvat);
                        $rowvat = $resultvat->fetch_assoc();
                        $vatamount = $rowvat['vat'];

                        $price = (($groupcategoryID == 1) ? $row['encustomer_newprice'] : (($groupcategoryID == 2) ? $row['encustomer_refillprice'] : $row['encustomer_emptyprice']));

                        $vatInclusivePrice = $price + ($price * ($vatamount / 100));
                
                        $fullstockcount = 0;
                        $emptystockcount = 0;
                
                        if (!empty($rowstockcheck['fullqty'])) {
                            $fullstockcount = $rowstockcheck['fullqty'];
                        }
                
                        if (!empty($rowstockcheck['emptyqty'])) {
                            $emptystockcount = $rowstockcheck['emptyqty'];
                        }
                ?>
                <tr class="<?php if($fullstockcount==0 || $emptystockcount==0){echo 'table-danger';}else{echo 'pointer';} ?>" id="<?php echo $row['idtbl_product'] ?>">
                    <td><?php echo $row['idtbl_product'] ?></td>
                    <td><?php echo $row['product_code'] ?></td>
                    <td><?php echo $row['product_name'] ?></td>
                    <td><?php echo $fullstockcount ?></td>
                    <td><?php echo $emptystockcount ?></td>
                    <td class="d-none"><?php echo $groupcategoryID ?></td>
                    <td class="text-right"><?php echo number_format($vatInclusivePrice, 2); ?></td>
                </tr>
                <?php }}else{ ?>
                <tr>
                    <td colspan="5">No Product To Show</td>
                </tr> 
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>