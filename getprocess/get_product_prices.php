<?php
require_once('../connection/db.php');

$areaID = $_POST['areaID'];
$customerID = $_POST['customerID'];

$sqlcustomer = "SELECT `specialcus_status`, `main_area`, `tbl_area_idtbl_area` FROM `tbl_customer` WHERE `idtbl_customer` = $customerID";
$resultCustomer = mysqli_query($conn, $sqlcustomer);

$customerData = mysqli_fetch_assoc($resultCustomer);
$specialStatus = $customerData['specialcus_status'];
$mainArea = $customerData['main_area'];
$areaID = $customerData['tbl_area_idtbl_area'];

if ($specialStatus == 1) {
    $query = "SELECT p.idtbl_product, p.orderlevel, p.product_name, ap.newsaleprice, ap.refillsaleprice, ap.emptysaleprice, ap.encustomer_newprice, ap.encustomer_refillprice, ap.encustomer_emptyprice, ap.discount_price
              FROM tbl_product p 
              LEFT JOIN tbl_areawise_product ap ON p.idtbl_product = ap.tbl_product_idtbl_product 
              WHERE `ap`.`status` = 1 AND p.tbl_product_category_idtbl_product_category IN (1,2) AND ap.`tbl_main_area_idtbl_main_area` = '$mainArea' 
              ";

    //Below new code not update this part. Because this one is $customerData['specialcus_status'] not use 
} else {
    // $query = "SELECT p.idtbl_product, p.orderlevel, p.product_name, ap.newsaleprice, ap.refillsaleprice, ap.emptysaleprice, ap.encustomer_newprice, ap.encustomer_refillprice, ap.encustomer_emptyprice, ap.discount_price
    //           FROM tbl_product p 
    //           LEFT JOIN tbl_areawise_product ap ON p.idtbl_product = ap.tbl_product_idtbl_product 
    //           JOIN `tbl_main_area` ma ON ap.`tbl_main_area_idtbl_main_area` = ma.`idtbl_main_area` 
    //           JOIN `tbl_area` sa ON ap.`tbl_main_area_idtbl_main_area` = sa.`tbl_main_area_idtbl_main_area`
    //           WHERE `ap`.`status` = 1 AND p.tbl_product_category_idtbl_product_category IN (1,2) AND sa.`idtbl_area` = '$areaID' 
    //           ";
    $query = "SELECT p.idtbl_product, p.orderlevel, p.product_name, ap.newsaleprice, ap.refillsaleprice, ap.emptysaleprice, ap.encustomer_newprice, CASE WHEN cd.discount_amount IS NOT NULL THEN cd.discount_amount ELSE ap.encustomer_refillprice END AS encustomer_refillprice, ap.encustomer_emptyprice, CASE WHEN ap.discount_price = 0 THEN cd.discount_amount ELSE ap.discount_price END AS discount_price
              FROM tbl_product p 
              LEFT JOIN tbl_areawise_product ap ON p.idtbl_product = ap.tbl_product_idtbl_product 
              LEFT JOIN `tbl_customer_discount` cd ON cd.tbl_product_idtbl_product = p.idtbl_product AND cd.tbl_customer_idtbl_customer = '$customerID'
              JOIN `tbl_main_area` ma ON ap.`tbl_main_area_idtbl_main_area` = ma.`idtbl_main_area` 
              JOIN `tbl_area` sa ON ap.`tbl_main_area_idtbl_main_area` = sa.`tbl_main_area_idtbl_main_area`
              WHERE `ap`.`status` = 1 AND p.tbl_product_category_idtbl_product_category IN (1,2) AND sa.`idtbl_area` = '$areaID'";
}

$result = mysqli_query($conn, $query);

$products = array();
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

// Sort products by orderlevel
usort($products, function($a, $b) {
    return $a['orderlevel'] - $b['orderlevel'];
});

mysqli_close($conn);

echo json_encode($products);
?>
