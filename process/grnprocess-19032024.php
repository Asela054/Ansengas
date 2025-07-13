<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];

$grnnum=$_POST['grnnum'];
$porderID=$_POST['ponumber'];
$grndate=$_POST['grndate'];
$grninvoice=$_POST['grninvoice'];
$grndispatch=$_POST['grndispatch'];
$grnnettotal=$_POST['grnnettotal'];
$grnnettotalwithoutvat=$_POST['grnnettotalwithoutvat'];
$taxamount=$_POST['taxamount'];
$tableData=$_POST['tableData'];

$updatedatetime=date('Y-m-d h:i:s');

$insertgrn="INSERT INTO `tbl_grn`(`date`, `total`, `taxamount`, `nettotal`, `invoicenum`, `dispatchnum`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$grndate','$grnnettotalwithoutvat','$taxamount','$grnnettotal','$grninvoice','$grndispatch','1','$updatedatetime','$userID')";
if($conn->query($insertgrn)==true){
    $grnid=$conn->insert_id;

    foreach($tableData as $rowtabledata){
        $product=$rowtabledata['col_2'];
        $unitprice=$rowtabledata['col_3'];
        $refillprice=$rowtabledata['col_4'];
        $emptyprice=$rowtabledata['col_5'];
        $unitpricewithvat=$rowtabledata['col_6'];
        $refillpricewithvat=$rowtabledata['col_7'];
        $emptypricewithvat=$rowtabledata['col_8'];
        $newqty=$rowtabledata['col_15'];
        $refillqty=$rowtabledata['col_16'];
        $emptyqty=$rowtabledata['col_17'];
        $trustqty=$rowtabledata['col_18'];
        $saftyqty=$rowtabledata['col_19'];
        $total=$rowtabledata['col_20'];
        $totalwithvat=$rowtabledata['col_21'];

        $insretgrndetail="INSERT INTO `tbl_grndetail`(`date`, `type`, `newqty`, `fillqty`, `emptyqty`, `returnqty`, `trustqty`, `saftyqty`, `saftyreturnqty`, `unitprice_withoutvat`, `refillprice_withoutvat`, `emptyprice_withoutvat`, `unitprice`, `refillprice`,`emptyprice`, `totalwithoutvat`, `total`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_grn_idtbl_grn`, `tbl_product_idtbl_product`) VALUES ('$grndate','0','$newqty','$refillqty','$emptyqty','0','$trustqty','$saftyqty','0','$unitprice','$refillprice','$emptyprice','$unitpricewithvat','$refillpricewithvat','$emptypricewithvat','$total','$totalwithvat','1','$updatedatetime','$userID','$grnid','$product')";
        $conn->query($insretgrndetail);

        $totqty = ($newqty + $refillqty + $trustqty + $saftyqty);

        if ($totqty > 0) {
            $updatestock = "UPDATE `tbl_stock` SET `fullqty` = (`fullqty` + '$newqty' + '$refillqty'+ '$trustqty' + '$saftyqty'), `emptyqty` = (`emptyqty` + '$emptyqty')  WHERE `tbl_product_idtbl_product` = '$product'";
            $conn->query($updatestock);
        } elseif ($emptyqty > 0) {
            $updatestock = "UPDATE `tbl_stock` SET `emptyqty` = (`emptyqty` + '$emptyqty') WHERE `tbl_product_idtbl_product` = '$product'";
            $conn->query($updatestock);
        }
        if($trustqty>0){
            $updatetruststock="UPDATE `tbl_stock_trust` SET `trustqty`=(`trustqty`+'$trustqty'), `saftyqty`=(`saftyqty`+'$saftyqty') WHERE `tbl_product_idtbl_product`='$product'";
            $conn->query($updatetruststock);
        }     
        
        $updateorder="UPDATE `tbl_porder` SET `grnissuestatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$porderID'";
        $conn->query($updateorder);
    }
    
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-check-circle';
    $actionObj->title='';
    $actionObj->message='Add Successfully';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='success';

    echo $actionJSON=json_encode($actionObj);
}
else{
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    echo $actionJSON=json_encode($actionObj);
}