<?php
require_once('../connection/db.php');

$recordID = $_POST['recordID'];

$query = "SELECT `tbl_areawise_product`.*, `tbl_product`.`product_name` FROM tbl_areawise_product LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_areawise_product`.`tbl_product_idtbl_product` WHERE tbl_main_area_idtbl_main_area = $recordID";
$resultproductprice = mysqli_query($conn, $query);

$html = '';

if ($resultproductprice->num_rows > 0) {
    while ($rowproductprice = $resultproductprice->fetch_assoc()) {

        $html .= '
        <tr>
            <td>' . $rowproductprice['idtbl_areawise_product'] . '</td>
            <td>' . $rowproductprice['product_name'] . '</td>
            <td>' . $rowproductprice['newsaleprice'] . '</td>
            <td>' . $rowproductprice['refillsaleprice'] . '</td>
            <td>' . $rowproductprice['emptysaleprice'] . '</td>
            <td>' . $rowproductprice['discountedprice'] . '</td>
            <td class="text-right">
                <button class="btn btn-outline-primary btn-sm btnEditproduct" id="' . $rowproductprice['idtbl_areawise_product'] . '"><i class="fas fa-pen"></i></button>
            </td>
        </tr>
        ';
    }
}
echo $html;
?>

