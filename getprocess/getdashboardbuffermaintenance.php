<?php
require_once('../connection/db.php');

$sql = "SELECT 
    c.idtbl_customer AS 'Customer ID',
    c.name AS 'Customer',
    e.name AS 'Executive',
    a.area AS 'Area',
    
    -- 2KG Product (ID 6)
    MAX(CASE WHEN p.idtbl_product = '6' THEN cs.fullqty END) AS 'Required Full (2KG)',
    MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(cbd.fullqty, 0) END) AS 'Available Full (2KG)',
    (MAX(CASE WHEN p.idtbl_product = '6' THEN cs.fullqty END) - 
     MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(cbd.fullqty, 0) END)) AS 'Balance Full (2KG)',
    MAX(CASE WHEN p.idtbl_product = '6' THEN cs.emptyqty END) AS 'Required Empty (2KG)',
    MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(cbd.emptyqty, 0) END) AS 'Available Empty (2KG)',
    (MAX(CASE WHEN p.idtbl_product = '6' THEN cs.emptyqty END) - 
     MAX(CASE WHEN p.idtbl_product = '6' THEN IFNULL(cbd.emptyqty, 0) END)) AS 'Balance Empty (2KG)',
    
    -- 5KG Product (ID 4)
    MAX(CASE WHEN p.idtbl_product = '4' THEN cs.fullqty END) AS 'Required Full (5KG)',
    MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(cbd.fullqty, 0) END) AS 'Available Full (5KG)',
    (MAX(CASE WHEN p.idtbl_product = '4' THEN cs.fullqty END) - 
     MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(cbd.fullqty, 0) END)) AS 'Balance Full (5KG)',
    MAX(CASE WHEN p.idtbl_product = '4' THEN cs.emptyqty END) AS 'Required Empty (5KG)',
    MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(cbd.emptyqty, 0) END) AS 'Available Empty (5KG)',
    (MAX(CASE WHEN p.idtbl_product = '4' THEN cs.emptyqty END) - 
     MAX(CASE WHEN p.idtbl_product = '4' THEN IFNULL(cbd.emptyqty, 0) END)) AS 'Balance Empty (5KG)',
    
    -- 12.5KG Product (ID 1)
    MAX(CASE WHEN p.idtbl_product = '1' THEN cs.fullqty END) AS 'Required Full (12.5KG)',
    MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(cbd.fullqty, 0) END) AS 'Available Full (12.5KG)',
    (MAX(CASE WHEN p.idtbl_product = '1' THEN cs.fullqty END) - 
     MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(cbd.fullqty, 0) END)) AS 'Balance Full (12.5KG)',
    MAX(CASE WHEN p.idtbl_product = '1' THEN cs.emptyqty END) AS 'Required Empty (12.5KG)',
    MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(cbd.emptyqty, 0) END) AS 'Available Empty (12.5KG)',
    (MAX(CASE WHEN p.idtbl_product = '1' THEN cs.emptyqty END) - 
     MAX(CASE WHEN p.idtbl_product = '1' THEN IFNULL(cbd.emptyqty, 0) END)) AS 'Balance Empty (12.5KG)',
    
    -- 37.5KG Product (ID 2)
    MAX(CASE WHEN p.idtbl_product = '2' THEN cs.fullqty END) AS 'Required Full (37.5KG)',
    MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(cbd.fullqty, 0) END) AS 'Available Full (37.5KG)',
    (MAX(CASE WHEN p.idtbl_product = '2' THEN cs.fullqty END) - 
     MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(cbd.fullqty, 0) END)) AS 'Balance Full (37.5KG)',
    MAX(CASE WHEN p.idtbl_product = '2' THEN cs.emptyqty END) AS 'Required Empty (37.5KG)',
    MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(cbd.emptyqty, 0) END) AS 'Available Empty (37.5KG)',
    (MAX(CASE WHEN p.idtbl_product = '2' THEN cs.emptyqty END) - 
     MAX(CASE WHEN p.idtbl_product = '2' THEN IFNULL(cbd.emptyqty, 0) END)) AS 'Balance Empty (37.5KG)'
    
FROM 
    tbl_customer c
JOIN 
    tbl_customer_stock cs ON c.idtbl_customer = cs.tbl_customer_idtbl_customer
LEFT JOIN 
    tbl_customerwise_salesrep cws ON c.idtbl_customer = cws.tbl_customer_idtbl_customer
LEFT JOIN
    tbl_employee e ON cws.tbl_employee_idtbl_employee = e.idtbl_employee
LEFT JOIN
    tbl_area a ON c.tbl_area_idtbl_area = a.idtbl_area
LEFT JOIN 
    tbl_customer_buffer_stock cbs ON c.idtbl_customer = cbs.tbl_customer_idtbl_customer 
    AND DATE(cbs.date) = CURDATE()
LEFT JOIN 
    tbl_customer_buffer_stock_detail cbd ON cbs.idtbl_customer_buffer_stock = cbd.tbl_customer_buffer_stock_idtbl_customer_buffer_stock
    AND cs.tbl_product_idtbl_product = cbd.tbl_product_idtbl_product
JOIN 
    tbl_product p ON cs.tbl_product_idtbl_product = p.idtbl_product
WHERE 
    c.status = 1 -- Active customers
    AND cs.status = 1 -- Active stock records
GROUP BY
    c.idtbl_customer, c.name
ORDER BY 
    c.name";
$result = $conn->query($sql);

$html = '';
if ($result->num_rows > 0) {   
    while ($row = $result->fetch_assoc()) {
        if($row['Required Full (2KG)']>$row['Available Full (2KG)']){$war2KG='table-danger';}else{$war2KG='';}
        if($row['Required Empty (2KG)']>$row['Available Empty (2KG)']){$war2KGEmpty='table-danger';}else{$war2KGEmpty='';}
        if($row['Required Full (5KG)']>$row['Available Full (5KG)']){$war5KG='table-danger';}else{$war5KG='';}
        if($row['Required Empty (5KG)']>$row['Available Empty (5KG)']){$war5KGEmpty='table-danger';}else{$war5KGEmpty='';}
        if($row['Required Full (12.5KG)']>$row['Available Full (12.5KG)']){$war12KG='table-danger';}else{$war12KG='';}
        if($row['Required Empty (12.5KG)']>$row['Available Empty (12.5KG)']){$war12KGEmpty='table-danger';}else{$war12KGEmpty='';}
        if($row['Required Full (37.5KG)']>$row['Available Full (37.5KG)']){$war37KG='table-danger';}else{$war37KG='';}
        if($row['Required Empty (37.5KG)']>$row['Available Empty (37.5KG)']){$war37KGEmpty='table-danger';}else{$war37KGEmpty='';}

        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($row['Customer']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['Executive']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['Area']) . '</td>';
        $html .= '<td class="text-center '.$war2KG.'">' . htmlspecialchars($row['Required Full (2KG)']) . '</td>';
        $html .= '<td class="text-center '.$war2KG.'">' . htmlspecialchars($row['Available Full (2KG)']) . '</td>';
        $html .= '<td class="text-center '.$war2KGEmpty.'">' . htmlspecialchars($row['Required Empty (2KG)']) . '</td>';
        $html .= '<td class="text-center '.$war2KGEmpty.'">' . htmlspecialchars($row['Available Empty (2KG)']) . '</td>';
        $html .= '<td class="text-center '.$war5KG.'">' . htmlspecialchars($row['Required Full (5KG)']) . '</td>';
        $html .= '<td class="text-center '.$war5KG.'">' . htmlspecialchars($row['Available Full (5KG)']) . '</td>';
        $html .= '<td class="text-center '.$war5KGEmpty.'">' . htmlspecialchars($row['Required Empty (5KG)']) . '</td>';
        $html .= '<td class="text-center '.$war5KGEmpty.'">' . htmlspecialchars($row['Available Empty (5KG)']) . '</td>';
        $html .= '<td class="text-center '.$war12KG.'">' . htmlspecialchars($row['Required Full (12.5KG)']) . '</td>';
        $html .= '<td class="text-center '.$war12KG.'">' . htmlspecialchars($row['Available Full (12.5KG)']) . '</td>';
        $html .= '<td class="text-center '.$war12KGEmpty.'">' . htmlspecialchars($row['Required Empty (12.5KG)']) . '</td>';
        $html .= '<td class="text-center '.$war12KGEmpty.'">' . htmlspecialchars($row['Available Empty (12.5KG)']) . '</td>';
        $html .= '<td class="text-center '.$war37KG.'">' . htmlspecialchars($row['Required Full (37.5KG)']) . '</td>';
        $html .= '<td class="text-center '.$war37KG.'">' . htmlspecialchars($row['Available Full (37.5KG)']) . '</td>';
        $html .= '<td class="text-center '.$war37KGEmpty.'">' . htmlspecialchars($row['Required Empty (37.5KG)']) . '</td>';
        $html .= '<td class="text-center '.$war37KGEmpty.'">' . htmlspecialchars($row['Available Empty (37.5KG)']) . '</td>';
        $html .= '</tr>';
    }
}

echo $html;