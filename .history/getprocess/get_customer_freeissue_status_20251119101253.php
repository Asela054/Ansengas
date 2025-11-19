<?php
require_once('../connection/db.php'); 

$customer_id = $_POST['customer_id'];

$query = "SELECT freeissue_status FROM tbl_customer WHERE idtbl_customer='$customer_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

echo $row['freeissue_status'];  // 1 or 0
?>
