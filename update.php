<?php
require 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Get current status
    $stmt = $conn->prepare("SELECT status FROM tasks WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();
    
    if ($task) {
        $new_status = $task['status'] == 'completed' ? 'pending' : 'completed';
        $completed_at = $new_status == 'completed' ? ', completed_at = NOW()' : '';
        
        $update_stmt = $conn->prepare("UPDATE tasks SET status = ? $completed_at WHERE id = ?");
        if (!$update_stmt) {
            die("Prepare failed: " . $conn->error);
        }
        
        $update_stmt->bind_param("si", $new_status, $id);
        
        if (!$update_stmt->execute()) {
            die("Execute failed: " . $update_stmt->error);
        }
    }
}

header("Location: index.php");
exit();
?>