<?php
require_once('../connection/db.php');

$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : null;

if (!isset($searchTerm)) {
    $sql = "SELECT `idtbl_invoice`, `tax_invoice_num` FROM `tbl_invoice` WHERE `status`=1 LIMIT 5";
    $result = $conn->query($sql);
} else {
    if (!empty($searchTerm)) {
        $sql = "SELECT `idtbl_invoice`, `tax_invoice_num` FROM `tbl_invoice` WHERE `status`=1 AND (`idtbl_invoice` LIKE '%$searchTerm%' OR `tax_invoice_num` LIKE '%$searchTerm%')";
        $result = $conn->query($sql);
    } else {
        $sql = "SELECT `idtbl_invoice`, `tax_invoice_num` FROM `tbl_invoice` WHERE `status`=1 LIMIT 5";
        $result = $conn->query($sql);
    }
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $invoice_display = !empty($row['tax_invoice_num']) ? 'AGT' .$row['tax_invoice_num'] : 'INV-' . $row['idtbl_invoice'];
    $data[] = array("id" => $row['idtbl_invoice'], "text" => $invoice_display);
}
echo json_encode($data);
?>