<?php 
require_once('../connection/db.php');
ini_set('max_execution_time', 6200); //6200 seconds = 120 minutes

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];
$exporttype=$_POST['exporttype'];

if($exporttype==1){
    // $sql="SELECT 
    //     `tbl_invoice`.`idtbl_invoice`, 
    //     `tbl_invoice`.`date`, 
    //     `tbl_invoice`.`tax_invoice_num`, 
    //     `tbl_invoice`.`non_tax_invoice_num`, 
    //     `tbl_customer`.`name`, 
    //     `tbl_customer`.`vat_status`, 
    //     `tbl_product`.`product_name`, 
    //     `tbl_vehicle`.`vehicleno`, 
    //     `tbl_invoice_detail`.`newqty`, 
    //     `tbl_invoice_detail`.`refillqty`, 
    //     `tbl_invoice_detail`.`emptyqty`, 
    //     `tbl_invoice_detail`.`trustqty`, 
        
    //     `tbl_invoice_detail`.`newprice`, 
    //     (`tbl_invoice_detail`.`newprice` * 18 / 100) AS `newprice_vat`, 
    //     (`tbl_invoice_detail`.`newprice` + (`tbl_invoice_detail`.`newprice` * 18 / 100)) AS `newprice_with_vat`, 
        
    //     `tbl_invoice_detail`.`refillprice`, 
    //     (`tbl_invoice_detail`.`refillprice` * 18 / 100) AS `refillprice_vat`, 
    //     (`tbl_invoice_detail`.`refillprice` + (`tbl_invoice_detail`.`refillprice` * 18 / 100)) AS `refillprice_with_vat`, 
        
    //     `tbl_invoice_detail`.`emptyprice`, 
    //     (`tbl_invoice_detail`.`emptyprice` * 18 / 100) AS `emptyprice_vat`, 
    //     (`tbl_invoice_detail`.`emptyprice` + (`tbl_invoice_detail`.`emptyprice` * 18 / 100)) AS `emptyprice_with_vat`, 
        
    //     `tbl_invoice_detail`.`encustomer_newprice`, 
    //     CASE 
    //         WHEN `tbl_customer`.`vat_status` = 1 THEN (`tbl_invoice_detail`.`encustomer_newprice` * 18 / 100)
    //         ELSE (`tbl_invoice_detail`.`encustomer_newprice` * 18 / (100 + 18)) 
    //     END AS `encustomer_newprice_vat`, 
    //     CASE 
    //         WHEN `tbl_customer`.`vat_status` = 1 THEN (`tbl_invoice_detail`.`encustomer_newprice` + (`tbl_invoice_detail`.`encustomer_newprice` * 18 / 100))
    //         ELSE (`tbl_invoice_detail`.`encustomer_newprice` - (`tbl_invoice_detail`.`encustomer_newprice` * 18 / (100 + 18)))
    //     END AS `encustomer_newprice_without_vat`, 
        
    //     `tbl_invoice_detail`.`encustomer_refillprice`, 
    //     CASE 
    //         WHEN `tbl_customer`.`vat_status` = 1 THEN (`tbl_invoice_detail`.`encustomer_refillprice` * 18 / 100)
    //         ELSE (`tbl_invoice_detail`.`encustomer_refillprice` * 18 / (100 + 18)) 
    //     END AS `encustomer_refillprice_vat`, 
    //     CASE 
    //         WHEN `tbl_customer`.`vat_status` = 1 THEN (`tbl_invoice_detail`.`encustomer_refillprice` + (`tbl_invoice_detail`.`encustomer_refillprice` * 18 / 100))
    //         ELSE (`tbl_invoice_detail`.`encustomer_refillprice` - (`tbl_invoice_detail`.`encustomer_refillprice` * 18 / (100 + 18)))
    //     END AS `encustomer_refillprice_without_vat`, 
        
    //     `tbl_invoice_detail`.`encustomer_emptyprice`, 
    //     CASE 
    //         WHEN `tbl_customer`.`vat_status` = 1 THEN (`tbl_invoice_detail`.`encustomer_emptyprice` * 18 / 100)
    //         ELSE (`tbl_invoice_detail`.`encustomer_emptyprice` * 18 / (100 + 18)) 
    //     END AS `encustomer_emptyprice_vat`, 
    //     CASE 
    //         WHEN `tbl_customer`.`vat_status` = 1 THEN (`tbl_invoice_detail`.`encustomer_emptyprice` + (`tbl_invoice_detail`.`encustomer_emptyprice` * 18 / 100))
    //         ELSE (`tbl_invoice_detail`.`encustomer_emptyprice` - (`tbl_invoice_detail`.`encustomer_emptyprice` * 18 / (100 + 18)))
    //     END AS `encustomer_emptyprice_without_vat`, 
        
    //     `tbl_invoice_detail`.`discount_price`, 
    //     CASE 
    //         WHEN `tbl_customer`.`vat_status` = 1 THEN (`tbl_invoice_detail`.`discount_price` * 18 / 100)
    //         ELSE (`tbl_invoice_detail`.`discount_price` * 18 / (100 + 18)) 
    //     END AS `discount_price_vat`, 
    //     CASE 
    //         WHEN `tbl_customer`.`vat_status` = 1 THEN (`tbl_invoice_detail`.`discount_price` + (`tbl_invoice_detail`.`discount_price` * 18 / 100))
    //         ELSE (`tbl_invoice_detail`.`discount_price` - (`tbl_invoice_detail`.`discount_price` * 18 / (100 + 18)))
    //     END AS `discount_price_without_vat`

    // FROM `tbl_invoice`
    // LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
    // LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
    // LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
    // LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
    // LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
    // WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
    // AND `tbl_invoice`.`status` = 1 
    // AND `tbl_invoice_detail`.`status` = 1
    // AND `tbl_customer`.`vat_status` = 1";
    $sql="SELECT 
            `tbl_invoice`.`idtbl_invoice`, 
            `tbl_invoice`.`date`, 
            `tbl_invoice`.`tax_invoice_num`, 
            `tbl_invoice`.`non_tax_invoice_num`, 
            `tbl_customer`.`name`, 
            `tbl_customer`.`vat_status`, 
            `tbl_product`.`product_name`, 
            `tbl_vehicle`.`vehicleno`, 
            `tbl_invoice_detail`.`newqty` AS `qty`,
            
            `tbl_invoice_detail`.`newprice` AS `pricewithout`, 
            (`tbl_invoice_detail`.`newprice` * 18 / 100) AS `vatamount`, 
            (`tbl_invoice_detail`.`newprice` + (`tbl_invoice_detail`.`newprice` * 18 / 100)) AS `pricewithvat`

        FROM `tbl_invoice`
        LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
        LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
        LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
        LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
        WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice`.`status` = 1 
        AND `tbl_invoice_detail`.`status` = 1
        AND `tbl_customer`.`vat_status` = 1
        AND `tbl_invoice_detail`.`newqty` > 0
        AND `tbl_invoice_detail`.`newprice` > 0
    UNION ALL
    SELECT 
            `tbl_invoice`.`idtbl_invoice`, 
            `tbl_invoice`.`date`, 
            `tbl_invoice`.`tax_invoice_num`, 
            `tbl_invoice`.`non_tax_invoice_num`, 
            `tbl_customer`.`name`, 
            `tbl_customer`.`vat_status`, 
            `tbl_product`.`product_name`, 
            `tbl_vehicle`.`vehicleno`, 
            `tbl_invoice_detail`.`refillqty` AS `qty`,
            
            `tbl_invoice_detail`.`refillprice` AS `pricewithout`, 
            (`tbl_invoice_detail`.`refillprice` * 18 / 100) AS `vatamount`, 
            (`tbl_invoice_detail`.`refillprice` + (`tbl_invoice_detail`.`refillprice` * 18 / 100)) AS `pricewithvat`

        FROM `tbl_invoice`
        LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
        LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
        LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
        LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
        WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice`.`status` = 1 
        AND `tbl_invoice_detail`.`status` = 1
        AND `tbl_customer`.`vat_status` = 1
        AND `tbl_invoice_detail`.`refillqty` > 0
        AND `tbl_invoice_detail`.`refillprice` > 0
    UNION ALL
    SELECT 
            `tbl_invoice`.`idtbl_invoice`, 
            `tbl_invoice`.`date`, 
            `tbl_invoice`.`tax_invoice_num`, 
            `tbl_invoice`.`non_tax_invoice_num`, 
            `tbl_customer`.`name`, 
            `tbl_customer`.`vat_status`, 
            `tbl_product`.`product_name`, 
            `tbl_vehicle`.`vehicleno`, 
            `tbl_invoice_detail`.`emptyqty` AS `qty`,
            
            `tbl_invoice_detail`.`emptyprice` AS `pricewithout`, 
            (`tbl_invoice_detail`.`emptyprice` * 18 / 100) AS `vatamount`, 
            (`tbl_invoice_detail`.`emptyprice` + (`tbl_invoice_detail`.`emptyprice` * 18 / 100)) AS `pricewithvat`

        FROM `tbl_invoice`
        LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
        LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
        LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
        LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
        WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice`.`status` = 1 
        AND `tbl_invoice_detail`.`status` = 1
        AND `tbl_customer`.`vat_status` = 1
        AND `tbl_invoice_detail`.`emptyqty` > 0
        AND `tbl_invoice_detail`.`emptyprice` > 0
    UNION ALL
    SELECT 
            `tbl_invoice`.`idtbl_invoice`, 
            `tbl_invoice`.`date`, 
            `tbl_invoice`.`tax_invoice_num`, 
            `tbl_invoice`.`non_tax_invoice_num`, 
            `tbl_customer`.`name`, 
            `tbl_customer`.`vat_status`, 
            `tbl_product`.`product_name`, 
            `tbl_vehicle`.`vehicleno`, 
            `tbl_invoice_detail`.`newqty` AS `qty`,
            
            `tbl_invoice_detail`.`encustomer_newprice` AS `pricewithout`, 
            (`tbl_invoice_detail`.`encustomer_newprice` * 18 / 100) AS `vatamount`, 
            (`tbl_invoice_detail`.`encustomer_newprice` + (`tbl_invoice_detail`.`encustomer_newprice` * 18 / 100)) AS `pricewithvat`

        FROM `tbl_invoice`
        LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
        LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
        LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
        LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
        WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice`.`status` = 1 
        AND `tbl_invoice_detail`.`status` = 1
        AND `tbl_customer`.`vat_status` = 1
        AND `tbl_invoice_detail`.`newqty` > 0
        AND `tbl_invoice_detail`.`encustomer_newprice` > 0
    UNION ALL
    SELECT 
            `tbl_invoice`.`idtbl_invoice`, 
            `tbl_invoice`.`date`, 
            `tbl_invoice`.`tax_invoice_num`, 
            `tbl_invoice`.`non_tax_invoice_num`, 
            `tbl_customer`.`name`, 
            `tbl_customer`.`vat_status`, 
            `tbl_product`.`product_name`, 
            `tbl_vehicle`.`vehicleno`, 
            `tbl_invoice_detail`.`refillqty` AS `qty`,
            
            `tbl_invoice_detail`.`encustomer_refillprice` AS `pricewithout`, 
            (`tbl_invoice_detail`.`encustomer_refillprice` * 18 / 100) AS `vatamount`, 
            (`tbl_invoice_detail`.`encustomer_refillprice` + (`tbl_invoice_detail`.`encustomer_refillprice` * 18 / 100)) AS `pricewithvat`

        FROM `tbl_invoice`
        LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
        LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
        LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
        LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
        WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice`.`status` = 1 
        AND `tbl_invoice_detail`.`status` = 1
        AND `tbl_customer`.`vat_status` = 1
        AND `tbl_invoice_detail`.`refillqty` > 0
        AND `tbl_invoice_detail`.`encustomer_refillprice` > 0
    UNION ALL
    SELECT 
            `tbl_invoice`.`idtbl_invoice`, 
            `tbl_invoice`.`date`, 
            `tbl_invoice`.`tax_invoice_num`, 
            `tbl_invoice`.`non_tax_invoice_num`, 
            `tbl_customer`.`name`, 
            `tbl_customer`.`vat_status`, 
            `tbl_product`.`product_name`, 
            `tbl_vehicle`.`vehicleno`, 
            `tbl_invoice_detail`.`emptyqty` AS `qty`,
            
            `tbl_invoice_detail`.`encustomer_emptyprice` AS `pricewithout`, 
            (`tbl_invoice_detail`.`encustomer_emptyprice` * 18 / 100) AS `vatamount`, 
            (`tbl_invoice_detail`.`encustomer_emptyprice` + (`tbl_invoice_detail`.`encustomer_emptyprice` * 18 / 100)) AS `pricewithvat`

        FROM `tbl_invoice`
        LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
        LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
        LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
        LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
        WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice`.`status` = 1 
        AND `tbl_invoice_detail`.`status` = 1
        AND `tbl_customer`.`vat_status` = 1
        AND `tbl_invoice_detail`.`emptyqty` > 0
        AND `tbl_invoice_detail`.`encustomer_emptyprice` > 0
    UNION ALL
    SELECT 
            `tbl_invoice`.`idtbl_invoice`, 
            `tbl_invoice`.`date`, 
            `tbl_invoice`.`tax_invoice_num`, 
            `tbl_invoice`.`non_tax_invoice_num`, 
            `tbl_customer`.`name`, 
            `tbl_customer`.`vat_status`, 
            `tbl_product`.`product_name`, 
            `tbl_vehicle`.`vehicleno`, 
            `tbl_invoice_detail`.`refillqty` AS `qty`,
            
            `tbl_invoice_detail`.`discount_price` AS `pricewithout`, 
            (`tbl_invoice_detail`.`discount_price` * 18 / 100) AS `vatamount`, 
            (`tbl_invoice_detail`.`discount_price` + (`tbl_invoice_detail`.`discount_price` * 18 / 100)) AS `pricewithvat`

        FROM `tbl_invoice`
        LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
        LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
        LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
        LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
        WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice`.`status` = 1 
        AND `tbl_invoice_detail`.`status` = 1
        AND `tbl_customer`.`vat_status` = 1
        AND `tbl_invoice_detail`.`refillqty` > 0  
        AND `tbl_invoice_detail`.`discount_price` > 0
    ORDER BY `idtbl_invoice` ASC";
    $result = $conn->query($sql);

    $invoicechange=0;
    $html='';
    $html.='
    <table class="table table-striped table-bordered table-sm small" id="table_content">
        <thead>
            <tr>
                <th>Date</th>
                <th>Vch/BIll No</th>
                <th>Particulars</th>
                <th>Item Details</th>
                <th>Material Centre</th>
                <th>Qty</th>
                <th>Unit</th>
                <th class="text-right">Price</th>
                <th class="text-right">VAT</th>
                <th class="text-right">Price + VAT</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>';
        while($row = $result->fetch_assoc()){
            $html.='<tr>';
            if($row['idtbl_invoice']!=$invoicechange){
                $invoicechange=$row['idtbl_invoice'];
                $html.='
                <td>'.date("d/m/Y", strtotime($row['date'])).'</td>
                <td>';
                if(!empty($row['tax_invoice_num'])){$html.=$row['tax_invoice_num'];}
                else{$html.='INV-'.$row['idtbl_invoice'];}
                $html.='</td>
                <td>'.$row['name'].'</td>';
            }
            else{
                $html.='
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                ';
            }
            $html.='
                <td>'.$row['product_name'].'</td>
                <td>'.$row['vehicleno'].'</td>';
                $html.='<td>'.$row['qty'].'</td>
                <td>PCS</td>';

                // Without Vat Price Info
                $html.='<td class="text-right">'.number_format(round($row['pricewithout'], 2), 2).'</td>';

                // Vat Price Info
                $html.='<td class="text-right">'.number_format(round($row['vatamount'], 2), 2).'</td>';

                $html.='<td class="text-right">'.number_format($row['idtbl_invoice'], 2).'</td>
                <td class="text-right">'.number_format(round(($row['qty']*$row['pricewithvat']), 2), 2).'</td>';
            $html.='</tr>';
        }
        $html.='</tbody>
    </table>
    ';
} else if($exporttype==2){
    $sql="SELECT `tbl_porder`.`orderdate`, `tbl_porder`.`idtbl_porder`, `tbl_porder_detail`.`refillqty`, `tbl_porder_detail`.`emptyqty`, `tbl_porder_detail`.`returnqty`, `tbl_porder_detail`.`newqty`, `tbl_porder_detail`.`trustqty`, `tbl_porder_detail`.`saftyqty`, `tbl_porder_detail`.`saftyreturnqty`, `tbl_porder_detail`.`unitprice_withoutvat`, `tbl_porder_detail`.`refillprice_withoutvat`, `tbl_porder_detail`.`emptyprice_withoutvat`, `tbl_porder_detail`.`unitprice`, `tbl_porder_detail`.`refillprice`, `tbl_porder_detail`.`emptyprice`, `tbl_product`.`product_name` FROM `tbl_porder` LEFT JOIN `tbl_porder_detail` ON `tbl_porder_detail`.`tbl_porder_idtbl_porder`=`tbl_porder`.`idtbl_porder` LEFT JOIN `tbl_product` ON `tbl_porder_detail`.`tbl_product_idtbl_product`=`tbl_product`.`idtbl_product` WHERE `tbl_porder`.`orderdate` BETWEEN '$validfrom' AND '$validto' AND `tbl_porder`.`status`=1";
    $result = $conn->query($sql);

    $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
    $resultvat = $conn->query($sqlvat);
    $rowvat = $resultvat->fetch_assoc();

    $vatamount = $rowvat['vat'];

    $porderchange=0;
    $html='';
    $html.='
    <table class="table table-striped table-bordered table-sm small" id="table_content">
        <thead>
            <tr>
                <th>Date</th>
                <th>Vch/BIll No</th>
                <th>Particulars</th>
                <th>Item Details</th>
                <th>Qty</th>
                <th>Unit</th>
                <th class="text-right">Price</th>
                <th class="text-right">VAT Amount</th>
                <th class="text-right">Price + VAT</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>';
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            if ($row['idtbl_porder'] != $porderchange) {
                $porderchange = $row['idtbl_porder'];
                $html .= '
                <td>' . date("d/m/Y", strtotime($row['orderdate'])) . '</td>
                <td>' . 'PO-' . $row['idtbl_porder'] . '</td>
                <td>Laugfs Gas PLC</td>';
            } else {
                $html .= '
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>';
            }
            
            $html .= '<td>' . $row['product_name'] . '</td>';
            
            if ($row['newqty'] > 0) {
                $qty = $row['newqty'];
            } elseif ($row['refillqty'] > 0) {
                $qty = $row['refillqty'];
            } elseif ($row['emptyqty'] > 0) {
                $qty = $row['emptyqty'];
            } elseif ($row['trustqty'] > 0) {
                $qty = $row['trustqty'];
            }
            
            $html .= '<td>' . $qty . '</td>
                    <td>PCS</td>';
            
            if ($row['unitprice_withoutvat'] > 0) {
                $price_without_vat = $row['unitprice_withoutvat'];
            } elseif ($row['refillprice_withoutvat'] > 0) {
                $price_without_vat = $row['refillprice_withoutvat'];
            } elseif ($row['emptyprice_withoutvat'] > 0) {
                $price_without_vat = $row['emptyprice_withoutvat'];
            }
            
            $vat_amount = ($price_without_vat * $vatamount) / 100;
            
            $price_with_vat = $price_without_vat + $vat_amount;
            
            $total_amount = $qty * $price_with_vat;
            
            $html .= '<td class="text-right">' . number_format($price_without_vat, 2) . '</td>
                    <td class="text-right">' . number_format($vat_amount, 2) . '</td>
                    <td class="text-right">' . number_format($price_with_vat, 2) . '</td>
                    <td class="text-right">' . number_format($total_amount, 2) . '</td>';
            
            $html .= '</tr>';
        }
        $html.='</tbody>
    </table>
     ';
} else if($exporttype==3){
    $sql="SELECT `tbl_vehicle_load`.`idtbl_vehicle_load`, `tbl_vehicle_load`.`date`, `tbl_vehicle`.`vehicleno`,`tbl_vehicle_load_detail`.`loadqty`,`tbl_vehicle_load_detail`.`emptyqty`, `tbl_product`.`product_name`, `tbl_product`.`newprice`,`tbl_product`.`refillprice`,`tbl_product`.`emptyprice` FROM `tbl_vehicle_load` LEFT JOIN `tbl_vehicle_load_detail` ON `tbl_vehicle_load_detail`.`tbl_vehicle_load_idtbl_vehicle_load`=`tbl_vehicle_load`.`idtbl_vehicle_load` LEFT JOIN `tbl_vehicle` ON `tbl_vehicle_load`.`lorryid`=`tbl_vehicle`.`idtbl_vehicle` LEFT JOIN `tbl_product` ON `tbl_vehicle_load_detail`.`tbl_product_idtbl_product`=`tbl_product`.`idtbl_product` WHERE `tbl_vehicle_load`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_vehicle_load`.`status`=1";
    $result = $conn->query($sql);

    $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
    $resultvat = $conn->query($sqlvat);
    $rowvat = $resultvat->fetch_assoc();

    $vatPercentage = $rowvat['vat'] / 100;

    $loadingchange=0;
    $html='';
    $html.='
    <table class="table table-striped table-bordered table-sm small" id="table_content">
        <thead>
            <tr>
                <th>Date</th>
                <th>Vch/BIll No</th>
                <th>From</th>
                <th>To</th>
                <th>Item Details</th>
                <th>Qty</th>
                <th>Unit</th>
                <th class="text-right">Price</th>
                <th class="text-right">VAT Amount</th>
                <th class="text-right">Price + VAT</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>';
        while ($row = $result->fetch_assoc()) {
            if ($row['idtbl_vehicle_load'] != $loadingchange) {
                $loadingchange = $row['idtbl_vehicle_load'];
                $html .= '
                <tr>
                    <td>' . date("d/m/Y", strtotime($row['date'])) . '</td>
                    <td>' . 'VL-' . $row['idtbl_vehicle_load'] . '</td>
                    <td>Main Store</td>
                    <td>' . $row['vehicleno'] . '</td>';
            } else {
                $html .= '
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>';
            }
        
            if ($row['loadqty'] > 0) {
                $price = $row['newprice'] ?? $row['refillprice'] ?? $row['emptyprice'];
                $vatAmount = ($price * $vatPercentage)/100;
                $priceWithVAT = $price + $vatAmount;
                $totalAmount = $row['loadqty'] * $priceWithVAT;
        
                $html .= '
                    <td>' . $row['product_name'] . '</td>
                    <td>' . $row['loadqty'] . '</td>
                    <td>PCS</td>
                    <td class="text-right">' . number_format($price, 2) . '</td>
                    <td class="text-right">' . number_format($vatAmount, 2) . '</td>
                    <td class="text-right">' . number_format($priceWithVAT, 2) . '</td>
                    <td class="text-right">' . number_format($totalAmount, 2) . '</td>
                </tr>';
            }
        
            // if ($row['emptyqty'] > 0) {
            //     $emptyPrice = $row['emptyprice'];
            //     $vatAmount = ($emptyPrice * $vatPercentage)/100;
            //     $priceWithVAT = $emptyPrice + $vatAmount;
            //     $totalAmount = $row['emptyqty'] * $priceWithVAT;
        
            //     $html .= '
            //     <tr>
            //         <td>&nbsp;</td>
            //         <td>&nbsp;</td>
            //         <td>&nbsp;</td>
            //         <td>&nbsp;</td>
            //         <td>' . $row['product_name'] . ' (Empty)</td>
            //         <td>' . $row['emptyqty'] . '</td>
            //         <td>PCS</td>
            //         <td class="text-right">' . number_format($emptyPrice, 2) . '</td>
            //         <td class="text-right">' . number_format($vatAmount, 2) . '</td>
            //         <td class="text-right">' . number_format($priceWithVAT, 2) . '</td>
            //         <td class="text-right">' . number_format($totalAmount, 2) . '</td>
            //     </tr>';
            // }
        }
        $html.='</tbody>
    </table>
    ';
} else if($exporttype==4){
    $sql="SELECT `tbl_vehicle_load`.`idtbl_vehicle_load`, `tbl_vehicle_load`.`date`, `tbl_vehicle`.`vehicleno`,`tbl_vehicle_load_detail`.`qty`,`tbl_vehicle_load_detail`.`emptyqty`, `tbl_product`.`product_name`, `tbl_product`.`newprice`,`tbl_product`.`refillprice`,`tbl_product`.`emptyprice` FROM `tbl_vehicle_load` LEFT JOIN `tbl_vehicle_load_detail` ON `tbl_vehicle_load_detail`.`tbl_vehicle_load_idtbl_vehicle_load`=`tbl_vehicle_load`.`idtbl_vehicle_load` LEFT JOIN `tbl_vehicle` ON `tbl_vehicle_load`.`lorryid`=`tbl_vehicle`.`idtbl_vehicle` LEFT JOIN `tbl_product` ON `tbl_vehicle_load_detail`.`tbl_product_idtbl_product`=`tbl_product`.`idtbl_product` WHERE `tbl_vehicle_load`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_vehicle_load`.`status`=1 AND `tbl_vehicle_load`.`unloadstatus`=1";
    $result = $conn->query($sql);

    $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
    $resultvat = $conn->query($sqlvat);
    $rowvat = $resultvat->fetch_assoc();

    $vatPercentage = $rowvat['vat'];

    $unloadingchange=0;
    $html='';
    $html.='
    <table class="table table-striped table-bordered table-sm small" id="table_content">
        <thead>
            <tr>
                <th>Date</th>
                <th>Vch/BIll No</th>
                <th>From</th>
                <th>To</th>
                <th>Item Details</th>
                <th>Qty</th>
                <th>Unit</th>
                <th class="text-right">Price</th>
                <th class="text-right">VAT Amount</th>
                <th class="text-right">Price + VAT</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>';
        while ($row = $result->fetch_assoc()) {
            if ($row['idtbl_vehicle_load'] != $unloadingchange) {
                $unloadingchange = $row['idtbl_vehicle_load'];
                $html .= '
                <tr>
                    <td>' . date("d/m/Y", strtotime($row['date'])) . '</td>
                    <td>' . 'VL-' . $row['idtbl_vehicle_load'] . '</td>
                    <td>Main Store</td>
                    <td>' . $row['vehicleno'] . '</td>';
            } else {
                $html .= '
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>';
            }
        
            if ($row['qty'] > 0) {
                $price = $row['newprice'] ?? $row['refillprice'] ?? $row['emptyprice'];
                $vatAmount = ($price * $vatPercentage)/100;
                $priceWithVAT = $price + $vatAmount;
                $totalAmount = $row['qty'] * $priceWithVAT;
        
                $html .= '
                    <td>' . $row['product_name'] . '</td>
                    <td>' . $row['qty'] . '</td>
                    <td>PCS</td>
                    <td class="text-right">' . number_format($price, 2) . '</td>
                    <td class="text-right">' . number_format($vatAmount, 2) . '</td>
                    <td class="text-right">' . number_format($priceWithVAT, 2) . '</td>
                    <td class="text-right">' . number_format($totalAmount, 2) . '</td>
                </tr>';
            }
        
            if ($row['emptyqty'] > 0) {
                $emptyPrice = $row['emptyprice'];
                $vatAmount = ($emptyPrice * $vatPercentage)/100;
                $priceWithVAT = $emptyPrice + $vatAmount;
                $totalAmount = $row['emptyqty'] * $priceWithVAT;
        
                $html .= '
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>' . $row['product_name'] . ' (Empty)</td>
                    <td>' . $row['emptyqty'] . '</td>
                    <td>PCS</td>
                    <td class="text-right">' . number_format($emptyPrice, 2) . '</td>
                    <td class="text-right">' . number_format($vatAmount, 2) . '</td>
                    <td class="text-right">' . number_format($priceWithVAT, 2) . '</td>
                    <td class="text-right">' . number_format($totalAmount, 2) . '</td>
                </tr>';
            }
        }
        $html.='</tbody>
    </table>
    ';
} else if($exporttype==5){
    $sql = "SELECT `tbl_damage_return`.`idtbl_damage_return`, `tbl_damage_return`.`comsenddate`, `tbl_damage_return`.`qty`, `tbl_customer`.`name`, `tbl_product`.`product_name`, `tbl_areawise_product`.`newsaleprice`, `tbl_areawise_product`.`refillsaleprice`, `tbl_areawise_product`.`emptysaleprice`, `tbl_areawise_product`.`encustomer_newprice`, `tbl_areawise_product`.`encustomer_refillprice`, `tbl_areawise_product`.`encustomer_emptyprice`, `tbl_areawise_product`.`discount_price` FROM `tbl_damage_return` LEFT JOIN `tbl_customer` ON `tbl_damage_return`.`tbl_customer_idtbl_customer` = `tbl_customer`.`idtbl_customer` LEFT JOIN `tbl_area` ON `tbl_customer`.`tbl_area_idtbl_area` = `tbl_area`.`idtbl_area` LEFT JOIN `tbl_main_area` ON `tbl_area`.`tbl_main_area_idtbl_main_area` = `tbl_main_area`.`idtbl_main_area` LEFT JOIN `tbl_product` ON `tbl_damage_return`.`tbl_product_idtbl_product` = `tbl_product`.`idtbl_product` LEFT JOIN `tbl_areawise_product` ON `tbl_product`.`idtbl_product` = `tbl_areawise_product`.`tbl_product_idtbl_product` AND `tbl_areawise_product`.`tbl_main_area_idtbl_main_area` = `tbl_main_area`.`idtbl_main_area` WHERE `tbl_damage_return`.`comsendstatus` = 1 AND `tbl_damage_return`.`comsenddate` BETWEEN '$validfrom' AND '$validto'";
    $result = $conn->query($sql);

    $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
    $resultvat = $conn->query($sqlvat);
    $rowvat = $resultvat->fetch_assoc();

    $vatamount = $rowvat['vat'];

    $sendcompanychange = 0;
    $html = '';
    $html .= '
    <table class="table table-striped table-bordered table-sm small" id="table_content">
        <thead>
            <tr>
                <th>Date</th>
                <th>Vch/Bill No</th>
                <th>Particulars</th>
                <th>Item Details</th>
                <th>Material Centre</th>
                <th>Qty</th>
                <th>Unit</th>
                <th class="text-right">Price</th>
                <th class="text-right">VAT Amount</th>
                <th class="text-right">Price + VAT</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>';
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            if ($row['idtbl_damage_return'] != $sendcompanychange) {
                $sendcompanychange = $row['idtbl_damage_return'];
                $html .= '
                <tr>
                    <td>' . date("d/m/Y", strtotime($row['comsenddate'])) . '</td>
                    <td>' . $row['idtbl_damage_return'] . '</td>
                    <td>' . $row['name'] . '</td>';
            } else {
                $html .= '
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>';
            }
    
            $html .= '<td>' . $row['product_name'] . '</td>';
    
            if ($row['qty'] > 0) {
                $qty = $row['qty'];
            }
            $html .= '<td>Main Store</td>
            <td>' . $qty . '</td>
                    <td>PCS</td>';
    
            if ($row['newsaleprice'] > 0) {
                $price_without_vat = $row['newsaleprice'];
            } elseif ($row['refillsaleprice'] > 0) {
                $price_without_vat = $row['refillsaleprice'];
            } elseif ($row['encustomer_newprice'] > 0) {
                $price_without_vat = $row['encustomer_newprice'];
            } elseif ($row['encustomer_refillprice'] > 0) {
                $price_without_vat = $row['encustomer_refillprice'];
            } elseif ($row['discount_price'] > 0) {
                $price_without_vat = $row['discount_price'];
            }
    
            $vat_amount = ($price_without_vat * $vatamount) / 100;
    
            $price_with_vat = $price_without_vat + $vat_amount;
    
            $total_amount = $qty * $price_with_vat;
    
            $html .= '<td class="text-right">' . number_format($price_without_vat, 2) . '</td>
                    <td class="text-right">' . number_format($vat_amount, 2) . '</td>
                    <td class="text-right">' . number_format($price_with_vat, 2) . '</td>
                    <td class="text-right">' . number_format($total_amount, 2) . '</td>';
    
            $html .= '</tr>';
        }
        $html .= '</tbody>
     </table>';
} else if($exporttype==6){
    $sql = "SELECT `tbl_damage_return`.`idtbl_damage_return`, `tbl_damage_return`.`comsenddate`, `tbl_damage_return`.`qty`, `tbl_customer`.`name`, `tbl_product`.`product_name`, `tbl_areawise_product`.`newsaleprice`, `tbl_areawise_product`.`refillsaleprice`, `tbl_areawise_product`.`emptysaleprice`, `tbl_areawise_product`.`encustomer_newprice`, `tbl_areawise_product`.`encustomer_refillprice`, `tbl_areawise_product`.`encustomer_emptyprice`, `tbl_areawise_product`.`discount_price` FROM `tbl_damage_return` LEFT JOIN `tbl_customer` ON `tbl_damage_return`.`tbl_customer_idtbl_customer` = `tbl_customer`.`idtbl_customer` LEFT JOIN `tbl_area` ON `tbl_customer`.`tbl_area_idtbl_area` = `tbl_area`.`idtbl_area` LEFT JOIN `tbl_main_area` ON `tbl_area`.`tbl_main_area_idtbl_main_area` = `tbl_main_area`.`idtbl_main_area` LEFT JOIN `tbl_product` ON `tbl_damage_return`.`tbl_product_idtbl_product` = `tbl_product`.`idtbl_product` LEFT JOIN `tbl_areawise_product` ON `tbl_product`.`idtbl_product` = `tbl_areawise_product`.`tbl_product_idtbl_product` AND `tbl_areawise_product`.`tbl_main_area_idtbl_main_area` = `tbl_main_area`.`idtbl_main_area` WHERE `tbl_damage_return`.`returncusstatus` = 1 AND `tbl_damage_return`.`returncusdate` BETWEEN '$validfrom' AND '$validto'";
    $result = $conn->query($sql);

    $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
    $resultvat = $conn->query($sqlvat);
    $rowvat = $resultvat->fetch_assoc();

    $vatamount = $rowvat['vat'];

    $sendcustomerchange = 0;
    $html = '';
    $html .= '
    <table class="table table-striped table-bordered table-sm small" id="table_content">
        <thead>
            <tr>
                <th>Date</th>
                <th>Vch/Bill No</th>
                <th>Particulars</th>
                <th>Item Details</th>
                <th>Material Centre</th>
                <th>Qty</th>
                <th>Unit</th>
                <th class="text-right">Price</th>
                <th class="text-right">VAT Amount</th>
                <th class="text-right">Price + VAT</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>';
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            if ($row['idtbl_damage_return'] != $sendcustomerchange) {
                $sendcustomerchange = $row['idtbl_damage_return'];
                $html .= '
                <tr>
                    <td>' . date("d/m/Y", strtotime($row['comsenddate'])) . '</td>
                    <td>' . $row['idtbl_damage_return'] . '</td>
                    <td>' . $row['name'] . '</td>';
            } else {
                $html .= '
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>';
            }
    
            $html .= '<td>' . $row['product_name'] . '</td>';
    
            if ($row['qty'] > 0) {
                $qty = $row['qty'];
            }
            $html .= '<td>Main Store</td>
            <td>' . $qty . '</td>
                    <td>PCS</td>';
    
            if ($row['newsaleprice'] > 0) {
                $price_without_vat = $row['newsaleprice'];
            } elseif ($row['refillsaleprice'] > 0) {
                $price_without_vat = $row['refillsaleprice'];
            } elseif ($row['encustomer_newprice'] > 0) {
                $price_without_vat = $row['encustomer_newprice'];
            } elseif ($row['encustomer_refillprice'] > 0) {
                $price_without_vat = $row['encustomer_refillprice'];
            } elseif ($row['discount_price'] > 0) {
                $price_without_vat = $row['discount_price'];
            }
    
            $vat_amount = ($price_without_vat * $vatamount) / 100;
    
            $price_with_vat = $price_without_vat + $vat_amount;
    
            $total_amount = $qty * $price_with_vat;
    
            $html .= '<td class="text-right">' . number_format($price_without_vat, 2) . '</td>
                    <td class="text-right">' . number_format($vat_amount, 2) . '</td>
                    <td class="text-right">' . number_format($price_with_vat, 2) . '</td>
                    <td class="text-right">' . number_format($total_amount, 2) . '</td>';
    
            $html .= '</tr>';
        }
        $html .= '</tbody>
    </table>';
} else if($exporttype==7){
    $sql="SELECT
        `tbl_invoice_payment`.`date`,
        `tbl_invoice_payment`.`idtbl_invoice_payment`,
        `tbl_invoice`.`idtbl_invoice`,
        `tbl_invoice_payment_detail`.`method`,
        `tbl_customer`.`name`,
        `tbl_invoice_payment_detail`.`amount`,
        SUM(`tbl_invoice_excess_payment`.`excess_amount`) AS `excesspay`
    FROM
        `tbl_invoice_payment`
    LEFT JOIN `tbl_invoice_payment_detail` ON `tbl_invoice_payment_detail`.`tbl_invoice_payment_idtbl_invoice_payment` = `tbl_invoice_payment`.`idtbl_invoice_payment`
    LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_payment_idtbl_invoice_payment` = `tbl_invoice_payment`.`idtbl_invoice_payment`
    LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice` = `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`
    LEFT JOIN `tbl_invoice_excess_payment` ON `tbl_invoice_excess_payment`.`payreceiptid` = `tbl_invoice_payment`.`idtbl_invoice_payment`
    LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
    WHERE
        `tbl_invoice_payment`.`status` = 1 
        AND `tbl_invoice_payment`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice_payment_detail`.`status` = 1 
        GROUP BY `tbl_invoice_payment`.`idtbl_invoice_payment`";
    $result = $conn->query($sql);
    $invoicechange=0;
    $html='';
    $html.='
    <table class="table table-striped table-bordered table-sm small" id="table_content">
        <thead>
            <tr>
                <th>Date</th>
                <th>Vch/BIll No</th>
                <th>Invoice No</th>
                <th>Mode</th>
                <th>Account</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>';
        while($row = $result->fetch_assoc()){
            $html.='</tr>';
            if($row['idtbl_invoice_payment']!=$invoicechange){
                $invoicechange=$row['idtbl_invoice_payment'];
                $html.='
                <td>'.date("d/m/Y", strtotime($row['date'])).'</td>
                <td>PR-'.$row['idtbl_invoice_payment'].'</td>
                <td>';
                if(!empty($row['tax_invoice_num'])){$html.=$row['tax_invoice_num'];}
                else{$html.='INV-'.$row['idtbl_invoice'];}
                $html.='</td>';
            }
            else{
                $html.='
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                ';
            }
            if($row['method']==1){$paytype="Cash";}
            else if($row['method']==2){$paytype="Cheque";}
            else if($row['method']==3){$paytype="Bank Transfer";}
            else if($row['method']==4){$paytype="Excess Claim";}
                $html.='
                <td>'.$paytype.'</td>
                <td>'.$row['name'].'</td>';
            if($row['method']==4){
                $html.='<td class="text-right">'.number_format(round($row['excesspay'], 2), 2).'</td>';
            }
            else{
                $html.='<td class="text-right">'.number_format(round($row['amount'], 2), 2).'</td>';
            }
            $html.='</tr>';
        }
        $html.='</tbody>
    </table>
    ';
} else if($exporttype==8){
    // $sql="SELECT 
    //     `tbl_invoice`.`idtbl_invoice`, 
    //     `tbl_invoice`.`date`, 
    //     `tbl_invoice`.`tax_invoice_num`, 
    //     `tbl_invoice`.`non_tax_invoice_num`, 
    //     `tbl_customer`.`name`, 
    //     `tbl_customer`.`vat_status`, 
    //     `tbl_product`.`product_name`, 
    //     `tbl_vehicle`.`vehicleno`, 
    //     `tbl_invoice_detail`.`newqty`, 
    //     `tbl_invoice_detail`.`refillqty`, 
    //     `tbl_invoice_detail`.`emptyqty`, 
    //     `tbl_invoice_detail`.`trustqty`, 
        
    //     `tbl_invoice_detail`.`newprice`, 
    //     (`tbl_invoice_detail`.`newprice` * 18 / 100) AS `newprice_vat`, 
    //     (`tbl_invoice_detail`.`newprice` + (`tbl_invoice_detail`.`newprice` * 18 / 100)) AS `newprice_with_vat`, 
        
    //     `tbl_invoice_detail`.`refillprice`, 
    //     (`tbl_invoice_detail`.`refillprice` * 18 / 100) AS `refillprice_vat`, 
    //     (`tbl_invoice_detail`.`refillprice` + (`tbl_invoice_detail`.`refillprice` * 18 / 100)) AS `refillprice_with_vat`, 
        
    //     `tbl_invoice_detail`.`emptyprice`, 
    //     (`tbl_invoice_detail`.`emptyprice` * 18 / 100) AS `emptyprice_vat`, 
    //     (`tbl_invoice_detail`.`emptyprice` + (`tbl_invoice_detail`.`emptyprice` * 18 / 100)) AS `emptyprice_with_vat`, 
        
    //     `tbl_invoice_detail`.`encustomer_newprice`, 
    //     (`tbl_invoice_detail`.`encustomer_newprice` * 18 / 100) AS `encustomer_newprice_vat`, 
    //     (`tbl_invoice_detail`.`encustomer_newprice` + (`tbl_invoice_detail`.`encustomer_newprice` * 18 / 100)) AS `encustomer_newprice_with_vat`, 
        
    //     `tbl_invoice_detail`.`encustomer_refillprice`, 
    //     (`tbl_invoice_detail`.`encustomer_refillprice` * 18 / 100) AS `encustomer_refillprice_vat`, 
    //     (`tbl_invoice_detail`.`encustomer_refillprice` + (`tbl_invoice_detail`.`encustomer_refillprice` * 18 / 100)) AS `encustomer_refillprice_with_vat`, 
        
    //     `tbl_invoice_detail`.`encustomer_emptyprice`, 
    //     (`tbl_invoice_detail`.`encustomer_emptyprice` * 18 / 100) AS `encustomer_emptyprice_vat`, 
    //     (`tbl_invoice_detail`.`encustomer_emptyprice` + (`tbl_invoice_detail`.`encustomer_emptyprice` * 18 / 100)) AS `encustomer_emptyprice_with_vat`, 
        
    //     `tbl_invoice_detail`.`discount_price`, 
    //     (`tbl_invoice_detail`.`discount_price` * 18 / 100) AS `discount_price_vat`, 
    //     (`tbl_invoice_detail`.`discount_price` + (`tbl_invoice_detail`.`discount_price` * 18 / 100)) AS `discount_price_with_vat`

    // FROM `tbl_invoice`
    // LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
    // LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
    // LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
    // LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
    // LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
    // WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
    // AND `tbl_invoice`.`status` = 1 
    // AND `tbl_invoice_detail`.`status` = 1
    // AND `tbl_customer`.`vat_status` = 0";
    $sql="SELECT 
            `tbl_invoice`.`idtbl_invoice`, 
            `tbl_invoice`.`date`, 
            `tbl_invoice`.`tax_invoice_num`, 
            `tbl_invoice`.`non_tax_invoice_num`, 
            `tbl_customer`.`name`, 
            `tbl_customer`.`vat_status`, 
            `tbl_product`.`product_name`, 
            `tbl_vehicle`.`vehicleno`, 
            `tbl_invoice_detail`.`newqty` AS `qty`,
            
            `tbl_invoice_detail`.`newprice` AS `pricewithout`, 
            (`tbl_invoice_detail`.`newprice` * 18 / 100) AS `vatamount`, 
            (`tbl_invoice_detail`.`newprice` + (`tbl_invoice_detail`.`newprice` * 18 / 100)) AS `pricewithvat`

        FROM `tbl_invoice`
        LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
        LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
        LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
        LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
        WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice`.`status` = 1 
        AND `tbl_invoice_detail`.`status` = 1
        AND `tbl_customer`.`vat_status` = 0
        AND `tbl_invoice_detail`.`newqty` > 0
        AND `tbl_invoice_detail`.`newprice` > 0
    UNION ALL
    SELECT 
            `tbl_invoice`.`idtbl_invoice`, 
            `tbl_invoice`.`date`, 
            `tbl_invoice`.`tax_invoice_num`, 
            `tbl_invoice`.`non_tax_invoice_num`, 
            `tbl_customer`.`name`, 
            `tbl_customer`.`vat_status`, 
            `tbl_product`.`product_name`, 
            `tbl_vehicle`.`vehicleno`, 
            `tbl_invoice_detail`.`refillqty` AS `qty`,
            
            `tbl_invoice_detail`.`refillprice` AS `pricewithout`, 
            (`tbl_invoice_detail`.`refillprice` * 18 / 100) AS `vatamount`, 
            (`tbl_invoice_detail`.`refillprice` + (`tbl_invoice_detail`.`refillprice` * 18 / 100)) AS `pricewithvat`

        FROM `tbl_invoice`
        LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
        LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
        LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
        LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
        WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice`.`status` = 1 
        AND `tbl_invoice_detail`.`status` = 1
        AND `tbl_customer`.`vat_status` = 0
        AND `tbl_invoice_detail`.`refillqty` > 0
        AND `tbl_invoice_detail`.`refillprice` > 0
    UNION ALL
    SELECT 
            `tbl_invoice`.`idtbl_invoice`, 
            `tbl_invoice`.`date`, 
            `tbl_invoice`.`tax_invoice_num`, 
            `tbl_invoice`.`non_tax_invoice_num`, 
            `tbl_customer`.`name`, 
            `tbl_customer`.`vat_status`, 
            `tbl_product`.`product_name`, 
            `tbl_vehicle`.`vehicleno`, 
            `tbl_invoice_detail`.`emptyqty` AS `qty`,
            
            `tbl_invoice_detail`.`emptyprice` AS `pricewithout`, 
            (`tbl_invoice_detail`.`emptyprice` * 18 / 100) AS `vatamount`, 
            (`tbl_invoice_detail`.`emptyprice` + (`tbl_invoice_detail`.`emptyprice` * 18 / 100)) AS `pricewithvat`

        FROM `tbl_invoice`
        LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
        LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
        LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
        LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
        WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice`.`status` = 1 
        AND `tbl_invoice_detail`.`status` = 1
        AND `tbl_customer`.`vat_status` = 0
        AND `tbl_invoice_detail`.`emptyqty` > 0
        AND `tbl_invoice_detail`.`emptyprice` > 0
    UNION ALL
    SELECT 
            `tbl_invoice`.`idtbl_invoice`, 
            `tbl_invoice`.`date`, 
            `tbl_invoice`.`tax_invoice_num`, 
            `tbl_invoice`.`non_tax_invoice_num`, 
            `tbl_customer`.`name`, 
            `tbl_customer`.`vat_status`, 
            `tbl_product`.`product_name`, 
            `tbl_vehicle`.`vehicleno`, 
            `tbl_invoice_detail`.`newqty` AS `qty`,
            
            `tbl_invoice_detail`.`encustomer_newprice` AS `pricewithout`, 
            (`tbl_invoice_detail`.`encustomer_newprice` * 18 / 100) AS `vatamount`, 
            (`tbl_invoice_detail`.`encustomer_newprice` + (`tbl_invoice_detail`.`encustomer_newprice` * 18 / 100)) AS `pricewithvat`

        FROM `tbl_invoice`
        LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
        LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
        LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
        LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
        WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice`.`status` = 1 
        AND `tbl_invoice_detail`.`status` = 1
        AND `tbl_customer`.`vat_status` = 0
        AND `tbl_invoice_detail`.`newqty` > 0
        AND `tbl_invoice_detail`.`encustomer_newprice` > 0
    UNION ALL
    SELECT 
            `tbl_invoice`.`idtbl_invoice`, 
            `tbl_invoice`.`date`, 
            `tbl_invoice`.`tax_invoice_num`, 
            `tbl_invoice`.`non_tax_invoice_num`, 
            `tbl_customer`.`name`, 
            `tbl_customer`.`vat_status`, 
            `tbl_product`.`product_name`, 
            `tbl_vehicle`.`vehicleno`, 
            `tbl_invoice_detail`.`refillqty` AS `qty`,
            
            `tbl_invoice_detail`.`encustomer_refillprice` AS `pricewithout`, 
            (`tbl_invoice_detail`.`encustomer_refillprice` * 18 / 100) AS `vatamount`, 
            (`tbl_invoice_detail`.`encustomer_refillprice` + (`tbl_invoice_detail`.`encustomer_refillprice` * 18 / 100)) AS `pricewithvat`

        FROM `tbl_invoice`
        LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
        LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
        LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
        LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
        WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice`.`status` = 1 
        AND `tbl_invoice_detail`.`status` = 1
        AND `tbl_customer`.`vat_status` = 0
        AND `tbl_invoice_detail`.`refillqty` > 0
        AND `tbl_invoice_detail`.`encustomer_refillprice` > 0
    UNION ALL
    SELECT 
            `tbl_invoice`.`idtbl_invoice`, 
            `tbl_invoice`.`date`, 
            `tbl_invoice`.`tax_invoice_num`, 
            `tbl_invoice`.`non_tax_invoice_num`, 
            `tbl_customer`.`name`, 
            `tbl_customer`.`vat_status`, 
            `tbl_product`.`product_name`, 
            `tbl_vehicle`.`vehicleno`, 
            `tbl_invoice_detail`.`emptyqty` AS `qty`,
            
            `tbl_invoice_detail`.`encustomer_emptyprice` AS `pricewithout`, 
            (`tbl_invoice_detail`.`encustomer_emptyprice` * 18 / 100) AS `vatamount`, 
            (`tbl_invoice_detail`.`encustomer_emptyprice` + (`tbl_invoice_detail`.`encustomer_emptyprice` * 18 / 100)) AS `pricewithvat`

        FROM `tbl_invoice`
        LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
        LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
        LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
        LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
        WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice`.`status` = 1 
        AND `tbl_invoice_detail`.`status` = 1
        AND `tbl_customer`.`vat_status` = 0
        AND `tbl_invoice_detail`.`emptyqty` > 0
        AND `tbl_invoice_detail`.`encustomer_emptyprice` > 0
    UNION ALL
    SELECT 
            `tbl_invoice`.`idtbl_invoice`, 
            `tbl_invoice`.`date`, 
            `tbl_invoice`.`tax_invoice_num`, 
            `tbl_invoice`.`non_tax_invoice_num`, 
            `tbl_customer`.`name`, 
            `tbl_customer`.`vat_status`, 
            `tbl_product`.`product_name`, 
            `tbl_vehicle`.`vehicleno`, 
            `tbl_invoice_detail`.`refillqty` AS `qty`,
            
            `tbl_invoice_detail`.`discount_price` AS `pricewithout`, 
            (`tbl_invoice_detail`.`discount_price` * 18 / 100) AS `vatamount`, 
            (`tbl_invoice_detail`.`discount_price` + (`tbl_invoice_detail`.`discount_price` * 18 / 100)) AS `pricewithvat`

        FROM `tbl_invoice`
        LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` = `tbl_invoice`.`idtbl_invoice`
        LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_invoice_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
        LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load` = `tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`
        LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle` = `tbl_vehicle_load`.`lorryid`
        WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' 
        AND `tbl_invoice`.`status` = 1 
        AND `tbl_invoice_detail`.`status` = 1
        AND `tbl_customer`.`vat_status` = 0
        AND `tbl_invoice_detail`.`refillqty` > 0  
        AND `tbl_invoice_detail`.`discount_price` > 0
    ORDER BY `idtbl_invoice` ASC";
    $result = $conn->query($sql);

    $invoicechange=0;
    $html='';
    $html.='
    <table class="table table-striped table-bordered table-sm small" id="table_content">
        <thead>
            <tr>
                <th>Date</th>
                <th>Vch/BIll No</th>
                <th>Particulars</th>
                <th>Item Details</th>
                <th>Material Centre</th>
                <th>Qty</th>
                <th>Unit</th>
                <th class="text-right">Price</th>
                <th class="text-right">VAT</th>
                <th class="text-right">Price + VAT</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>';
        while($row = $result->fetch_assoc()){
            $html.='<tr>';
            if($row['idtbl_invoice']!=$invoicechange){
                $invoicechange=$row['idtbl_invoice'];
                $html.='
                <td>'.date("d/m/Y", strtotime($row['date'])).'</td>
                <td>';
                if(!empty($row['tax_invoice_num'])){$html.=$row['tax_invoice_num'];}
                else{$html.='INV-'.$row['idtbl_invoice'];}
                $html.='</td>
                <td>'.$row['name'].'</td>';
            }
            else{
                $html.='
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                ';
            }
            $html.='
                <td>'.$row['product_name'].'</td>
                <td>'.$row['vehicleno'].'</td>';
                $html.='<td>'.$row['qty'].'</td>
                <td>PCS</td>';

                // Without Vat Price Info
                $html.='<td class="text-right">'.number_format(round($row['pricewithout'], 2), 2).'</td>';

                // Vat Price Info
                $html.='<td class="text-right">'.number_format(round($row['vatamount'], 2), 2).'</td>';

                $html.='<td class="text-right">'.number_format($row['pricewithvat'], 2).'</td>
                <td class="text-right">'.number_format(round(($row['qty']*$row['pricewithvat']), 2), 2).'</td>';
            $html.='</tr>';
        }
        $html.='</tbody>
    </table>
    ';
}

echo $html;