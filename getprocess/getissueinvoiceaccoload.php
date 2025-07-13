<?php 
require_once('../connection/db.php');

$loadID=$_POST['loadID'];

$sql="SELECT * FROM `tbl_invoice` WHERE `tbl_vehicle_load_idtbl_vehicle_load`='$loadID' AND `status`=1";
$result=$conn->query($sql);

?>
<ul class="list-group">
    <?php while($row=$result->fetch_assoc()){ ?>
    <li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2 small pointer btninvoiceview" id="<?php echo $row['idtbl_invoice'] ?>">
        INV-<?php echo $row['idtbl_invoice']; ?>
        <?php if($row['paymentcomplete']==0){ echo '<span class="text-danger">Pending Payment</span>'; }else{ echo '<span class="text-success">Payment Complete</span>'; } ?>
        <div>
            <i class="fas fa-eye"></i>
        </div>
    </li>
    <?php } ?>
</ul>