<?php 
session_start();
if(!isset($_SESSION['userid'])){
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$products=$_POST['product'];
$disvalue=addslashes($_POST['disvalue']);
$hiddenID=addslashes($_POST['hiddenID']);
$updatedatetime=date('Y-m-d h:i:s');

$success=true;
$insertedCount = 0;

foreach($products as $productID){
    $productID=addslashes($productID);
    $insert="INSERT INTO `tbl_customer_product_special`(`discountprice`, `status`, `insertdatetime`, `updateuser`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$disvalue','1','$updatedatetime','$userID','$hiddenID','$productID')";
    if($conn->query($insert)==true){        
        $insertedCount++;
    }
    else{
        $success=false;
        break;
    }
}

if($success==true){        
    echo json_encode(["status" => "success", "message" => "Inserted $insertedCount records", "action" => "4"]);
}
else{
    echo json_encode(["status" => "error", "message" => "Database error", "action" => "5"]);
}
?>