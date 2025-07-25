<?php
require_once('../connection/db.php');

// Get parameters from POST with fallback defaults
$monthFrom = $_POST['validfrom'] ?? date('Y-m');
$monthTo = $_POST['validto'] ?? date('Y-m');
$cusType = $_POST['cusType'];
$dataselector = $_POST['dataselector'];
$typeSelector = $_POST['typeSelector'];

// First, get all accessory products
$accessoryProducts = $conn->query("
    SELECT idtbl_product, product_name, size 
    FROM tbl_product 
    WHERE status = 1 AND tbl_product_category_idtbl_product_category = 2
    ORDER BY product_name
");

$accessoryList = [];
while ($row = $accessoryProducts->fetch_assoc()) {
    $accessoryList[] = $row;
}

// Convert month inputs to proper date format
$validfrom = date('Y-m-01', strtotime($monthFrom));
$validto = date('Y-m-t', strtotime($monthTo));

// Get current month dates
$currentMonthStart = date('Y-m-01');
$currentMonthEnd = date('Y-m-t');
$currentMonth = date('Y-m');

// Create selected period in descending order
$selectedPeriodMonths = [];
$start = new DateTime($validfrom);
$end = new DateTime($validto);
$interval = new DateInterval('P1M');

if ($start <= $end) {
    $period = new DatePeriod($start, $interval, $end);
    foreach ($period as $dt) {
        $monthYear = $dt->format("Y-m");
        if ($monthYear !== $currentMonth) {
            $selectedPeriodMonths[] = $monthYear;
        }
    }
    $selectedPeriodMonths = array_reverse($selectedPeriodMonths);
}

// Combine all months - current month first, then selected period in descending order
$allMonths = array_merge([$currentMonth], $selectedPeriodMonths);

// Generate headers
$monthHeaders = [];
$monthSubHeaders = [];
foreach ($allMonths as $monthYear) {
    // 4 gas products + number of accessories
    $colspan = 4 + count($accessoryList);
    $monthHeaders[] = "<th colspan='{$colspan}' class='text-center'>" . $monthYear . "</th>";
    
    // Fixed gas product headers
    $monthSubHeaders[] = "<th nowrap class='text-center'>2KG</th><th nowrap class='text-center'>5KG</th><th nowrap class='text-center'>12.5KG</th><th nowrap class='text-center'>37.5KG</th>";
    
    // Dynamic accessory headers
    foreach ($accessoryList as $product) {
        $monthSubHeaders[] = "<th nowrap class='text-center'>" . htmlspecialchars($product['product_name']) . "</th>";
    }
}

// Calculate the overall date range needed
$minDate = min($currentMonthStart, $validfrom);
$maxDate = max($currentMonthEnd, $validto);

// Build the SQL query
$sql = "SELECT 
    c.idtbl_customer,
    c.name AS Customer,
    c.type AS CustomerType,
    c.`phone`, 
    c.`address`, 
    a.`area`";

// Add dynamic columns for gas products (fixed order)
foreach ($allMonths as $monthYear) {
    foreach (['6' => '2KG', '4' => '5KG', '1' => '12.5KG', '2' => '37.5KG'] as $productId => $size) {
        $sql .= ",
        SUM(CASE WHEN DATE_FORMAT(i.date, '%Y-%m') = '$monthYear' AND p.idtbl_product = '$productId' THEN (COALESCE(id.newqty, 0) + COALESCE(id.refillqty, 0) + COALESCE(id.trustqty, 0)) ELSE 0 END) AS '{$monthYear}_{$size}'";
    }
}

// Add dynamic columns for accessory products
if (!empty($accessoryList)) {
    foreach ($allMonths as $monthYear) {
        foreach ($accessoryList as $product) {
            $productKey = strtolower(str_replace([' ', '.', '-', '/'], '_', $product['product_name'] . '_' . $product['size']));
            $sql .= ",
            SUM(CASE WHEN DATE_FORMAT(i.date, '%Y-%m') = '$monthYear' AND p.idtbl_product = '{$product['idtbl_product']}' THEN (COALESCE(id.newqty, 0) + COALESCE(id.refillqty, 0) + COALESCE(id.trustqty, 0)) ELSE 0 END) AS '{$monthYear}_{$productKey}'";
        }
    }
}

$sql .= "
FROM 
    tbl_customer c
LEFT JOIN 
    tbl_invoice i ON c.idtbl_customer = i.tbl_customer_idtbl_customer
    AND i.date BETWEEN '" . $conn->real_escape_string($minDate) . "' AND '" . $conn->real_escape_string($maxDate) . "'
    AND i.status = 1
LEFT JOIN
    tbl_area a ON c.tbl_area_idtbl_area = a.idtbl_area
LEFT JOIN 
    tbl_invoice_detail id ON i.idtbl_invoice = id.tbl_invoice_idtbl_invoice
LEFT JOIN 
    tbl_product p ON id.tbl_product_idtbl_product = p.idtbl_product";

if($typeSelector==2) {
    $sql .= " LEFT JOIN tbl_customerwise_salesrep cs ON cs.tbl_customer_idtbl_customer = c.idtbl_customer AND cs.tbl_product_idtbl_product = id.tbl_product_idtbl_product";
} 
if($typeSelector==3 || $typeSelector==4) {
    $sql .= " LEFT JOIN tbl_vehicle_load vl ON vl.idtbl_vehicle_load = i.tbl_vehicle_load_idtbl_vehicle_load";
} 

$sql .= "
WHERE 
    1=1";

if($typeSelector==1 && !empty($dataselector)){
    $sql .= " AND c.idtbl_customer = '$dataselector'";
}
if ($typeSelector==2 && !empty($cusType)) {
    $sql .= " AND c.type = '$cusType'";
}
if ($typeSelector==2 && !empty($dataselector)) {
    $sql .= " AND cs.tbl_employee_idtbl_employee = '$dataselector'";
}
if ($typeSelector==3 && !empty($dataselector)) {
    $sql .= " AND vl.lorryid = '$dataselector'";
}
if ($typeSelector==4 && !empty($dataselector)) {
    $sql .= " AND vl.driverid = '$dataselector'";
}
if ($typeSelector==5 && !empty($dataselector)) {
    $sql .= " AND c.tbl_area_idtbl_area = '$dataselector'";
}

$sql .= "
GROUP BY 
    c.idtbl_customer, c.name, c.type
ORDER BY 
    c.tbl_area_idtbl_area ASC";

// Execute the query
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Start HTML output
$html = '<div class="scrollbar pb-3 table-container" id="style-2" style="width:100%">';
$html .= '<table class="table table-striped table-bordered table-sm small sticky-header" id="customerProductReport">';
$html .= '<thead class="thead-dark">';
$html .= '<tr>';
$html .= '<th rowspan="2" class="align-top">Area</th>';
$html .= '<th rowspan="2" class="align-top">Customer</th>';
$html .= '<th rowspan="2" class="align-top">Address</th>';
$html .= '<th rowspan="2" class="align-top">Telephone Number</th>';
$html .= implode('', $monthHeaders);
$html .= '</tr>';
$html .= '<tr>';
$html .= implode('', $monthSubHeaders);
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

if ($result->num_rows > 0) {
    // Initialize grand totals
    $grandTotals = [];
    foreach ($allMonths as $monthYear) {
        $grandTotals[$monthYear] = ['2KG' => 0, '5KG' => 0, '12.5KG' => 0, '37.5KG' => 0];
        foreach ($accessoryList as $product) {
            $productKey = strtolower(str_replace([' ', '.', '-', '/'], '_', $product['product_name'] . '_' . $product['size']));
            $grandTotals[$monthYear][$productKey] = 0;
        }
    }

    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td nowrap>' . htmlspecialchars($row['area']) . '</td>';
        $html .= '<td nowrap>' . htmlspecialchars($row['Customer']) . '</td>';
        $html .= '<td nowrap>' . htmlspecialchars($row['address']) . '</td>';
        $html .= '<td nowrap>' . htmlspecialchars($row['phone']) . '</td>';
        
        foreach ($allMonths as $monthYear) {
            // Fixed gas products
            foreach (['2KG', '5KG', '12.5KG', '37.5KG'] as $size) {
                $value = $row["{$monthYear}_{$size}"] ?? 0;
                $html .= '<td class="text-center">' . ($value == 0 ? '-' : $value) . '</td>';
                $grandTotals[$monthYear][$size] += $value;
            }
            
            // Dynamic accessories
            foreach ($accessoryList as $product) {
                $productKey = strtolower(str_replace([' ', '.', '-', '/'], '_', $product['product_name'] . '_' . $product['size']));
                $value = $row["{$monthYear}_{$productKey}"] ?? 0;
                $html .= '<td class="text-center">' . ($value == 0 ? '-' : $value) . '</td>';
                $grandTotals[$monthYear][$productKey] += $value;
            }
        }
        
        $html .= '</tr>';
    }

    // Add grand totals row
    $html .= '<tr>';
    $html .= '<th colspan="4">Grand Total</th>';
    foreach ($allMonths as $monthYear) {
        // Gas product totals
        foreach (['2KG', '5KG', '12.5KG', '37.5KG'] as $size) {
            $html .= '<th class="text-center">' . $grandTotals[$monthYear][$size] . '</th>';
        }
        // Accessory totals
        foreach ($accessoryList as $product) {
            $productKey = strtolower(str_replace([' ', '.', '-', '/'], '_', $product['product_name'] . '_' . $product['size']));
            $html .= '<th class="text-center">' . $grandTotals[$monthYear][$productKey] . '</th>';
        }
    }
    $html .= '</tr>';
} else {
    $colspan = 4 + (count($allMonths) * (4 + count($accessoryList)));
    $html .= '<tr><th colspan="' . $colspan . '" class="text-center text-muted">No records found for the selected criteria</th></tr>';
}

$html .= '</tbody>';
$html .= '</table>';
$html .= '</div>';

echo $html;

// Close connection
$conn->close();
?>