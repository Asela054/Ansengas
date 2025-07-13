<?php
require_once('dbConnect.php');

$sql = "SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array( "idtbl_area" => $row['idtbl_area'], "area" => $row['area']));
}

print(json_encode($result));
mysqli_close($con);
?>