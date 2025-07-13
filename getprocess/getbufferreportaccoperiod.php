<?php 
require_once('../connection/db.php');

$date = $_POST['date'];
$customerID = $_POST['customer'];
$type = $_POST['type'];

// $sqlCustomerBufferStock = "
//     SELECT  `tbl_customer_buffer_stock`.`idtbl_customer_buffer_stock`,`tbl_customer_buffer_stock`.`fullqty`,  `tbl_customer_buffer_stock`.`emptyqty`,  `tbl_customer_buffer_stock`.`tbl_customer_idtbl_customer`,  `tbl_customer`.`name` as customer_name FROM  `tbl_customer_buffer_stock` JOIN 
//     `tbl_customer` ON tbl_customer.idtbl_customer = tbl_customer_buffer_stock.tbl_customer_idtbl_customer WHERE  `tbl_customer_buffer_stock`.`date` = '$date'  AND `tbl_customer_buffer_stock`.`tbl_customer_idtbl_customer` = '$customerID'  AND `tbl_customer_buffer_stock`.`status` = 1
// ";
// $resultCustomerBufferStock = $conn->query($sqlCustomerBufferStock);
// $rowCustomerBufferStock = $resultCustomerBufferStock->fetch_assoc();

// $sqlCustomerBufferStockDetail = "
//     SELECT  `tbl_customer_buffer_stock_detail`.`fullqty`,  `tbl_customer_buffer_stock_detail`.`emptyqty`,  `tbl_customer_buffer_stock_detail`.`tbl_product_idtbl_product`,  `tbl_product`.`product_name`, `tbl_product`.`orderlevel` FROM  `tbl_customer_buffer_stock_detail` JOIN 
//     `tbl_product` ON tbl_product.idtbl_product = tbl_customer_buffer_stock_detail.tbl_product_idtbl_product WHERE `tbl_customer_buffer_stock_detail`.`tbl_customer_buffer_stock_idtbl_customer_buffer_stock` = '{$rowCustomerBufferStock['idtbl_customer_buffer_stock']}' AND `tbl_customer_buffer_stock_detail`.`status` = 1 ORDER BY `tbl_product`.`orderlevel` ASC";
// $resultCustomerBufferStockDetail = $conn->query($sqlCustomerBufferStockDetail);

$sql="SELECT 
    c.name AS Customer,
    c.pv_num AS 'PV Number',
    r.idtbl_reject_reason AS 'rejectid',
    r.reason AS 'rejectreason',
    bs.customreason AS 'rejectcustomreason',

    -- 2KG (Product ID 6)
    COALESCE(SUM(CASE WHEN d.tbl_product_idtbl_product = 6 THEN d.fullqty ELSE 0 END), 0) AS '2KG_Full',
    COALESCE(SUM(CASE WHEN d.tbl_product_idtbl_product = 6 THEN d.emptyqty ELSE 0 END), 0) AS '2KG_Empty',
    
    -- 5KG (Product ID 4)
    COALESCE(SUM(CASE WHEN d.tbl_product_idtbl_product = 4 THEN d.fullqty ELSE 0 END), 0) AS '5KG_Full',
    COALESCE(SUM(CASE WHEN d.tbl_product_idtbl_product = 4 THEN d.emptyqty ELSE 0 END), 0) AS '5KG_Empty',
    
    -- 12.5KG (Product ID 1)
    COALESCE(SUM(CASE WHEN d.tbl_product_idtbl_product = 1 THEN d.fullqty ELSE 0 END), 0) AS '12_5KG_Full',
    COALESCE(SUM(CASE WHEN d.tbl_product_idtbl_product = 1 THEN d.emptyqty ELSE 0 END), 0) AS '12_5KG_Empty',
    
    -- 37.5KG (Product ID 2)
    COALESCE(SUM(CASE WHEN d.tbl_product_idtbl_product = 2 THEN d.fullqty ELSE 0 END), 0) AS '37_5KG_Full',
    COALESCE(SUM(CASE WHEN d.tbl_product_idtbl_product = 2 THEN d.emptyqty ELSE 0 END), 0) AS '37_5KG_Empty',
    
    -- Total Columns
    COALESCE(SUM(d.fullqty), 0) AS 'Total_Full',
    COALESCE(SUM(d.emptyqty), 0) AS 'Total_Empty'
FROM 
    tbl_customer c
LEFT JOIN 
    tbl_customer_buffer_stock bs ON c.idtbl_customer = bs.tbl_customer_idtbl_customer
    AND bs.date = '$date'
LEFT JOIN 
    tbl_customer_buffer_stock_detail d ON bs.idtbl_customer_buffer_stock = d.tbl_customer_buffer_stock_idtbl_customer_buffer_stock
    AND d.tbl_product_idtbl_product IN (1, 2, 4, 6)
LEFT JOIN `tbl_reject_reason` r ON bs.tbl_reject_reason_idtbl_reject_reason = r.idtbl_reject_reason";
if ($type == '2' && !empty($customerID)) {
    $sql .= " LEFT JOIN tbl_customerwise_salesrep cs ON cs.tbl_customer_idtbl_customer = c.idtbl_customer";
}
if ($type == '4' && !empty($customerID)) {
    $sql .= " LEFT JOIN tbl_vehicle_load vl ON vl.idtbl_vehicle_load = bs.tbl_vehicle_load_idtbl_vehicle_load";
}
$sql .= " WHERE 
    c.status = 1";
if ($type == '1' && !empty($customerID)) {
    $sql .= " AND c.idtbl_customer = '$customerID'";
}   
if ($type == '2' && !empty($customerID)) {
    $sql .= " AND cs.tbl_employee_idtbl_employee = '$customerID'";
}
if ($type == '3' && !empty($customerID)) {
    $sql .= " AND bs.tbl_vehicle_idtbl_vehicle = '$customerID'";
}
if ($type == '4' && !empty($customerID)) {
    $sql .= " AND vl.driverid = '$customerID'";
}
if ($type == '5' && !empty($customerID)) {
    $sql .= " AND c.tbl_area_idtbl_area = '$customerID'";
}
$sql .= " GROUP BY 
    c.idtbl_customer, c.name, c.pv_num
HAVING
    SUM(d.fullqty) > 0 OR SUM(d.emptyqty) > 0  -- Only show customers with stock
ORDER BY 
    c.name";
$result = $conn->query($sql);

// Initialize totals array
$totals = array(
    '2KG_Full' => 0,
    '2KG_Empty' => 0,
    '5KG_Full' => 0,
    '5KG_Empty' => 0,
    '12_5KG_Full' => 0,
    '12_5KG_Empty' => 0,
    '37_5KG_Full' => 0,
    '37_5KG_Empty' => 0
);

$html='';
    
// if ($result->num_rows > 0) {
    $html.='<div class="scrollbar pb-3" id="style-2">
    <table class="table table-striped table-bordered table-sm small" id="bufferreport">
        <thead class="thead-dark">
            <tr>
                <th nowrap rowspan="2">Customer</th>
                <th nowrap rowspan="2">Reason</th>
                <th nowrap colspan="2" class="text-center">2KG</th>
                <th nowrap colspan="2" class="text-center">5KG</th>
                <th nowrap colspan="2" class="text-center">12.5KG</th>
                <th nowrap colspan="2" class="text-center">37.5KG</th>
            </tr>
            <tr>                    
                <!-- 2KG Columns -->
                <th class="text-center">Full Qty</th>
                <th class="text-center">Empty Qty</th>
                
                <!-- 5KG Columns -->
                <th class="text-center">Full Qty</th>
                <th class="text-center">Empty Qty</th>
                
                <!-- 12.5KG Columns -->
                <th class="text-center">Full Qty</th>
                <th class="text-center">Empty Qty</th>
                
                <!-- 37.5KG Columns -->
                <th class="text-center">Full Qty</th>
                <th class="text-center">Empty Qty</th>
            </tr>
        </thead>
        <tbody>';
        while ($rowinfo = $result->fetch_assoc()) {
            $totals['2KG_Full'] += $rowinfo['2KG_Full'];
            $totals['2KG_Empty'] += $rowinfo['2KG_Empty'];
            $totals['5KG_Full'] += $rowinfo['5KG_Full'];
            $totals['5KG_Empty'] += $rowinfo['5KG_Empty'];
            $totals['12_5KG_Full'] += $rowinfo['12_5KG_Full'];
            $totals['12_5KG_Empty'] += $rowinfo['12_5KG_Empty'];
            $totals['37_5KG_Full'] += $rowinfo['37_5KG_Full'];
            $totals['37_5KG_Empty'] += $rowinfo['37_5KG_Empty'];
            
            $html.='<tr>
                <td nowrap>'.$rowinfo['Customer'].'</td>';
                if($rowinfo['rejectid'] > 1 && $rowinfo['rejectid'] < 8) {
                    $html.='<td nowrap>'.$rowinfo['rejectreason'].'</td>';
                } else {
                    $html.='<td nowrap>'.$rowinfo['rejectcustomreason'].'</td>';
                }
                
                $html.='
                <!-- 2KG Columns -->
                <td class="text-center">'.($rowinfo['2KG_Full'] <= 0 ? '' : ceil($rowinfo['2KG_Full'])).'</td>
                <td class="text-center">'.($rowinfo['2KG_Empty'] <= 0 ? '' : ceil($rowinfo['2KG_Empty'])).'</td>
                
                <!-- 5KG Columns -->
                <td class="text-center">'.($rowinfo['5KG_Full'] <= 0 ? '' : ceil($rowinfo['5KG_Full'])).'</td>
                <td class="text-center">'.($rowinfo['5KG_Empty'] <= 0 ? '' : ceil($rowinfo['5KG_Empty'])).'</td>
                
                <!-- 12.5KG Columns -->
                <td class="text-center">'.($rowinfo['12_5KG_Full'] <= 0 ? '' : ceil($rowinfo['12_5KG_Full'])).'</td>
                <td class="text-center">'.($rowinfo['12_5KG_Empty'] <= 0 ? '' : ceil($rowinfo['12_5KG_Empty'])).'</td>
                
                <!-- 37.5KG Columns -->
                <td class="text-center">'.($rowinfo['37_5KG_Full'] <= 0 ? '' : ceil($rowinfo['37_5KG_Full'])).'</td>
                <td class="text-center">'.($rowinfo['37_5KG_Empty'] <= 0 ? '' : ceil($rowinfo['37_5KG_Empty'])).'</td>
            </tr>';
        }
    $html.='</tbody>
        <tfoot class="">
            <tr>
                <th nowrap>Total</th>
                <th class="text-center">'.($totals['2KG_Full'] <= 0 ? '' : ceil($totals['2KG_Full'])).'</th>
                <th class="text-center">'.($totals['2KG_Empty'] <= 0 ? '' : ceil($totals['2KG_Empty'])).'</th>
                <th class="text-center">'.($totals['5KG_Full'] <= 0 ? '' : ceil($totals['5KG_Full'])).'</th>
                <th class="text-center">'.($totals['5KG_Empty'] <= 0 ? '' : ceil($totals['5KG_Empty'])).'</th>
                <th class="text-center">'.($totals['12_5KG_Full'] <= 0 ? '' : ceil($totals['12_5KG_Full'])).'</th>
                <th class="text-center">'.($totals['12_5KG_Empty'] <= 0 ? '' : ceil($totals['12_5KG_Empty'])).'</th>
                <th class="text-center">'.($totals['37_5KG_Full'] <= 0 ? '' : ceil($totals['37_5KG_Full'])).'</th>
                <th class="text-center">'.($totals['37_5KG_Empty'] <= 0 ? '' : ceil($totals['37_5KG_Empty'])).'</th>
            </tr>
        </tfoot></table></div>';
// }
echo $html;

?>

<!-- <table class="table table-striped table-bordered table-sm" id="tableoutstanding">
    <thead>
        <tr class="bg-danger-soft">
            <th>Customer</th>
            <th>Total Full Quantity</th>
            <th>Total Empty Quantity</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($rowCustomerBufferStock) { ?>
        <tr>
            <td><?php echo $rowCustomerBufferStock['customer_name']; ?></td>
            <td><?php echo $rowCustomerBufferStock['fullqty']; ?></td>
            <td><?php echo $rowCustomerBufferStock['emptyqty']; ?></td>
        </tr>
        <tr class="bg-warning-soft">
            <th class="text-center">Product</th>
            <th class="text-center">Full Quantity</th>
            <th class="text-center">Empty Quantity</th>
        </tr>
        <?php while($rowCustomerBufferStockDetail = $resultCustomerBufferStockDetail->fetch_assoc()) { ?>
        <tr>
            <td class="text-center"><?php echo $rowCustomerBufferStockDetail['product_name']; ?></td>
            <td class="text-center"><?php echo $rowCustomerBufferStockDetail['fullqty']; ?></td>
            <td class="text-center"><?php echo $rowCustomerBufferStockDetail['emptyqty']; ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
            <td colspan="4" class="text-center">No data found for the selected date and customer</td>
        </tr>
        <?php } ?>
    </tbody>
</table> -->
