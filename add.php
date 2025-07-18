<?php
require 'db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task'])) {
    $task = trim($_POST['task']);
    
    if (!empty($task)) {
        $stmt = $conn->prepare("INSERT INTO tasks (task) VALUES (?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("s", $task);
        
        if ($stmt->execute()) {
            // Success - redirect back
            header("Location: index.php");
            exit();
        } else {
            die("Execute failed: " . $stmt->error);
        }
    }
}

// If we get here, something went wrong
header("Location: index.php?error=add_failed");
exit();
?>