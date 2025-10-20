<?php
session_start();
require_once('connection/db.php');
$userID = $_SESSION['userid'];

$successCount = 0;
$failureCount = 0;
$totalRecords = 0;
$failedRecords = array(); // Array to store failed records with details

$updatedatetime = date('Y-m-d h:i:s');
$filename = "customerbuffer.csv";

if (($handle = fopen($filename, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $totalRecords++;
        $recordSuccess = true;
        $currentRecordId = $data[0];
        $errorMessages = array(); // Store error messages for this record
        
        $id = $data[0];
        $area = $data[1];
        $customer = $data[2];
        $aliasname = $data[3];
        $type = $data[4];
        $nic = $data[5];
        $contact = $data[6];
        $action = $data[7];
        $owner = $data[8];
        $product12_5kg = $data[9];
        $product5kg = $data[10];
        $product2kg = $data[11];
        $product37_5kg = $data[12];

        // Store product information for error reporting
        $productInfo = array();
        if ($product12_5kg > 0) $productInfo[] = "12.5kg: " . $product12_5kg;
        if ($product5kg > 0) $productInfo[] = "5kg: " . $product5kg;
        if ($product2kg > 0) $productInfo[] = "2kg: " . $product2kg;
        if ($product37_5kg > 0) $productInfo[] = "37.5kg: " . $product37_5kg;

        // Update customer record
        $updatecustomer = "UPDATE `tbl_customer` SET `owner_name`='$owner', `phone`='$contact' WHERE `idtbl_customer`='$id'";
        if ($conn->query($updatecustomer)) {
            // Process product stocks
            if ($product12_5kg > 0) {
                $insert12_5kg = "INSERT INTO `tbl_customer_stock`(`fullqty`, `emptyqty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$product12_5kg','0','1','$updatedatetime','$userID','$id','1')";
                if (!$conn->query($insert12_5kg)) {
                    $recordSuccess = false;
                    $errorMessages[] = "12.5kg product insert failed: " . $conn->error;
                }
            }
            
            if ($product5kg > 0) {
                $insert5kg = "INSERT INTO `tbl_customer_stock`(`fullqty`, `emptyqty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$product5kg','0','1','$updatedatetime','$userID','$id','4')";
                if (!$conn->query($insert5kg)) {
                    $recordSuccess = false;
                    $errorMessages[] = "5kg product insert failed: " . $conn->error;
                }
            }
            
            if ($product2kg > 0) {
                $insert2kg = "INSERT INTO `tbl_customer_stock`(`fullqty`, `emptyqty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$product2kg','0','1','$updatedatetime','$userID','$id','6')";
                if (!$conn->query($insert2kg)) {
                    $recordSuccess = false;
                    $errorMessages[] = "2kg product insert failed: " . $conn->error;
                }
            }
            
            if ($product37_5kg > 0) {
                $insert37_5kg = "INSERT INTO `tbl_customer_stock`(`fullqty`, `emptyqty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$product37_5kg','0','1','$updatedatetime','$userID','$id','2')";
                if (!$conn->query($insert37_5kg)) {
                    $recordSuccess = false;
                    $errorMessages[] = "37.5kg product insert failed: " . $conn->error;
                }
            }
            
        } else {
            // Update customer failed
            $recordSuccess = false;
            $errorMessages[] = "Customer update failed: " . $conn->error;
        }
        
        // Update counts and store failed records
        if ($recordSuccess) {
            $successCount++;
        } else {
            $failureCount++;
            $failedRecords[] = array(
                'customer_id' => $currentRecordId,
                'customer_name' => $customer,
                'alias_name' => $aliasname,
                'contact' => $contact,
                'owner' => $owner,
                'products' => $productInfo,
                'errors' => $errorMessages
            );
        }
    }
    fclose($handle);
    
    // Display results
    echo "<div class='result-summary'>";
    echo "<h3>Import Results:</h3>";
    echo "<p>Total Records Processed: " . $totalRecords . "</p>";
    echo "<p style='color: green;'>Successfully Processed: " . $successCount . "</p>";
    echo "<p style='color: red;'>Failed Records: " . $failureCount . "</p>";
    
    // Display failed records with detailed information
    if (!empty($failedRecords)) {
        echo "<div style='margin-top: 20px;'>";
        echo "<h4 style='color: red;'>Failed Records Details:</h4>";
        echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
        echo "<thead style='background-color: #f2f2f2;'>";
        echo "<tr>";
        echo "<th>Customer ID</th>";
        echo "<th>Customer Name</th>";
        echo "<th>Alias Name</th>";
        echo "<th>Contact</th>";
        echo "<th>Owner</th>";
        echo "<th>Products</th>";
        echo "<th>Errors</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        
        foreach ($failedRecords as $failedRecord) {
            echo "<tr style='color: red;'>";
            echo "<td><strong>" . htmlspecialchars($failedRecord['customer_id']) . "</strong></td>";
            echo "<td>" . htmlspecialchars($failedRecord['customer_name']) . "</td>";
            echo "<td>" . htmlspecialchars($failedRecord['alias_name']) . "</td>";
            echo "<td>" . htmlspecialchars($failedRecord['contact']) . "</td>";
            echo "<td>" . htmlspecialchars($failedRecord['owner']) . "</td>";
            echo "<td>" . (!empty($failedRecord['products']) ? implode("<br>", $failedRecord['products']) : "No products") . "</td>";
            echo "<td>" . implode("<br>", $failedRecord['errors']) . "</td>";
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    }
    
    if ($totalRecords > 0) {
        $successRate = ($successCount / $totalRecords) * 100;
        echo "<p style='margin-top: 15px;'><strong>Success Rate:</strong> " . number_format($successRate, 2) . "%</p>";
    }
    echo "</div>";
    
} else {
    echo "Error opening file<br>";
}

mysqli_close($conn);
?>