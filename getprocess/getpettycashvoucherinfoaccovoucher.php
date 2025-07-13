<?php 
require_once('../connection/db.php');

$pettyvoucher=$_POST['pettyvoucher'];

$sqlpetty="SELECT `amount`, `chequeno`, `desc` FROM `tbl_pettycash_voucher` WHERE `idtbl_pettycash_voucher`='$pettyvoucher'";
$resultpetty=$conn->query($sqlpetty);
$rowpetty=$resultpetty->fetch_assoc();

$sql="SELECT * FROM `tbl_pettycash` WHERE `status`=1 AND `idtbl_pettycash` IN (SELECT `tbl_pettycash_idtbl_pettycash` FROM `tbl_pettycash_voucher_has_tbl_pettycash` WHERE `tbl_pettycash_voucher_idtbl_pettycash_voucher`='$pettyvoucher')";
$result=$conn->query($sql);
?>
<table class="table table-striped table-bordered table-sm small">
    <thead>
        <tr>
            <th>#</th>
            <th>Code</th>
            <th>Date</th>
            <th>Tras. Code</th>
            <th>REf. Code</th>
            <th>Debit Account</th>
            <th>Desc</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['idtbl_pettycash'] ?></td>
            <td><?php echo 'PTC000'.$row['idtbl_pettycash'] ?></td>
            <td><?php echo $row['date'] ?></td>
            <td><?php echo $row['transcode'] ?></td>
            <td><?php echo $row['refcode'] ?></td>
            <td><?php echo $row['debitaccount'] ?></td>
            <td><?php echo $row['desc'] ?></td>
            <td class="text-right"><?php echo number_format($row['amount'], 2); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<hr>
<div class="row">
    <div class="col-12 text-right">
        <h4><?php echo 'Rs '.number_format($rowpetty['amount'], 2); ?></h4>
    </div>
    <div class="col-12 small">
        <hr>
        <label class="font-weight-bold text-dark">Cheque No:&nbsp;</label><?php echo $rowpetty['chequeno']; ?><br>
        <label class="font-weight-bold text-dark">Description:&nbsp;</label><?php echo $rowpetty['desc']; ?>
    </div>
</div>