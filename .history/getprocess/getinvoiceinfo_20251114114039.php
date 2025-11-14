<?php 
require_once('../connection/db.php');

$invoicedate = $_POST['invoicedate'];
$customerID = $_POST['customerID'];
$html = '';

$sql = "SELECT 
            i.idtbl_invoice,
            i.date,
            i.tax_invoice_num,
            i.non_tax_invoice_num,
            c.idtbl_customer,
            c.name as customer_name,
            p.idtbl_product,
            p.product_name,
            id.newqty,
            id.refillqty,
            id.emptyqty,
            id.trustqty,
            id.trustreturnqty,
            id.discount_price,
            COALESCE(cps.discountprice, p.newsaleprice) as special_price
        FROM tbl_invoice i
        INNER JOIN tbl_invoice_detail id ON i.idtbl_invoice = id.tbl_invoice_idtbl_invoice
        INNER JOIN tbl_customer c ON i.tbl_customer_idtbl_customer = c.idtbl_customer
        INNER JOIN tbl_product p ON id.tbl_product_idtbl_product = p.idtbl_product
        LEFT JOIN tbl_customer_product_special cps ON (c.idtbl_customer = cps.tbl_customer_idtbl_customer 
                                                    AND p.idtbl_product = cps.tbl_product_idtbl_product 
                                                    AND cps.status = 1)
        WHERE i.status = 1 AND id.status = 1";

if(!empty($customerID)) {
    $sql .= " AND i.tbl_customer_idtbl_customer = '$customerID'";
}
if(!empty($invoicedate)) {
    $sql .= " AND i.date = '$invoicedate'";
}

$sql .= " AND EXISTS (
    SELECT 1 FROM tbl_customer_product_special cps2 
    WHERE cps2.tbl_customer_idtbl_customer = c.idtbl_customer 
    AND cps2.status = 1
)";

$result = $conn->query($sql);

if($result && $result->num_rows > 0) {
    $counter = 1;
    while($row = $result->fetch_assoc()) {
        $total_qty = $row['refillqty'];
        
        $price = (!empty($row['special_price'])) ? $row['special_price'] : $row['discount_price'];
        
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
    }
} else {
    $html .= '<tr><td colspan="8" class="text-center">No records found</td></tr>';
}

echo $html;
?>