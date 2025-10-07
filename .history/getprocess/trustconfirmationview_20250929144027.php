<?php
require_once('../connection/db.php');

$recordID = $_POST['recordID'];

$sql_main = "SELECT `p`.*, c.name AS customer_name, c.address AS customer_address,c.phone 
             END AS customer_phone,
             CASE 
                 WHEN p.customertype = 2 THEN '' 
                 ELSE c.email 
             END AS customer_email
             FROM `tbl_local_purchase` AS `p` 
             LEFT JOIN `tbl_customer` AS `c` ON (`c`.`idtbl_customer` = `p`.`tbl_customer_idtbl_customer` AND p.customertype = 1)
             LEFT JOIN `tbl_local_purchase_customers` AS `lpc` ON (`lpc`.`idtbl_local_purchase_customers` = `p`.`tbl_customer_idtbl_customer` AND p.customertype = 2)
             WHERE `p`.`status` = ? AND `p`.`idtbl_local_purchase` = ?";
$stmt_main = $conn->prepare($sql_main);
$stmt_main->bind_param("ii", $status, $recordID);
$status = 1;
$stmt_main->execute();
$result_main = $stmt_main->get_result();
$purchase_data = $result_main->fetch_assoc();

$sql_details = "SELECT `d`.*, `p`.`product_name`, `p`.`product_code` 
                FROM `tbl_local_purchasedetail` AS `d` 
                LEFT JOIN `tbl_product` AS `p` ON (`p`.`idtbl_product` = `d`.`tbl_product_idtbl_product`) 
                WHERE `d`.`tbl_local_purchase_idtbl_local_purchase` = ? AND `d`.`status` = ?";
$stmt_details = $conn->prepare($sql_details);
$stmt_details->bind_param("ii", $recordID, $status);
$stmt_details->execute();
$result_details = $stmt_details->get_result();

$html = '';

$html .= '
<div class="row">
    <div class="col-6 small">
        <label class="small font-weight-bold text-dark mb-1">Date:</label> '.$purchase_data['date'].'<br>
        <label class="small font-weight-bold text-dark mb-1">Local Purchase No:</label> '.'LP-'.$purchase_data['idtbl_local_purchase'].'<br>
        <label class="small font-weight-bold text-dark mb-1">Customer:</label> '.$purchase_data['customer_name'].'
    </div>
    <div class="col-6 small">
        <label class="small font-weight-bold text-dark mb-1">Address:</label> '.$purchase_data['customer_address'].'<br>
        <label class="small font-weight-bold text-dark mb-1">Contact:</label> '.$purchase_data['customer_phone'].'<br>
    </div>
</div>
<hr class="border-dark">
<div class="row"></div>
<div class="row">
    <div class="col-12">
        <hr>';

$showFullColumns = false;
$showEmptyColumns = false;

$result_details->data_seek(0);
while ($row = $result_details->fetch_assoc()) {
    if ($row['fullqty'] > 0 || $row['full_unitprice_withoutvat'] > 0) {
        $showFullColumns = true;
    }
    if ($row['emptyqty'] > 0 || $row['empty_unitprice_withoutvat'] > 0) {
        $showEmptyColumns = true;
    }
    
    if ($showFullColumns && $showEmptyColumns) {
        break;
    }
}

$html .= '<table class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>Product Info</th>';
                    
if ($showFullColumns) {
    $html .= '<th class="text-right">Full Qty</th>
              <th class="text-right">Full Unit Price</th>';
}

if ($showEmptyColumns) {
    $html .= '<th class="text-right">Empty Qty</th>
              <th class="text-right">Empty Unit Price</th>';
}

$html .= '<th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>';

$result_details->data_seek(0);

while ($row = $result_details->fetch_assoc()) {
    $total = ($row['fullqty'] * $row['full_unitprice_withoutvat']) + ($row['emptyqty'] * $row['empty_unitprice_withoutvat']);
    
    $html .= '<tr>
        <td>'.$row['product_name'].'</td>';
    
    if ($showFullColumns) {
        $html .= '<td class="text-right">'.($row['fullqty'] > 0 ? $row['fullqty'] : '0').'</td>
                  <td class="text-right">'.($row['full_unitprice_withoutvat'] > 0 ? number_format($row['full_unitprice_withoutvat'], 2) : '0.00').'</td>';
    }
    
    if ($showEmptyColumns) {
        $html .= '<td class="text-right">'.($row['emptyqty'] > 0 ? $row['emptyqty'] : '0').'</td>
                  <td class="text-right">'.($row['empty_unitprice_withoutvat'] > 0 ? number_format($row['empty_unitprice_withoutvat'], 2) : '0.00').'</td>';
    }
    
    $html .= '<td class="text-right">'.number_format($total, 2).'</td>
    </tr>';
}

$html .= '</tbody>
        </table>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12 text-right">
        <h3 class="font-weight-normal">
            <strong>Total</strong> &nbsp; &nbsp;
            <b>Rs. '.number_format($purchase_data['total'], 2).'</b>
        </h3>
    </div>
    <div class="col-12"><h6 class="title-style"><span>Remark Information</span></h6></div>
    <p class="col-12">'.$purchase_data['remark'].'</p>
</div>';

echo $html;

$stmt_main->close();
$stmt_details->close();
$conn->close();
?>