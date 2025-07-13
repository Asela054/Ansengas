<?php 
require_once('../connection/db.php');

$mainclass=$_POST['mainclass'];

$sql="SELECT `code`, `subclass` FROM `tbl_subclass` WHERE `status`=1 AND `mainclasscode`='$mainclass'";
$result=$conn->query($sql);

$arraylist=array();
while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->code=$row['code'];
    $obj->subclass=$row['subclass'];
    
    array_push($arraylist, $obj);
}
echo json_encode($arraylist);
?>