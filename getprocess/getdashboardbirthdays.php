<?php 
require_once('../connection/db.php');

$bdaytype = $_POST['bdaytype'];

// $sql="SELECT 
//     '1' AS type,
//     idtbl_employee AS id,
//     'Ansen Gas' AS shop_name,
//     name,
//     dob AS birthday,
//     phone,
//     address,
//     '' AS area,
//     '' AS salesrep_name
// FROM 
//     tbl_employee
// WHERE 
//     dob IS NOT NULL
//     AND DATE_FORMAT(dob, '%m-%d') BETWEEN DATE_FORMAT(CURDATE(), '%m-%d') 
//                                        AND DATE_FORMAT(LAST_DAY(CURDATE()), '%m-%d')

// UNION ALL

// SELECT 
//     '2' AS type,
//     idtbl_customer AS id,
//     tbl_customer.name AS shop_name,
//     owner_name AS name,
//     owner_dob AS birthday,
//     tbl_customer.phone,
//     tbl_customer.address,
//     area,
//     tbl_employee.name AS salesrep_name
// FROM 
//     tbl_customer
// LEFT JOIN 
//     tbl_customerwise_salesrep ON tbl_customer.idtbl_customer = tbl_customerwise_salesrep.tbl_customer_idtbl_customer
// LEFT JOIN
//     tbl_employee ON tbl_customerwise_salesrep.tbl_employee_idtbl_employee = tbl_employee.idtbl_employee
// LEFT JOIN
//     tbl_area ON tbl_customer.tbl_area_idtbl_area = tbl_area.idtbl_area
// WHERE 
//     owner_dob IS NOT NULL
//     AND DATE_FORMAT(owner_dob, '%m-%d') BETWEEN DATE_FORMAT(CURDATE(), '%m-%d') 
//                                             AND DATE_FORMAT(LAST_DAY(CURDATE()), '%m-%d')
// GROUP BY 
//     tbl_customerwise_salesrep.tbl_customer_idtbl_customer
// ORDER BY DATE_FORMAT(birthday, '%m-%d')";

if($bdaytype == 1) {
    // Current date to end of current month
    $dateCondition1 = "DATE_FORMAT(dob, '%m-%d') BETWEEN DATE_FORMAT(CURDATE(), '%m-%d') 
                      AND DATE_FORMAT(LAST_DAY(CURDATE()), '%m-%d')";
    $dateCondition2 = "DATE_FORMAT(owner_dob, '%m-%d') BETWEEN DATE_FORMAT(CURDATE(), '%m-%d') 
                      AND DATE_FORMAT(LAST_DAY(CURDATE()), '%m-%d')";
} elseif($bdaytype == 2) {
    // All birthdays in next month
    $nextMonth = date('m', strtotime('+1 month'));
    $dateCondition1 = "DATE_FORMAT(dob, '%m') = '$nextMonth'";
    $dateCondition2 = "DATE_FORMAT(owner_dob, '%m') = '$nextMonth'";
}

$sql = "SELECT 
    '1' AS type,
    idtbl_employee AS id,
    'Ansen Gas' AS shop_name,
    name,
    dob AS birthday,
    phone,
    address,
    '' AS area,
    '' AS salesrep_name
FROM 
    tbl_employee
WHERE 
    dob IS NOT NULL
    AND $dateCondition1

UNION ALL

SELECT 
    '2' AS type,
    idtbl_customer AS id,
    tbl_customer.name AS shop_name,
    owner_name AS name,
    owner_dob AS birthday,
    tbl_customer.phone,
    tbl_customer.address,
    area,
    tbl_employee.name AS salesrep_name
FROM 
    tbl_customer
LEFT JOIN 
    tbl_customerwise_salesrep ON tbl_customer.idtbl_customer = tbl_customerwise_salesrep.tbl_customer_idtbl_customer
LEFT JOIN
    tbl_employee ON tbl_customerwise_salesrep.tbl_employee_idtbl_employee = tbl_employee.idtbl_employee
LEFT JOIN
    tbl_area ON tbl_customer.tbl_area_idtbl_area = tbl_area.idtbl_area
WHERE 
    owner_dob IS NOT NULL
    AND $dateCondition2
GROUP BY 
    tbl_customerwise_salesrep.tbl_customer_idtbl_customer
ORDER BY DATE_FORMAT(birthday, '%m-%d')";
$result = $conn->query($sql);

$html='';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html.='<tr class="'; if(date("m-d", strtotime($row['birthday']))==date('m-d')){$html.='bg-danger-soft';} else if($row['type']==1){$html.='bg-warning-soft';} $html.='">
            <td nowrap>'.$row['shop_name'].'</td>
            <td nowrap>'.$row['name'].'</td>
            <td nowrap>'.$row['birthday'].'</td>
            <td nowrap>'.$row['phone'].'</td>
            <td nowrap>'.$row['address'].'</td>
            <td nowrap>'.$row['area'].'</td>
            <td nowrap>'.$row['salesrep_name'].'</td>
        </tr>';
    }
}

 echo $html;