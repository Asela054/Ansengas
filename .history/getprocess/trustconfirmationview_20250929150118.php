<?php
require_once('../connection/db.php');

$recordID = $_POST['recordID'];

$sql_main = "SELECT `p`.*, c.name AS customer_name, c.address AS customer_address, c.phone AS customer_phone, c.email AS customer_email,
                    e.name AS employee_name
             FROM `tbl_trust_confirmation` AS `p` 
             LEFT JOIN `tbl_customer` AS `c` ON (`c`.`idtbl_customer` = `p`.`tbl_customer_idtbl_customer`)
             LEFT JOIN `tbl_employee` AS `e` ON (`e`.`idtbl_employee` = `p`.`tbl_employee_idtbl_employee`)
             WHERE `p`.`status` = ? AND `p`.`idtbl_trust_confirmation` = ?";
$stmt_main = $conn->prepare($sql_main);
$stmt_main->bind_param("ii", $status, $recordID);
$status = 1;
$stmt_main->execute();
$result_main = $stmt_main->get_result();
$purchase_data = $result_main->fetch_assoc();

$sql_details = "SELECT `d`.*, `p`.`product_name`, `p`.`product_code` 
                FROM `tbl_trust_confirmation_detail` AS `d` 
                LEFT JOIN `tbl_product` AS `p` ON (`p`.`idtbl_product` = `d`.`tbl_product_idtbl_product`) 
                WHERE `d`.`tbl_trust_confirmation_idtbl_trust_confirmation` = ? AND `d`.`status` = ?";
$stmt_details = $conn->prepare($sql_details);
$stmt_details->bind_param("ii", $recordID, $status);
$stmt_details->execute();
$result_details = $stmt_details->get_result();

$html = '';

$html .= '
<div class="row">
    <div class="col-6 small">
        <label class="small font-weight-bold text-dark mb-1">Date:</label> '.$purchase_data['date'].'<br>
        <label class="small font-weight-bold text-dark mb-1">Trust Confirmation No:</label> '.'TRC-'.$purchase_data['idtbl_trust_confirmation'].'<br>
        <label class="small font-weight-bold text-dark mb-1">Customer:</label> '.$purchase_data['customer_name'].'<br>
        <label class="small font-weight-bold text-dark mb-1">Executive:</label> '.($purchase_data['employee_name'] ?? 'N/A').'
    </div>
    <div class="col-6 small">
        <label class="small font-weight-bold text-dark mb-1">Address:</label> '.$purchase_data['customer_address'].'<br>
        <label class="small font-weight-bold text-dark mb-1">Contact:</label> '.$purchase_data['customer_phone'].'<br>
        <label class="small font-weight-bold text-dark mb-1">Email:</label> '.$purchase_data['customer_email'].'
    </div>
</div>
<hr class="border-dark">
<div class="row">
    <div class="col-12">
        <table class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th class="text-right">Quantity</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>';

$totalQty = 0;
while ($row = $result_details->fetch_assoc()) {
    $qty = $row['qty'] ?? 0;
    $comment = $row['comment'] ?? '';
    $totalQty += $qty;
    
    $html .= '<tr>
        <td>'.$row['product_name'].'</td>
        <td class="text-right">'.number_format($qty).'</td>
        <td>'.$comment.'</td>
    </tr>';
}

$html .= '</tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td class="text-right">Total:</td>
                    <td class="text-right">'.number_format($totalQty).'</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <h6 class="title-style"><span>Remark Information</span></h6>
        <p>'.$purchase_data['remark'].'</p>
    </div>
</div>';

echo $html;

$stmt_main->close();
$stmt_details->close();
$conn->close();
?>