<?php
require_once('../connection/db.php');

$type = $_POST['type'];
$response = array();

if ($type === '1') {
    $sqlcustomer="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1";
    $resultcustomer =$conn-> query($sqlcustomer);

    $options = array();
    if ($resultcustomer->num_rows > 0) {
        while ($row = $resultcustomer->fetch_assoc()) {
            $options[] = array('value' => $row['idtbl_customer'], 'text' => $row['name']);
        }
    }
    $response['options'] = $options;
} elseif ($type === '2') {
    $sqlemployee="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=7 ORDER BY `name` ASC";
    $resultemployee =$conn-> query($sqlemployee);

    $options = array();
    if ($resultemployee->num_rows > 0) {
        while ($row = $resultemployee->fetch_assoc()) {
            $options[] = array('value' => $row['idtbl_employee'], 'text' => $row['name']);
        }
    }
    $response['options'] = $options;
} elseif ($type === '3') {
    $sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `status`=1 AND `type`=0 ORDER BY `vehicleno` ASC";
    $resultvehicle =$conn-> query($sqlvehicle);

    $options = array();
    if ($resultvehicle->num_rows > 0) {
        while ($row = $resultvehicle->fetch_assoc()) {
            $options[] = array('value' => $row['idtbl_vehicle'], 'text' => $row['vehicleno']);
        }
    }
    $response['options'] = $options;
} elseif ($type === '4') {
    $sqldriver="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `status`=1 AND `tbl_user_type_idtbl_user_type`=4 ORDER BY `name` ASC";
    $resultdriver =$conn-> query($sqldriver);

    $options = array();
    if ($resultdriver->num_rows > 0) {
        while ($row = $resultdriver->fetch_assoc()) {
            $options[] = array('value' => $row['idtbl_employee'], 'text' => $row['name']);
        }
    }
    $response['options'] = $options;
} else {
    $response['options'] = array();
}

echo json_encode($response);
?>
