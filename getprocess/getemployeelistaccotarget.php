<?php 
require_once('../connection/db.php');

$reporttype=$_POST['reporttype'];
$arraylist=array();

if($reporttype == 1 || $reporttype == 2) {
    if($reporttype == 1){$usertype= 7;} // For Executive
    if($reporttype == 2){$usertype= 4;} // For Driver
    $sql = "SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`='$usertype' ORDER BY `name` ASC";
    $result = $conn->query($sql);
    while($row=$result->fetch_assoc()){
        $obj=new stdClass();
        $obj->id=$row['idtbl_employee'];
        $obj->text=$row['name'];
        
        array_push($arraylist, $obj);
    }
}
else if($reporttype == 3) {
    $sql = "SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1 ORDER BY `area` ASC";
    $result = $conn->query($sql);
    while($row=$result->fetch_assoc()){
        $obj=new stdClass();
        $obj->id=$row['idtbl_area'];
        $obj->text=$row['area'];
        
        array_push($arraylist, $obj);
    }
}
else if($reporttype == 4) {
    $sql = "SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1";
    if(!empty($_POST['searchTerm'])){
        $search = $_POST['searchTerm'];
        $sql .= " AND `name` LIKE '%$search%' ORDER BY `name` ASC";
    }
    else{
        $sql .= " ORDER BY `name` ASC LIMIT 5";
    }
    $result = $conn->query($sql);
    while($row=$result->fetch_assoc()){
        $obj=new stdClass();
        $obj->id=$row['idtbl_customer'];
        $obj->text=$row['name'];
        
        array_push($arraylist, $obj);
    }
}
else if($reporttype == 5) {
    $sql = "SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `status`=1 ORDER BY `vehicleno` ASC";
    $result = $conn->query($sql);
    while($row=$result->fetch_assoc()){
        $obj=new stdClass();
        $obj->id=$row['idtbl_vehicle'];
        $obj->text=$row['vehicleno'];
        
        array_push($arraylist, $obj);
    }
}

echo json_encode($arraylist);
?>