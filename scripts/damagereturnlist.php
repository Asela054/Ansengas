<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
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
$table = 'tbl_damage_return';

// Table's primary key
$primaryKey = 'idtbl_damage_return';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`main`.`idtbl_damage_return`', 'dt' => 'idtbl_damage_return', 'field' => 'idtbl_damage_return' ),
	array( 'db' => '`main`.`returndate`', 'dt' => 'returndate', 'field' => 'returndate' ),
	array( 'db' => '`main`.`qty`', 'dt' => 'qty', 'field' => 'qty' ),
	array( 'db' => '`main`.`comsendstatus`', 'dt' => 'comsendstatus', 'field' => 'comsendstatus' ),
	array( 'db' => '`main`.`comsenddate`',   'dt' => 'comsenddate', 'field' => 'comsenddate' ),
	array( 'db' => '`main`.`backstockstatus`',   'dt' => 'backstockstatus', 'field' => 'backstockstatus' ),
	array( 'db' => '`main`.`backstockdate`',   'dt' => 'backstockdate', 'field' => 'backstockdate' ),
	array( 'db' => '`main`.`returncusstatus`',   'dt' => 'returncusstatus', 'field' => 'returncusstatus' ),
	array( 'db' => '`main`.`returncusdate`',   'dt' => 'returncusdate', 'field' => 'returncusdate' ),
	array( 'db' => '`main`.`seriel_no`',   'dt' => 'seriel_no', 'field' => 'seriel_no' ),
	array( 'db' => '`main`.`reference_no`',   'dt' => 'reference_no', 'field' => 'reference_no' ),
	array( 'db' => '`main`.`idtbl_customer`',   'dt' => 'idtbl_customer', 'field' => 'idtbl_customer' ),
	array( 'db' => '`main`.`customer_name`',   'dt' => 'customer_name', 'field' => 'customer_name' ),
	array( 'db' => '`main`.`product_name`',   'dt' => 'product_name', 'field' => 'product_name' ),
	array( 'db' => '`main`.`status`',   'dt' => 'status', 'field' => 'status' )
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

$joinQuery = "
    FROM (
        SELECT 
            CASE 
                WHEN u.tbl_customer_idtbl_customer = 848 THEN uc.cusname 
                ELSE ua.name 
            END AS customer_name,
            u.idtbl_damage_return,
            u.returndate,
            u.qty,
            u.comsendstatus,
            u.comsenddate,
            u.backstockstatus,
            u.backstockdate,
            u.returncusstatus,
            u.returncusdate,
            u.seriel_no,
            u.reference_no,
            u.tbl_customer_idtbl_customer AS idtbl_customer,
            ua.name,
            ub.product_name,
            u.status
        FROM 
            `tbl_damage_return` AS `u`
        LEFT JOIN 
            `tbl_customer` AS `ua` 
            ON (`ua`.`idtbl_customer` = `u`.`tbl_customer_idtbl_customer`)
        LEFT JOIN 
            `tbl_product` AS `ub` 
            ON (`ub`.`idtbl_product` = `u`.`tbl_product_idtbl_product`)
        LEFT JOIN 
            `tbl_damage_return_customer_detail` AS `uc` 
            ON (`u`.`idtbl_damage_return` = `uc`.`tbl_damage_return_idtbl_damage_return`)
    ) AS main";

$extraWhere = "`main`.`status` IN (1, 2)";


echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);