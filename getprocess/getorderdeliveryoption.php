<?php
require_once('../connection/db.php');

$record=$_POST['orderID'];

$sql="SELECT * FROM `tbl_porder_delivery` WHERE `tbl_porder_idtbl_porder`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

if($result->num_rows>0){
    $obj=new stdClass();
    $obj->id=$row['idtbl_porder_delivery'];
    $obj->vehicleid=$row['vehicleid'];
    $obj->trailerid=$row['trailerid'];
    $obj->scheduletime=date("h:i:s", strtotime($row['scheduletime']));
    $obj->comlorrystatus=$row['comlorrystatus'];
    $obj->dislorrystatus=$row['dislorrystatus'];
}
else{
    $obj=new stdClass();
    $obj->id='0';
}

echo json_encode($obj);
?>