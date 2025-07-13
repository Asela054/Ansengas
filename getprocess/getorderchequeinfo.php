<?php
require_once('../connection/db.php');

$orderID=$_POST['orderID'];

$sqlordercheque="SELECT * FROM `tbl_porder_payment` LEFT JOIN `tbl_bank` ON `tbl_bank`.`idtbl_bank`=`tbl_porder_payment`.`tbl_bank_idtbl_bank` WHERE `tbl_porder_payment`.`tbl_porder_idtbl_porder`='$orderID' AND `tbl_porder_payment`.`status`=1";
$resultordercheque=$conn->query($sqlordercheque);
?>
<table class="table table-striped table-bordered table-sm" id="chequeinfotable">
    <thead>
        <tr>
            <th>Bank</th>
            <th>Che: no</th>
            <th>Date</th>
            <th class="text-right">Amount</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php while($rowordercheque=$resultordercheque->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $rowordercheque['bankname'] ?></td>
            <td><?php echo $rowordercheque['chequeno'] ?></td>
            <td><?php echo $rowordercheque['chequedate'] ?></td>
            <td class="text-right"><?php echo number_format($rowordercheque['amount'], 2) ?></td>
            <td class="text-center"><button class="btn btn-outline-danger btn-sm btnchequeremove" id="<?php echo $rowordercheque['idtbl_porder_payment'] ?>"><i class="fas fa-trash"></i></button></td>
        </tr>
        <?php } ?>
    </tbody>
</table>