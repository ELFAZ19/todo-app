<?php
require 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id);
    
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
}

header("Location: index.php");
exit();
?>