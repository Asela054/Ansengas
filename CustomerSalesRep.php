<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'erav_ansengas';

// CSV file path
$csv_file = 'Product Information.csv';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get all active products (status = 1)
    $products = [];
    $stmt = $conn->query("SELECT idtbl_product FROM tbl_product WHERE status = 1");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $products[] = $row['idtbl_product'];
    }

    if (empty($products)) {
        throw new Exception("No active products found in the database.");
    }

    // Open the CSV file
    if (($handle = fopen($csv_file, "r")) !== FALSE) {
        // Skip header row if needed
        fgetcsv($handle);
        
        // Prepare insert statement
        $insert_stmt = $conn->prepare("
            INSERT INTO tbl_customerwise_salesrep 
            (status, insertdatetime, updateuser, updatedatetime, 
             tbl_customer_idtbl_customer, tbl_product_idtbl_product, tbl_employee_idtbl_employee) 
            VALUES 
            (1, NOW(), '', '', :customer_id, :product_id, :employee_id)
        ");

        // Prepare customer validation statement
        $customer_stmt = $conn->prepare("
            SELECT idtbl_customer 
            FROM tbl_customer 
            WHERE idtbl_customer = :customer_id 
            AND (status = 1 OR status = 2) 
            LIMIT 1
        ");

        $processed_customers = [];
        $success_count = 0;
        $error_count = 0;
        $not_found_customers = [];

        // Read CSV line by line
        while (($data = fgetcsv($handle)) !== FALSE) {
            // CSV columns:
            // 0: Customer ID (idtbl_customer)
            // 1: Feq. No, 2: Area, 3: Name, 4: Alias Name, 
            // 5: Type, 6: NIC, 7: Contact, 8: Actions, 9: SE (employee_id)
            
            $customer_id = trim($data[0]);  // Column 0: Customer ID
            $employee_id = trim($data[9]);  // Column 9: SE
            
            if (empty($customer_id) || empty($employee_id)) {
                $error_count++;
                continue;
            }

            // Check if we've already processed this customer
            if (in_array($customer_id, $processed_customers)) {
                continue;
            }

            // Validate customer exists and is active
            $customer_stmt->bindParam(':customer_id', $customer_id);
            $customer_stmt->execute();
            $customer = $customer_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$customer) {
                $not_found_customers[] = $customer_id;
                $error_count++;
                continue;
            }

            // Insert a record for each product for this customer
            foreach ($products as $product_id) {
                try {
                    $insert_stmt->bindParam(':customer_id', $customer_id);
                    $insert_stmt->bindParam(':product_id', $product_id);
                    $insert_stmt->bindParam(':employee_id', $employee_id);
                    $insert_stmt->execute();
                    $success_count++;
                } catch (PDOException $e) {
                    $error_count++;
                    error_log("Error inserting record for customer ID $customer_id: " . $e->getMessage());
                }
            }

            // Mark customer as processed
            $processed_customers[] = $customer_id;
        }

        fclose($handle);

        // Output results
        echo "<h2>Import Results</h2>";
        echo "<p>Records inserted successfully: $success_count</p>";
        echo "<p>Errors encountered: $error_count</p>";
        
        if (!empty($not_found_customers)) {
            echo "<h3>Customer IDs not found in database:</h3>";
            echo "<ul>";
            foreach ($not_found_customers as $id) {
                echo "<li>$id</li>";
            }
            echo "</ul>";
        }
    } else {
        throw new Exception("Could not open CSV file.");
    }
} catch (PDOException $e) {
    echo "<div style='color:red;'>Database Error: " . $e->getMessage() . "</div>";
} catch (Exception $e) {
    echo "<div style='color:red;'>Error: " . $e->getMessage() . "</div>";
}
?>