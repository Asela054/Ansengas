<?php 
require_once('../connection/db.php');

$empType = $_POST['empType'];

// First execute the setup queries separately
$setupQueries = [
    "SET SESSION group_concat_max_len = 1000000",
    "SET @accessory_columns = NULL",
    "SELECT GROUP_CONCAT(DISTINCT
        CONCAT(
            'MAX(CASE WHEN p.idtbl_product = ''', idtbl_product, ''' THEN et.targettank END) AS `target_', 
            REPLACE(REPLACE(REPLACE(REPLACE(LOWER(CONCAT(product_name, '_', size)), ' ', '_'), '.', '_'), '-', '_'), '/', '_'), '`,\n',
            'MAX(CASE WHEN p.idtbl_product = ''', idtbl_product, ''' THEN IFNULL(sales.targetcomplete, 0) END) AS `completed_', 
            REPLACE(REPLACE(REPLACE(REPLACE(LOWER(CONCAT(product_name, '_', size)), ' ', '_'), '.', '_'), '-', '_'), '/', '_'), '`,\n',
            '(MAX(CASE WHEN p.idtbl_product = ''', idtbl_product, ''' THEN et.targettank END) - ',
            'MAX(CASE WHEN p.idtbl_product = ''', idtbl_product, ''' THEN IFNULL(sales.targetcomplete, 0) END)) AS `balance_', 
            REPLACE(REPLACE(REPLACE(REPLACE(LOWER(CONCAT(product_name, '_', size)), ' ', '_'), '.', '_'), '-', '_'), '/', '_'), '`,\n',
            'CASE WHEN MAX(CASE WHEN p.idtbl_product = ''', idtbl_product, ''' THEN et.targettank END) > 0 THEN ',
            'ROUND(((MAX(CASE WHEN p.idtbl_product = ''', idtbl_product, ''' THEN et.targettank END) - ',
            'MAX(CASE WHEN p.idtbl_product = ''', idtbl_product, ''' THEN IFNULL(sales.targetcomplete, 0) END)) / ',
            'MAX(CASE WHEN p.idtbl_product = ''', idtbl_product, ''' THEN et.targettank END)) * 100, 2) ',
            'ELSE 0 END AS `balance_percentage_', 
            REPLACE(REPLACE(REPLACE(REPLACE(LOWER(CONCAT(product_name, '_', size)), ' ', '_'), '.', '_'), '-', '_'), '/', '_'), '`,\n',
            'ROUND((MAX(CASE WHEN p.idtbl_product = ''', idtbl_product, ''' THEN et.targettank END) - ',
            'MAX(CASE WHEN p.idtbl_product = ''', idtbl_product, ''' THEN IFNULL(sales.targetcomplete, 0) END)) / ',
            'GREATEST(DAY(LAST_DAY(CURDATE())) - DAY(CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 2) AS `avg_day_', 
            REPLACE(REPLACE(REPLACE(REPLACE(LOWER(CONCAT(product_name, '_', size)), ' ', '_'), '.', '_'), '-', '_'), '/', '_'), '`'
        )
    SEPARATOR ',\n\n') 
    INTO @accessory_columns
    FROM tbl_product 
    WHERE status = 1 AND tbl_product_category_idtbl_product_category = 2"
];

foreach ($setupQueries as $query) {
    $conn->query($query);
}

// Get the accessory columns string
$accessoryColumnsResult = $conn->query("SELECT @accessory_columns AS accessory_columns");
$accessoryColumnsRow = $accessoryColumnsResult->fetch_assoc();
$accessoryColumns = $accessoryColumnsRow['accessory_columns'];

// Now build the main query with accessory columns directly included
$sql = "
SELECT 
    e.name AS employee_name,

    -- Static columns for gas products
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
        GREATEST(DAY(LAST_DAY(CURDATE())) - DAY(CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
    2) AS avg_day_2kg,

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
        GREATEST(DAY(LAST_DAY(CURDATE())) - DAY(CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
    2) AS avg_day_5kg,

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
        GREATEST(DAY(LAST_DAY(CURDATE())) - DAY(CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
    2) AS avg_day_12_5kg,

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
        GREATEST(DAY(LAST_DAY(CURDATE())) - DAY(CURDATE()) - IFNULL(hm.total_holidays_remaining, 0), 1), 
    2) AS avg_day_37_5kg,

    -- Dynamically added accessory product columns
    " . $accessoryColumns . "

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

$sql.=" WHERE 
        tbl_invoice_detail.status = 1 
        AND tbl_invoice.status = 1
        AND YEAR(tbl_invoice.date) = YEAR(CURRENT_DATE()) 
        AND MONTH(tbl_invoice.date) = MONTH(CURRENT_DATE())
    GROUP BY 
        tbl_employee.idtbl_employee, tbl_invoice_detail.tbl_product_idtbl_product
) AS sales ON sales.tbl_product_idtbl_product = p.idtbl_product 
          AND sales.idtbl_employee = e.idtbl_employee
LEFT JOIN 
(
    SELECT 
        MONTH(date) AS month, 
        COUNT(*) AS total_holidays_remaining
    FROM 
        tbl_month_holiday
    WHERE 
        MONTH(date) = MONTH(CURDATE()) AND 
        YEAR(date) = YEAR(CURDATE()) AND 
        date > CURDATE()
    GROUP BY 
        MONTH(date)
) AS hm ON MONTH(CURDATE()) = hm.month
WHERE 
    e.tbl_user_type_idtbl_user_type = '$empType' AND
    e.status = 1 AND
    YEAR(et.month) = YEAR(CURRENT_DATE()) AND
    MONTH(et.month) = MONTH(CURRENT_DATE())
GROUP BY 
    e.idtbl_employee";

$result = $conn->query($sql);

// First, get all accessory product names and their column names
$accessoryProducts = $conn->query("
    SELECT 
        idtbl_product,
        CONCAT(product_name, ' ', size) as display_name,
        LOWER(REPLACE(REPLACE(REPLACE(REPLACE(CONCAT(product_name, '_', size), ' ', '_'), '.', '_'), '-', '_'), '/', '_')) as col_name
    FROM tbl_product 
    WHERE status = 1 AND tbl_product_category_idtbl_product_category = 2
");

// Store accessory product info in an array
$accessories = [];
if ($accessoryProducts && $accessoryProducts->num_rows > 0) {
    while ($accessory = $accessoryProducts->fetch_assoc()) {
        $accessories[] = $accessory;
    }
}

// Calculate total columns for colspan (4 gas products * 5 columns + employee name + accessories * 5)
$totalColumns = 1 + (4 * 5) + (count($accessories) * 5);

$html='';
$emplistID=0;

if ($result && $result->num_rows > 0) {
    while ($rowinfo = $result->fetch_assoc()) {
        $html.='<tr>
            <td nowrap>'.$rowinfo['employee_name'].'</td>
            
            <!-- 2KG Columns -->
            <td class="text-center '.($rowinfo['balance_percentage_2kg'] <= 0 && $rowinfo['target_2kg'] != '' ? 'table-success' : '').'">'.$rowinfo['target_2kg'].'</td>
            <td class="text-center '.($rowinfo['balance_percentage_2kg'] <= 0 && $rowinfo['target_2kg'] != '' ? 'table-success' : '').'">'.$rowinfo['completed_2kg'].'</td>
            <td class="text-center '.($rowinfo['balance_percentage_2kg'] <= 0 && $rowinfo['target_2kg'] != '' ? 'table-success' : '').'">'.($rowinfo['balance_2kg'] <= 0 ? '' : ceil($rowinfo['balance_2kg'])).'</td>
            <td class="text-center '.($rowinfo['balance_percentage_2kg'] <= 0 && $rowinfo['target_2kg'] != '' ? 'table-success' : '').'">'.($rowinfo['balance_percentage_2kg'] <= 0 ? '0%' : ceil($rowinfo['balance_percentage_2kg']).'%').'</td>
            <td class="text-center '.($rowinfo['balance_percentage_2kg'] <= 0 && $rowinfo['target_2kg'] != '' ? 'table-success' : '').'">'.($rowinfo['balance_2kg'] <= 0 ? '0' : ceil($rowinfo['avg_day_2kg'])).'</td>
            
            <!-- 5KG Columns -->
            <td class="text-center '.($rowinfo['balance_percentage_5kg'] <= 0 && $rowinfo['target_5kg'] != '' ? 'table-success' : '').'">'.$rowinfo['target_5kg'].'</td>
            <td class="text-center '.($rowinfo['balance_percentage_5kg'] <= 0 && $rowinfo['target_5kg'] != '' ? 'table-success' : '').'">'.$rowinfo['completed_5kg'].'</td>
            <td class="text-center '.($rowinfo['balance_percentage_5kg'] <= 0 && $rowinfo['target_5kg'] != '' ? 'table-success' : '').'">'.($rowinfo['balance_5kg'] <= 0 ? '' : ceil($rowinfo['balance_5kg'])).'</td>
            <td class="text-center '.($rowinfo['balance_percentage_5kg'] <= 0 && $rowinfo['target_5kg'] != '' ? 'table-success' : '').'">'.($rowinfo['balance_percentage_5kg'] <= 0 ? '0%' : ceil($rowinfo['balance_percentage_5kg']).'%').'</td>
            <td class="text-center '.($rowinfo['balance_percentage_5kg'] <= 0 && $rowinfo['target_5kg'] != '' ? 'table-success' : '').'">'.($rowinfo['balance_5kg'] <= 0 ? '' : ceil($rowinfo['avg_day_5kg'])).'</td>
            
            <!-- 12.5KG Columns -->
            <td class="text-center '.($rowinfo['balance_percentage_12_5kg'] <= 0 && $rowinfo['target_12_5kg'] != '' ? 'table-success' : '').'">'.$rowinfo['target_12_5kg'].'</td>
            <td class="text-center '.($rowinfo['balance_percentage_12_5kg'] <= 0 && $rowinfo['target_12_5kg'] != '' ? 'table-success' : '').'">'.$rowinfo['completed_12_5kg'].'</td>
            <td class="text-center '.($rowinfo['balance_percentage_12_5kg'] <= 0 && $rowinfo['target_12_5kg'] != '' ? 'table-success' : '').'">'.($rowinfo['balance_12_5kg'] <= 0 ? '' : ceil($rowinfo['balance_12_5kg'])).'</td>
            <td class="text-center '.($rowinfo['balance_percentage_12_5kg'] <= 0 && $rowinfo['target_12_5kg'] != '' ? 'table-success' : '').'">'.($rowinfo['balance_percentage_12_5kg'] <= 0 ? '0%' : ceil($rowinfo['balance_percentage_12_5kg']).'%').'</td>
            <td class="text-center '.($rowinfo['balance_percentage_12_5kg'] <= 0 && $rowinfo['target_12_5kg'] != '' ? 'table-success' : '').'">'.($rowinfo['balance_12_5kg'] <= 0 ? '0' : ceil($rowinfo['avg_day_12_5kg'])).'</td>
            
            <!-- 37.5KG Columns -->
            <td class="text-center '.($rowinfo['balance_percentage_37_5kg'] <= 0 && $rowinfo['target_37_5kg'] != '' ? 'table-success' : '').'">'.$rowinfo['target_37_5kg'].'</td>
            <td class="text-center '.($rowinfo['balance_percentage_37_5kg'] <= 0 && $rowinfo['target_37_5kg'] != '' ? 'table-success' : '').'">'.$rowinfo['completed_37_5kg'].'</td>
            <td class="text-center '.($rowinfo['balance_percentage_37_5kg'] <= 0 && $rowinfo['target_37_5kg'] != '' ? 'table-success' : '').'">'.($rowinfo['balance_37_5kg'] <= 0 ? '' : ceil($rowinfo['balance_37_5kg'])).'</td>
            <td class="text-center '.($rowinfo['balance_percentage_37_5kg'] <= 0 && $rowinfo['target_37_5kg'] != '' ? 'table-success' : '').'">'.($rowinfo['balance_percentage_37_5kg'] <= 0 ? '0%' : ceil($rowinfo['balance_percentage_37_5kg']).'%').'</td>
            <td class="text-center '.($rowinfo['balance_percentage_37_5kg'] <= 0 && $rowinfo['target_37_5kg'] != '' ? 'table-success' : '').'">'.($rowinfo['balance_37_5kg'] <= 0 ? '0' : ceil($rowinfo['avg_day_37_5kg'])).'</td>';
            
            // Render accessory product columns
            foreach ($accessories as $accessory) {
                $colName = $accessory['col_name'];
                $html .= renderAccessoryColumns($rowinfo, $colName);
            }

            $html .= '</tr>';
    }
} else {
    $html.='<tr><td colspan="'.$totalColumns.'">No data available</td></tr>';
}

echo $html;

// Helper function to render accessory product columns
function renderAccessoryColumns($row, $colName) {
    // Check if this accessory has data (target exists and is not null)
    $hasData = isset($row['target_'.$colName]) && $row['target_'.$colName] !== null && $row['target_'.$colName] !== '';
    
    if ($hasData) {
        $isComplete = ($row['balance_percentage_'.$colName] <= 0 && $row['target_'.$colName] != '');
        $successClass = $isComplete ? 'table-success' : '';
        
        return '
            <td class="text-center '.$successClass.'">'.$row['target_'.$colName].'</td>
            <td class="text-center '.$successClass.'">'.$row['completed_'.$colName].'</td>
            <td class="text-center '.$successClass.'">'.($row['balance_'.$colName] > 0 ? ceil($row['balance_'.$colName]) : '').'</td>
            <td class="text-center '.$successClass.'">'.($row['balance_percentage_'.$colName] <= 0 ? '0%' : ceil($row['balance_percentage_'.$colName]).'%').'</td>
            <td class="text-center '.$successClass.'">'.($row['balance_'.$colName] > 0 ? ceil($row['avg_day_'.$colName]) : '0').'</td>';
    } else {
        // Return empty columns if no data exists for this accessory
        return '
            <td class="text-center">-</td>
            <td class="text-center">-</td>
            <td class="text-center">-</td>
            <td class="text-center">-</td>
            <td class="text-center">-</td>';
    }
}