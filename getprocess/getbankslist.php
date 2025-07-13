<?php
require_once('../connection/db.php');


$sql="SELECT * FROM `tbl_bank` WHERE `status` IN (1,2)";


$result=$conn->query($sql);
$data = array();
while ($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->id=$row['idtbl_bank'];
    $obj->name=$row['bankname'];
    $obj->code=$row['code'];
    $data[] = $obj;
}


echo json_encode($data);
?>
