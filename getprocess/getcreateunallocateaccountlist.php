<?php 
require_once('../connection/db.php');

$company=$_POST['company'];
$companybranch=$_POST['companybranch'];
$mainclass=$_POST['mainclass'];
$mainaccount=$_POST['mainaccount'];
$category=$_POST['category'];

$sqlcompany="SELECT `code` FROM `tbl_company` WHERE `idtbl_company`='$company' AND `status`=1";
$resultcompany=$conn->query($sqlcompany);
$rowcompany=$resultcompany->fetch_assoc();

$sqlcompanybranch="SELECT `code` FROM `tbl_company_branch` WHERE `idtbl_company_branch`='$companybranch' AND `status`=1";
$resultcompanybranch=$conn->query($sqlcompanybranch);
$rowcompanybranch=$resultcompanybranch->fetch_assoc();

$sqlmainclass="SELECT `code` FROM `tbl_mainclass` WHERE `idtbl_mainclass`='$mainclass' AND `status`=1";
$resultmainclass=$conn->query($sqlmainclass);
$rowmainclass=$resultmainclass->fetch_assoc();

$sqlmainaccount="SELECT `code` FROM `tbl_mainaccount` WHERE `idtbl_mainaccount`='$mainaccount' AND `status`=1";
$resultmainaccount=$conn->query($sqlmainaccount);
$rowmainaccount=$resultmainaccount->fetch_assoc();

$sqltype="SELECT `type` FROM tbl_types WHERE typecate =4 AND  status=1 AND `idtbl_types`='$category'";
$resulttype=$conn->query($sqltype);
$rowtype=$resulttype->fetch_assoc();

?>
<tr>
    <td><?php echo $rowcompany['code'].$rowcompanybranch['code'].$rowmainclass['code'].$rowmainaccount['code'] ?></td>
    <td>&nbsp;</td>
    <td><?php echo $rowtype['type']; ?></td>
    <td><?php echo $company ?></td>
    <td><?php echo $companybranch ?></td>
    <td><?php echo $mainclass ?></td>
    <td><?php echo $mainaccount ?></td>
    <td><?php echo $category ?></td>
    <td class="text-center">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="customCheck1">
            <label class="custom-control-label" for="customCheck1">&nbsp;</label>
        </div>
    </td>
</tr>