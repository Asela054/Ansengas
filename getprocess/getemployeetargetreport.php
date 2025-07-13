<?php 
require_once('../connection/db.php');

$month=$_POST['month'];
$validfrom = date('Y-m-01', strtotime($month));
$validto = date('Y-m-t', strtotime($month));
$employee=$_POST['employee'];
$reporttype=$_POST['reporttype'];

if($reporttype == 1 || $reporttype == 2) {
    if($reporttype == 1){$empType= 7;} // For Executive
    if($reporttype == 2){$empType= 4;} // For Driver
    $sql="SELECT 
        e.name AS employee_name,

        -- 2KG (Product ID 6)
        MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) AS target_2kg,
        MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_2kg,
        (MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_2kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_2kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_2kg,

        -- 5KG (Product ID 4)
        MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) AS target_5kg,
        MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_5kg,
        (MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_5kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_5kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_5kg,

        -- 12.5KG (Product ID 1)
        MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) AS target_12_5kg,
        MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_12_5kg,
        (MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_12_5kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_12_5kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_12_5kg,

        -- 37.5KG (Product ID 2)
        MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) AS target_37_5kg,
        MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_37_5kg,
        (MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_37_5kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_37_5kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_37_5kg

    FROM 
        tbl_employee_target et
    INNER JOIN 
        tbl_employee e ON et.tbl_employee_idtbl_employee = e.idtbl_employee
    INNER JOIN 
        tbl_product p ON et.tbl_product_idtbl_product = p.idtbl_product
    LEFT JOIN 
    (
        SELECT 
            tbl_employee.idtbl_employee,
            tbl_invoice_detail.tbl_product_idtbl_product,
            SUM(tbl_invoice_detail.newqty + tbl_invoice_detail.refillqty + tbl_invoice_detail.trustqty) AS targetcomplete
        FROM 
            tbl_invoice_detail
        JOIN 
            tbl_invoice ON tbl_invoice.idtbl_invoice = tbl_invoice_detail.tbl_invoice_idtbl_invoice
        JOIN 
            tbl_vehicle_load ON tbl_vehicle_load.idtbl_vehicle_load = tbl_invoice.tbl_vehicle_load_idtbl_vehicle_load";
    if($empType==4){
        $sql.=" JOIN 
            tbl_employee ON tbl_employee.idtbl_employee = tbl_vehicle_load.driverid";
    }
    else if($empType==7){
        $sql.=" JOIN 
            tbl_customerwise_salesrep ON tbl_customerwise_salesrep.tbl_customer_idtbl_customer = tbl_invoice.tbl_customer_idtbl_customer AND tbl_customerwise_salesrep.tbl_product_idtbl_product = tbl_invoice_detail.tbl_product_idtbl_product
            JOIN 
            tbl_employee ON tbl_employee.idtbl_employee = tbl_customerwise_salesrep.tbl_employee_idtbl_employee";
    }
        $sql.="
        WHERE 
            tbl_invoice_detail.status = 1 
            AND tbl_invoice.status = 1
            AND tbl_invoice.date BETWEEN '$validfrom' AND '$validto'
        GROUP BY 
            tbl_employee.idtbl_employee, tbl_invoice_detail.tbl_product_idtbl_product
    ) AS sales ON sales.tbl_product_idtbl_product = p.idtbl_product 
            AND sales.idtbl_employee = e.idtbl_employee
    LEFT JOIN 
    (
        SELECT 
            COUNT(*) AS total_holidays_remaining
        FROM 
            tbl_month_holiday
        WHERE 
            date BETWEEN CURDATE() AND '$validto'
    ) AS hm ON 1=1
    WHERE 
        e.tbl_user_type_idtbl_user_type = '$empType' AND
        e.status = 1 AND
        et.month BETWEEN '$validfrom' AND '$validto'";
        
    if(!empty($employee)) {
        $sql .= " AND e.idtbl_employee = '$employee'";
    }
    
    $sql .= " GROUP BY 
        e.idtbl_employee";
    $result = $conn->query($sql);

    $html='';
    
    if ($result->num_rows > 0) {
        $html.='<div class="scrollbar pb-3" id="style-2">
        <table class="table table-striped table-bordered table-sm small" id="table_content">
            <thead class="table-dark">
                <tr>
                    <th nowrap rowspan="2">Driver</th>
                    <th nowrap colspan="5" class="text-center">2KG</th>
                    <th nowrap colspan="5" class="text-center">5KG</th>
                    <th nowrap colspan="5" class="text-center">12.5KG</th>
                    <th nowrap colspan="5" class="text-center">37.5KG</th>
                </tr>
                <tr>                    
                    <!-- 2KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                    
                    <!-- 5KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                    
                    <!-- 12.5KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                    
                    <!-- 37.5KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                </tr>
            </thead>
            <tbody>';
        while ($rowinfo = $result->fetch_assoc()) {
            $html.='<tr>
                <td nowrap>'.$rowinfo['employee_name'].'</td>
                
                <!-- 2KG Columns -->
                <td class="text-center">'.$rowinfo['target_2kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_2kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_2kg'] <= 0 ? '' : ceil($rowinfo['balance_2kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_2kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_2kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_2kg'] <= 0 ? '' : ceil($rowinfo['avg_day_2kg'])).'</td>
                
                <!-- 5KG Columns -->
                <td class="text-center">'.$rowinfo['target_5kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_5kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_5kg'] <= 0 ? '' : ceil($rowinfo['balance_5kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_5kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_5kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_5kg'] <= 0 ? '' : ceil($rowinfo['avg_day_5kg'])).'</td>
                
                <!-- 12.5KG Columns -->
                <td class="text-center">'.$rowinfo['target_12_5kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_12_5kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_12_5kg'] <= 0 ? '' : ceil($rowinfo['balance_12_5kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_12_5kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_12_5kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_12_5kg'] <= 0 ? '' : ceil($rowinfo['avg_day_12_5kg'])).'</td>
                
                <!-- 37.5KG Columns -->
                <td class="text-center">'.$rowinfo['target_37_5kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_37_5kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_37_5kg'] <= 0 ? '' : ceil($rowinfo['balance_37_5kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_37_5kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_37_5kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_37_5kg'] <= 0 ? '' : ceil($rowinfo['avg_day_37_5kg'])).'</td>
            </tr>';
        }
        $html.='</tbody></table></div>';
    }
} else if($reporttype == 3) {
    $sql="SELECT 
        e.area AS area,

        -- 2KG (Product ID 6)
        MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) AS target_2kg,
        MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_2kg,
        (MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_2kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_2kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_2kg,

        -- 5KG (Product ID 4)
        MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) AS target_5kg,
        MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_5kg,
        (MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_5kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_5kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_5kg,

        -- 12.5KG (Product ID 1)
        MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) AS target_12_5kg,
        MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_12_5kg,
        (MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_12_5kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_12_5kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_12_5kg,

        -- 37.5KG (Product ID 2)
        MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) AS target_37_5kg,
        MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_37_5kg,
        (MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_37_5kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_37_5kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_37_5kg

    FROM 
        tbl_area_target et
    INNER JOIN 
        tbl_area e ON et.tbl_area_idtbl_area = e.idtbl_area
    INNER JOIN 
        tbl_product p ON et.tbl_product_idtbl_product = p.idtbl_product
    LEFT JOIN 
    (
        SELECT 
            tbl_customer.tbl_area_idtbl_area,
            tbl_invoice_detail.tbl_product_idtbl_product,
            SUM(tbl_invoice_detail.newqty + tbl_invoice_detail.refillqty + tbl_invoice_detail.trustqty) AS targetcomplete
        FROM 
            tbl_invoice_detail
        JOIN 
            tbl_invoice ON tbl_invoice.idtbl_invoice = tbl_invoice_detail.tbl_invoice_idtbl_invoice
        JOIN 
            tbl_customer ON tbl_customer.idtbl_customer = tbl_invoice.tbl_customer_idtbl_customer
        WHERE 
            tbl_invoice_detail.status = 1 
            AND tbl_invoice.status = 1";
            if(!empty($employee)) {
                $sql .= " AND tbl_customer.tbl_area_idtbl_area = '$employee'";
            }
            $sql .= " AND tbl_invoice.date BETWEEN '$validfrom' AND '$validto'
        GROUP BY 
            tbl_customer.tbl_area_idtbl_area, tbl_invoice_detail.tbl_product_idtbl_product
    ) AS sales ON sales.tbl_product_idtbl_product = p.idtbl_product 
            AND sales.tbl_area_idtbl_area = e.idtbl_area
    LEFT JOIN 
    (
        SELECT 
            COUNT(*) AS total_holidays_remaining
        FROM 
            tbl_month_holiday
        WHERE 
            date BETWEEN CURDATE() AND '$validto'
    ) AS hm ON 1=1
    WHERE 
        e.status = 1 AND
        et.month BETWEEN '$validfrom' AND '$validto'";
        if(!empty($employee)) {
            $sql .= " AND e.idtbl_area = '$employee'";
        }
    $sql .= " GROUP BY 
        e.idtbl_area";
    $result = $conn->query($sql);

    $html='';
    
    if ($result->num_rows > 0) {
        $html.='<div class="scrollbar pb-3" id="style-2">
        <table class="table table-striped table-bordered table-sm small" id="table_content">
            <thead class="table-dark">
                <tr>
                    <th nowrap rowspan="2">Area</th>
                    <th nowrap colspan="5" class="text-center">2KG</th>
                    <th nowrap colspan="5" class="text-center">5KG</th>
                    <th nowrap colspan="5" class="text-center">12.5KG</th>
                    <th nowrap colspan="5" class="text-center">37.5KG</th>
                </tr>
                <tr>                    
                    <!-- 2KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                    
                    <!-- 5KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                    
                    <!-- 12.5KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                    
                    <!-- 37.5KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                </tr>
            </thead>
            <tbody>';
        while ($rowinfo = $result->fetch_assoc()) {
            $html.='<tr>
                <td nowrap>'.$rowinfo['area'].'</td>
                
                <!-- 2KG Columns -->
                <td class="text-center">'.$rowinfo['target_2kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_2kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_2kg'] <= 0 ? '' : ceil($rowinfo['balance_2kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_2kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_2kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_2kg'] <= 0 ? '' : ceil($rowinfo['avg_day_2kg'])).'</td>
                
                <!-- 5KG Columns -->
                <td class="text-center">'.$rowinfo['target_5kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_5kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_5kg'] <= 0 ? '' : ceil($rowinfo['balance_5kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_5kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_5kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_5kg'] <= 0 ? '' : ceil($rowinfo['avg_day_5kg'])).'</td>
                
                <!-- 12.5KG Columns -->
                <td class="text-center">'.$rowinfo['target_12_5kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_12_5kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_12_5kg'] <= 0 ? '' : ceil($rowinfo['balance_12_5kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_12_5kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_12_5kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_12_5kg'] <= 0 ? '' : ceil($rowinfo['avg_day_12_5kg'])).'</td>
                
                <!-- 37.5KG Columns -->
                <td class="text-center">'.$rowinfo['target_37_5kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_37_5kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_37_5kg'] <= 0 ? '' : ceil($rowinfo['balance_37_5kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_37_5kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_37_5kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_37_5kg'] <= 0 ? '' : ceil($rowinfo['avg_day_37_5kg'])).'</td>
            </tr>';
        }
        $html.='</tbody></table></div>';
    }
} else if($reporttype == 4) {
    $sql="SELECT 
        e.name AS name,

        -- 2KG (Product ID 6)
        MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) AS target_2kg,
        MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_2kg,
        (MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_2kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_2kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_2kg,

        -- 5KG (Product ID 4)
        MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) AS target_5kg,
        MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_5kg,
        (MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_5kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_5kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_5kg,

        -- 12.5KG (Product ID 1)
        MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) AS target_12_5kg,
        MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_12_5kg,
        (MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_12_5kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_12_5kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_12_5kg,

        -- 37.5KG (Product ID 2)
        MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) AS target_37_5kg,
        MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_37_5kg,
        (MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_37_5kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_37_5kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_37_5kg

    FROM 
        tbl_cutomer_target et
    INNER JOIN 
        tbl_customer e ON et.tbl_customer_idtbl_customer = e.idtbl_customer
    INNER JOIN 
        tbl_product p ON et.tbl_product_idtbl_product = p.idtbl_product
    LEFT JOIN 
    (
        SELECT 
            tbl_customer.idtbl_customer,
            tbl_invoice_detail.tbl_product_idtbl_product,
            SUM(tbl_invoice_detail.newqty + tbl_invoice_detail.refillqty + tbl_invoice_detail.trustqty) AS targetcomplete
        FROM 
            tbl_invoice_detail
        JOIN 
            tbl_invoice ON tbl_invoice.idtbl_invoice = tbl_invoice_detail.tbl_invoice_idtbl_invoice
        JOIN 
            tbl_customer ON tbl_customer.idtbl_customer = tbl_invoice.tbl_customer_idtbl_customer
        WHERE 
            tbl_invoice_detail.status = 1 
            AND tbl_invoice.status = 1";
            if(!empty($employee)) {
                $sql .= " AND tbl_customer.idtbl_customer = '$employee'";
            }
            $sql .= " AND tbl_invoice.date BETWEEN '$validfrom' AND '$validto'
        GROUP BY 
            tbl_customer.idtbl_customer, tbl_invoice_detail.tbl_product_idtbl_product
    ) AS sales ON sales.tbl_product_idtbl_product = p.idtbl_product 
            AND sales.idtbl_customer = e.idtbl_customer
    LEFT JOIN 
    (
        SELECT 
            COUNT(*) AS total_holidays_remaining
        FROM 
            tbl_month_holiday
        WHERE 
            date BETWEEN CURDATE() AND '$validto'
    ) AS hm ON 1=1
    WHERE 
        e.status = 1 AND
        et.month BETWEEN '$validfrom' AND '$validto'";
        if(!empty($employee)) {
            $sql .= " AND e.idtbl_customer = '$employee'";
        }
    $sql .= " GROUP BY 
        e.idtbl_customer";
    $result = $conn->query($sql);

    $html='';
    
    if ($result->num_rows > 0) {
        $html.='<div class="scrollbar pb-3" id="style-2">
        <table class="table table-striped table-bordered table-sm small" id="table_content">
            <thead class="table-dark">
                <tr>
                    <th nowrap rowspan="2">Customer</th>
                    <th nowrap colspan="5" class="text-center">2KG</th>
                    <th nowrap colspan="5" class="text-center">5KG</th>
                    <th nowrap colspan="5" class="text-center">12.5KG</th>
                    <th nowrap colspan="5" class="text-center">37.5KG</th>
                </tr>
                <tr>                    
                    <!-- 2KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                    
                    <!-- 5KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                    
                    <!-- 12.5KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                    
                    <!-- 37.5KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                </tr>
            </thead>
            <tbody>';
        while ($rowinfo = $result->fetch_assoc()) {
            $html.='<tr>
                <td nowrap>'.$rowinfo['name'].'</td>
                
                <!-- 2KG Columns -->
                <td class="text-center">'.$rowinfo['target_2kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_2kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_2kg'] <= 0 ? '' : ceil($rowinfo['balance_2kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_2kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_2kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_2kg'] <= 0 ? '' : ceil($rowinfo['avg_day_2kg'])).'</td>
                
                <!-- 5KG Columns -->
                <td class="text-center">'.$rowinfo['target_5kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_5kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_5kg'] <= 0 ? '' : ceil($rowinfo['balance_5kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_5kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_5kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_5kg'] <= 0 ? '' : ceil($rowinfo['avg_day_5kg'])).'</td>
                
                <!-- 12.5KG Columns -->
                <td class="text-center">'.$rowinfo['target_12_5kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_12_5kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_12_5kg'] <= 0 ? '' : ceil($rowinfo['balance_12_5kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_12_5kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_12_5kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_12_5kg'] <= 0 ? '' : ceil($rowinfo['avg_day_12_5kg'])).'</td>
                
                <!-- 37.5KG Columns -->
                <td class="text-center">'.$rowinfo['target_37_5kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_37_5kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_37_5kg'] <= 0 ? '' : ceil($rowinfo['balance_37_5kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_37_5kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_37_5kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_37_5kg'] <= 0 ? '' : ceil($rowinfo['avg_day_37_5kg'])).'</td>
            </tr>';
        }
        $html.='</tbody></table></div>';
    }
} else if($reporttype == 5) {
    $sql="SELECT 
        e.vehicleno AS vehicleno,

        -- 2KG (Product ID 6)
        MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) AS target_2kg,
        MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_2kg,
        (MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_2kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_2kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '6' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_2kg,

        -- 5KG (Product ID 4)
        MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) AS target_5kg,
        MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_5kg,
        (MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_5kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_5kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '4' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_5kg,

        -- 12.5KG (Product ID 1)
        MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) AS target_12_5kg,
        MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_12_5kg,
        (MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_12_5kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_12_5kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '1' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_12_5kg,

        -- 37.5KG (Product ID 2)
        MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) AS target_37_5kg,
        MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END) AS completed_37_5kg,
        (MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) - 
        MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END)) AS balance_37_5kg,
        CASE 
            WHEN MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) > 0 
            THEN ROUND(
                ((MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) - 
                MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END)) / 
                MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END)) * 100, 2
            )
            ELSE 0 
        END AS balance_percentage_37_5kg,
        ROUND(
            (MAX(CASE WHEN p.idtbl_product = '2' THEN et.targettank END) - 
            MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(sales.targetcomplete, 0) END)) / 
            GREATEST(DATEDIFF('$validto', CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
        2) AS avg_day_37_5kg

    FROM 
        tbl_vehicle_target et
    INNER JOIN 
        tbl_vehicle e ON et.tbl_vehicle_idtbl_vehicle = e.idtbl_vehicle
    INNER JOIN 
        tbl_product p ON et.tbl_product_idtbl_product = p.idtbl_product
    LEFT JOIN 
    (
        SELECT 
            tbl_vehicle_load.lorryid,
            tbl_invoice_detail.tbl_product_idtbl_product,
            SUM(tbl_invoice_detail.newqty + tbl_invoice_detail.refillqty + tbl_invoice_detail.trustqty) AS targetcomplete
        FROM 
            tbl_invoice_detail
        JOIN 
            tbl_invoice ON tbl_invoice.idtbl_invoice = tbl_invoice_detail.tbl_invoice_idtbl_invoice
        JOIN 
            tbl_vehicle_load ON tbl_vehicle_load.idtbl_vehicle_load = tbl_invoice.tbl_vehicle_load_idtbl_vehicle_load
        WHERE 
            tbl_invoice_detail.status = 1 
            AND tbl_invoice.status = 1";
            if(!empty($employee)) {
                $sql .= " AND tbl_vehicle_load.lorryid = '$employee'";
            }
            $sql .= " AND tbl_invoice.date BETWEEN '$validfrom' AND '$validto'
        GROUP BY 
            tbl_vehicle_load.lorryid, tbl_invoice_detail.tbl_product_idtbl_product
    ) AS sales ON sales.tbl_product_idtbl_product = p.idtbl_product 
            AND sales.lorryid = e.idtbl_vehicle
    LEFT JOIN 
    (
        SELECT 
            COUNT(*) AS total_holidays_remaining
        FROM 
            tbl_month_holiday
        WHERE 
            date BETWEEN CURDATE() AND '$validto'
    ) AS hm ON 1=1
    WHERE 
        e.status = 1 AND
        et.month BETWEEN '$validfrom' AND '$validto'";
        if(!empty($employee)) {
            $sql .= " AND e.idtbl_vehicle = '$employee'";
        }
    $sql .= " GROUP BY 
        e.idtbl_vehicle";
    $result = $conn->query($sql);

    $html='';

    if ($result->num_rows > 0) {
        $html.='<div class="scrollbar pb-3" id="style-2">
        <table class="table table-striped table-bordered table-sm small" id="table_content">
            <thead class="table-dark">
                <tr>
                    <th nowrap rowspan="2">Vehicle</th>
                    <th nowrap colspan="5" class="text-center">2KG</th>
                    <th nowrap colspan="5" class="text-center">5KG</th>
                    <th nowrap colspan="5" class="text-center">12.5KG</th>
                    <th nowrap colspan="5" class="text-center">37.5KG</th>
                </tr>
                <tr>                    
                    <!-- 2KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                    
                    <!-- 5KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                    
                    <!-- 12.5KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                    
                    <!-- 37.5KG Columns -->
                    <th class="text-center">Target</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Balance %</th>
                    <th class="text-center">Avg/Day</th>
                </tr>
            </thead>
            <tbody>';
        while ($rowinfo = $result->fetch_assoc()) {
            $html.='<tr>
                <td nowrap>'.$rowinfo['vehicleno'].'</td>
                
                <!-- 2KG Columns -->
                <td class="text-center">'.$rowinfo['target_2kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_2kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_2kg'] <= 0 ? '' : ceil($rowinfo['balance_2kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_2kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_2kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_2kg'] <= 0 ? '' : ceil($rowinfo['avg_day_2kg'])).'</td>
                
                <!-- 5KG Columns -->
                <td class="text-center">'.$rowinfo['target_5kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_5kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_5kg'] <= 0 ? '' : ceil($rowinfo['balance_5kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_5kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_5kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_5kg'] <= 0 ? '' : ceil($rowinfo['avg_day_5kg'])).'</td>
                
                <!-- 12.5KG Columns -->
                <td class="text-center">'.$rowinfo['target_12_5kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_12_5kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_12_5kg'] <= 0 ? '' : ceil($rowinfo['balance_12_5kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_12_5kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_12_5kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_12_5kg'] <= 0 ? '' : ceil($rowinfo['avg_day_12_5kg'])).'</td>
                
                <!-- 37.5KG Columns -->
                <td class="text-center">'.$rowinfo['target_37_5kg'].'</td>
                <td class="text-center">'.$rowinfo['completed_37_5kg'].'</td>
                <td class="text-center">'.($rowinfo['balance_37_5kg'] <= 0 ? '' : ceil($rowinfo['balance_37_5kg'])).'</td>
                <td class="text-center">'.($rowinfo['balance_percentage_37_5kg'] <= 0 ? '' : ceil($rowinfo['balance_percentage_37_5kg']).'%').'</td>
                <td class="text-center">'.($rowinfo['balance_37_5kg'] <= 0 ? '' : ceil($rowinfo['avg_day_37_5kg'])).'</td>
            </tr>';
        }
        $html.='</tbody></table></div>';
    }
}

echo $html;

// if(!empty($_POST['employee'])){
//     $sqlref="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `idtbl_employee`='$employee'";
//     $resultref =$conn-> query($sqlref);
// }
// else{
//     $sqlref="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1";
//     $resultref =$conn-> query($sqlref);
// }
?>
<!-- <div class="table-container">
    <table class="table table-striped table-bordered table-sm sticky-header" id="table_content">
        <thead class="thead-dark">
            <tr>
                <th>Sale ref name</th>
                <th>Product</th>
                <th>Target Cylinders</th>
                <th>Complete Cylinders</th>
                <th>Target Accessories</th>
                <th>Complete Accessories</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while($rowref = $resultref->fetch_assoc()){ 
                $salerefID = $rowref['idtbl_employee'];

                // $sqlproductlist = "SELECT `tbl_product`.`product_name`, 
                //                         SUM(`tbl_employee_target`.`targettank`) AS `targettank`, 
                //                         SUM(`tbl_employee_target`.`targetcomplete`) AS `targetcomplete`,
                //                         `tbl_product`.`tbl_product_category_idtbl_product_category` AS `category`
                //                 FROM `tbl_employee_target` 
                //                 LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_employee_target`.`tbl_product_idtbl_product` 
                //                 WHERE `tbl_employee_target`.`tbl_employee_idtbl_employee` = '$salerefID' 
                //                 AND `tbl_employee_target`.`status` = 1 
                //                 AND `tbl_employee_target`.`month` BETWEEN '$validfrom' AND '$validto' 
                //                 GROUP BY `tbl_product`.`product_name`";
                $sqlproductlist="SELECT * FROM (SELECT `tbl_employee_target`.`month`, `tbl_employee_target`.`targettank`, `tbl_product`.`idtbl_product`, `tbl_product`.`product_name`, `tbl_product`.`tbl_product_category_idtbl_product_category` AS `category` FROM `tbl_employee_target` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_employee_target`.`tbl_employee_idtbl_employee` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_employee_target`.`tbl_product_idtbl_product` WHERE `tbl_employee`.`idtbl_employee`='$salerefID' AND `tbl_employee_target`.`month` BETWEEN '$validfrom' AND '$validto') AS `dmain` LEFT JOIN (SELECT SUM(`newqty`+`refillqty`+`trustqty`) AS `targetcomplete`, `tbl_invoice_detail`.`tbl_product_idtbl_product` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_vehicle_load`.`driverid` WHERE `tbl_invoice_detail`.`status`=1 AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_employee`.`idtbl_employee`='$salerefID' GROUP BY `tbl_invoice_detail`.`tbl_product_idtbl_product`) AS `dsub` ON `dsub`.`tbl_product_idtbl_product`=`dmain`.`idtbl_product`";
                $resultproductlist = $conn->query($sqlproductlist);
            ?>
            <tr>
                <td colspan="6"><?php echo $rowref['name']; ?></td>
            </tr>
            <?php 
            while($rowproductlist = $resultproductlist->fetch_assoc()){  
            ?>
            <tr>
                <td>&nbsp;</td>
                <td><?php echo $rowproductlist['product_name'] ?></td>

                <?php if (in_array($rowproductlist['category'], [1, 3])) { ?>
                    <td><?php echo $rowproductlist['targettank'] ?></td>
                    <td><?php echo $rowproductlist['targetcomplete'] ?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                <?php } elseif ($rowproductlist['category'] == 2) { ?>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><?php echo $rowproductlist['targettank'] ?></td>
                    <td><?php echo $rowproductlist['targetcomplete'] ?></td>
                <?php } ?>
            </tr>
            <?php }} ?>
        </tbody>
    </table>
</div> -->