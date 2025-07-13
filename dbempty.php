<?php
$mysqli = new mysqli('localhost', 'root', 'eRaws79rvDB' , 'erav_ansengas');
$mysqli->query('SET foreign_key_checks = 0');
if ($result = $mysqli->query("SHOW TABLES"))
{
    while($row = $result->fetch_array(MYSQLI_NUM))
    {
    //    echo $row[0].'<br>';
       if($row[0]=='tbl_dispatch' | $row[0]=='tbl_dispatch_detail' | $row[0]=='tbl_employee_target' | $row[0]=='tbl_grn' | $row[0]=='tbl_grndetail' | $row[0]=='tbl_invoice' | $row[0]=='tbl_invoice_detail' | $row[0]=='tbl_invoice_payment' | $row[0]=='tbl_invoice_payment_detail' | $row[0]=='tbl_invoice_payment_has_tbl_invoice' | $row[0]=='tbl_porder' | $row[0]=='tbl_porder_delivery' | $row[0]=='tbl_porder_detail' | $row[0]=='tbl_porder_payment' | $row[0]=='tbl_vehicle_load' | $row[0]=='tbl_vehicle_load_detail' | $row[0]=='tbl_vehicle_target'){
            $mysqli->query('TRUNCATE '.$row[0]);
            // echo $row[0].'<br>';
       }
    //     // $mysqli->query('TRUNCATE '.$row[0]);
    }
}

$mysqli->query('SET foreign_key_checks = 1');
$mysqli->close();
?>