<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php"); exit();}
require_once('../connection/db.php');

$userID = $_SESSION['userid'];

$grnnum = $_POST['grnnum'];
$porderID = $_POST['ponumber'];
$grndate = $_POST['grndate'];
$grninvoice = $_POST['grninvoice'];
$grndispatch = $_POST['grndispatch'];
$grnnettotal = $_POST['grnnettotal'];
$grnnettotalwithoutvat = $_POST['grnnettotalwithoutvat'];
$taxamount = $_POST['taxamount'];
$tableData = $_POST['tableData'];

$updatedatetime = date('Y-m-d H:i:s');

$insertgrn = "INSERT INTO `tbl_grn`
(`date`, `total`, `taxamount`, `nettotal`, `invoicenum`, `dispatchnum`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) 
VALUES ('$grndate','$grnnettotalwithoutvat','$taxamount','$grnnettotal','$grninvoice','$grndispatch','1','$updatedatetime','$userID')";

if($conn->query($insertgrn) === TRUE){
    $grnid = $conn->insert_id;

    foreach($tableData as $rowtabledata){
        $product = $rowtabledata['col_2'];
        $unitprice = $rowtabledata['col_3'];
        $refillprice = $rowtabledata['col_4'];
        $emptyprice = $rowtabledata['col_5'];
        $unitpricewithvat = $rowtabledata['col_6'];
        $refillpricewithvat = $rowtabledata['col_7'];
        $emptypricewithvat = $rowtabledata['col_8'];
        $newqty = $rowtabledata['col_15'];
        $refillqty = $rowtabledata['col_16'];
        $emptyqty = $rowtabledata['col_17'];
        $trustqty = $rowtabledata['col_18'];
        $saftyqty = $rowtabledata['col_19'];
        $total = $rowtabledata['col_20'];
        $totalwithvat = $rowtabledata['col_21'];

        $insertgrndetail = "INSERT INTO `tbl_grndetail` (`date`, `type`, `newqty`, `fillqty`, `emptyqty`, `returnqty`, `trustqty`, `saftyqty`, `saftyreturnqty`, `unitprice_withoutvat`, `refillprice_withoutvat`, `emptyprice_withoutvat`, `unitprice`, `refillprice`, `emptyprice`, `totalwithoutvat`, `total`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_grn_idtbl_grn`, `tbl_product_idtbl_product`) 
         VALUES ('$grndate','0','$newqty','$refillqty','$emptyqty','0','$trustqty','$saftyqty','0','$unitprice','$refillprice','$emptyprice','$unitpricewithvat','$refillpricewithvat','$emptypricewithvat',
          '$total','$totalwithvat','1','$updatedatetime','$userID','$grnid','$product')";
        $conn->query($insertgrndetail);

        $totqty = ($newqty + $refillqty + $trustqty + $saftyqty);

        $checkStockQuery = "SELECT * FROM tbl_stock WHERE tbl_product_idtbl_product = '$product'";
        $result = $conn->query($checkStockQuery);

        if ($result && $result->num_rows > 0) {
            $rowcheckstock = $result->fetch_assoc();
            $prevfullstock = $rowcheckstock['fullqty'];
            $prevemptystock = $rowcheckstock['emptyqty'];

            $updatestock = "UPDATE `tbl_stock` SET `fullqty` = (`fullqty` + '$totqty'), `emptyqty` = (`emptyqty` + '$emptyqty') WHERE `tbl_product_idtbl_product` = '$product'";
            if (!$conn->query($updatestock)) {
                error_log("Stock Update Failed: " . $conn->error);
            }

        } else {
            $insertStockQuery = "INSERT INTO `tbl_stock` (`fullqty`, `emptyqty`, `status`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`) VALUES ('$totqty', '$emptyqty', '1', '$userID', '$product')";
            
            if ($conn->query($insertStockQuery) === TRUE) {
                $prevfullstock = 0;
                $prevemptystock = 0;
            } else {
                error_log("Stock Insert Failed for product $product: " . $conn->error);
            }
        }

        $avafullstock = $prevfullstock + $totqty;
        $avaemptystock = $prevemptystock + $emptyqty;

        $inserthistory = "INSERT INTO `tbl_stock_history`
        (`transtype`, `date`, `prevfullqty`, `issuefullqty`, `avafullqty`, 
         `prevemptyqty`, `issueemptyqty`, `avaemptyqty`, `status`, `insertdatetime`, 
         `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `record_id`) 
        VALUES 
        ('1','$grndate','$prevfullstock','$totqty','$avafullstock',
         '$prevemptystock','$emptyqty','$avaemptystock',
         '1','$updatedatetime','$userID','$product','$grnid')";
        $conn->query($inserthistory);

        $checkTrustStockQuery = "SELECT * FROM tbl_stock_trust WHERE tbl_product_idtbl_product = '$product'";
        $resultTrust = $conn->query($checkTrustStockQuery);

        if ($resultTrust && $resultTrust->num_rows > 0) {
            if ($trustqty > 0) {
                $updatetruststock = "UPDATE `tbl_stock_trust` SET `trustqty` = (`trustqty` + '$trustqty'), `saftyqty` = (`saftyqty` + '$saftyqty') WHERE `tbl_product_idtbl_product` = '$product'";
                $conn->query($updatetruststock);
            }
        } else {
            $insertTrustStockQuery = "INSERT INTO `tbl_stock_trust` (`trustqty`, `returnqty`, `saftyqty`, `saftyreturnqty`, `status`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`) VALUES ('$trustqty', '0', '$saftyqty', '0', '1', '$userID', '$product')";
            $conn->query($insertTrustStockQuery);
        }

        $updateorder = "UPDATE `tbl_porder` SET `grnissuestatus`='1', `updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$porderID'";
        $conn->query($updateorder);
    }

    $actionObj = new stdClass();
    $actionObj->icon = 'fas fa-check-circle';
    $actionObj->title = '';
    $actionObj->message = 'Add Successfully';
    $actionObj->url = '';
    $actionObj->target = '_blank';
    $actionObj->type = 'success';
    echo json_encode($actionObj);

} else {
    $actionObj = new stdClass();
    $actionObj->icon = 'fas fa-exclamation-triangle';
    $actionObj->title = '';
    $actionObj->message = 'Record Error: '.$conn->error;
    $actionObj->url = '';
    $actionObj->target = '_blank';
    $actionObj->type = 'danger';
    echo json_encode($actionObj);
}

