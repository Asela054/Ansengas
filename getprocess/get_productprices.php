<?php
require_once('../connection/db.php');

$recordID = $_POST['recordID'];

$query = "SELECT `tbl_areawise_product`.*, `tbl_product`.`product_name` FROM tbl_areawise_product LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_areawise_product`.`tbl_product_idtbl_product` WHERE `tbl_areawise_product`.`idtbl_areawise_product` = $recordID";
$resultproductprice = mysqli_query($conn, $query);

if (!$resultproductprice) {
    $response = array('status' => 'error', 'message' => 'Error in SQL query: ' . mysqli_error($conn));
    echo json_encode($response);
} else {
    if ($resultproductprice->num_rows > 0) {
        $rowproductprice = $resultproductprice->fetch_assoc();

        echo json_encode($rowproductprice);
    } else {
        $response = array('status' => 'error', 'message' => 'No records found for idtbl_areawise_product: ' . $recordID);
        echo json_encode($response);
    }
}

mysqli_close($conn);
?>
