<?php 
session_start();
if(!isset($_SESSION['userid'])){
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

// Check if products exist and is an array
if(!isset($_POST['product']) || !is_array($_POST['product']) || empty($_POST['product'])) {
    echo json_encode(["status" => "error", "message" => "No products selected"]);
    exit;
}

$products=$_POST['product'];
$disvalue=addslashes($_POST['disvalue']);
$hiddenID=addslashes($_POST['hiddenID']);
$updatedatetime=date('Y-m-d h:i:s');

$success=true;
$insertedCount = 0;

foreach($products as $productID){
    $productID=addslashes($productID);
    $insert="INSERT INTO `tbl_customer_product_special`(`discountprice`, `status`, `insertdatetime`, `updateuser`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$disvalue','1','$updatedatetime','$userID','$hiddenID','$productID')";
    
    if($conn->query($insert)){        
        $insertedCount++;
    }
    else{
        $success=false;
        error_log("Database error: " . $conn->error);
        break;
    }
}

if($success == true && $insertedCount > 0){        
    echo json_encode(["status" => "success", "message" => "Inserted $insertedCount records", "action" => "4"]);
}
else{
    echo json_encode(["status" => "error", "message" => "Failed to insert records. Inserted: $insertedCount", "action" => "5"]);
}
?>