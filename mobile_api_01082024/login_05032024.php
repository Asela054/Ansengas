<?php

require_once('dbConnect.php');
$username = $_POST["username"];
$password1 = $_POST["password"];
$lorryId = $_POST["lorryId"];
$deviceId = $_POST["deviceId"];

$md5password = md5($password1);
$id = "";
$type = "";
$name = "";
$mobile = "";
$address = "";
$code = "500";
$rootI = "";

$sql = "SELECT * FROM `tbl_user` WHERE `status`='1' AND `tbl_user_type_idtbl_user_type`='7' AND `username`='$username' AND `password`='$md5password'";
$result = mysqli_query($con, $sql);
// $response = array();
if (mysqli_num_rows($result) > 0) {

    $row = mysqli_fetch_row($result);
    $id = $row[0];

    $sqlCheckUser="SELECT COUNT(*) AS `count` FROM `tbl_user_logindata` WHERE `deviceid`='$deviceId' AND `lorryid`='$lorryId' AND `tbl_user_idtbl_user`='$id' AND `logoutstatus`='0'";
    $resultCheckUser=mysqli_query($con,$sqlCheckUser);
    $rowCheckUser = mysqli_fetch_row($resultCheckUser);

    if($rowCheckUser['count']>0){

        $sqlvehcleload = "SELECT * FROM `tbl_vehicle_load` WHERE `lorryid`='$lorryId' AND `approvestatus`='1' AND `unloadstatus`='0' AND `status`='1' AND `date`=DATE(Now())";
        $resultvehcleload = mysqli_query($con, $sqlvehcleload);
        $rowvehcleload = mysqli_fetch_row($resultvehcleload);
    
        $name = $row[1];
        $mobile = '1234567890';
        $rootId = $rowvehcleload[11];
        $code = "200";

    }
    else{

        $sqlinsertMobileRec="INSERT INTO `tbl_user_logindata`(`lorryid`, `deviceid`, `logindate`, `logintime`, `logoutstatus`, `status`, `tbl_user_idtbl_user`) VALUES ('$lorryId','$deviceId',DATE(NOW()),TIME(NOW()),'0','1','$id')";
        $resultMobileRecord=mysqli_query($con, $sqlinsertMobileRec);

        $sqlvehcleload = "SELECT * FROM `tbl_vehicle_load` WHERE `lorryid`='$lorryId' AND `approvestatus`='1' AND `unloadstatus`='0' AND `status`='1' AND `date`=DATE(Now())";
        $resultvehcleload = mysqli_query($con, $sqlvehcleload);
        $rowvehcleload = mysqli_fetch_row($resultvehcleload);
    
        $name = $row[1];
        $mobile = '1234567890';
        $rootId = $rowvehcleload[11];
        $code = "200";
    }

    $response = array("code" => $code, "refid" => $id, "name" => $name, "mobile" => $mobile, "rootId" => $rootId);
    print_r(json_encode($response));
} else {

    $row = mysqli_fetch_row($result);

    $code = "500";
    $response = array("code" => $code, "refid" => $id, "reftype" => $type, "name" => $name, "address" => $address, "mobile" => $mobile, "rootId" => $rootId);
    print_r(json_encode($response));
}
mysqli_close($con);

?>
