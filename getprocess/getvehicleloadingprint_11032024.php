<?php
require_once('../connection/db.php');

$loadID = $_POST['loadID'];

$sqlload="SELECT * FROM `tbl_vehicle_load` WHERE `idtbl_vehicle_load`='$loadID'";
$resultload=$conn->query($sqlload);
$rowload=$resultload->fetch_assoc();

$sqlloaddetail="SELECT * FROM `tbl_vehicle_load_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_vehicle_load_detail`.`tbl_product_idtbl_product` WHERE `tbl_vehicle_load_detail`.`tbl_vehicle_load_idtbl_vehicle_load`='$loadID' AND `tbl_vehicle_load_detail`.`status`=1";
$resultloaddetail=$conn->query($sqlloaddetail);

$sqlcateoneproduct="SELECT `product_name` FROM `tbl_product` WHERE `tbl_product_category_idtbl_product_category`=1 AND `status`=1";
$resultcateoneproduct=$conn->query($sqlcateoneproduct);

$sqlcatetwoproduct="SELECT `product_name` FROM `tbl_product` WHERE `tbl_product_category_idtbl_product_category`=2 AND `status`=1";
$resultcatetwoproduct=$conn->query($sqlcatetwoproduct);
 
$vehID=$rowload['lorryid']; 
$sqlveh="SELECT `vehicleno` FROM `tbl_vehicle` WHERE `idtbl_vehicle`='$vehID'"; 
$resultveh=$conn->query($sqlveh); 
$rowveh=$resultveh->fetch_assoc();

$driverID=$rowload['driverid']; 
$sqldriver="SELECT `name` FROM `tbl_employee` WHERE `idtbl_employee`='$driverID' AND `status`=1";
$resultdriver =$conn-> query($sqldriver);
$rowdriver=$resultdriver->fetch_assoc();

$officerID=$rowload['officerid']; 
$sqlofficer="SELECT `name` FROM `tbl_employee` WHERE `idtbl_employee`='$officerID' AND `status`=1";
$resultofficer =$conn-> query($sqlofficer);
$rowofficer=$resultofficer->fetch_assoc();

$sqlhelper="SELECT `name` FROM `tbl_employee` WHERE `idtbl_employee` IN (SELECT `tbl_employee_idtbl_employee` FROM `tbl_employee_has_tbl_vehicle_load` WHERE `tbl_vehicle_load_idtbl_vehicle_load`='$loadID') AND `status`=1";
$resulthelper =$conn-> query($sqlhelper);
?>
<div class="row">
    <div class="col-12">
        <table class="w-100 tableprint">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td class="text-right"><img src="images/logoprint.png" width="80" height="80" class="img-fluid"></td>
                    <td colspan="2" class="text-center small align-middle">
                    <h2 class="font-weight-light m-0">ANSEN GAS DISTRIBUTORS (PVT) LTD</h2>
                        65, Arcbishop, Archbishop Nicholas Marcus Fernando Mawatha, Negombo, Sri Lanka<br>
                        Tel: 0312 235 050 | Fax: 00**-**-******* info@ansengas.lk<br>
                        <span class="font-weight-bold">Distributor for LAUGFS Gas PLC.</span>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>            
        </table>  
    </div>
</div>
<div class="row mt-4">
    <div class="col-12">
        <table class="w-100 tableprint mt-3">
            <tbody>
                <tr>
                    <td class="align-top" style="border-right: 1px dotted black;padding-right:10px;">
                        <h4 class="font-weight-light">DETAIL OF ITEMS ISSUED FOR DELIVERY</h4>
                        Date:  <?php echo $rowload['date'] ?><br>
                        Vehicle No:  <?php echo $rowveh['vehicleno'] ?><br>
                        Driver Name:  <?php echo $rowdriver['name'] ?><br>
                        <table class="table-striped table-sm w-100 tableprint mb-3">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                while($rowloaddetail=$resultloaddetail->fetch_assoc()){ 
                                    $productID=$rowloaddetail['idtbl_product'];

                                    $sqlissueproduct="SELECT SUM(`newqty`+`refillqty`+`emptyqty`) AS `sumqty` FROM `tbl_invoice_detail` WHERE `tbl_product_idtbl_product`='$productID' AND `status`=1 AND `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `status`=1 AND `tbl_vehicle_load_idtbl_vehicle_load`='$loadID')";
                                    $resultissueproduct =$conn-> query($sqlissueproduct);
                                    $rowissueproduct=$resultissueproduct->fetch_assoc();
                                ?>
                                <tr>
                                    <td><?php echo $rowloaddetail['product_name'] ?></td>
                                    <td class="text-center"><?php echo $rowloaddetail['qty']+$rowissueproduct['sumqty'] ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        Driver's Name:  <?php echo $rowdriver['name'] ?><br>
                        Driver's Signature:  .........................................................<br><br>
                        Officer's Name:  <?php echo $rowofficer['name'] ?><br>
                        Officer's Signature:  .........................................................<br><br>
                        Security Officer's Name:  .............................................<br>
                        Security Officer's Signature:  ........................................<br>
                    </td>
                    <td colspan="2" class="align-top" style="padding-left:10px;">
                        <h4 class="font-weight-light">DETAIL OF ITEMS RETURNED TO YARD</h4>
                        <table class="table table-striped table-borderless table-sm small mt-2 tableprint">
                            <tbody>
                                <?php while($rowcateoneproduct=$resultcateoneproduct->fetch_assoc()){ ?>
                                <tr>
                                    <td colspan="2"><?php echo $rowcateoneproduct['product_name'] ?></td>
                                    <td>Full</td>
                                    <td class="border border-dark">&nbsp;</td>
                                    <td>Empty</td>
                                    <td class="border border-dark">&nbsp;</td>
                                    <td>...............</td>
                                    <td class="border border-dark">&nbsp;</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <table class="table table-striped table-borderless table-sm mt-3 mb-3 tableprint">
                            <tbody>
                                <?php while($rowcatetwoproduct=$resultcatetwoproduct->fetch_assoc()){ ?>
                                <tr>
                                    <td><?php echo $rowcatetwoproduct['product_name'].':' ?></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <table class="w-100 tableprint mt-3">
                            <tbody>
                                <tr>
                                    <td class="align-top">
                                        Security Officer's Name:  ..................................................<br>
                                        Security Officer's Signature:  ............................................<br><br>
                                        Driver's Name:  .........................................................<br>
                                        Driver's Signature:  .........................................................<br><br>
                                        Officer's Name:  .........................................................<br>
                                        Officer's Signature:  .........................................................<br>
                                    </td>
                                    <td class="align-top small" style="border: 1px dotted black;padding-right:10px;padding:10px;">
                                        <h6 class="small">Helper list</h6>
                                        <?php 
                                        $i=1;
                                        while($rowhelper=$resulthelper->fetch_assoc()){
                                            echo $i.'-'.$rowhelper['name'].'<br>';
                                            $i++;
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>            
        </table>  
    </div>
</div>