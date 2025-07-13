<?php

require_once('dbConnect.php');

$areaID = $_POST['areaID'];
$customerID = $_POST['customerID'];

$sqlcustomer = "SELECT * FROM `tbl_customer` WHERE `idtbl_customer` = $customerID";
$resultcustomer = mysqli_query($con, $sqlcustomer);
$rowcustomer = mysqli_fetch_assoc($resultcustomer);

$specialStatus = $rowcustomer['specialcus_status'];
$mainArea = $rowcustomer['main_area'];
$areaID = $rowcustomer['tbl_area_idtbl_area'];

$result = array();

if (mysqli_num_rows($resultcustomer) > 0) {
    if ($specialStatus == 1) {
        $query = "SELECT p.idtbl_product, p.product_name, ap.newsaleprice, ap.refillsaleprice, ap.emptysaleprice, ap.encustomer_newprice, ap.encustomer_refillprice, ap.encustomer_emptyprice, ap.discount_price, p.tbl_product_category_idtbl_product_category
                  FROM tbl_product p 
                  LEFT JOIN tbl_areawise_product ap ON p.idtbl_product = ap.tbl_product_idtbl_product 
                  WHERE `ap`.`status` = 1 AND p.tbl_product_category_idtbl_product_category IN (1,2) AND ap.`tbl_main_area_idtbl_main_area` = '$mainArea' 
                  ORDER BY p.orderlevel ASC";
    } else {
        $query = "SELECT p.idtbl_product, p.product_name, ap.newsaleprice, ap.refillsaleprice, ap.emptysaleprice, ap.encustomer_newprice, ap.encustomer_refillprice, ap.encustomer_emptyprice, ap.discount_price, p.tbl_product_category_idtbl_product_category
                  FROM tbl_product p 
                  LEFT JOIN tbl_areawise_product ap ON p.idtbl_product = ap.tbl_product_idtbl_product 
                  JOIN `tbl_main_area` ma ON ap.`tbl_main_area_idtbl_main_area` = ma.`idtbl_main_area` 
                  JOIN `tbl_area` sa ON ap.`tbl_main_area_idtbl_main_area` = sa.`tbl_main_area_idtbl_main_area`
                  WHERE `ap`.`status` = 1 AND p.tbl_product_category_idtbl_product_category IN (1,2) AND sa.`idtbl_area` = '$areaID' 
                  ORDER BY p.orderlevel ASC";
    }

    $res = mysqli_query($con, $query);
    if ($res) {
        while ($row = mysqli_fetch_array($res)) {
            array_push($result, array(
                "id" => $row['idtbl_product'],
                "product_name" => $row['product_name'],
                "categoryid" => $row['tbl_product_category_idtbl_product_category'],
                "new_saleprice" => $row['newsaleprice'],
                "refill_saleprice" => $row['refillsaleprice'],
                "empty_saleprice" => $row['emptysaleprice'],
                "encustomer_newprice" => $row['encustomer_newprice'],
                "encustomer_refillprice" => $row['encustomer_refillprice'],
                "encustomer_emptyprice" => $row['encustomer_emptyprice'],
                "discount_price" => $row['discount_price']
            ));
        }
    }
}

print(json_encode($result));
mysqli_close($con);
?>
