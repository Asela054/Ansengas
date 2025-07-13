<?php 
require_once('../connection/db.php');

$pettycashaccount=$_POST['pettycashaccount'];
$pettycashdate=$_POST['pettycashdate'];

$sql="SELECT `tbl_pettycash`.*, `tbl_subaccount`.`subaccountname`  FROM `tbl_pettycash` LEFT JOIN `tbl_subaccount` ON `tbl_subaccount`.`subaccount`=`tbl_pettycash`.`debitaccount` WHERE `tbl_pettycash`.`status`=1 AND `tbl_pettycash`.`pettyaccount`='$pettycashaccount' AND `tbl_pettycash`.`poststatus`=0 AND `tbl_pettycash`.`date`='$pettycashdate'";
$result=$conn->query($sql);
while ($row = $result-> fetch_assoc()) {
?>
<tr>
    <td><?php echo $row['idtbl_pettycash'] ?></td>
    <td><?php echo $row['date'] ?></td>
    <td><?php echo $row['debitaccount'].' - '.$row['subaccountname'] ?></td>
    <td><?php echo $row['desc'] ?></td>
    <td><?php echo $row['amount'] ?></td>
    <td class="text-right"><?php echo number_format($row['amount'], 2) ?></td>
    <td class="text-center">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input checkallocate" id="customCheck<?php echo $row['idtbl_pettycash'] ?>">
            <label class="custom-control-label" for="customCheck<?php echo $row['idtbl_pettycash'] ?>"></label>
        </div>
    </td>
</tr>
<?php } ?>