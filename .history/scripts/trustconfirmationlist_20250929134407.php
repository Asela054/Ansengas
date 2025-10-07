<?php
/*
 * DataTables example server-side processing script.
 */
$table = 'tbl_local_purchase';
$primaryKey = 'idtbl_local_purchase';

$columns = array(
    array( 'db' => '`main`.`idtbl_local_purchase`', 'dt' => 'idtbl_local_purchase', 'field' => 'idtbl_local_purchase' ),
    array( 'db' => '`main`.`date`', 'dt' => 'date', 'field' => 'date' ),
    array( 'db' => '`main`.`total`', 'dt' => 'total', 'field' => 'total' ),
    array( 'db' => '`main`.`approvestatus`', 'dt' => 'approvestatus', 'field' => 'approvestatus' ),
    array( 
        'db' => '`main`.`customer_name`', 
        'dt' => 'customer_name',  // Changed to match DataTables config
        'field' => 'customer_name'
    ),
    array( 'db' => '`main`.`status`', 'dt' => 'status', 'field' => 'status' )
);

require('config.php');
$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

require('ssp.customized.class.php');

$joinQuery = "
    FROM (
        SELECT 
            u.idtbl_local_purchase,
            u.date,
            u.total,
            u.approvestatus,
            u.status,
            COALESCE(
                CASE WHEN u.customertype = 2 THEN lpc.name END,
                CASE WHEN u.customertype = 1 THEN c.name END,
                'Unknown Customer'
            ) AS customer_name
        FROM 
            `tbl_local_purchase` AS `u`
        LEFT JOIN 
            `tbl_customer` AS `c` 
            ON (`c`.`idtbl_customer` = `u`.`tbl_customer_idtbl_customer`)
        LEFT JOIN 
            `tbl_local_purchase_customers` AS `lpc` 
            ON (`lpc`.`idtbl_local_purchase_customers` = `u`.`tbl_customer_idtbl_customer`)
        WHERE u.status = 1
    ) AS main";

$extraWhere = "";

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);