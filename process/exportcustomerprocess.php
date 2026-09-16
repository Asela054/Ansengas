<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:../index.php");
    exit;
}
require_once('../connection/db.php');

$sql = "SELECT 
            c.`idtbl_customer`, c.`type`, c.`name`, c.`alias_name`, c.`pv_num`,
            c.`owner_name`, c.`owner_dob`, c.`nic`, c.`phone`, c.`email`,
            c.`address`, c.`owner_address`, c.`tax_cus_name`, c.`vat_status`,
            c.`discount_status`, c.`vat_num`, c.`s_vat`, c.`numofvisitdays`,
            c.`creditlimit`, c.`credittype`, c.`creditperiod`, c.`emergencydate`,
            c.`specialcus_status`, c.`main_area`, c.`moreinvissue`, c.`feqno`,
            c.`freeissue_status`, c.`status`, c.`updatedatetime`,
            c.`tbl_user_idtbl_user`, c.`tbl_area_idtbl_area`,
            c.`tbl_group_category_idtbl_group_category`,
            a.`area` AS area_name,
            g.`category` AS group_category_name,
            m.`main_area` AS special_area_name
        FROM `tbl_customer` c
        LEFT JOIN `tbl_area` a ON a.`idtbl_area` = c.`tbl_area_idtbl_area`
        LEFT JOIN `tbl_group_category` g ON g.`idtbl_group_category` = c.`tbl_group_category_idtbl_group_category`
        LEFT JOIN `tbl_main_area` m ON m.`idtbl_main_area` = c.`main_area`
        WHERE 1";

$result = $conn->query($sql);

if (!$result) {
    die('Query failed: ' . $conn->error);
}

// Lookup arrays matching the labels already used in customer.php / datatable render
$typeLabels = [1 => 'Commercial', 2 => 'Dealer'];
$creditTypeLabels = [1 => 'Bill To Bill', 2 => 'Credit Days', 3 => 'Cash'];
$statusLabels = [1 => 'Active', 2 => 'Pending/Deactivated', 5 => 'Closed'];

function yesNo($val) {
    return !empty($val) ? 'Yes' : 'No';
}

$filename = 'customer_export_' . date('Ymd_His') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

// Header row — same order/labels as the Add Customer form
fputcsv($output, [
    'Customer ID',
    'Customer Type',
    'Name of Shop/Company',
    'Alias Name',
    'PV Num',
    'Name of Owner/Director',
    'DOB of Owner/Director',
    'NIC',
    'Contact',
    'Email',
    'Address of Shop/Company',
    'Address of Owner/Director',
    'Tax Customer Name',
    'VAT Status',
    'Discounted Customer',
    'Tax Num',
    'S-Vat',
    'No of Visit Days',
    'Credit Limit',
    'Credit Type',
    'Credit Period/Days',
    'Emergency Date',
    'Special Customer',
    'Main Area (Special)',
    'More Inv Issue',
    'Feq No',
    'Free Issue',
    'Area',
    'Group Category',
]);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['idtbl_customer'],
        $typeLabels[$row['type']] ?? $row['type'],
        $row['name'],
        $row['alias_name'],
        $row['pv_num'],
        $row['owner_name'],
        $row['owner_dob'],
        $row['nic'],
        $row['phone'],
        $row['email'],
        $row['address'],
        $row['owner_address'],
        $row['tax_cus_name'],
        yesNo($row['vat_status']),
        yesNo($row['discount_status']),
        $row['vat_num'],
        $row['s_vat'],
        $row['numofvisitdays'],
        $row['creditlimit'],
        $creditTypeLabels[$row['credittype']] ?? $row['credittype'],
        $row['creditperiod'],
        $row['emergencydate'],
        yesNo($row['specialcus_status']),
        $row['special_area_name'],
        $row['moreinvissue'],
        $row['feqno'],
        yesNo($row['freeissue_status']),
        $row['area_name'],
        $row['group_category_name'],
    ]);
}

fclose($output);
exit;