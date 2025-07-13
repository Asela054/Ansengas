<?php
require_once('dbConnect.php');

$sql="SELECT `idtbl_vehicle`,`vehicleno` FROM tbl_vehicle WHERE `type`='0' AND `status`='1'";
$result = mysqli_query($con, $sql);

$lorryArray = array();
while ($row = mysqli_fetch_array($result)) {
    array_push($lorryArray, array("id" => $row['idtbl_vehicle'], "vehicleno" => $row['vehicleno']));
}
echo json_encode($lorryArray);

?>