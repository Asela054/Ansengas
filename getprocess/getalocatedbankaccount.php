<?php
require_once('../connection/db.php');

$mainclass=$_POST['mainclass'];
$mainaccount=$_POST['mainaccount'];
$bank=$_POST['bank'];
$bankbranch=$_POST['bankbranch'];

$sql="";
$sql.="SELECT `subaccount`, `subaccountname` FROM `tbl_subaccount` WHERE `subaccount` IN (SELECT `accountno` FROM `tbl_bank_account_allocation` WHERE `tbl_bank_idtbl_bank`='$bank' AND `tbl_bank_branch_idtbl_bank_branch`='$bankbranch') AND `status`=1 AND `tbl_account_category_idtbl_account_category`=1";

if(!empty($mainclass)){
    $sql.=" AND `mainclasscode`='$mainclass'";
}
if(!empty($mainaccount)){
    $sql.=" AND `mainaccountcode`='$mainaccount'";
}
$result=$conn->query($sql);

$sqlbank="SELECT `bankname` FROM `tbl_bank` WHERE `idtbl_bank`='$bank'";
$resultbank=$conn->query($sqlbank);
$rowbank=$resultbank->fetch_assoc();

$sqlbankbranch="SELECT `branchname` FROM `tbl_bank_branch` WHERE `idtbl_bank_branch`='$bankbranch'";
$resultbankbranch=$conn->query($sqlbankbranch);
$rowbankbranch=$resultbankbranch->fetch_assoc();

while($row=$result->fetch_assoc()){
?>
<tr>
    <td><?php echo $row['subaccount'] ?></td>
    <td><?php echo $row['subaccountname'] ?></td>
    <td><?php echo $rowbank['bankname'] ?></td>
    <td><?php echo $rowbankbranch['branchname'] ?></td>
</tr>
<?php } ?>