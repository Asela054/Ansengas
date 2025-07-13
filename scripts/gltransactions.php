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
$table = 'tbl_account_transaction';

// Table's primary key
$primaryKey = 'idtbl_account_transaction';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`log_id`', 'dt' => 'detail_id', 'field' => 'log_id' ),
	array( 'db' => '`tradate`', 'dt' => 'col_date', 'field' => 'tradate' ),
	array( 'db' => '`cheque_no`', 'dt' => 'cheque_no', 'field' => 'cheque_no' ),
	array( 'db' => '`narration`', 'dt' => 'col_narration', 'field' => 'narration' ),
	array( 'db' => '`accamount`', 'dt' => 'col_amount', 'field' => 'accamount' ),
	array( 'db' => '`idtbl_account_transaction`', 'dt' => 'header_id', 'field' => 'idtbl_account_transaction' ),
	array( 'db' => '`log_cancel`', 'dt' => 'detail_cancel', 'field' => 'log_cancel' ), 
	array( 'db' => '`ismatched`', 'dt' => 'is_matched', 'field' => 'ismatched' )
);

require('../connection/db.php');

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

$optCode = isset($_POST['set_code'])?$conn->real_escape_string($_POST['set_code']):'-1';
$optCrDr = isset($_POST['set_crdr'])?$conn->real_escape_string($_POST['set_crdr']):'-1';

$setDate = $conn->real_escape_string(isset($_POST['set_date'])?$_POST['set_date']:'');
$setBankAc = $conn->real_escape_string(isset($_POST['set_acnum'])?$_POST['set_acnum']:'-');


$revCrDr = "";


if($optCrDr=='C'){
	/*$joinQuery = "FROM (SELECT `ud`.`log_id`, `u`.`tradate`, `uc`.`cheque_no`, `u`.`narration`, `u`.`accamount`, `u`.`idtbl_account_transaction`, `ud`.`log_cancel`, `u`.`ismatched` FROM (SELECT * FROM `tbl_account_transaction` WHERE `tradate`<='".$setDate."' AND `acccode`='".$setBankAc."' AND `crdr`='".$optCrDr."' AND tratype='D' AND trano NOT IN (SELECT trano FROM tbl_gl_transaction_revoke_regs UNION ALL SELECT CONCAT('X', SUBSTRING(CONCAT('00000000', id), -9, 9)) AS trano FROM tbl_gl_transaction_revoke_regs)) AS u LEFT OUTER JOIN (select `id` AS log_id, `particulars_id`, `log_cancel`, `log_insert` from `tbl_gl_ac_audit_details` WHERE `tbl_gl_ac_audit_id`='".$optCode."' AND reg_group_code='GENL') AS `ud` ON `u`.`idtbl_account_transaction`=`ud`.`particulars_id` LEFT OUTER JOIN (SELECT `idtbl_account_transaction`, `cheque_no` FROM `tbl_gl_account_transaction_details` WHERE `tratype`='D') AS uc ON `u`.`idtbl_account_transaction`=`uc`.`idtbl_account_transaction` WHERE  IFNULL(`ud`.`log_insert`, 0)=0 AND ((IFNULL(`ud`.`log_cancel`, 1)=0) OR (`u`.`ismatched`=0))) AS drv";*/
	
	/*$joinQuery = "FROM (SELECT `ud`.`log_id`, `u`.`tradate`, `uc`.`cheque_no`, `u`.`narration`, `u`.`accamount`, `u`.`idtbl_account_transaction`, `ud`.`log_cancel`, `u`.`ismatched` FROM (SELECT * FROM `tbl_account_transaction` WHERE `tradate`<='".$setDate."' AND `acccode`='".$setBankAc."' AND `crdr`='".$optCrDr."' AND trano NOT IN (SELECT trano FROM tbl_gl_transaction_revoke_regs UNION ALL SELECT CONCAT('X', SUBSTRING(CONCAT('00000000', id), -9, 9)) AS trano FROM tbl_gl_transaction_revoke_regs)) AS u LEFT OUTER JOIN (select `id` AS log_id, `particulars_id`, `log_cancel`, `log_insert` from `tbl_gl_ac_audit_details` WHERE `tbl_gl_ac_audit_id`='".$optCode."' AND reg_group_code='GENL') AS `ud` ON `u`.`idtbl_account_transaction`=`ud`.`particulars_id` LEFT OUTER JOIN (SELECT `idtbl_account_transaction`, `cheque_no` FROM `tbl_gl_account_transaction_details`) AS uc ON `u`.`idtbl_account_transaction`=`uc`.`idtbl_account_transaction` WHERE  IFNULL(`ud`.`log_insert`, 0)=0 AND ((IFNULL(`ud`.`log_cancel`, 1)=0) OR (`u`.`ismatched`=0))) AS drv";*/
	$revCrDr = 'D';
}else if($optCrDr=='D'){
	$revCrDr = 'C';
}



/*
test-query = "SELECT u.trano, `ud`.`log_id`, `u`.`tradate`, `uc`.`cheque_no`, `u`.`narration`, ifnull(`uc`.`accamount`, u.accamount) as accamount, ifnull(`uc`.`idtbl_account_transaction`, `u`.`idtbl_account_transaction`) as idtbl_account_transaction, `ud`.`log_cancel`, `u`.`ismatched` FROM (SELECT * FROM `tbl_account_transaction` WHERE `tradate`<='2021-05-16' AND `acccode`='asca00020001' AND `crdr`='D' AND paytype IN ('CA', 'CH') AND trano NOT IN (SELECT trano FROM tbl_gl_transaction_revoke_regs UNION ALL SELECT CONCAT('X', SUBSTRING(CONCAT('00000000', id), -9, 9)) AS trano FROM tbl_gl_transaction_revoke_regs)) AS u LEFT OUTER JOIN (select `id` AS log_id, `particulars_id`, `log_cancel`, `log_insert` from `tbl_gl_ac_audit_details` WHERE `tbl_gl_ac_audit_id`='' AND reg_group_code='GENL') AS `ud` ON `u`.`idtbl_account_transaction`=`ud`.`particulars_id` LEFT OUTER JOIN (

SELECT drv_rev.trano, `tbl_gl_account_transaction_details`.`idtbl_account_transaction`, `tbl_gl_account_transaction_details`.`cheque_no`, `drv_rev`.accamount FROM `tbl_gl_account_transaction_details` INNER JOIN (select idtbl_account_transaction, trano, accamount from tbl_account_transaction WHERE  `tradate`<='2021-05-16' AND paytype IN ('CA', 'CH') AND reversstatus=0) as drv_rev on `tbl_gl_account_transaction_details`.idtbl_account_transaction=drv_rev.idtbl_account_transaction WHERE tbl_gl_account_transaction_details.crdr='C'

) AS uc ON `u`.`trano`=`uc`.`trano` WHERE IFNULL(`ud`.`log_insert`, 0)=0 AND ((IFNULL(`ud`.`log_cancel`, 1)=0) OR (`u`.`ismatched`=0))
group by idtbl_account_transaction"
*/
/*
new-test-query = "SELECT u.trano, `ud`.`log_id`, `u`.`tradate`, `uc`.`cheque_no`, `u`.`narration`, ifnull(`uc`.`accamount`, u.accamount) as accamount, ifnull(`uc`.`idtbl_account_transaction`, `u`.`idtbl_account_transaction`) as idtbl_account_transaction, `ud`.`log_cancel`, `u`.`ismatched` FROM (SELECT * FROM `tbl_account_transaction` WHERE `tradate`<='2021-05-16' AND `acccode`='asca00020001' AND `crdr`='D' AND paytype IN ('CA', 'CH') AND trano NOT IN (SELECT trano FROM tbl_gl_transaction_revoke_regs UNION ALL SELECT CONCAT('X', SUBSTRING(CONCAT('00000000', id), -9, 9)) AS trano FROM tbl_gl_transaction_revoke_regs)) AS u LEFT OUTER JOIN (select `id` AS log_id, `particulars_id`, `log_cancel`, `log_insert` from `tbl_gl_ac_audit_details` WHERE `tbl_gl_ac_audit_id`='' AND reg_group_code='GENL') AS `ud` ON `u`.`idtbl_account_transaction`=`ud`.`particulars_id` LEFT OUTER JOIN (

SELECT drv_rev.trano, `tbl_gl_account_transaction_details`.`idtbl_account_transaction`, `tbl_gl_account_transaction_details`.`cheque_no`, `drv_rev`.accamount FROM `tbl_gl_account_transaction_details` INNER JOIN (select idtbl_account_transaction, trano, accamount from tbl_account_transaction WHERE `tradate`<='2021-05-16' AND paytype IN ('CA', 'CH') AND reversstatus=0) as drv_rev on `tbl_gl_account_transaction_details`.idtbl_account_transaction=drv_rev.idtbl_account_transaction GROUP BY drv_rev.trano, tbl_gl_account_transaction_details.cheque_no ORDER BY tbl_gl_account_transaction_details.idtbl_account_transaction ASC 


) AS uc ON `u`.`trano`=`uc`.`trano` WHERE IFNULL(`ud`.`log_insert`, 0)=0 AND ((IFNULL(`ud`.`log_cancel`, 1)=0) OR (`u`.`ismatched`=0))
group by idtbl_account_transaction"
*/

$joinQuery = "FROM (SELECT `ud`.`log_id`, `u`.`tradate`, `u`.`cheque_no`, `u`.`narration`, u.accamount as accamount, `u`.`idtbl_account_transaction`, `ud`.`log_cancel`, `u`.`ismatched` FROM (SELECT `ub`.`tradate`, `uc`.`cheque_no`, `ub`.`narration`, ifnull(`uc`.`accamount`, ub.accamount) as accamount, ifnull(`uc`.`idtbl_account_transaction`, `ub`.`idtbl_account_transaction`) as idtbl_account_transaction, `ub`.`ismatched` FROM (SELECT idtbl_account_transaction, trano, tradate, narration, accamount, ismatched FROM `tbl_account_transaction` WHERE `tradate`<='".$setDate."' AND `acccode`='".$setBankAc."' AND `crdr`='".$optCrDr."' AND paytype IN ('CA', 'CH') AND trano NOT IN (SELECT trano FROM tbl_gl_transaction_revoke_regs UNION ALL SELECT CONCAT('X', SUBSTRING(CONCAT('00000000', id), -9, 9)) AS trano FROM tbl_gl_transaction_revoke_regs)) AS ub ";

$joinQuery .= "LEFT OUTER JOIN (SELECT drv_rev.trano, `tbl_gl_account_transaction_details`.`idtbl_account_transaction`, `tbl_gl_account_transaction_details`.`cheque_no`, `drv_rev`.accamount FROM `tbl_gl_account_transaction_details` INNER JOIN (select idtbl_account_transaction, trano, accamount FROM tbl_account_transaction WHERE `tradate`<='".$setDate."' AND paytype IN ('CA', 'CH') AND reversstatus=0) as drv_rev on `tbl_gl_account_transaction_details`.idtbl_account_transaction=drv_rev.idtbl_account_transaction GROUP BY drv_rev.trano, tbl_gl_account_transaction_details.cheque_no ORDER BY tbl_gl_account_transaction_details.idtbl_account_transaction ASC) AS uc ON `ub`.`trano`=`uc`.`trano`) AS u LEFT OUTER JOIN (select `id` AS log_id, `particulars_id`, `log_cancel`, `log_insert` from `tbl_gl_ac_audit_details` WHERE `tbl_gl_ac_audit_id`='".$optCode."' AND reg_group_code='GENL') AS `ud` ON `u`.`idtbl_account_transaction`=`ud`.`particulars_id` ";

$joinQuery .= "WHERE IFNULL(`ud`.`log_insert`, 0)=0 AND ((IFNULL(`ud`.`log_cancel`, 1)=0) OR (`u`.`ismatched`=0))";
$joinQuery .= "group by `u`.`idtbl_account_transaction`) AS drv";



$extraWhere = "";//"`u`.`tradate`<='".$setDate."' AND `u`.`acccode`='".$setBankAc."' AND (`u`.`crdr`='".$optCrDr."')";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);