<?php
session_start();
require_once('../connection/db.php');

$orderID=$_POST['orderID'];

$sqlorder="SELECT `idtbl_porder`, `nettotal`, `remark`, `orderdate` FROM `tbl_porder` WHERE `idtbl_porder`='$orderID'";
$resultorder=$conn->query($sqlorder);
$roworder=$resultorder->fetch_assoc();

$orderdate=$roworder['orderdate'];

$sqlordercount="SELECT COUNT(*) AS `count` FROM `tbl_porder` WHERE `orderdate`='$orderdate'";
$resultordercount=$conn->query($sqlordercount);
$rowordercount=$resultordercount->fetch_assoc();

$sqlorderproductone="SELECT `refillqty`, `returnqty`, `newqty`, `trustqty`, `saftyqty`, `saftyreturnqty` FROM `tbl_porder_detail` WHERE `status`=1 AND `tbl_porder_idtbl_porder`='$orderID' AND `tbl_product_idtbl_product`=1";
$resultorderproductone=$conn->query($sqlorderproductone);
$roworderproductone=$resultorderproductone->fetch_assoc();

$sqlorderproducttwo="SELECT `refillqty`, `returnqty`, `newqty`, `trustqty`, `saftyqty`, `saftyreturnqty` FROM `tbl_porder_detail` WHERE `status`=1 AND `tbl_porder_idtbl_porder`='$orderID' AND `tbl_product_idtbl_product`=2";
$resultorderproducttwo=$conn->query($sqlorderproducttwo);
$roworderproducttwo=$resultorderproducttwo->fetch_assoc();

$sqlorderproductthree="SELECT `refillqty`, `returnqty`, `newqty`, `trustqty`, `saftyqty`, `saftyreturnqty` FROM `tbl_porder_detail` WHERE `status`=1 AND `tbl_porder_idtbl_porder`='$orderID' AND `tbl_product_idtbl_product`=4";
$resultorderproductthree=$conn->query($sqlorderproductthree);
$roworderproductthree=$resultorderproductthree->fetch_assoc();

$sqlorderproductfour="SELECT `refillqty`, `returnqty`, `newqty`, `trustqty`, `saftyqty`, `saftyreturnqty` FROM `tbl_porder_detail` WHERE `status`=1 AND `tbl_porder_idtbl_porder`='$orderID' AND `tbl_product_idtbl_product`=6";
$resultorderproductfour=$conn->query($sqlorderproductfour);
$roworderproductfour=$resultorderproductfour->fetch_assoc();

// $sqlorderproductfive="SELECT `refillqty`, `returnqty`, `newqty` FROM `tbl_porder_detail` WHERE `status`=1 AND `tbl_porder_idtbl_porder`='$orderID' AND `tbl_product_idtbl_product`=5";
// $resultorderproductfive=$conn->query($sqlorderproductfive);
// $roworderproductfive=$resultorderproductfive->fetch_assoc();

// $sqlcheque="SELECT `chequeno`, `chequedate` FROM `tbl_porder_payment` WHERE `tbl_porder_idtbl_porder`='$orderID' AND `status`=1";
// $resultcheque=$conn->query($sqlcheque);
// $rowcheque=$resultcheque->fetch_assoc();

$sqldelivery="SELECT * FROM `tbl_porder_delivery` WHERE `tbl_porder_idtbl_porder`='$orderID' AND `status`=1";
$resultdelivery=$conn->query($sqldelivery);
$rowdelivery=$resultdelivery->fetch_assoc();

$lorryID=$rowdelivery['vehicleid'];
$trailerID=$rowdelivery['trailerid'];

$sqlvehicle="SELECT `vehicleno` FROM `tbl_vehicle` WHERE `idtbl_vehicle`='$lorryID' AND `status`=1 AND `type`=0";
$resultvehicle=$conn->query($sqlvehicle);
$rowvehicle=$resultvehicle->fetch_assoc();

$sqltrailer="SELECT `vehicleno` FROM `tbl_vehicle` WHERE `idtbl_vehicle`='$trailerID' AND `status`=1 AND `type`=1";
$resulttrailer=$conn->query($sqltrailer);
$rowtrailer=$resulttrailer->fetch_assoc();

$arrayaccessories=array();
$sqlaccessories="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1 AND `tbl_product_category_idtbl_product_category`=2";
$resultaccessories=$conn->query($sqlaccessories);
while($rowaccessories=$resultaccessories->fetch_assoc()){
    $objaccessories=new stdClass();
    $objaccessories->accessoriesID=$rowaccessories['idtbl_product'];
    $objaccessories->accessories=$rowaccessories['product_name'];

    array_push($arrayaccessories, $objaccessories);
}

?>
<div class="row">
    <div class="col-12 small">
        <table class="table table-borderless table-sm text-center w-100 tableprint">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                        <h3 class="font-weight-light m-0">Laugfs Gas PLC</h3>
                        <h4 class="mt-2">Daily Collection Plan</h4>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>            
        </table>        
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-bordered table-black bg-transparent table-sm small w-100 tableprint">
            <tr>
                <td class="small">Order Date</td>
                <td class="small"><?php echo $roworder['orderdate'] ?></td>
                <td class="small">Distributor</td>
                <td class="small" colspan="3">Ansen Gas Distributor (Pvt) Ltd</td>
                <td class="small">Code</td>
                <td class="small">1008684</td>
            </tr>
            <tr>
                <td class="small">ASM Name</td>
                <td class="small" colspan="7"><?php echo $_SESSION['name'] ?></td>
            </tr>
            <tr>
                <td class="small">No Lorries</td>
                <td class="small">Company Lorries</td>
                <td class="small"><?php if($rowdelivery['comlorrystatus']==1){echo 'Yes';}else{echo 'No';} ?></td>
                <td class="small" colspan="2">Date to be delivered to distributor</td>
                <td class="small" nowrap><?php echo $roworder['orderdate'] ?></td>
                <td class="small">Preferred mode</td>
                <td class="small"><?php echo $rowtrailer['vehicleno'] ?></td>
            </tr>
            <tr>
                <td class="small">&nbsp;</td>
                <td class="small">Distributor lorries</td>
                <td class="small"><?php if($rowdelivery['dislorrystatus']==1){echo 'Yes';}else{echo 'No';} ?></td>
                <td class="small" colspan="2">Collection date from the plant</td>
                <td class="small" nowrap><?php echo $roworder['orderdate'] ?></td>
                <td class="small">No of lorries</td>
                <td class="small"><?php echo $rowvehicle['vehicleno'] ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-striped table-bordered table-black table-sm small bg-transparent text-center mb-0 tableprint">
            <thead>
                <tr>
                    <td rowspan="2" class="align-top small">Vehicle No</td>
                    <td rowspan="2" class="align-top small">Time</td>
                    <td rowspan="2" class="align-top small">Pack no</td>
                    <td colspan="6" class="small">12.5KG</td>
                    <td colspan="6" class="small">37.5KG</td>
                    <td colspan="6" class="small">5KG</td>
                    <td colspan="6" class="small">2KG</td>
                </tr>
                <tr>
                    <td class="small">Refill</td>
                    <td class="small">Return</td>
                    <td class="small">Trust</td>
                    <td class="small">New</td>
                    <td class="small">Safty</td>
                    <td class="small">Saf. Re</td>

                    <td class="small">Refill</td>
                    <td class="small">Return</td>
                    <td class="small">Trust</td>
                    <td class="small">New</td>
                    <td class="small">Safty</td>
                    <td class="small">Saf. Re</td>

                    <td class="small">Refill</td>
                    <td class="small">Return</td>
                    <td class="small">Trust</td>
                    <td class="small">New</td>
                    <td class="small">Safty</td>
                    <td class="small">Saf. Re</td>

                    <td class="small">Refill</td>
                    <td class="small">Return</td>
                    <td class="small">Trust</td>
                    <td class="small">New</td>
                    <td class="small">Safty</td>
                    <td class="small">Saf. Re</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="small">Trailer 01</td>
                    <td class="small"><?php if($rowordercount['count']==1){echo date("h:i A", strtotime($rowdelivery['scheduletime']));} ?></td>
                    <td class="small">P01</td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductone['refillqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductone['returnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductone['trustqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductone['newqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductone['saftyqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductone['saftyreturnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproducttwo['refillqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproducttwo['returnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproducttwo['trustqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproducttwo['newqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproducttwo['saftyqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproducttwo['saftyreturnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductthree['refillqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductthree['returnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductthree['trustqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductthree['newqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductthree['saftyqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductthree['saftyreturnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductfour['refillqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductfour['returnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductfour['trustqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductfour['newqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductfour['saftyqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==1){echo $roworderproductfour['saftyreturnqty'];} ?></td>
                </tr>
                <tr>
                    <td class="small">Trailer 02</td>
                    <td class="small"><?php if($rowordercount['count']==2){echo date("h:i A", strtotime($rowdelivery['scheduletime']));} ?></td>
                    <td class="small">P02</td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductone['refillqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductone['returnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductone['trustqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductone['newqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductone['saftyqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductone['saftyreturnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproducttwo['refillqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproducttwo['returnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproducttwo['trustqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproducttwo['newqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproducttwo['saftyqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproducttwo['saftyreturnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductthree['refillqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductthree['returnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductthree['trustqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductthree['newqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductthree['saftyqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductthree['saftyreturnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductfour['refillqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductfour['returnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductfour['trustqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductfour['newqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductfour['saftyqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==2){echo $roworderproductfour['saftyreturnqty'];} ?></td>
                </tr>
                <tr>
                    <td class="small">Trailer 03</td>
                    <td class="small"><?php if($rowordercount['count']==3){echo date("h:i A", strtotime($rowdelivery['scheduletime']));} ?></td>
                    <td class="small">-</td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductone['refillqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductone['returnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductone['trustqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductone['newqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductone['saftyqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductone['saftyreturnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproducttwo['refillqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproducttwo['returnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproducttwo['trustqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproducttwo['newqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproducttwo['saftyqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproducttwo['saftyreturnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductthree['refillqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductthree['returnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductthree['trustqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductthree['newqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductthree['saftyqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductthree['saftyreturnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductfour['refillqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductfour['returnqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductfour['trustqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductfour['newqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductfour['saftyqty'];} ?></td>
                    <td class="small"><?php if($rowordercount['count']==3){echo $roworderproductfour['saftyreturnqty'];} ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12 small">
        <table class="table table-borderless table-sm text-center w-100 tableprint w-100">
            <tbody>
                <tr>
                    <td>
                        **Collection only<br>
                        **Time (For collections - approximate plant arrival time / for delivery - approximate time to arrive to distributor)
                    </td>
                </tr>
            </tbody>            
        </table>        
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-striped table-bordered table-black table-sm small bg-transparent text-center tableprint">
            <thead>
                <tr>
                    <?php foreach($arrayaccessories as $rowaccessorieslist){ ?>
                    <td class="small"><?php echo $rowaccessorieslist->accessories; ?></td>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php foreach($arrayaccessories as $rowaccessorieslist){ ?>
                    <td class="small">
                    <?php 
                        $accessoriesID=$rowaccessorieslist->accessoriesID; 
                        $sqlaccessoriescount="SELECT `newqty` FROM `tbl_porder_detail` WHERE `status`=1 AND `tbl_porder_idtbl_porder`='$orderID' AND `tbl_product_idtbl_product`='$accessoriesID'";
                        $resultaccessoriescount=$conn->query($sqlaccessoriescount);
                        $rowaccessoriescount=$resultaccessoriescount->fetch_assoc();
                        if($resultaccessoriescount->num_rows>0){echo $rowaccessoriescount['newqty'];}
                        else{echo '0';}
                    ?>
                    </td>
                    <?php } ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="w-100 tableprint">
            <tbody>
                <tr>
                    <td class="align-top">
                        <table class="table table-striped table-bordered table-black table-sm small bg-transparent text-left">
                            <tr>
                                <td>Date: </td>
                            </tr>
                        </table>
                    </td>
                    <td>&nbsp;</td>
                    <td colspan="2" class="align-top">
                        <table class="table table-striped table-bordered table-black table-sm small bg-transparent text-center">
                            <thead>
                                <tr>
                                    <th class="small" colspan="2">12.5 KG</th>
                                    <th class="small" colspan="2">37.5 KG</th>
                                    <th class="small" colspan="2">5 KG</th>
                                    <th class="small" colspan="2">2 KG</th>
                                </tr>
                                <tr>
                                    <th class="small">Empty</th>
                                    <th class="small">Fill</th>
                                    <th class="small">Empty</th>
                                    <th class="small">Fill</th>
                                    <th class="small">Empty</th>
                                    <th class="small">Fill</th>
                                    <th class="small">Empty</th>
                                    <th class="small">Fill</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="small">&nbsp;</td>
                                    <td class="small">&nbsp;</td>
                                    <td class="small">&nbsp;</td>
                                    <td class="small">&nbsp;</td>
                                    <td class="small">&nbsp;</td>
                                    <td class="small">&nbsp;</td>
                                    <td class="small">&nbsp;</td>
                                    <td class="small">&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>            
        </table>       
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-striped table-bordered table-black table-sm small bg-transparent text-center tableprint">
            <thead>
                <tr>
                    <th class="small">Reg.</th>
                    <th class="small">Hose</th>
                    <th class="small">Clip</th>
                    <th class="small">Stv.</th>
                    <th class="small">Lan.</th>
                    <th class="small">Glass</th>
                    <th class="small">VA2</th>
                    <th class="small">Hp Reg.</th>
                    <th class="small">Mantel</th>
                    <th class="small">Stv. Burner</th>
                    <th class="small" colspan="3">Banded offer pack</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="small">&nbsp;</td>
                    <td class="small">&nbsp;</td>
                    <td class="small">&nbsp;</td>
                    <td class="small">&nbsp;</td>
                    <td class="small">&nbsp;</td>
                    <td class="small">&nbsp;</td>
                    <td class="small">&nbsp;</td>
                    <td class="small">&nbsp;</td>
                    <td class="small">&nbsp;</td>
                    <td class="small">&nbsp;</td>
                    <td class="small">&nbsp;</td>
                    <td class="small">&nbsp;</td>
                    <td class="small">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12 small">
        <table class="w-100 tableprint">
            <tbody>
                <tr>
                    <td class="align-top">
                        <h6 class="small">Cheque Information</h6>
                        Cheque no: <?php //echo $rowcheque['chequeno'] ?><br>
                        Cheque Date : <?php //echo $rowcheque['chequedate'] ?>
                    </td>
                    <td>&nbsp;</td>
                    <td class="text-center small align-bottom">
                        <hr class="border-dark m-0">
                        Confirm By: Signature of distribution manager
                    </td>
                    <td class="text-center small align-bottom">
                        <hr class="border-dark m-0">
                        Agreed by: Signature of ASM / DSE /DSO
                    </td>
                </tr>
            </tbody>            
        </table>          
    </div>
</div>