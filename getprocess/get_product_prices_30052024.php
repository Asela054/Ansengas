<?php
require_once('../connection/db.php');

$areaID = $_POST['areaID'];
$customerID = $_POST['customerID'];

$sqlcustomer = "SELECT `specialcus_status`, `main_area` FROM `tbl_customer` WHERE `idtbl_customer` = $customerID";
$resultCustomer = mysqli_query($conn, $sqlcustomer);

$customerData = mysqli_fetch_assoc($resultCustomer);
$specialStatus = $customerData['specialcus_status'];
$mainArea = $customerData['main_area'];

if ($specialStatus == 1) {
    $query = "SELECT p.idtbl_product, p.product_name, ap.newsaleprice, ap.refillsaleprice, ap.emptysaleprice, ap.encustomer_newprice, ap.encustomer_refillprice, ap.        encustomer_emptyprice, ap.discount_price
              FROM tbl_product p 
              LEFT JOIN tbl_areawise_product ap ON p.idtbl_product = ap.tbl_product_idtbl_product 
              WHERE `ap`.`status` = 1 AND p.tbl_product_category_idtbl_product_category IN (1,2) AND ap.`tbl_main_area_idtbl_main_area` = '$mainArea' 
              ORDER BY p.idtbl_product";
} else {
    $query = "SELECT p.idtbl_product, p.product_name, ap.newsaleprice, ap.refillsaleprice, ap.emptysaleprice, ap.encustomer_newprice, ap.encustomer_refillprice, ap.        encustomer_emptyprice, ap.discount_price
              FROM tbl_product p 
              LEFT JOIN tbl_areawise_product ap ON p.idtbl_product = ap.tbl_product_idtbl_product 
              JOIN `tbl_main_area` ma ON ap.`tbl_main_area_idtbl_main_area` = ma.`idtbl_main_area` 
              JOIN `tbl_area` sa ON ap.`tbl_main_area_idtbl_main_area` = sa.`tbl_main_area_idtbl_main_area`
              WHERE `ap`.`status` = 1 AND p.tbl_product_category_idtbl_product_category IN (1,2) AND sa.`idtbl_area` = '$areaID' 
              ORDER BY p.idtbl_product";
}

$result = mysqli_query($conn, $query);

$products = array();
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

mysqli_close($conn);

echo json_encode($products);
?>
