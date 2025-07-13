<?php
require_once('../connection/db.php');

if (isset($_POST['invoice_id'])) {
    $invoice_id = $_POST['invoice_id'];

    $sql_invoice = "SELECT tbl_customer_idtbl_customer FROM tbl_invoice WHERE idtbl_invoice = ?";
    $stmt_invoice = $conn->prepare($sql_invoice);
    $stmt_invoice->bind_param('i', $invoice_id);
    $stmt_invoice->execute();
    $result_invoice = $stmt_invoice->get_result();

    if ($result_invoice->num_rows > 0) {
        $row_invoice = $result_invoice->fetch_assoc();
        $customer_id = $row_invoice['tbl_customer_idtbl_customer'];

        $sql_customer = "SELECT idtbl_customer, name FROM tbl_customer WHERE idtbl_customer = ?";
        $stmt_customer = $conn->prepare($sql_customer);
        $stmt_customer->bind_param('i', $customer_id);
        $stmt_customer->execute();
        $result_customer = $stmt_customer->get_result();

        if ($result_customer->num_rows > 0) {
            while ($row_customer = $result_customer->fetch_assoc()) {
                echo '<option value="' . $row_customer['idtbl_customer'] . '">' . $row_customer['name'] . '</option>';
            }
        } else {
            echo '<option value="">No customers found</option>';
        }
        
        $stmt_customer->close();
    } else {
        echo '<option value="">Invalid invoice</option>';
    }

    $stmt_invoice->close();
}
?>
