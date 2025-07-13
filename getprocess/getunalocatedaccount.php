<?php
require_once('../connection/db.php');

$mainclass=$_POST['mainclass'];
$mainaccount=$_POST['mainaccount'];
$company=$_POST['company'];
$companybranch=$_POST['companybranch'];

$sql="";
$sql.="SELECT `idtbl_subaccount`, `subaccount`, `subaccountname` FROM `tbl_subaccount` WHERE `subaccount` NOT IN (SELECT `subaccountno` FROM `tbl_account_allocation` WHERE `status`=1) AND `status`=1";

if(!empty($mainclass)){
    $sql.=" AND `mainclasscode`='$mainclass'";
}
if(!empty($mainaccount)){
    $sql.=" AND `mainaccountcode`='$mainaccount'";
}
$result=$conn->query($sql);

$sqlcompany="SELECT `name` FROM `tbl_company` WHERE `idtbl_company`='$company'";
$resultcompany=$conn->query($sqlcompany);
$rowcompany=$resultcompany->fetch_assoc();

$sqlcompanybranch="SELECT `branch` FROM `tbl_company_branch` WHERE `idtbl_company_branch`='$companybranch'";
$resultcompanybranch=$conn->query($sqlcompanybranch);
$rowcompanybranch=$resultcompanybranch->fetch_assoc();

while($row=$result->fetch_assoc()){
?>
<tr>
    <td><?php echo $row['subaccount'] ?></td>
    <td><?php echo $row['subaccountname'] ?></td>
    <td class="d-none"><?php echo $company; ?></td>
    <td class="d-none"><?php echo $companybranch; ?></td>
    <td class="d-none"><?php echo $row['idtbl_subaccount'] ?></td>
    <td><?php echo $rowcompany['name'] ?></td>
    <td><?php echo $rowcompanybranch['branch'] ?></td>
    <td class="text-center">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input checkallocate" id="customCheck<?php echo $row['idtbl_subaccount'] ?>">
            <label class="custom-control-label" for="customCheck<?php echo $row['idtbl_subaccount'] ?>"></label>
        </div>
    </td>
</tr>
<?php } ?>