<?php
require 'db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white" style ="background-color: #FFC107 !important;">
                        <h3 class="mb-0"><i class="bi bi-check2-circle"></i> Todo App</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="add.php" class="mb-4">
                            <div class="input-group">
                                <input type="text" name="task" class="form-control" placeholder="Add new task..." required>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>

                        <?php
                        // Fetch all tasks
                        $result = $conn->query("SELECT * FROM tasks ORDER BY 
                            CASE WHEN status = 'pending' THEN 0 ELSE 1 END,
                            created_at DESC");
                        
                        if ($result === false) {
                            die("Error fetching tasks: " . $conn->error);
                        }
                        ?>

                        <ul class="list-group">
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center <?= $row['status'] == 'completed' ? 'list-group-item-success' : '' ?>">
                                        <div>
                                            <form action="update.php" method="GET" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn btn-sm <?= $row['status'] == 'completed' ? 'btn-warning' : 'btn-success' ?> me-2">
                                                    <?= $row['status'] == 'completed' ? 'Undo' : 'Complete' ?>
                                                </button>
                                            </form>
                                            <span class="<?= $row['status'] == 'completed' ? 'text-decoration-line-through' : '' ?>">
                                                <?= htmlspecialchars($row['task']) ?>
                                            </span>
                                            <small class="text-muted d-block mt-1">
                                                Created: <?= date('M j, Y g:i A', strtotime($row['created_at'])) ?>
                                                <?php if ($row['status'] == 'completed' && $row['completed_at']): ?>
                                                    | Completed: <?= date('M j, Y g:i A', strtotime($row['completed_at'])) ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                        <form action="delete.php" method="GET">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li class="list-group-item text-center text-muted py-4">
                                    <i class="bi bi-emoji-frown display-4 d-block mb-2"></i>
                                    No tasks found. Add one above!
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="card-footer text-muted">
                        <?php
                            $total = $conn->query("SELECT COUNT(*) FROM tasks")->fetch_row()[0];
                            $completed = $conn->query("SELECT COUNT(*) FROM tasks WHERE status='completed'")->fetch_row()[0];
                        ?>
                        <small>Total: <?= $total ?> | Completed: <?= $completed ?> | Pending: <?= $total - $completed ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>