<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_customer_contact_person` WHERE `status`=1 AND `tbl_customer_idtbl_customer`='$record'";
$result=$conn->query($sql);
?>

<table class="table table-striped table-bordered table-sm" id="dataTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Contact Name</th>
            <th>Contact Type</th>
            <th>Mobile </th>
            <th>WhattsApp </th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['idtbl_customer_contact_person'] ?></td>
            <td><?php echo $row['name'] ?></td>
            <td><?php echo $row['contact_type'] ?></td>
            <td><?php echo $row['mobile'] ?></td>
            <td><?php echo $row['whatsapp_num'] ?></td>
            <td><?php echo $row['email'] ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
