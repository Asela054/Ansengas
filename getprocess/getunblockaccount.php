<?php
require_once('../connection/db.php');

$mainclass=$_POST['mainclass'];
$mainaccount=$_POST['mainaccount'];
$company=$_POST['company'];
$companybranch=$_POST['companybranch'];

$sql="";
$sql.="SELECT `tbl_subaccount`.`subaccount`, `tbl_subaccount`.`subaccountname`, `tbl_account_allocation`.`idtbl_account_allocation` FROM `tbl_subaccount` LEFT JOIN `tbl_account_allocation` ON `tbl_account_allocation`.`subaccountno`=`tbl_subaccount`.`subaccount` WHERE `tbl_account_allocation`.`tbl_company_idtbl_company`='$company' AND `tbl_account_allocation`.`tbl_company_branch_idtbl_company_branch`='$companybranch' AND `tbl_account_allocation`.`status`=1";

if(!empty($mainclass)){
    $sql.=" AND `tbl_subaccount`.`mainclasscode`='$mainclass'";
}
if(!empty($mainaccount)){
    $sql.=" AND `tbl_subaccount`.`mainaccountcode`='$mainaccount'";
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
    <td><?php echo $row['idtbl_account_allocation'] ?></td>
    <td><?php echo $row['subaccount'] ?></td>
    <td><?php echo $row['subaccountname'] ?></td>
    <td><?php echo $rowcompany['name'] ?></td>
    <td><?php echo $rowcompanybranch['branch'] ?></td>
    <td class="text-center">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input checkallocate" id="customCheck<?php echo $row['idtbl_account_allocation'] ?>">
            <label class="custom-control-label" for="customCheck<?php echo $row['idtbl_account_allocation'] ?>"></label>
        </div>
    </td>
</tr>
<?php } ?>