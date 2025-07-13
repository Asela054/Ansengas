<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'tbl_invoice_payment';

// Table's primary key
$primaryKey = 'idtbl_invoice_payment';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`main`.`idtbl_invoice_payment`', 'dt' => 'idtbl_invoice_payment', 'field' => 'idtbl_invoice_payment' ),
	array( 'db' => '`main`.`invoice_number`', 'dt' => 'invoice_number', 'field' => 'invoice_number' ),
	array( 'db' => '`main`.`date`', 'dt' => 'date', 'field' => 'date' ),
	array( 'db' => '`main`.`payment`', 'dt' => 'payment', 'field' => 'payment' ),
	array( 'db' => '`main`.`balance`', 'dt' => 'balance', 'field' => 'balance' ),
	array( 'db' => '`main`.`excess_amount`', 'dt' => 'excess_amount', 'field' => 'excess_amount' ),
	array( 'db' => '`main`.`name`', 'dt' => 'name', 'field' => 'name' ),
	array( 'db' => '`main`.`status`', 'dt' => 'status', 'field' => 'status' )
);


// SQL server connection information
require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('ssp.customized.class.php' );

// $joinQuery = "FROM (
//     SELECT 
//         `u`.`idtbl_invoice_payment`,
//         `u`.`date`,
//         `u`.`payment`,
//         `u`.`balance`,
//         `ub`.`excess_amount`,
//         `u`.`status`,
//         GROUP_CONCAT(
//             CASE 
//                 WHEN `i`.`tax_invoice_num` IS NOT NULL AND `i`.`tax_invoice_num` != '' THEN CONCAT('AGT-', `i`.`tax_invoice_num`)
//                 ELSE CONCAT('INV-', `i`.`idtbl_invoice`)
//             END
//         ) AS `invoice_number`
//     FROM 
//         `tbl_invoice_payment` AS `u` 
//     LEFT JOIN 
//         `tbl_invoice_payment_has_tbl_invoice` AS `ua` ON `ua`.`tbl_invoice_payment_idtbl_invoice_payment` = `u`.`idtbl_invoice_payment` 
//     LEFT JOIN 
//         `tbl_invoice_excess_payment` AS `ub` ON `u`.`idtbl_invoice_payment` = `ub`.`tbl_invoice_payment_idtbl_invoice_payment` 
//     LEFT JOIN 
//         `tbl_invoice` AS `i` ON `i`.`idtbl_invoice` = `ua`.`tbl_invoice_idtbl_invoice` 
//     WHERE 
//         `u`.`status` IN (1, 2, 3) 
//     GROUP BY 
//         `u`.`idtbl_invoice_payment`
// ) AS main";
$joinQuery = "FROM (
    SELECT 
        u.idtbl_invoice_payment,
        u.date,
        u.payment,
        u.balance,
        ub.excess_amount,
        uc.name,
        u.status,
        GROUP_CONCAT(
            CASE 
                WHEN i.tax_invoice_num IS NOT NULL AND i.tax_invoice_num != '' THEN CONCAT('AGT-', i.tax_invoice_num)
                ELSE CONCAT('INV-', i.idtbl_invoice)
            END
        ) AS invoice_number
    FROM 
        tbl_invoice_payment AS u 
    LEFT JOIN 
        tbl_invoice_payment_has_tbl_invoice AS ua ON ua.tbl_invoice_payment_idtbl_invoice_payment = u.idtbl_invoice_payment 
    LEFT JOIN 
        tbl_invoice_excess_payment AS ub ON u.idtbl_invoice_payment = ub.tbl_invoice_payment_idtbl_invoice_payment 
    LEFT JOIN 
        tbl_invoice AS i ON i.idtbl_invoice = ua.tbl_invoice_idtbl_invoice 
    LEFT JOIN 
        tbl_customer AS uc ON uc.idtbl_customer = i.tbl_customer_idtbl_customer 
    WHERE 
        u.status IN (1, 2, 3) 
    GROUP BY 
        u.idtbl_invoice_payment
) AS main";


echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery)
);
