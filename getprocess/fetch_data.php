<?php
require_once('../connection/db.php');

$type = $_POST['type'];
if(!empty($_POST['groupcategory'])){$groupcategory=$_POST['groupcategory'];}
if(!empty($_POST['selectdate'])){$selectdate=$_POST['selectdate'];}

if ($type === '1') {
    if(!isset($_POST["searchTerm"])){
        $sql="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE 1=1";
        if(!empty($_POST['groupcategory'])){ 
            $groupcategory = $_POST['groupcategory'];
            $sql.=" AND `tbl_group_category_idtbl_group_category`='$groupcategory'";
        }
        $sql.=" AND `status`=1 LIMIT 5";
        $result=$conn->query($sql);
    }
    else{
        $searchTerm=$_POST["searchTerm"];
        
        if(!empty($searchTerm)){
            $sql="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE 1=1";
            if(!empty($_POST['groupcategory'])){ 
                $groupcategory = $_POST['groupcategory'];
                $sql.=" AND `tbl_group_category_idtbl_group_category`='$groupcategory'";
            }
            $sql.=" AND `status`=1 AND `name` LIKE '%$searchTerm%'";
            $result=$conn->query($sql);
        }
        else{
            $sql="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE 1=1";
            if(!empty($_POST['groupcategory'])){ 
                $groupcategory = $_POST['groupcategory'];
                $sql.=" AND `tbl_group_category_idtbl_group_category`='$groupcategory'";
            }
            $sql.=" AND `status`=1 LIMIT 5";
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
} elseif ($type === '5') {
    if(!isset($_POST["searchTerm"])){
        $sql="SELECT `idtbl_area`, `area` FROM `tbl_area` "; if(!empty($_POST['selectdate'])){$sql.="LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`tbl_area_idtbl_area` = `tbl_area`.`idtbl_area`";} $sql.=" WHERE `tbl_area`.`status`=1 "; if(!empty($_POST['selectdate'])){ $sql.="AND `tbl_vehicle_load`.`date` = '$selectdate' GROUP BY `tbl_vehicle_load`.`tbl_area_idtbl_area`";} $sql.=" ORDER BY `tbl_area`.`area` ASC LIMIT 5";
        $result=$conn->query($sql);
    }
    else{
        $searchTerm=$_POST["searchTerm"];
        
        if(!empty($searchTerm)){
            $sql="SELECT `idtbl_area`, `area` FROM `tbl_area` "; if(!empty($_POST['selectdate'])){$sql.="LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`tbl_area_idtbl_area` = `tbl_area`.`idtbl_area`";} $sql.=" WHERE `tbl_area`.`status`=1 AND `tbl_area`.`area` LIKE '%$searchTerm%' "; if(!empty($_POST['selectdate'])){ $sql.="AND `tbl_vehicle_load`.`date` = '$selectdate' GROUP BY `tbl_vehicle_load`.`tbl_area_idtbl_area`";} $sql.=" ORDER BY `tbl_area`.`area` ASC";
            $result=$conn->query($sql);
        }
        else{
            $sql="SELECT `idtbl_area`, `area` FROM `tbl_area` "; if(!empty($_POST['selectdate'])){$sql.="LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`tbl_area_idtbl_area` = `tbl_area`.`idtbl_area`";} $sql.=" WHERE `tbl_area`.`status`=1 "; if(!empty($_POST['selectdate'])){ $sql.="AND `tbl_vehicle_load`.`date` = '$selectdate' GROUP BY `tbl_vehicle_load`.`tbl_area_idtbl_area`";} $sql.=" ORDER BY `tbl_area`.`area` ASC LIMIT 5";
            $result=$conn->query($sql);
        }
    }
    
    $data=array();
    
    while($row=$result->fetch_assoc()) {
        $data[]=array("id"=>$row['idtbl_area'], "text"=>$row['area']);
    }
}

echo json_encode($data);
?>
