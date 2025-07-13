<?php 
require_once('../connection/db.php');

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];
$employee=$_POST['employee'];

if(!empty($_POST['employee'])){
    $sqlref="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `idtbl_employee`='$employee'";
    $resultref =$conn-> query($sqlref);
}
else{
    $sqlref="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1";
    $resultref =$conn-> query($sqlref);
}
?>
<div class="table-container">
<table class="table table-striped table-bordered table-sm sticky-header" id="table_content">
    <thead class="thead-dark">
        <tr>
            <th>Sale ref name</th>
            <th>Product</th>
            <th>Target Cylinders</th>
            <th>Complete Cylinders</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        while($rowref = $resultref-> fetch_assoc()){ 
            $salerefID=$rowref['idtbl_employee'];

            $sqlproductlist="SELECT `tbl_product`.`product_name`, SUM(`tbl_employee_target`.`targettank`) AS `targettank`, SUM(`tbl_employee_target`.`targetcomplete`) AS `targetcomplete` FROM `tbl_employee_target` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_employee_target`.`tbl_product_idtbl_product` WHERE `tbl_employee_target`.`tbl_employee_idtbl_employee`='$salerefID' AND `tbl_employee_target`.`status`=1 AND `tbl_employee_target`.`month` BETWEEN '$validfrom' AND '$validto' GROUP BY `tbl_product`.`product_name`";
            $resultproductlist =$conn-> query($sqlproductlist);
        ?>
        <tr>
            <td colspan="4"><?php echo $rowref['name']; ?></td>
        </tr>
        <?php while($rowproductlist = $resultproductlist-> fetch_assoc()){  ?>
        <tr>
            <td>&nbsp;</td>
            <td><?php echo $rowproductlist['product_name'] ?></td>
            <td><?php echo $rowproductlist['targettank'] ?></td>
            <td><?php echo $rowproductlist['targetcomplete'] ?></td>
        </tr>
        <?php }} ?>
    </tbody>
</table>
        </div>