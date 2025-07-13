<?php
require_once('../connection/db.php');

$type = $_POST['type'];

if ($type === '1') {
    if(!isset($_POST["searchTerm"])){
        $sql="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 LIMIT 5";
        $result=$conn->query($sql);
    }
    else{
        $searchTerm=$_POST["searchTerm"];
        
        if(!empty($searchTerm)){
            $sql="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 AND `name` LIKE '%$searchTerm%'";
            $result=$conn->query($sql);
        }
        else{
            $sql="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 LIMIT 5";
            $result=$conn->query($sql);
        }
    }
    
    $data=array();
    
    while($row=$result->fetch_assoc()) {
        $data[]=array("id"=>$row['idtbl_customer'], "text"=>$row['name']);
    }
} elseif ($type === '2') {
    if(!isset($_POST["searchTerm"])){
        $sql="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=7 ORDER BY `name` ASC LIMIT 5";
        $result=$conn->query($sql);
    }
    else{
        $searchTerm=$_POST["searchTerm"];
        
        if(!empty($searchTerm)){
            $sql="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=7 AND `name` LIKE '%$searchTerm%' ORDER BY `name` ASC";
            $result=$conn->query($sql);
        }
        else{
            $sql="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=7 ORDER BY `name` ASC LIMIT 5";
            $result=$conn->query($sql);
        }
    }
    
    $data=array();
    
    while($row=$result->fetch_assoc()) {
        $data[]=array("id"=>$row['idtbl_employee'], "text"=>$row['name']);
    }
} elseif ($type === '3') {
    if(!isset($_POST["searchTerm"])){
        $sql="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `status`=1 AND `type`=0 ORDER BY `vehicleno` ASC LIMIT 5";
        $result=$conn->query($sql);
    }
    else{
        $searchTerm=$_POST["searchTerm"];
        
        if(!empty($searchTerm)){
            $sql="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `status`=1 AND `type`=0 AND `vehicleno` LIKE '%$searchTerm%' ORDER BY `vehicleno` ASC";
            $result=$conn->query($sql);
        }
        else{
            $sql="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `status`=1 AND `type`=0 ORDER BY `vehicleno` ASC LIMIT 5";
            $result=$conn->query($sql);
        }
    }
    
    $data=array();
    
    while($row=$result->fetch_assoc()) {
        $data[]=array("id"=>$row['idtbl_vehicle'], "text"=>$row['vehicleno']);
    }
} elseif ($type === '4') {
    if(!isset($_POST["searchTerm"])){
        $sql="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=4 ORDER BY `name` ASC LIMIT 5";
        $result=$conn->query($sql);
    }
    else{
        $searchTerm=$_POST["searchTerm"];
        
        if(!empty($searchTerm)){
            $sql="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=4 AND `name` LIKE '%$searchTerm%' ORDER BY `name` ASC";
            $result=$conn->query($sql);
        }
        else{
            $sql="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=4 ORDER BY `name` ASC LIMIT 5";
            $result=$conn->query($sql);
        }
    }
    
    $data=array();
    
    while($row=$result->fetch_assoc()) {
        $data[]=array("id"=>$row['idtbl_employee'], "text"=>$row['name']);
    }
}

echo json_encode($data);
?>
