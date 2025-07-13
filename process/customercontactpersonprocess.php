<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$company_contact_person=$_POST['company_contact_person'];
$name=$_POST['name'];
$type=$_POST['type'];
$mobile=$_POST['mobile'];
$phone=$_POST['phone'];
$whatsapp_num=$_POST['whatsapp_num'];
$mail=$_POST['mail'];
$contact_person_dob=$_POST['contact_person_dob'];
$hiddenid=$_POST['hiddenid'];

$updatedatetime=date('Y-m-d h:i:s');

$query = "INSERT INTO `tbl_customer_contact_person`(`company_contact_person`,`name`, `contact_type`, `mobile`, `phone`, `whatsapp_num`,`email`, `contact_person_dob`,`status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`) VALUES ('$company_contact_person','$name','$type','$mobile','$phone','$whatsapp_num','$mail','$contact_person_dob','1','$updatedatetime','$userID','$hiddenid')";
if($conn->query($query)==true){
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-check-circle';
    $actionObj->title='';
    $actionObj->message='Add Successfully';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='success';

    echo $actionJSON=json_encode($actionObj);
}
else{
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    echo $actionJSON=json_encode($actionObj);
}

?>