<?php 
require_once('../connection/db.php');

$customer=$_POST['customer'];

// $sql="SELECT 
//     c.name AS Customer,
//     -- 2KG (Product ID 6)
//     SUM(CASE WHEN d.tbl_product_idtbl_product = 6 THEN d.trustqty ELSE 0 END) AS 'Trust_2KG',
//     SUM(CASE WHEN d.tbl_product_idtbl_product = 6 THEN d.trustreturnqty ELSE 0 END) AS 'Trust_Return_2KG',
//     (SUM(CASE WHEN d.tbl_product_idtbl_product = 6 THEN d.trustqty ELSE 0 END) - 
//      SUM(CASE WHEN d.tbl_product_idtbl_product = 6 THEN d.trustreturnqty ELSE 0 END)) AS 'Balance_2KG',
    
//     -- 5KG (Product ID 4)
//     SUM(CASE WHEN d.tbl_product_idtbl_product = 4 THEN d.trustqty ELSE 0 END) AS 'Trust_5KG',
//     SUM(CASE WHEN d.tbl_product_idtbl_product = 4 THEN d.trustreturnqty ELSE 0 END) AS 'Trust_Return_5KG',
//     (SUM(CASE WHEN d.tbl_product_idtbl_product = 4 THEN d.trustqty ELSE 0 END) - 
//      SUM(CASE WHEN d.tbl_product_idtbl_product = 4 THEN d.trustreturnqty ELSE 0 END)) AS 'Balance_5KG',
    
//     -- 12.5KG (Product ID 1)
//     SUM(CASE WHEN d.tbl_product_idtbl_product = 1 THEN d.trustqty ELSE 0 END) AS 'Trust_12_5KG',
//     SUM(CASE WHEN d.tbl_product_idtbl_product = 1 THEN d.trustreturnqty ELSE 0 END) AS 'Trust_Return_12_5KG',
//     (SUM(CASE WHEN d.tbl_product_idtbl_product = 1 THEN d.trustqty ELSE 0 END) - 
//      SUM(CASE WHEN d.tbl_product_idtbl_product = 1 THEN d.trustreturnqty ELSE 0 END)) AS 'Balance_12_5KG',
    
//     -- 37.5KG (Product ID 2)
//     SUM(CASE WHEN d.tbl_product_idtbl_product = 2 THEN d.trustqty ELSE 0 END) AS 'Trust_37_5KG',
//     SUM(CASE WHEN d.tbl_product_idtbl_product = 2 THEN d.trustreturnqty ELSE 0 END) AS 'Trust_Return_37_5KG',
//     (SUM(CASE WHEN d.tbl_product_idtbl_product = 2 THEN d.trustqty ELSE 0 END) - 
//      SUM(CASE WHEN d.tbl_product_idtbl_product = 2 THEN d.trustreturnqty ELSE 0 END)) AS 'Balance_37_5KG'
// FROM 
//     tbl_customer c
// INNER JOIN 
//     tbl_invoice i ON c.idtbl_customer = i.tbl_customer_idtbl_customer
// INNER JOIN 
//     tbl_invoice_detail d ON i.idtbl_invoice = d.tbl_invoice_idtbl_invoice
// WHERE 
//     i.status = 1
//     AND d.tbl_product_idtbl_product IN (1, 2, 4, 6)
//     AND (d.trustqty > 0 OR d.trustreturnqty > 0)
//     AND d.trustqty != d.trustreturnqty";
// if($customer != "") {
//     $sql.=" AND c.idtbl_customer = '$customer'";
// }
// $sql.=" GROUP BY 
//     c.idtbl_customer
// HAVING 
//     -- Only include customers who have at least one product with unequal trust quantities
//     SUM(CASE WHEN d.tbl_product_idtbl_product = 6 AND Balance_2KG THEN 1 ELSE 0 END) > 0 OR
//     SUM(CASE WHEN d.tbl_product_idtbl_product = 4 AND Balance_5KG THEN 1 ELSE 0 END) > 0 OR
//     SUM(CASE WHEN d.tbl_product_idtbl_product = 1 AND Balance_12_5KG THEN 1 ELSE 0 END) > 0 OR
//     SUM(CASE WHEN d.tbl_product_idtbl_product = 2 AND Balance_37_5KG THEN 1 ELSE 0 END) > 0
// ORDER BY 
//     c.name";

$sql="SELECT 
    c.name AS Customer,
    -- 2KG (Product ID 6)
    SUM(CASE WHEN d.tbl_product_idtbl_product = 6 THEN d.trustqty ELSE 0 END) AS 'Trust_2KG',
    SUM(CASE WHEN d.tbl_product_idtbl_product = 6 THEN d.trustreturnqty ELSE 0 END) AS 'Trust_Return_2KG',
    (SUM(CASE WHEN d.tbl_product_idtbl_product = 6 THEN d.trustqty ELSE 0 END) - 
     SUM(CASE WHEN d.tbl_product_idtbl_product = 6 THEN d.trustreturnqty ELSE 0 END)) AS 'Balance_2KG',
    
    -- 5KG (Product ID 4)
    SUM(CASE WHEN d.tbl_product_idtbl_product = 4 THEN d.trustqty ELSE 0 END) AS 'Trust_5KG',
    SUM(CASE WHEN d.tbl_product_idtbl_product = 4 THEN d.trustreturnqty ELSE 0 END) AS 'Trust_Return_5KG',
    (SUM(CASE WHEN d.tbl_product_idtbl_product = 4 THEN d.trustqty ELSE 0 END) - 
     SUM(CASE WHEN d.tbl_product_idtbl_product = 4 THEN d.trustreturnqty ELSE 0 END)) AS 'Balance_5KG',
    
    -- 12.5KG (Product ID 1)
    SUM(CASE WHEN d.tbl_product_idtbl_product = 1 THEN d.trustqty ELSE 0 END) AS 'Trust_12_5KG',
    SUM(CASE WHEN d.tbl_product_idtbl_product = 1 THEN d.trustreturnqty ELSE 0 END) AS 'Trust_Return_12_5KG',
    (SUM(CASE WHEN d.tbl_product_idtbl_product = 1 THEN d.trustqty ELSE 0 END) - 
     SUM(CASE WHEN d.tbl_product_idtbl_product = 1 THEN d.trustreturnqty ELSE 0 END)) AS 'Balance_12_5KG',
    
    -- 37.5KG (Product ID 2)
    SUM(CASE WHEN d.tbl_product_idtbl_product = 2 THEN d.trustqty ELSE 0 END) AS 'Trust_37_5KG',
    SUM(CASE WHEN d.tbl_product_idtbl_product = 2 THEN d.trustreturnqty ELSE 0 END) AS 'Trust_Return_37_5KG',
    (SUM(CASE WHEN d.tbl_product_idtbl_product = 2 THEN d.trustqty ELSE 0 END) - 
     SUM(CASE WHEN d.tbl_product_idtbl_product = 2 THEN d.trustreturnqty ELSE 0 END)) AS 'Balance_37_5KG'
FROM 
    tbl_customer c
INNER JOIN 
    tbl_invoice i ON c.idtbl_customer = i.tbl_customer_idtbl_customer
INNER JOIN 
    tbl_invoice_detail d ON i.idtbl_invoice = d.tbl_invoice_idtbl_invoice
WHERE 
    i.status = 1
    AND d.tbl_product_idtbl_product IN (1, 2, 4, 6)
    AND (d.trustqty > 0 OR d.trustreturnqty > 0)
    AND d.trustqty != d.trustreturnqty
";
if($customer != "") {
    $sql.=" AND c.idtbl_customer = '$customer'";
}
$sql.=" GROUP BY 
    c.idtbl_customer
HAVING 
    -- Only include customers who have at least one non-zero balance
    (SUM(CASE WHEN d.tbl_product_idtbl_product = 6 THEN d.trustqty ELSE 0 END) - 
     SUM(CASE WHEN d.tbl_product_idtbl_product = 6 THEN d.trustreturnqty ELSE 0 END)) != 0 OR
    (SUM(CASE WHEN d.tbl_product_idtbl_product = 4 THEN d.trustqty ELSE 0 END) - 
     SUM(CASE WHEN d.tbl_product_idtbl_product = 4 THEN d.trustreturnqty ELSE 0 END)) != 0 OR
    (SUM(CASE WHEN d.tbl_product_idtbl_product = 1 THEN d.trustqty ELSE 0 END) - 
     SUM(CASE WHEN d.tbl_product_idtbl_product = 1 THEN d.trustreturnqty ELSE 0 END)) != 0 OR
    (SUM(CASE WHEN d.tbl_product_idtbl_product = 2 THEN d.trustqty ELSE 0 END) - 
     SUM(CASE WHEN d.tbl_product_idtbl_product = 2 THEN d.trustreturnqty ELSE 0 END)) != 0
ORDER BY 
    c.name";
    // echo $sql;
$result = $conn->query($sql);

$totals = array(
    'Trust_2KG' => 0,
    'Trust_Return_2KG' => 0,
    'Balance_2KG' => 0,
    'Trust_5KG' => 0,
    'Trust_Return_5KG' => 0,
    'Balance_5KG' => 0,
    'Trust_12_5KG' => 0,
    'Trust_Return_12_5KG' => 0,
    'Balance_12_5KG' => 0,
    'Trust_37_5KG' => 0,
    'Trust_Return_37_5KG' => 0,
    'Balance_37_5KG' => 0
);

$html='';
    
if ($result->num_rows > 0) {
    $html.='<div class="scrollbar pb-3 table-container" id="style-2">
    <table class="table table-striped table-bordered table-sm small sticky-header" id="trustbreakdownreport">
        <thead class="thead-dark">
            <tr>
                <th nowrap rowspan="2">Customer</th>
                <th nowrap colspan="3" class="text-center">2KG</th>
                <th nowrap colspan="3" class="text-center">5KG</th>
                <th nowrap colspan="3" class="text-center">12.5KG</th>
                <th nowrap colspan="3" class="text-center">37.5KG</th>
            </tr>
            <tr>                    
                <!-- 2KG Columns -->
                <th class="text-center">Trust</th>
                <th class="text-center">Trust Return</th>
                <th class="text-center">Balance</th>
                
                <!-- 5KG Columns -->
                <th class="text-center">Trust</th>
                <th class="text-center">Trust Return</th>
                <th class="text-center">Balance</th>
                
                <!-- 12.5KG Columns -->
                <th class="text-center">Trust</th>
                <th class="text-center">Trust Return</th>
                <th class="text-center">Balance</th>
                
                <!-- 37.5KG Columns -->
                <th class="text-center">Trust</th>
                <th class="text-center">Trust Return</th>
                <th class="text-center">Balance</th>
            </tr>
        </thead>
        <tbody>';
        while ($rowinfo = $result->fetch_assoc()) {
            // Add to totals
            foreach ($totals as $key => $value) {
                $totals[$key] += $rowinfo[$key];
            }

            $html.='<tr>
                <td nowrap>'.$rowinfo['Customer'].'</td>
                
                <!-- 2KG Columns -->
                <td class="text-center">'.($rowinfo['Trust_2KG'] <= 0 ? '' : ceil($rowinfo['Trust_2KG'])).'</td>
                <td class="text-center">'.($rowinfo['Trust_Return_2KG'] <= 0 ? '' : ceil($rowinfo['Trust_Return_2KG'])).'</td>
                <td class="text-center">'.($rowinfo['Balance_2KG'] <= 0 ? '' : ceil($rowinfo['Balance_2KG'])).'</td>
                
                <!-- 5KG Columns -->
                <td class="text-center">'.($rowinfo['Trust_5KG'] <= 0 ? '' : ceil($rowinfo['Trust_5KG'])).'</td>
                <td class="text-center">'.($rowinfo['Trust_Return_5KG'] <= 0 ? '' : ceil($rowinfo['Trust_Return_5KG'])).'</td>
                <td class="text-center">'.($rowinfo['Balance_5KG'] <= 0 ? '' : ceil($rowinfo['Balance_5KG'])).'</td>
                
                <!-- 12.5KG Columns -->
                <td class="text-center">'.($rowinfo['Trust_12_5KG'] <= 0 ? '' : ceil($rowinfo['Trust_12_5KG'])).'</td>
                <td class="text-center">'.($rowinfo['Trust_Return_12_5KG'] <= 0 ? '' : ceil($rowinfo['Trust_Return_12_5KG'])).'</td>
                <td class="text-center">'.($rowinfo['Balance_12_5KG'] <= 0 ? '' : ceil($rowinfo['Balance_12_5KG'])).'</td>
                
                <!-- 37.5KG Columns -->
                <td class="text-center">'.($rowinfo['Trust_37_5KG'] <= 0 ? '' : ceil($rowinfo['Trust_37_5KG'])).'</td>
                <td class="text-center">'.($rowinfo['Trust_Return_37_5KG'] <= 0 ? '' : ceil($rowinfo['Trust_Return_37_5KG'])).'</td>
                <td class="text-center">'.($rowinfo['Balance_37_5KG'] <= 0 ? '' : ceil($rowinfo['Balance_37_5KG'])).'</td>
            </tr>';
        }
        // Add footer row with totals
        $html.='</tbody>
        <tfoot class="table-dark">
            <tr>
                <th nowrap>Total</th>
                
                <!-- 2KG Totals -->
                <th class="text-center">'.($totals['Trust_2KG'] <= 0 ? '' : ceil($totals['Trust_2KG'])).'</th>
                <th class="text-center">'.($totals['Trust_Return_2KG'] <= 0 ? '' : ceil($totals['Trust_Return_2KG'])).'</th>
                <th class="text-center">'.($totals['Balance_2KG'] == 0 ? '' : ceil($totals['Balance_2KG'])).'</th>
                
                <!-- 5KG Totals -->
                <th class="text-center">'.($totals['Trust_5KG'] <= 0 ? '' : ceil($totals['Trust_5KG'])).'</th>
                <th class="text-center">'.($totals['Trust_Return_5KG'] <= 0 ? '' : ceil($totals['Trust_Return_5KG'])).'</th>
                <th class="text-center">'.($totals['Balance_5KG'] == 0 ? '' : ceil($totals['Balance_5KG'])).'</th>
                
                <!-- 12.5KG Totals -->
                <th class="text-center">'.($totals['Trust_12_5KG'] <= 0 ? '' : ceil($totals['Trust_12_5KG'])).'</th>
                <th class="text-center">'.($totals['Trust_Return_12_5KG'] <= 0 ? '' : ceil($totals['Trust_Return_12_5KG'])).'</th>
                <th class="text-center">'.($totals['Balance_12_5KG'] == 0 ? '' : ceil($totals['Balance_12_5KG'])).'</th>
                
                <!-- 37.5KG Totals -->
                <th class="text-center">'.($totals['Trust_37_5KG'] <= 0 ? '' : ceil($totals['Trust_37_5KG'])).'</th>
                <th class="text-center">'.($totals['Trust_Return_37_5KG'] <= 0 ? '' : ceil($totals['Trust_Return_37_5KG'])).'</th>
                <th class="text-center">'.($totals['Balance_37_5KG'] == 0 ? '' : ceil($totals['Balance_37_5KG'])).'</th>
            </tr>
        </tfoot>
    </table></div>';
}
echo $html;