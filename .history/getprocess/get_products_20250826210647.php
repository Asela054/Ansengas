<?php
require_once('../connection/db.php');

$query = "SELECT * FROM tbl_product WHERE tbl_product_category_idtbl_product_category IN (1,2) AND status =1";
$result = mysqli_query($conn, $query);

$sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
$resultvat = $conn->query($sqlvat);
$rowvat = $resultvat->fetch_assoc();

if (!$result || !$resultvat) {
    die(json_encode(array('error' => 'Query failed: ' . mysqli_error($conn))));
}

$products = array();
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

// Sort products by orderlevel
usort($products, function($a, $b) {
    return $a['orderlevel'] - $b['orderlevel'];
});

// Add VAT information to the products array
$products['vat'] = $rowvat['vat'];

mysqli_close($conn);

// Return only valid JSON
echo json_encode($products);
?>
