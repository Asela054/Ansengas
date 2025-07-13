<?php 
require_once('../connection/db.php');

$today = date('Y-m-d');
$mainarray = array();
$products = [];
$vehicleSequence = []; // Initialize vehicle sequence tracker

// Get gas products (category 1)
$gasProducts = $conn->query("SELECT idtbl_product, product_name FROM tbl_product 
                           WHERE tbl_product_category_idtbl_product_category = 1 
                           AND status = 1 ORDER BY orderlevel");
while ($product = $gasProducts->fetch_assoc()) {
    $products[] = $product;
}

// Get accessories (category 2)
$accessories = $conn->query("SELECT idtbl_product, product_name FROM tbl_product 
                            WHERE tbl_product_category_idtbl_product_category = 2 
                            AND status = 1");
while ($accessory = $accessories->fetch_assoc()) {
    $products[] = $accessory;
}

$sql = "SET @sql = NULL;
        -- Get gas products (category 1) ordered by orderlevel
        SELECT GROUP_CONCAT(DISTINCT 
            CONCAT('SUM(CASE WHEN p.idtbl_product = ', idtbl_product, 
            ' THEN vld.loadqty ELSE 0 END) AS `', REPLACE(product_name, '`', '``'), '_Stock`, ',
            'SUM(CASE WHEN p.idtbl_product = ', idtbl_product, 
            ' THEN vld.loadqty - vld.qty ELSE 0 END) AS `', 
            REPLACE(product_name, '`', '``'), '_Sale`')
            ORDER BY orderlevel SEPARATOR ', '
        ) INTO @gas_columns
        FROM tbl_product 
        WHERE status = 1 AND tbl_product_category_idtbl_product_category = 1;
        
        -- Get accessories (category 2)
        SELECT GROUP_CONCAT(DISTINCT 
            CONCAT('SUM(CASE WHEN p.idtbl_product = ', idtbl_product, 
            ' THEN vld.loadqty ELSE 0 END) AS `', REPLACE(product_name, '`', '``'), '_Stock`, ',
            'SUM(CASE WHEN p.idtbl_product = ', idtbl_product, 
            ' THEN vld.loadqty - vld.qty ELSE 0 END) AS `', 
            REPLACE(product_name, '`', '``'), '_Sale`')
            SEPARATOR ', '
        ) INTO @accessory_columns
        FROM tbl_product 
        WHERE status = 1 AND tbl_product_category_idtbl_product_category = 2;
        
        SET @sql = CONCAT('
        SELECT
            vl.idtbl_vehicle_load,
            v.vehicleno,
            vl.unloadstatus,
            ', IFNULL(@gas_columns, ''), 
            IF(@gas_columns IS NOT NULL AND @accessory_columns IS NOT NULL, ', ', ''),
            IFNULL(@accessory_columns, ''), ',
            SUM(vld.loadqty) AS `Total_Stock`,
            SUM(vld.loadqty - vld.qty) AS `Total_Sale`
        FROM
            tbl_vehicle_load_detail vld
        JOIN
            tbl_product p ON p.idtbl_product = vld.tbl_product_idtbl_product
        JOIN
            tbl_vehicle_load vl ON vl.idtbl_vehicle_load = vld.tbl_vehicle_load_idtbl_vehicle_load
        JOIN
            tbl_vehicle v ON v.idtbl_vehicle = vl.lorryid
        WHERE
            DATE(vl.date) = CURDATE()
            AND vl.approvestatus = 1
            AND vl.status = 1
        GROUP BY
            v.vehicleno, vl.idtbl_vehicle_load
        ORDER BY
            v.vehicleno;
        ');
        
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt";

if ($conn->multi_query($sql)) {
    do {
        if ($result = $conn->store_result()) {
            while ($row = $result->fetch_assoc()) {
                $obj = new stdClass();
                $obj->loadID = $row['idtbl_vehicle_load'];
                
                // Parse vehicle number to get base and sequence
                $vehicleNumber = $row['vehicleno'];
                $baseVehicle = $vehicleNumber;
                $currentSeq = 1;
                
                // Check if vehicle number already has a sequence number
                if (preg_match('/^(.*?)(?:\s*\((\d+)\))?$/', $vehicleNumber, $matches)) {
                    $baseVehicle = trim($matches[1]);
                    $currentSeq = isset($matches[2]) ? (int)$matches[2] : 1;
                }
                
                // Track sequence numbers for each base vehicle
                if (!isset($vehicleSequence[$baseVehicle])) {
                    $vehicleSequence[$baseVehicle] = $currentSeq;
                } else {
                    // Only increment if this is a new load for the same base vehicle
                    if ($currentSeq <= $vehicleSequence[$baseVehicle]) {
                        $vehicleSequence[$baseVehicle]++;
                    }
                    $currentSeq = $vehicleSequence[$baseVehicle];
                }
                
                $obj->loadVehicle = $baseVehicle;
                $obj->feqno = $currentSeq;
                $obj->unloadstatus = $row['unloadstatus'];
                
                $obj->products = [];
                foreach ($products as $product) {
                    $colName = $product['product_name'];
                    $productObj = new stdClass();
                    $productObj->stock = $row[$colName.'_Stock'] ?? 0;
                    $productObj->sale = $row[$colName.'_Sale'] ?? 0;
                    $obj->products[$product['product_name']] = $productObj;
                }
                
                $obj->total_stock = $row['Total_Stock'];
                $obj->total_sale = $row['Total_Sale'];
                
                array_push($mainarray, $obj);
            }
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
} else {
    die("Query failed: " . $conn->error);
}

// Initialize totals array
$totals = [
    'stock' => [],
    'sale' => []
];

// Initialize all product totals to 0
foreach ($products as $product) {
    $totals['stock'][$product['product_name']] = 0;
    $totals['sale'][$product['product_name']] = 0;
    $totals['balance'][$product['product_name']] = 0;
}

$html = '';
foreach($mainarray as $rowdatalist) {
    $html .= '<tr class="';
    if($rowdatalist->unloadstatus == 1) {
        $html .= 'table-success';
    }
    $html .= '">';
    $html .= '<td>' . $rowdatalist->loadVehicle . ' (' . $rowdatalist->feqno . ')</td>';
    
    foreach($rowdatalist->products as $productName => $productlist) {
        $productstock = 0;
        $productsale = 0;

        if($productlist->stock > 0 && $rowdatalist->unloadstatus == 0) {
            $productstock = $productlist->stock;
            $totals['stock'][$productName] += $productlist->stock;
        }
        if($productlist->sale > 0) {
            $productsale = $productlist->sale;
            $totals['sale'][$productName] += $productlist->sale;
        }

        $balanceqty = ($productstock - $productsale) > 0 ? ($productstock - $productsale) : 0;
        $totals['balance'][$productName] += $balanceqty;
        
        // Format the display values
        $display_stock = ($rowdatalist->unloadstatus == 0 && $productstock > 0) ? $productstock : '-';
        $display_sale = $productsale > 0 ? $productsale : '-';
        $display_balance = ($rowdatalist->unloadstatus == 0 && $balanceqty > 0) ? $balanceqty : '-';

        $html .= '<td class="text-center">' . $display_stock . '</td>
                 <td class="text-center">' . $display_sale . '</td>
                 <td class="text-center">' . $display_balance . '</td>';
    }
    $html .= '</tr>';
}

// Add footer row with totals
$html .= '<tr class=""><td><strong>Total</strong></td>';
foreach ($products as $product) {
    $productName = $product['product_name'];
    $totalStock = $totals['stock'][$productName] > 0 ? $totals['stock'][$productName] : '-';
    $totalSale = $totals['sale'][$productName] > 0 ? $totals['sale'][$productName] : '-';
    // $balance = ($totals['stock'][$productName] - $totals['sale'][$productName]) > 0 ? ($totals['stock'][$productName] - $totals['sale'][$productName]) : '-';
    $balance = $totals['balance'][$productName] > 0 ? $totals['balance'][$productName] : '-';
    
    $html .= '<td class="text-center"><strong>' . $totalStock . '</strong></td>
              <td class="text-center"><strong>' . $totalSale . '</strong></td>
              <td class="text-center"><strong>' . $balance . '</strong></td>';
}
$html .= '</tr>';

echo $html;
?>