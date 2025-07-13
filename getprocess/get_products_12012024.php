<?php
require_once('../connection/db.php');

$query = "SELECT * FROM tbl_product WHERE tbl_product_category_idtbl_product_category IN (1,2) AND status =1";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$products = array();
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

mysqli_close($conn);

echo json_encode($products);
?>
