<?php
require_once('dbConnect.php');

$sql = "SELECT `msg` FROM `tbl_motivation_msg` WHERE `status`=1";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array( "msg" => $row['msg']));
}

print(json_encode($result));
mysqli_close($con);
?>