<?php

require_once('dbConnect.php');

$username = $_POST["username"]; //$_POST["username"];
$password1 = $_POST["password"]; //$_POST["password"];
$lorryId = $_POST["lorryId"];
$deviceId = $_POST["deviceId"];

$query = "SELECT * FROM tbl_user WHERE username='$username' AND status=1";// AND password = $userPass";
$result = mysqli_query( $con, $query);
$row = mysqli_fetch_array($result);
    
$hash_password = password_verify($password1, $row['password']);

if($hash_password === true) {
    $id = $row['idtbl_user'];
    $type = "";
    $name = "";
    $mobile = "";
    $address = "";
    $successcode = "200";
    $rootID = "";


    // $sqlvehcleload="SELECT * FROM `tbl_vehicle_load` WHERE `lorryid`='$lorryId' AND `approvestatus`='1' AND `unloadstatus`='0' AND `status`='1' AND `date`=DATE(Now())";
    $sqlvehcleload="SELECT `tbl_vehicle_load`.`idtbl_vehicle_load`, `tbl_vehicle_load`.`tbl_area_idtbl_area`, `tbl_vehicle_load`.`lorryid` FROM `tbl_vehicle_load` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_vehicle_load`.`driverid` WHERE `tbl_vehicle_load`.`approvestatus`='1' AND `tbl_vehicle_load`.`unloadstatus`='0' AND `tbl_vehicle_load`.`status`='1' AND `tbl_vehicle_load`.`date`=DATE(Now()) AND `tbl_employee`.`useraccount_id`='$id'";
    $resultvehcleload=$con->query($sqlvehcleload);
    $rowvehcleload=$resultvehcleload->fetch_assoc();

    $response = array("load_id" => $rowvehcleload['idtbl_vehicle_load'], "code" => $successcode, "refid" => $row['idtbl_user'], "name" => $row['name'], "mobile" => $mobile, "rootId" => $rowvehcleload['tbl_area_idtbl_area'], "lorryid" => $rowvehcleload['lorryid']);
    print_r(json_encode($response));
} else {

    $row = mysqli_fetch_row($result);

    $code = "500";
    $response = array("load_id" => '', "code" => $code, "refid" => '', "name" => '', "mobile" => '', "rootId" => '', "lorryid" => '');    
    print_r(json_encode($response));
}
mysqli_close($con);

?>
