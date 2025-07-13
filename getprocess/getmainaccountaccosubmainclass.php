<?php 
require_once('../connection/db.php');

$mainclass=$_POST['mainclass'];
$subclass=$_POST['subclass'];

$sql="SELECT `code`, `accountname` FROM `tbl_mainaccount` WHERE `mainclasscode`='$mainclass' AND `subclasscode`='$subclass' AND `status`=1";
$result=$conn->query($sql);

$arraylist=array();
while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->code=$row['code'];
    $obj->accountname=$row['accountname'];
    
    array_push($arraylist, $obj);
}
echo json_encode($arraylist);
?>