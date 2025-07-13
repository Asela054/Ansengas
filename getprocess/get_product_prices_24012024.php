<?php
require_once('../connection/db.php');

$areaID=$_POST['areaID'];

$query = "SELECT p.idtbl_product, p.product_name, ap.newsaleprice, ap.refillsaleprice, ap.emptysaleprice, ap.discountedprice
          FROM tbl_product p 
          LEFT JOIN tbl_areawise_product ap ON p.idtbl_product = ap.tbl_product_idtbl_product JOIN `tbl_main_area` ma ON ap.`tbl_main_area_idtbl_main_area` = ma.`idtbl_main_area` JOIN `tbl_area` sa ON ap.`tbl_main_area_idtbl_main_area` = sa.`tbl_main_area_idtbl_main_area`
          WHERE `ap`.`status`=1 AND p.tbl_product_category_idtbl_product_category IN (1,2) AND sa.`idtbl_area` = '$areaID'";

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
