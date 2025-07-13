<?php
require_once('../connection/db.php');

$mainclass = $_POST['mainclass'] ?? -2;
$mainclassqry = "";
if($mainclass > 0 ){
    $mainclassqry = " AND tbl_mainclass_idtbl_mainclass = $mainclass ";
}

$sql="SELECT * FROM `tbl_subclass` WHERE `status` IN (1,2) $mainclassqry";


$result=$conn->query($sql);
$data = array();
while ($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->id=$row['idtbl_subclass'];
    $obj->name=$row['subclass'];
    $obj->code=$row['code'];
    $data[] = $obj;
}


echo json_encode($data);
?>
