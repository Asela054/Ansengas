<?php 
require_once('../connection/db.php');

$invdate=$_POST['invdate'];
$refID=$_POST['refID'];

$sql="SELECT `idtbl_dispatch` FROM `tbl_dispatch` WHERE `ref_id`='$refID' AND `status`=1 AND `date`='$invdate'";
$result=$conn->query($sql);

$arraylist=array();
while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->id=$row['idtbl_dispatch'];
    
    array_push($arraylist, $obj);
}

echo json_encode($arraylist);
?>