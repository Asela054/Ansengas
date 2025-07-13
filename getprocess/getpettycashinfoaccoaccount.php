<?php 
require_once('../connection/db.php');

$accountno=$_POST['accountno'];
$company=$_POST['company'];
$companybranch=$_POST['companybranch'];

$sql="SELECT * FROM `tbl_pettycash` WHERE `pettyaccount`='$accountno' AND `tbl_company_idtbl_company`='$company' AND `tbl_company_branch_idtbl_company_branch`='$companybranch' AND `status`=1 AND `poststatus`=1 AND `reimbursestatus`=0";
$result=$conn->query($sql);

while($row=$result->fetch_assoc()){
?>
<tr>
    <td><?php echo $row['idtbl_pettycash'] ?></td>
    <td><?php echo 'PTC000'.$row['idtbl_pettycash'] ?></td>
    <td><?php echo $row['date'] ?></td>
    <td><?php echo $row['refcode'] ?></td>
    <td><?php echo $row['desc'] ?></td>
    <td class="text-center"><?php echo 'C'; ?></td>
    <td class="d-none total"><?php echo $row['amount']; ?></td>
    <td class="text-right"><?php echo number_format($row['amount'], 2); ?></td>
    <td class="text-center">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input checkallocate" id="customCheck<?php echo $row['idtbl_pettycash'] ?>">
            <label class="custom-control-label" for="customCheck<?php echo $row['idtbl_pettycash'] ?>"></label>
        </div>
    </td>
</tr>
<?php
}
?>