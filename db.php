<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "todo";
$port = 3307; // Default MySQL port (change to 3307 if needed)

// Create connection
$conn = new mysqli($servername, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create tasks table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task VARCHAR(255) NOT NULL,
    status ENUM('pending', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL
)";

if (!$conn->query($sql)) {
    die("Error creating table: " . $conn->error);
}

// Function to verify table structure (for debugging)
function debugDatabase() {
    global $conn;
    $result = $conn->query("DESCRIBE tasks");
    echo "<pre>Table Structure:\n";
    while($row = $result->fetch_assoc()) {
        print_r($row);
    }
    echo "</pre>";
}
?>