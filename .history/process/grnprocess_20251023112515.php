<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID = $_SESSION['userid'];

// Get POST data
$grnnum = $_POST['grnnum'];
$porderID = $_POST['ponumber'];
$grndate = $_POST['grndate'];
$grninvoice = $_POST['grninvoice'];
$grndispatch = $_POST['grndispatch'];
$grnnettotal = $_POST['grnnettotal'];
$grnnettotalwithoutvat = $_POST['grnnettotalwithoutvat'];
$taxamount = $_POST['taxamount'];
$tableData = $_POST['tableData'];

$updatedatetime = date('Y-m-d h:i:s');

// Start transaction for data consistency
$conn->autocommit(FALSE);
$success = true;

try {
    $insertgrn = "INSERT INTO `tbl_grn`(`date`, `total`, `taxamount`, `nettotal`, `invoicenum`, `dispatchnum`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$grndate','$grnnettotalwithoutvat','$taxamount','$grnnettotal','$grninvoice','$grndispatch','1','$updatedatetime','$userID')";
    
    if($conn->query($insertgrn)) {
        $grnid = $conn->insert_id;
        
        foreach($tableData as $rowtabledata) {
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

            $insertgrndetail = "INSERT INTO `tbl_grndetail`(`date`, `type`, `newqty`, `fillqty`, `emptyqty`, `returnqty`, `trustqty`, `saftyqty`, `saftyreturnqty`, `unitprice_withoutvat`, `refillprice_withoutvat`, `emptyprice_withoutvat`, `unitprice`, `refillprice`,`emptyprice`, `totalwithoutvat`, `total`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_grn_idtbl_grn`, `tbl_product_idtbl_product`) VALUES ('$grndate','0','$newqty','$refillqty','$emptyqty','0','$trustqty','$saftyqty','0','$unitprice','$refillprice','$emptyprice','$unitpricewithvat','$refillpricewithvat','$emptypricewithvat','$total','$totalwithvat','1','$updatedatetime','$userID','$grnid','$product')";
            
            if(!$conn->query($insertgrndetail)) {
                $success = false;
                error_log("GRN Detail Insert Failed: " . $conn->error);
                error_log("Query: " . $insertgrndetail);
            }

            $totqty = ($newqty + $refillqty + $trustqty + $saftyqty);

            $checkStockQuery = "SELECT * FROM tbl_stock WHERE tbl_product_idtbl_product = '$product'";
            $result = $conn->query($checkStockQuery);
            $rowcheckstock = $result->fetch_assoc();
            
            if ($result->num_rows > 0) {
                if ($totqty > 0 || $emptyqty > 0) {
                    $total_full_qty = $newqty + $refillqty + $trustqty + $saftyqty;
                    $updatestock = "UPDATE `tbl_stock` SET `fullqty` = (`fullqty` + '$total_full_qty'), `emptyqty` = (`emptyqty` + '$emptyqty') WHERE `tbl_product_idtbl_product` = '$product'";
                    if(!$conn->query($updatestock)) {
                        $success = false;
                        error_log("Stock Update Failed: " . $conn->error);
                    }
                }
            } else {
                $total_full_qty = $newqty + $refillqty + $trustqty + $saftyqty;
                $insertStockQuery = "INSERT INTO tbl_stock (fullqty, emptyqty, damageqty, status, tbl_user_idtbl_user, tbl_product_idtbl_product) VALUES ('$total_full_qty', '$emptyqty', '0', '1', '$userID', '$product')";
                if(!$conn->query($insertStockQuery)) {
                    $success = false;
                    error_log("Stock Insert Failed: " . $conn->error);
                }
            }

            // Check and update trust stock
            $checkTrustStockQuery = "SELECT * FROM tbl_stock_trust WHERE tbl_product_idtbl_product = '$product'";
            $resultTrust = $conn->query($checkTrustStockQuery);

            if ($resultTrust->num_rows > 0) {
                if ($trustqty > 0 || $saftyqty > 0) {
                    $updatetruststock = "UPDATE `tbl_stock_trust` SET `trustqty`=(`trustqty`+'$trustqty'), `saftyqty`=(`saftyqty`+'$saftyqty') WHERE `tbl_product_idtbl_product`='$product'";
                    if(!$conn->query($updatetruststock)) {
                        $success = false;
                        error_log("Trust Stock Update Failed: " . $conn->error);
                    }
                }
            } else {
                if ($trustqty > 0 || $saftyqty > 0) {
                    $insertTrustStockQuery = "INSERT INTO tbl_stock_trust (`trustqty`, `returnqty`, `saftyqty`, `saftyreturnqty`, `status`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`) VALUES ('$trustqty', '0', '$saftyqty', '0', '1', '$userID', '$product')";
                    if(!$conn->query($insertTrustStockQuery)) {
                        $success = false;
                        error_log("Trust Stock Insert Failed: " . $conn->error);
                    }
                }
            }

            // Insert stock history
            if(isset($rowcheckstock)) {
                $prevfullstock = $rowcheckstock['fullqty'] ?? 0;
                $prevemptystock = $rowcheckstock['emptyqty'] ?? 0;
                $avafullstock = $prevfullstock + $totqty;
                $avaemptystock = $prevemptystock + $emptyqty;

                $inserthistory = "INSERT INTO `tbl_stock_history`(`transtype`, `date`, `prevfullqty`, `issuefullqty`, `avafullqty`, `prevemptyqty`, `issueemptyqty`, `avaemptyqty`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `record_id`) VALUES ('1','$grndate','$prevfullstock','$totqty','$avafullstock','$prevemptystock','$emptyqty','$avaemptystock','1','$updatedatetime','$userID','$product','$grnid')"; 
                if(!$conn->query($inserthistory)) {
                    $success = false;
                    error_log("Stock History Insert Failed: " . $conn->error);
                }
            }
        }
        
        // Update purchase order status
        $updateorder = "UPDATE `tbl_porder` SET `grnissuestatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$porderID'";
        if(!$conn->query($updateorder)) {
            $success = false;
            error_log("Order Update Failed: " . $conn->error);
        }
        
    } else {
        $success = false;
        error_log("GRN Insert Failed: " . $conn->error);
    }
    
    // Commit or rollback transaction
    if($success) {
        $conn->commit();
        
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-check-circle';
        $actionObj->title = '';
        $actionObj->message = 'Add Successfully';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'success';
        echo json_encode($actionObj);
    } else {
        $conn->rollback();
        throw new Exception("Transaction failed");
    }
    
} catch (Exception $e) {
    $conn->rollback();
    
    $actionObj = new stdClass();
    $actionObj->icon = 'fas fa-exclamation-triangle';
    $actionObj->title = '';
    $actionObj->message = 'Record Error: ' . $e->getMessage();
    $actionObj->url = '';
    $actionObj->target = '_blank';
    $actionObj->type = 'danger';
    echo json_encode($actionObj);
} finally {
    $conn->autocommit(TRUE);
}