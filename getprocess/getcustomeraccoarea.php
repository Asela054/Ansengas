<?php 
require_once('../connection/db.php');

$areaID = $_POST['areaID'];

$sql = "SELECT `idtbl_customer`,`name` FROM `tbl_customer` WHERE `tbl_area_idtbl_area`='$areaID'";
$result = $conn->query($sql);

$menulistArray = array();

while ($row = $result->fetch_assoc()) {
    $objmenulist = new stdClass();
    $objmenulist->customerID = $row['idtbl_customer'];
    $objmenulist->customerName = $row['name'];
    array_push($menulistArray, $objmenulist);
}

$obj = new stdClass();
$obj->customer = $menulistArray;

echo json_encode($obj);
?>
