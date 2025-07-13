<?php 
require_once('../connection/db.php');

$areaID=$_POST['areaID'];

$sqlcus = "SELECT `tbl_customer`.`idtbl_customer`, `tbl_customer`.`name`, `tbl_customer`.`type` FROM `tbl_customer` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_customer`.`tbl_area_idtbl_area` WHERE `tbl_customer`.`status`=1 AND `tbl_area`.`idtbl_area`='$areaID' ORDER BY `tbl_customer`.`name` ASC";
$resultcus = $conn->query($sqlcus);

$arraylist=array();
while($row=$resultcus->fetch_assoc()){
    $obj=new stdClass();
    $obj->id=$row['idtbl_customer'];
    $obj->name=$row['name'];
    $obj->type=$row['type'];
    
    array_push($arraylist, $obj);
}

echo json_encode($arraylist);
?>