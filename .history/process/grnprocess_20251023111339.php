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
    // Insert GRN main record
    $insertgrn = "INSERT INTO `tbl_grn`(`date`, `total`, `taxamount`, `nettotal`, `invoicenum`, `dispatchnum`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES (?, ?, ?, ?, ?, ?, '1', ?, ?)";
    $stmt = $conn->prepare($insertgrn);
    $stmt->bind_param("sddssssi", $grndate, $grnnettotalwithoutvat, $taxamount, $grnnettotal, $grninvoice, $grndispatch, $updatedatetime, $userID);
    
    if($stmt->execute()) {
        $grnid = $conn->insert_id;
        $stmt->close();
        
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

            // Insert GRN detail
            $insertgrndetail = "INSERT INTO `tbl_grndetail`(`date`, `type`, `newqty`, `fillqty`, `emptyqty`, `returnqty`, `trustqty`, `saftyqty`, `saftyreturnqty`, `unitprice_withoutvat`, `refillprice_withoutvat`, `emptyprice_withoutvat`, `unitprice`, `refillprice`,`emptyprice`, `totalwithoutvat`, `total`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_grn_idtbl_grn`, `tbl_product_idtbl_product`) VALUES (?, '0', ?, ?, ?, '0', ?, ?, '0', ?, ?, ?, ?, ?, ?, ?, ?, '1', ?, ?, ?, ?)";
            $stmt_detail = $conn->prepare($insertgrndetail);
            $stmt_detail->bind_param("siiiissssssssssssiii", $grndate, $newqty, $refillqty, $emptyqty, $trustqty, $saftyqty, $unitprice, $refillprice, $emptyprice, $unitpricewithvat, $refillpricewithvat, $emptypricewithvat, $total, $totalwithvat, $updatedatetime, $userID, $grnid, $product);
            
            if(!$stmt_detail->execute()) {
                $success = false;
                error_log("GRN Detail Insert Failed: " . $stmt_detail->error);
            }
            $stmt_detail->close();

            $totqty = ($newqty + $refillqty + $trustqty + $saftyqty);

            // Check and update stock
            $checkStockQuery = "SELECT * FROM tbl_stock WHERE tbl_product_idtbl_product = ?";
            $stmt_stock = $conn->prepare($checkStockQuery);
            $stmt_stock->bind_param("i", $product);
            $stmt_stock->execute();
            $result = $stmt_stock->get_result();
            $rowcheckstock = $result->fetch_assoc();
            
            if ($result->num_rows > 0) {
                if ($totqty > 0) {
                    $updatestock = "UPDATE `tbl_stock` SET `fullqty` = (`fullqty` + ?), `emptyqty` = (`emptyqty` + ?) WHERE `tbl_product_idtbl_product` = ?";
                    $stmt_update = $conn->prepare($updatestock);
                    $total_full_qty = $newqty + $refillqty + $trustqty + $saftyqty;
                    $stmt_update->bind_param("ddi", $total_full_qty, $emptyqty, $product);
                    if(!$stmt_update->execute()) {
                        $success = false;
                        error_log("Stock Update Failed: " . $stmt_update->error);
                    }
                    $stmt_update->close();
                } elseif ($emptyqty > 0) {
                    $updatestock = "UPDATE `tbl_stock` SET `emptyqty` = (`emptyqty` + ?) WHERE `tbl_product_idtbl_product` = ?";
                    $stmt_update = $conn->prepare($updatestock);
                    $stmt_update->bind_param("di", $emptyqty, $product);
                    if(!$stmt_update->execute()) {
                        $success = false;
                        error_log("Empty Stock Update Failed: " . $stmt_update->error);
                    }
                    $stmt_update->close();
                }
            } else {
                $insertStockQuery = "INSERT INTO tbl_stock (fullqty, emptyqty, damageqty, status, tbl_user_idtbl_user, tbl_product_idtbl_product) VALUES (?, ?, '0', '1', ?, ?)";
                $total_full_qty = $newqty + $refillqty + $trustqty + $saftyqty;
                $stmt_insert = $conn->prepare($insertStockQuery);
                $stmt_insert->bind_param("ddii", $total_full_qty, $emptyqty, $userID, $product);
                if(!$stmt_insert->execute()) {
                    $success = false;
                    error_log("Stock Insert Failed: " . $stmt_insert->error);
                }
                $stmt_insert->close();
            }
            $stmt_stock->close();

            // Check and update trust stock
            $checkTrustStockQuery = "SELECT * FROM tbl_stock_trust WHERE tbl_product_idtbl_product = ?";
            $stmt_trust = $conn->prepare($checkTrustStockQuery);
            $stmt_trust->bind_param("i", $product);
            $stmt_trust->execute();
            $resultTrust = $stmt_trust->get_result();

            if ($resultTrust->num_rows > 0) {
                if ($trustqty > 0 || $saftyqty > 0) {
                    $updatetruststock = "UPDATE `tbl_stock_trust` SET `trustqty`=(`trustqty`+?), `saftyqty`=(`saftyqty`+?) WHERE `tbl_product_idtbl_product`=?";
                    $stmt_trust_update = $conn->prepare($updatetruststock);
                    $stmt_trust_update->bind_param("ddi", $trustqty, $saftyqty, $product);
                    if(!$stmt_trust_update->execute()) {
                        $success = false;
                        error_log("Trust Stock Update Failed: " . $stmt_trust_update->error);
                    }
                    $stmt_trust_update->close();
                }
            } else {
                if ($trustqty > 0 || $saftyqty > 0) {
                    $insertTrustStockQuery = "INSERT INTO tbl_stock_trust (`trustqty`, `returnqty`, `saftyqty`, `saftyreturnqty`, `status`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`) VALUES (?, '0', ?, '0', '1', ?, ?)";
                    $stmt_trust_insert = $conn->prepare($insertTrustStockQuery);
                    $stmt_trust_insert->bind_param("ddii", $trustqty, $saftyqty, $userID, $product);
                    if(!$stmt_trust_insert->execute()) {
                        $success = false;
                        error_log("Trust Stock Insert Failed: " . $stmt_trust_insert->error);
                    }
                    $stmt_trust_insert->close();
                }
            }
            $stmt_trust->close();

            // Insert stock history
            if(isset($rowcheckstock)) {
                $prevfullstock = $rowcheckstock['fullqty'] ?? 0;
                $prevemptystock = $rowcheckstock['emptyqty'] ?? 0;
                $avafullstock = $prevfullstock + $totqty;
                $avaemptystock = $prevemptystock + $emptyqty;

                $inserthistory = "INSERT INTO `tbl_stock_history`(`transtype`, `date`, `prevfullqty`, `issuefullqty`, `avafullqty`, `prevemptyqty`, `issueemptyqty`, `avaemptyqty`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `record_id`) VALUES ('1', ?, ?, ?, ?, ?, ?, ?, '1', ?, ?, ?, ?)"; 
                $stmt_history = $conn->prepare($inserthistory);
                $stmt_history->bind_param("sddddddsiii", $grndate, $prevfullstock, $totqty, $avafullstock, $prevemptystock, $emptyqty, $avaemptystock, $updatedatetime, $userID, $product, $grnid);
                if(!$stmt_history->execute()) {
                    $success = false;
                    error_log("Stock History Insert Failed: " . $stmt_history->error);
                }
                $stmt_history->close();
            }
        }
        
        // Update purchase order status
        $updateorder = "UPDATE `tbl_porder` SET `grnissuestatus`='1',`updatedatetime`=?,`tbl_user_idtbl_user`=? WHERE `idtbl_porder`=?";
        $stmt_order = $conn->prepare($updateorder);
        $stmt_order->bind_param("sii", $updatedatetime, $userID, $porderID);
        if(!$stmt_order->execute()) {
            $success = false;
            error_log("Order Update Failed: " . $stmt_order->error);
        }
        $stmt_order->close();
        
    } else {
        $success = false;
        error_log("GRN Insert Failed: " . $stmt->error);
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