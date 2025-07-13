<?php
require_once('dbConnect.php');

$datalist=array();

$sql="SELECT `idtbl_reject_reason`, `reason` FROM `tbl_reject_reason` WHERE `status`='1'";
$res = mysqli_query($con, $sql);
while($row = mysqli_fetch_array($res)){
    $obj= new stdClass();
    $obj->id=$row['idtbl_reject_reason'];
    $obj->reason=$row['reason'];

    array_push($datalist, $obj);
}
print(json_encode($datalist));
mysqli_close($con);

?>