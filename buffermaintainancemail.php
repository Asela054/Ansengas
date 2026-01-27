<?php
require_once('connection/db.php');

$date = date('Y-m-d');
$type = 2;

ini_set('memory_limit', '999M');
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$sqlexecutive = "SELECT `idtbl_employee`, `name`, `email` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=7 ORDER BY `idtbl_employee` ASC";
$resultexecutive=$conn->query($sqlexecutive);
while($rowexecutive=$resultexecutive->fetch_assoc()) {
    $customerID = $rowexecutive['idtbl_employee'];
    $executivename = $rowexecutive['name'];
    $executiveemail = $rowexecutive['email'];

    $sql="SELECT 
        c.idtbl_customer,
        c.name AS Customer,
        c.pv_num AS 'PV Number',
        r.idtbl_reject_reason AS 'rejectid',
        r.reason AS 'rejectreason',
        bs.customreason AS 'rejectcustomreason',

        -- 2KG (Product ID 6)
        COALESCE((SELECT SUM(fullqty) FROM tbl_customer_buffer_stock_detail 
                WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                AND tbl_product_idtbl_product = 6 AND status = 1), 0) AS '2KG_Full',
        
        COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                AND tbl_product_idtbl_product = 6 AND status = 1), 0) AS '2KG_Empty',
        
        COALESCE((SELECT SUM(id.refillqty) FROM tbl_invoice i 
                JOIN tbl_invoice_detail id ON i.idtbl_invoice = id.tbl_invoice_idtbl_invoice 
                WHERE i.tbl_customer_idtbl_customer = c.idtbl_customer 
                AND i.date = '$date' 
                AND id.tbl_product_idtbl_product = 6 
                AND i.status = 1), 0) AS '2KG_Sales',
        
        -- Percentage 2KG
        CASE 
            WHEN COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                        WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                        AND tbl_product_idtbl_product = 6 AND status = 1), 0) > 0
            THEN 
                (
                    COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                            WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                            AND tbl_product_idtbl_product = 6 AND status = 1), 0) 
                    - COALESCE((SELECT SUM(id.refillqty) FROM tbl_invoice i 
                            JOIN tbl_invoice_detail id ON i.idtbl_invoice = id.tbl_invoice_idtbl_invoice 
                            WHERE i.tbl_customer_idtbl_customer = c.idtbl_customer 
                            AND i.date = '$date' 
                            AND id.tbl_product_idtbl_product = 6 
                            AND i.status = 1), 0)
                ) / COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                            WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                            AND tbl_product_idtbl_product = 6 AND status = 1), 0) * 100
            ELSE NULL
        END AS '2KG_Loss_Percentage',
        
        -- 5KG (Product ID 4)
        COALESCE((SELECT SUM(fullqty) FROM tbl_customer_buffer_stock_detail 
                WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                AND tbl_product_idtbl_product = 4 AND status = 1), 0) AS '5KG_Full',
        
        COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                AND tbl_product_idtbl_product = 4 AND status = 1), 0) AS '5KG_Empty',
        
        COALESCE((SELECT SUM(id.refillqty) FROM tbl_invoice i 
                JOIN tbl_invoice_detail id ON i.idtbl_invoice = id.tbl_invoice_idtbl_invoice 
                WHERE i.tbl_customer_idtbl_customer = c.idtbl_customer 
                AND i.date = '$date' 
                AND id.tbl_product_idtbl_product = 4 
                AND i.status = 1), 0) AS '5KG_Sales',
        
        -- Percentage 5KG
        CASE 
            WHEN COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                        WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                        AND tbl_product_idtbl_product = 4 AND status = 1), 0) > 0
            THEN 
                (
                    COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                            WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                            AND tbl_product_idtbl_product = 4 AND status = 1), 0) 
                    - COALESCE((SELECT SUM(id.refillqty) FROM tbl_invoice i 
                            JOIN tbl_invoice_detail id ON i.idtbl_invoice = id.tbl_invoice_idtbl_invoice 
                            WHERE i.tbl_customer_idtbl_customer = c.idtbl_customer 
                            AND i.date = '$date' 
                            AND id.tbl_product_idtbl_product = 4 
                            AND i.status = 1), 0)
                ) / COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                            WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                            AND tbl_product_idtbl_product = 4 AND status = 1), 0) * 100
            ELSE NULL
        END AS '5KG_Loss_Percentage',
        
        -- 12.5KG (Product ID 1)
        COALESCE((SELECT SUM(fullqty) FROM tbl_customer_buffer_stock_detail 
                WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                AND tbl_product_idtbl_product = 1 AND status = 1), 0) AS '12_5KG_Full',
        
        COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                AND tbl_product_idtbl_product = 1 AND status = 1), 0) AS '12_5KG_Empty',
        
        COALESCE((SELECT SUM(id.refillqty) FROM tbl_invoice i 
                JOIN tbl_invoice_detail id ON i.idtbl_invoice = id.tbl_invoice_idtbl_invoice 
                WHERE i.tbl_customer_idtbl_customer = c.idtbl_customer 
                AND i.date = '$date' 
                AND id.tbl_product_idtbl_product = 1 
                AND i.status = 1), 0) AS '12_5KG_Sales',
        
        -- Percentage 12.5KG
        CASE 
            WHEN COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                        WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                        AND tbl_product_idtbl_product = 1 AND status = 1), 0) > 0
            THEN 
                (
                    COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                            WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                            AND tbl_product_idtbl_product = 1 AND status = 1), 0) 
                    - COALESCE((SELECT SUM(id.refillqty) FROM tbl_invoice i 
                            JOIN tbl_invoice_detail id ON i.idtbl_invoice = id.tbl_invoice_idtbl_invoice 
                            WHERE i.tbl_customer_idtbl_customer = c.idtbl_customer 
                            AND i.date = '$date' 
                            AND id.tbl_product_idtbl_product = 1 
                            AND i.status = 1), 0)
                ) / COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                            WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                            AND tbl_product_idtbl_product = 1 AND status = 1), 0) * 100
            ELSE NULL
        END AS '12_5KG_Loss_Percentage',
        
        -- 37.5KG (Product ID 2)
        COALESCE((SELECT SUM(fullqty) FROM tbl_customer_buffer_stock_detail 
                WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                AND tbl_product_idtbl_product = 2 AND status = 1), 0) AS '37_5KG_Full',
        
        COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                AND tbl_product_idtbl_product = 2 AND status = 1), 0) AS '37_5KG_Empty',
        
        COALESCE((SELECT SUM(id.refillqty) FROM tbl_invoice i 
                JOIN tbl_invoice_detail id ON i.idtbl_invoice = id.tbl_invoice_idtbl_invoice 
                WHERE i.tbl_customer_idtbl_customer = c.idtbl_customer 
                AND i.date = '$date' 
                AND id.tbl_product_idtbl_product = 2 
                AND i.status = 1), 0) AS '37_5KG_Sales',
        
        -- Percentage 37.5KG
        CASE 
            WHEN COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                        WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                        AND tbl_product_idtbl_product = 2 AND status = 1), 0) > 0
            THEN 
                (
                    COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                            WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                            AND tbl_product_idtbl_product = 2 AND status = 1), 0) 
                    - COALESCE((SELECT SUM(id.refillqty) FROM tbl_invoice i 
                            JOIN tbl_invoice_detail id ON i.idtbl_invoice = id.tbl_invoice_idtbl_invoice 
                            WHERE i.tbl_customer_idtbl_customer = c.idtbl_customer 
                            AND i.date = '$date' 
                            AND id.tbl_product_idtbl_product = 2 
                            AND i.status = 1), 0)
                ) / COALESCE((SELECT SUM(emptyqty) FROM tbl_customer_buffer_stock_detail 
                            WHERE tbl_customer_buffer_stock_idtbl_customer_buffer_stock = bs.idtbl_customer_buffer_stock 
                            AND tbl_product_idtbl_product = 2 AND status = 1), 0) * 100
            ELSE NULL
        END AS '37_5KG_Loss_Percentage'

    FROM 
        tbl_customer c
    LEFT JOIN 
        tbl_customer_buffer_stock bs ON c.idtbl_customer = bs.tbl_customer_idtbl_customer
        AND bs.date = '$date'
    LEFT JOIN 
        `tbl_reject_reason` r ON bs.tbl_reject_reason_idtbl_reject_reason = r.idtbl_reject_reason";

    if ($type == '2' && !empty($customerID)) {
        $sql .= " LEFT JOIN tbl_customerwise_salesrep cs ON cs.tbl_customer_idtbl_customer = c.idtbl_customer";
    }
    if ($type == '4' && !empty($customerID)) {
        $sql .= " LEFT JOIN tbl_vehicle_load vl ON vl.idtbl_vehicle_load = bs.tbl_vehicle_load_idtbl_vehicle_load";
        $sql .= " LEFT JOIN tbl_area a ON vl.tbl_area_idtbl_area = a.idtbl_area";
    }
    $sql .= " WHERE 
        c.status = 1";
    if ($type == '1' && !empty($customerID)) {
        $sql .= " AND c.idtbl_customer = '$customerID'";
    }   
    if($type == '1' && !empty($_POST['groupcategory'])){
        $sql .= " AND c.tbl_group_category_idtbl_group_category = '$groupcategory'";
    }
    if ($type == '2' && !empty($customerID)) {
        $sql .= " AND cs.tbl_employee_idtbl_employee = '$customerID'";
    }
    if ($type == '3' && !empty($customerID)) {
        $sql .= " AND bs.tbl_vehicle_idtbl_vehicle = '$customerID'";
    }
    if ($type == '4' && !empty($customerID)) {
        $sql .= " AND vl.driverid = '$customerID'";
        $sql .= " AND c.tbl_area_idtbl_area = vl.tbl_area_idtbl_area";
    }
    if ($type == '5' && !empty($customerID)) {
        $sql .= " AND c.tbl_area_idtbl_area = '$customerID'";
    }

    $sql .= " AND bs.idtbl_customer_buffer_stock IS NOT NULL";

    $sql .= " GROUP BY 
        c.idtbl_customer, c.name, c.pv_num, r.idtbl_reject_reason, r.reason, bs.customreason, bs.tbl_customer_idtbl_customer
    ORDER BY 
        c.name";
    $result = $conn->query($sql);

    // Initialize totals array
    $totals = array(
        '2KG_Full' => 0,
        '2KG_Empty' => 0,
        '2KG_Sales' => 0,
        '5KG_Full' => 0,
        '5KG_Empty' => 0,
        '5KG_Sales' => 0,
        '12_5KG_Full' => 0,
        '12_5KG_Empty' => 0,
        '12_5KG_Sales' => 0,
        '37_5KG_Full' => 0,
        '37_5KG_Empty' => 0,
        '37_5KG_Sales' => 0
    );

    $html='';
        
    // if ($result->num_rows > 0) {
    $html.='<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>LAUGFS Gas PLC - Buffer maintainance report on '.$date.'</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 14px;
                margin: 0px;
            }
        </style>
    </head>

    <body>
    <h3 style="text-align: center;margin: 0;">Buffer maintainance report on '.$date.'</h3>
    <h4 style="text-align: center;margin: 5px 0px 10px 0px;">Executive '.$executivename.'</h4>
    <table style="border-collapse: collapse;width: 100%; font-size: 12px;" id="bufferreport">
        <thead>
            <tr>
                <th nowrap rowspan="2" style="border: 1px solid black; padding: 5px;text-align: left;">Customer</th>
                <th nowrap rowspan="2" style="border: 1px solid black; padding: 5px;text-align: left;">Reason</th>
                <th nowrap colspan="4" style="border: 1px solid black; padding: 5px;text-align: center;">2KG</th>
                <th nowrap colspan="4" style="border: 1px solid black; padding: 5px;text-align: center;">5KG</th>
                <th nowrap colspan="4" style="border: 1px solid black; padding: 5px;text-align: center;">12.5KG</th>
                <th nowrap colspan="4" style="border: 1px solid black; padding: 5px;text-align: center;">37.5KG</th>
            </tr>
            <tr>                    
                <!-- 2KG Columns -->
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Full Qty</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Empty Qty</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Sale Qty</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Loss %</th>
                
                <!-- 5KG Columns -->
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Full Qty</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Empty Qty</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Sale Qty</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Loss %</th>
                
                <!-- 12.5KG Columns -->
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Full Qty</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Empty Qty</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Sale Qty</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Loss %</th>
                
                <!-- 37.5KG Columns -->
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Full Qty</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Empty Qty</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Sale Qty</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">Loss %</th>
            </tr>
        </thead>
        <tbody>';
        while ($rowinfo = $result->fetch_assoc()) {
            $bufferenteredcustomers[] = $rowinfo['idtbl_customer'];
            $totals['2KG_Full'] += $rowinfo['2KG_Full'];
            $totals['2KG_Empty'] += $rowinfo['2KG_Empty'];
            $totals['2KG_Sales'] += $rowinfo['2KG_Sales'];
            $totals['5KG_Full'] += $rowinfo['5KG_Full'];
            $totals['5KG_Empty'] += $rowinfo['5KG_Empty'];
            $totals['5KG_Sales'] += $rowinfo['5KG_Sales'];
            $totals['12_5KG_Full'] += $rowinfo['12_5KG_Full'];
            $totals['12_5KG_Empty'] += $rowinfo['12_5KG_Empty'];
            $totals['12_5KG_Sales'] += $rowinfo['12_5KG_Sales'];
            $totals['37_5KG_Full'] += $rowinfo['37_5KG_Full'];
            $totals['37_5KG_Empty'] += $rowinfo['37_5KG_Empty'];
            $totals['37_5KG_Sales'] += $rowinfo['37_5KG_Sales'];
            
            $html.='<tr>
                <td nowrap style="border: 1px solid black; padding: 5px;text-align: left;" style="border: 1px solid black; padding: 5px;text-align: left;">'.$rowinfo['Customer'].'</td>';
                if($rowinfo['rejectid'] > 1 && $rowinfo['rejectid'] < 8) {
                    $html.='<td nowrap style="border: 1px solid black; padding: 5px;text-align: left;">'.$rowinfo['rejectreason'].'</td>';
                } else {
                    $html.='<td nowrap style="border: 1px solid black; padding: 5px;text-align: left;">'.$rowinfo['rejectcustomreason'].'</td>';
                }
                
                $html.='
                <!-- 2KG Columns -->
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['2KG_Full'] <= 0 ? '' : ceil($rowinfo['2KG_Full'])).'</td>
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['2KG_Empty'] <= 0 ? '' : ceil($rowinfo['2KG_Empty'])).'</td>
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['2KG_Sales'] <= 0 ? '' : ceil($rowinfo['2KG_Sales'])).'</td>
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['2KG_Loss_Percentage'] <= 0 ? '' : ceil($rowinfo['2KG_Loss_Percentage']).'%').'</td>
                
                <!-- 5KG Columns -->
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['5KG_Full'] <= 0 ? '' : ceil($rowinfo['5KG_Full'])).'</td>
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['5KG_Empty'] <= 0 ? '' : ceil($rowinfo['5KG_Empty'])).'</td>
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['5KG_Sales'] <= 0 ? '' : ceil($rowinfo['5KG_Sales'])).'</td>
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['5KG_Loss_Percentage'] <= 0 ? '' : ceil($rowinfo['5KG_Loss_Percentage']).'%').'</td>
                
                <!-- 12.5KG Columns -->
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['12_5KG_Full'] <= 0 ? '' : ceil($rowinfo['12_5KG_Full'])).'</td>
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['12_5KG_Empty'] <= 0 ? '' : ceil($rowinfo['12_5KG_Empty'])).'</td>
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['12_5KG_Sales'] <= 0 ? '' : ceil($rowinfo['12_5KG_Sales'])).'</td>
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['12_5KG_Loss_Percentage'] <= 0 ? '' : ceil($rowinfo['12_5KG_Loss_Percentage']).'%').'</td>
                
                <!-- 37.5KG Columns -->
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['37_5KG_Full'] <= 0 ? '' : ceil($rowinfo['37_5KG_Full'])).'</td>
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['37_5KG_Empty'] <= 0 ? '' : ceil($rowinfo['37_5KG_Empty'])).'</td>
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['37_5KG_Sales'] <= 0 ? '' : ceil($rowinfo['37_5KG_Sales'])).'</td>
                <td style="border: 1px solid black; padding: 5px;text-align: center;">'.($rowinfo['37_5KG_Loss_Percentage'] <= 0 ? '' : ceil($rowinfo['37_5KG_Loss_Percentage']).'%').'</td>
            </tr>';
        }

        if($type == '4'){
            $removedubplicates = array_diff($areacustomers, $bufferenteredcustomers);;
            if(count($removedubplicates) > 0){
                foreach($removedubplicates as $customerid){
                    $sqlcustomername = "SELECT `name` FROM `tbl_customer` WHERE `idtbl_customer`='$customerid'";
                    $resultcustomername = $conn->query($sqlcustomername);
                    $rowcustomername = $resultcustomername->fetch_assoc();
                    $html.='<tr>
                        <td nowrap style="border: 1px solid black; padding: 5px;text-align: left;">'.$rowcustomername['name'].'</td>
                        <td nowrap style="border: 1px solid black; padding: 5px;text-align: left;"></td>
                        <!-- 2KG Columns -->
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        
                        <!-- 5KG Columns -->
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        
                        <!-- 12.5KG Columns -->
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        
                        <!-- 37.5KG Columns -->
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px;text-align: center;"></td>
                    </tr>';
                }
            }   
        }
    $html.='</tbody>
        <tfoot class="">
            <tr>
                <th colspan="2" nowrap style="border: 1px solid black; padding: 5px;text-align: left;">Total</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['2KG_Full'] <= 0 ? '' : ceil($totals['2KG_Full'])).'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['2KG_Empty'] <= 0 ? '' : ceil($totals['2KG_Empty'])).'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['2KG_Sales'] <= 0 ? '' : ceil($totals['2KG_Sales'])).'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['2KG_Empty'] <= 0 ? '' : ceil(max(0, ($totals['2KG_Empty'] - $totals['2KG_Sales']) / $totals['2KG_Empty']) * 100).'%').'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['5KG_Full'] <= 0 ? '' : ceil($totals['5KG_Full'])).'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['5KG_Empty'] <= 0 ? '' : ceil($totals['5KG_Empty'])).'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['5KG_Sales'] <= 0 ? '' : ceil($totals['5KG_Sales'])).'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['5KG_Empty'] <= 0 ? '' : ceil(max(0, ($totals['5KG_Empty'] - $totals['5KG_Sales']) / $totals['5KG_Empty']) * 100).'%').'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['12_5KG_Full'] <= 0 ? '' : ceil($totals['12_5KG_Full'])).'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['12_5KG_Empty'] <= 0 ? '' : ceil($totals['12_5KG_Empty'])).'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['12_5KG_Sales'] <= 0 ? '' : ceil($totals['12_5KG_Sales'])).'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['12_5KG_Empty'] <= 0 ? '' : ceil(max(0, ($totals['12_5KG_Empty'] - $totals['12_5KG_Sales']) / $totals['12_5KG_Empty']) * 100).'%').'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['37_5KG_Full'] <= 0 ? '' : ceil($totals['37_5KG_Full'])).'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['37_5KG_Empty'] <= 0 ? '' : ceil($totals['37_5KG_Empty'])).'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['37_5KG_Sales'] <= 0 ? '' : ceil($totals['37_5KG_Sales'])).'</th>
                <th style="border: 1px solid black; padding: 5px;text-align: center;">'.($totals['37_5KG_Empty'] <= 0 ? '' : ceil(max(0, ($totals['37_5KG_Empty'] - $totals['37_5KG_Sales']) / $totals['37_5KG_Empty']) * 100).'%').'</th>
            </tr>
        </tfoot></table>
        </body>
    </html>';

    // 1. Generate PDF in memory
    $dompdf = new Dompdf();
    // $html = "<h1>Temporary PDF</h1><p>Sent via CURL without permanent storage.</p>";
    $dompdf->loadHtml($html);
    $dompdf->setPaper([0, 0, 612, 1180], 'landscape');
    $dompdf->render();
    // $dompdf->stream("ansen_gas_invoice_test.pdf", ["Attachment" => false]);

    // 2. Create a temporary file
    $tempFile = tmpfile();
    $tempFilePath = stream_get_meta_data($tempFile)['uri'];

    // 3. Write the PDF content to the temporary file
    fwrite($tempFile, $dompdf->output());

    
    // 4. Prepare CURL (using the temp file path)
    $mail_body = "Dear Team,<br><br>";
    $mail_body .= "Please find the attached Buffer Maintenance Report.<br><br>";
    $mail_body .= "Executive Name: " . $executivename . "<br>";
    $mail_body .= "Report Date: " . $date . "<br><br>";
    $mail_body .= "This is an automated message. The attached PDF was generated temporarily for this delivery.";

    $replylist = 'angelo@ansenagriculture.lk;nalaka.warnakulasuriya@laugfs.lk;dbmanager@ansengas.lk;nalindaa@laugfs.lk;info@ansengas.lk;'.$executiveemail;
    // $replylist = 'asela.indrajith@gmail.com;asela.indrajith4@gmail.com';

    $post = [
        'inquire_now'        => 'Ansen Gas PLC',
        'replyto'            => $replylist,
        'contsubj'           => 'Buffer Maintainance Report '.$executivename.' ON '.$date,
        'contbody'           => $mail_body,
        'mail_attachment'    => curl_file_create($tempFilePath, 'application/pdf', 'buffer_maintainance_report_'.$executivename.'_on_'.$date.'.pdf'),
        'attachment_name'    => 'buffer_maintainance_report_'.$executivename.'_on_'.$date.'.pdf',
        'attachment_mimetxt' => 'application/pdf'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://aws.erav.lk/Temp/bf360/eravawsmail_vattch.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    $response = curl_exec($ch);
    curl_close($ch);

    // 5. Close the handle (This automatically deletes the physical file from /tmp)
    fclose($tempFile);

    var_dump($response);
}
?>