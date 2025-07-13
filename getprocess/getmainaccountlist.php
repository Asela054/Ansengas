<?php
require_once('../connection/db.php');

$subclassQry = "";
if(isset($_POST['subclass']) && $_POST['subclass'] > 0){
    $subclassQry = " AND tbl_subclass_idtbl_subclass = ".$_POST['subclass'];
}

$sql="SELECT * FROM `tbl_mainaccount` WHERE `status` IN (1,2) $subclassQry ";


$result=$conn->query($sql);
$data = array();
while ($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->id=$row['idtbl_mainaccount'];
    $obj->name=$row['accountname'];
    $obj->code=$row['code'];
    $data[] = $obj;
}


echo json_encode($data);
?>
