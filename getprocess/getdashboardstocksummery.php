<?php 
// require_once('../connection/db.php');

// $arrayproduct = array();

// $sql = "SELECT `idtbl_product` FROM `tbl_product` WHERE `status` = 1";
// $result = $conn->query($sql);

// if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         $arrayproduct[] = $row['idtbl_product'];
//     }
// }

// $qtyarray = array();
// foreach ($arrayproduct as $rowproduct) {
//     $sqlqty = "SELECT p.product_name, p.orderlevel, s.fullqty, s.emptyqty 
//                FROM tbl_stock s 
//                INNER JOIN tbl_product p ON s.tbl_product_idtbl_product = p.idtbl_product 
//                WHERE s.status = 1 AND s.tbl_product_idtbl_product = '$rowproduct'";
//     $resultqty = $conn->query($sqlqty);
//     $rowqty = $resultqty->fetch_assoc();

//     $obj = new stdClass();
//     $obj->gass = $rowqty['product_name']; 
//     $obj->orderlevel = $rowqty['orderlevel'];
//     $obj->fullqty = $rowqty['fullqty']; 
//     $obj->emptyqty = $rowqty['emptyqty'];
    
//     array_push($qtyarray, $obj);
// }

// usort($qtyarray, function($a, $b) {
//     return $a->orderlevel - $b->orderlevel;
// });

// $html='';
// $nettotalfull=0;
// $nettotalempty=0;
// $nettotaldamage=0;
// foreach($qtyarray as $rowqty) {
//     $nettotalfull+=$rowqty->fullqty;
//     $nettotalempty+=$rowqty->emptyqty;
//     $nettotaldamage+=0;
//     $html.='<tr>
//         <td>'.$rowqty->gass.'</td>
//         <td class="text-center">'.$rowqty->fullqty.'</td>
//         <td class="text-center">'.$rowqty->emptyqty.'</td>
//         <td class="text-center">0</td>
//     </tr>';
// }

// $html.='<tr>
//     <th>Total</th>
//     <th class="text-center">'.$nettotalfull.'</th>
//     <th class="text-center">'.$nettotalempty.'</th>
//     <th class="text-center">'.$nettotaldamage.'</th>
// </tr>';
// echo $html;
require_once('../connection/db.php');

// Get all active products
$arrayproduct = array();
$sql = "SELECT `idtbl_product`, `product_name`, `orderlevel` FROM `tbl_product` WHERE `status` = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $arrayproduct[$row['idtbl_product']] = [
            'product_name' => $row['product_name'],
            'orderlevel' => $row['orderlevel'],
            'fullqty' => 0,
            'emptyqty' => 0
        ];
    }
}

// 1. Get yard stock
foreach ($arrayproduct as $productId => $product) {
    $sqlqty = "SELECT s.fullqty, s.emptyqty 
               FROM tbl_stock s 
               WHERE s.status = 1 AND s.tbl_product_idtbl_product = '$productId'";
    $resultqty = $conn->query($sqlqty);
    
    if ($resultqty->num_rows > 0) {
        $rowqty = $resultqty->fetch_assoc();
        $arrayproduct[$productId]['fullqty'] = $rowqty['fullqty'];
        $arrayproduct[$productId]['emptyqty'] = $rowqty['emptyqty'];
    }
}

// 2. Get today's vehicle load data (unloaded vehicles)
$today = date('Y-m-d');
$sqlVehicleLoad = "SELECT v.idtbl_vehicle_load, v.lorryid, v.unloadstatus
                   FROM tbl_vehicle_load v
                   WHERE v.date = '$today' AND v.unloadstatus = 0 AND v.status = 1";
$resultVehicleLoad = $conn->query($sqlVehicleLoad);

while ($rowVehicle = $resultVehicleLoad->fetch_assoc()) {
    $loadId = $rowVehicle['idtbl_vehicle_load'];
    
    // Get load details for this vehicle
    $sqlLoadDetails = "SELECT d.tbl_product_idtbl_product, d.loadqty, d.qty, d.emptyqty
                       FROM tbl_vehicle_load_detail d
                       WHERE d.tbl_vehicle_load_idtbl_vehicle_load = '$loadId' AND d.status = 1";
    $resultLoadDetails = $conn->query($sqlLoadDetails);
    
    while ($rowDetail = $resultLoadDetails->fetch_assoc()) {
        $productId = $rowDetail['tbl_product_idtbl_product'];
        
        if (isset($arrayproduct[$productId])) {
            // Calculate remaining full cylinders (loadqty - qty)
            // $remainingFull = $rowDetail['loadqty'] - $rowDetail['qty'];
            $remainingFull = $rowDetail['qty'];
            
            // Add to the product totals
            $arrayproduct[$productId]['fullqty'] += $remainingFull;
            $arrayproduct[$productId]['emptyqty'] += $rowDetail['emptyqty'];
        }
    }
}

// Prepare data for display
$qtyarray = array();
foreach ($arrayproduct as $productId => $product) {
    $obj = new stdClass();
    $obj->gass = $product['product_name']; 
    $obj->orderlevel = $product['orderlevel'];
    $obj->fullqty = $product['fullqty']; 
    $obj->emptyqty = $product['emptyqty'];
    
    array_push($qtyarray, $obj);
}

// Sort by order level
usort($qtyarray, function($a, $b) {
    return $a->orderlevel - $b->orderlevel;
});

// Generate HTML
$html = '';
$nettotalfull = 0;
$nettotalempty = 0;
$nettotaldamage = 0;

foreach($qtyarray as $rowqty) {
    $nettotalfull += $rowqty->fullqty;
    $nettotalempty += $rowqty->emptyqty;
    
    $html .= '<tr>
        <td>'.$rowqty->gass.'</td>
        <td class="text-center">'.$rowqty->fullqty.'</td>
        <td class="text-center">'.$rowqty->emptyqty.'</td>
        <td class="text-center">0</td>
    </tr>';
}

// $html .= '<tr>
//     <th>Total</th>
//     <th class="text-center">'.$nettotalfull.'</th>
//     <th class="text-center">'.$nettotalempty.'</th>
//     <th class="text-center">'.$nettotaldamage.'</th>
// </tr>';

echo $html;