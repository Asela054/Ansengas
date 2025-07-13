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
$tableData=$_POST['tableData'];

$updatedatetime=date('Y-m-d h:i:s');

$insertgrn="INSERT INTO `tbl_grn`(`date`, `total`, `invoicenum`, `dispatchnum`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$grndate','$grnnettotal','$grninvoice','$grndispatch','1','$updatedatetime','$userID')";
if($conn->query($insertgrn)==true){
    $grnid=$conn->insert_id;

    foreach($tableData as $rowtabledata){
        $product=$rowtabledata['col_2'];
        $unitprice=$rowtabledata['col_3'];
        $refillprice=$rowtabledata['col_4'];
        $newqty=$rowtabledata['col_7'];
        $refillqty=$rowtabledata['col_8'];
        $emptyqty=$rowtabledata['col_9'];
        $trustqty=$rowtabledata['col_10'];
        $saftyqty=$rowtabledata['col_11'];
        $total=$rowtabledata['col_12'];

        $insretgrndetail="INSERT INTO `tbl_grndetail`(`date`, `type`, `newqty`, `fillqty`, `emptyqty`, `returnqty`, `trustqty`, `saftyqty`, `saftyreturnqty`, `unitprice`, `refillprice`, `total`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_grn_idtbl_grn`, `tbl_product_idtbl_product`) VALUES ('$grndate','0','$newqty','$refillqty','$emptyqty','0','$trustqty','$saftyqty','0','$unitprice','$refillprice','$total','1','$updatedatetime','$userID','$grnid','$product')";
        $conn->query($insretgrndetail);

        $totqty=($newqty+$refillqty+$trustqty+$saftyqty);
        if ($totqty > 0) {
            $updatestock = "UPDATE `tbl_stock` SET `fullqty` = (`fullqty` + '$totqty'), `emptyqty` = (`emptyqty` - '$refillqty') WHERE `tbl_product_idtbl_product` = '$product'";
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