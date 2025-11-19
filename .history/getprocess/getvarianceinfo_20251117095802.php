<?php 
require_once('../connection/db.php');

$invoicedate = $_POST['invoicedate'];
$customerID = $_POST['customerID'];
$html = '';

$sql = "SELECT tbl_invoice.date, SUM(tbl_invoice_detail.newqty+tbl_invoice_detail.refillqty+tbl_invoice_detail.trustqty) AS qty37_5KG, '131.78' AS unitprice, (SUM(tbl_invoice_detail.newqty+tbl_invoice_detail.refillqty+tbl_invoice_detail.trustqty)*131.78) AS totalamount, '1' AS status, NOW() AS insertdatetime, '1' AS userID, tbl_invoice.tbl_customer_idtbl_customer, tbl_invoice.idtbl_invoice, '2' AS productID FROM tbl_invoice_detail LEFT JOIN tbl_invoice ON tbl_invoice.idtbl_invoice = tbl_invoice_detail.tbl_invoice_idtbl_invoice LEFT JOIN tbl_customer ON tbl_customer.idtbl_customer = tbl_invoice.tbl_customer_idtbl_customer WHERE tbl_customer.type = 1 AND tbl_invoice.status = 1 AND tbl_invoice_detail.status = 1 AND tbl_invoice_detail.tbl_product_idtbl_product = 2 GROUP BY tbl_invoice.tbl_customer_idtbl_customer, tbl_invoice.idtbl_invoice";

if(!empty($customerID)) {
    $sql .= " AND i.tbl_customer_idtbl_customer = '$customerID'";
}
if(!empty($invoicedate)) {
    $sql .= " AND i.date = '$invoicedate'";
}

$result = $conn->query($sql);

if($result && $result->num_rows > 0) {
    $counter = 1;
    $hasRecords = false;
    
    while($row = $result->fetch_assoc()) {
        $total_qty = $row['refillqty'];
        
        if($total_qty <= 0) {
            continue;
        }
        
        $price = (!empty($row['special_price']));
        
        if(empty($price) || $price <= 0) {
            continue;
        }
        
        $total_amount = $total_qty * $price;
        
        if ($row['tax_invoice_num'] == null) {
            $invoiceno = 'INV-'.$row['idtbl_invoice'];
        } else {
            $invoiceno = 'AGT'.$row['tax_invoice_num'];
        }
        
        $html .= '<tr>
            <td class="text-center">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="check'.$counter.'" 
                        data-invoiceid="'.$row['idtbl_invoice'].'" 
                        data-productid="'.$row['idtbl_product'].'"
                        data-customer="'.$row['idtbl_customer'].'" 
                        data-price="'.$price.'"
                        data-total="'.$total_amount.'">
                    <label class="custom-control-label m-0" for="check'.$counter.'"></label>
                </div>
            </td>
            <td>'.$row['customer_name'].'</td>
            <td>'.$invoiceno.'</td>
            <td>'.$row['date'].'</td>
            <td>'.$row['product_name'].'</td>
            <td>'.$total_qty.'</td>
            <td>'.number_format($price, 2).'</td>
            <td class="text-right">'.number_format($total_amount, 2).'</td>
        </tr>';
        $counter++;
        $hasRecords = true;
    }
    
    if(!$hasRecords) {
        $html .= '<tr><td colspan="8" class="text-center">No records found</td></tr>';
    }
} else {
    $html .= '<tr><td colspan="8" class="text-center">No records found</td></tr>';
}

echo $html;
?>