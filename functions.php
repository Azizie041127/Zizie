<?php
// functions.php
include_once 'db.php';
include 'connection.php';
// functions.php

// Fetch SLA details by ID
function fetchSLADetails($id) {
    $db = getDatabaseConnection();
    $query = "SELECT reference_number,sla_name , contract_start_date, contract_end_date, vendor_id, status FROM SLA WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/// Fetch all SLAs with additional search functionality
function fetchSLAs($filter = []) {
    global $pdo;

    // Define the SQL query with JOIN to include vendor details
    $query = "SELECT SLA.*, Vendors.name AS vendor_name 
              FROM SLA 
              JOIN Vendors ON SLA.vendor_id = Vendors.vendor_id 
              WHERE 1=1";

    // Check if search term is provided
    if (!empty($filter['search'])) {
        $searchTerm = '%' . $filter['search'] . '%';
        $query .= " AND (SLA.reference_number LIKE :search OR SLA.vendor_id LIKE :search OR SLA.sla_name LIKE :search)";
    }

    // Apply status filter if selected
    if (!empty($filter['status'])) {
        if ($filter['status'] === 'Nearing Expiration') {
            $query .= " AND DATEDIFF(SLA.contract_end_date, CURDATE()) BETWEEN 0 AND 10";
        } elseif ($filter['status'] === 'Expired') {
            $query .= " AND SLA.contract_end_date < CURDATE()";
        } elseif ($filter['status'] === 'active') {
            $query .= " AND SLA.contract_end_date >= CURDATE()";
        }
    }

    // Apply vendor filter if provided
    if (!empty($filter['vendor_id'])) {
        $query .= " AND SLA.vendor_id = :vendor_id";
    }

    // Apply date range filter if provided
    if (!empty($filter['date_range']['start']) && !empty($filter['date_range']['end'])) {
        $query .= " AND SLA.contract_end_date BETWEEN :start_date AND :end_date";
    }

    // Order by contract_end_date
    $query .= " ORDER BY SLA.contract_end_date ASC";

    $stmt = $pdo->prepare($query);

    // Bind values for search and filters
    if (!empty($filter['search'])) {
        $stmt->bindValue(':search', $searchTerm);
    }

    if (!empty($filter['vendor_id'])) {
        $stmt->bindValue(':vendor_id', $filter['vendor_id']);
    }

    if (!empty($filter['date_range']['start']) && !empty($filter['date_range']['end'])) {
        $stmt->bindValue(':start_date', $filter['date_range']['start']);
        $stmt->bindValue(':end_date', $filter['date_range']['end']);
    }

    $stmt->execute();
    $slas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Update the status of SLAs based on their contract_end_date
    $currentDate = new DateTime();
    foreach ($slas as &$sla) {
        $endDate = new DateTime($sla['contract_end_date']);
        $interval = $currentDate->diff($endDate);

        // If within 10 days of expiration, set the status to "nearing_expiration"
        if ($interval->invert == 0 && $interval->days <= 10) {
            $sla['status'] = 'Nearing Expiration';
        }
        // If expired, set the status to "expired"
        elseif ($interval->invert == 1) {
            $sla['status'] = 'Expired';
        } else {
            $sla['status'] = 'Active';
        }
    }

    return $slas;
}


// Check if vendor_id exists in the vendors table

function vendorExists($vendor_id) {
    global $pdo;
    $query = "SELECT COUNT(*) FROM Vendors WHERE vendor_id = :vendor_id";  // Use vendor_id instead of id
    $stmt = $pdo->prepare($query);
    $stmt->execute(['vendor_id' => $vendor_id]);
    return $stmt->fetchColumn() > 0;
}


// Add a new SLA with vendor ID validation
function addSLA($data) {
    global $pdo; // Assuming you're using PDO for database connection
    $sql = "INSERT INTO SLA (reference_number, sla_name, contract_start_date, contract_end_date, vendor_id, status) 
            VALUES (:reference_number, :sla_name, :contract_start_date, :contract_end_date, :vendor_id, :status)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':reference_number', $data['reference_number']);
    $stmt->bindParam(':sla_name', $data['sla_name']);
    $stmt->bindParam(':contract_start_date', $data['contract_start_date']);
    $stmt->bindParam(':contract_end_date', $data['contract_end_date']);
    $stmt->bindParam(':vendor_id', $data['vendor_id']);
    $stmt->bindParam(':status', $data['status']);

    $stmt->execute();  // Insert the SLA into the database

    return $pdo->lastInsertId();  // Return the ID of the inserted SLA
}

// Edit an existing SLA
function updateSLA($id, $data) {
    global $pdo;
    $query = "UPDATE SLA SET reference_number = :reference_number, contract_start_date = :contract_start_date, 
              contract_end_date = :contract_end_date, vendor_id = :vendor_id, status = :status WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array_merge($data, ['id' => $id]));
}

// Delete an SLA
function deleteSLA($id) {
    global $pdo;
    $query = "DELETE FROM SLA WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
}

// User authentication
function authenticateUser($username, $password) {
    global $pdo;
    $query = "SELECT * FROM Users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        return $user;
    }
    return false;
}



// Example connection setup
// Initialize $pdo connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=sla_management', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Define add_vendor function and make $pdo global
function add_vendor($vendor_id, $name, $contact_info, $address) {
    global $pdo; // Declare $pdo as global

    $sql = "INSERT INTO vendors (vendor_id, name, contact_info, address) VALUES (:vendor_id, :name, :contact_info, :address)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':vendor_id' => $vendor_id,
        ':name' => $name,
        ':contact_info' => $contact_info,
        ':address' => $address
    ]);
}

// Fetch vendor details by vendor_id
function fetchVendorDetails($vendor_id) {
    global $pdo;
    $query = "SELECT * FROM Vendors WHERE vendor_id = :vendor_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['vendor_id' => $vendor_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Upload document function
function uploadDocument($sla_id, $file_name, $file_path) {
    $db = getDatabaseConnection();

    $sql = "INSERT INTO Documents (sla_id, file_name, file_path)
            VALUES (:sla_id, :file_name, :file_path)";

    $stmt = $db->prepare($sql);

    $stmt->execute([
        ':sla_id' => $sla_id,
        ':file_name' => $file_name,
        ':file_path' => $file_path
    ]);
}

// Fetch documents by SLA ID
function fetchDocumentsBySLA($sla_id) {
    global $pdo;
    $query = "SELECT * FROM Documents WHERE sla_id = :sla_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':sla_id' => $sla_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDatabaseConnection() {
    $host = 'localhost';
    $dbname = 'sla_management';
    $username = 'root'; // Adjust if your DB uses a different username
    $password = '';     // Adjust if your DB uses a password

    try {
        return new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}






?>



