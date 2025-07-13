<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_customer` WHERE `idtbl_customer`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$sqlcusdays="SELECT `dayname` FROM `tbl_customer_visitdays` WHERE `tbl_customer_idtbl_customer`='$record' AND `status`=1";
$resultcusdays=$conn->query($sqlcusdays);

$daysArray=array();
while($rowcusdays=$resultcusdays->fetch_assoc()){
    $objdays=new stdClass();
    $objdays->daysID=$rowcusdays['dayname'];
    array_push($daysArray, $objdays);
}

$obj=new stdClass();
$obj->id=$row['idtbl_customer'];
$obj->type=$row['type'];
$obj->name=$row['name'];
$obj->pv_num=$row['pv_num'];
$obj->owner_name=$row['owner_name'];
$obj->owner_dob=$row['owner_dob'];
$obj->nic=$row['nic'];
$obj->phone=$row['phone'];
$obj->email=$row['email'];
$obj->address=$row['address'];
$obj->owner_address=$row['owner_address'];
$obj->tax_cus_name=$row['tax_cus_name'];
$obj->vat_status=$row['vat_status'];
$obj->discount_status=$row['discount_status'];
$obj->tax_num=$row['vat_num'];
//$obj->svat0=$row['s_vat'];
$obj->area=$row['tbl_area_idtbl_area'];
$obj->nodays=$row['numofvisitdays'];
$obj->credit=$row['creditlimit'];
$obj->credittype=$row['credittype'];
$obj->creditperiod=$row['creditperiod'];
$obj->specialcusstatus=$row['specialcus_status'];
$obj->mainarea=$row['main_area'];
$obj->dayslist=$daysArray;

echo json_encode($obj);
?>