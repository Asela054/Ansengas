<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$yearid=$_POST['financialyearselect'];
$updatedatetime=date('Y-m-d h:i:s');


$update="UPDATE `tbl_finacial_year` SET `actstatus`= 0 ;";
$updateRec="UPDATE `tbl_finacial_year` SET `actstatus`= 1 WHERE `idtbl_finacial_year` = 1 ;";

if($conn->query($update)===true && $conn->query($updateRec)===true ){
    header("Location:../financialyear.php?action=6");
}
else{
    header("Location:../financialyear.php?action=5");
}

?>