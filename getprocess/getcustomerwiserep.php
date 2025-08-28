<?php
require_once('../connection/db.php');

$record = $_POST['recordID'];

$sql = "SELECT s.idtbl_customerwise_salesrep, s.tbl_customer_idtbl_customer, c.name, s.tbl_product_idtbl_product, s.tbl_employee_idtbl_employee FROM tbl_customerwise_salesrep s JOIN tbl_customer c ON s.tbl_customer_idtbl_customer = c.idtbl_customer WHERE s.idtbl_customerwise_salesrep = '$record'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$obj = new stdClass();
$obj->id = $row['idtbl_customerwise_salesrep'];
$obj->customer = [
    "id" => $row['tbl_customer_idtbl_customer'],
    "text" => $row['name']
];
$obj->product = $row['tbl_product_idtbl_product'];
$obj->employee = $row['tbl_employee_idtbl_employee'];

echo json_encode($obj);
?>
