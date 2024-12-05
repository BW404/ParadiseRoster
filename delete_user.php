<?php
// Include database connection
include 'db_connect.php';

// Get user ID from URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id > 0) {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // First, delete user's participant associations
        $stmt = $conn->prepare("DELETE FROM user_participants WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        // Delete user's log entries
        $stmt = $conn->prepare("DELETE FROM log_entries WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        // Finally, delete the user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        
        // Redirect with success message
        header("Location: admin_dashboard.php?message=User+deleted+successfully");
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        header("Location: admin_dashboard.php?error=Error+deleting+user:+" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Invalid user ID
    header("Location: admin_dashboard.php?error=Invalid+user+ID");
    exit();
}

$conn->close();
?>
