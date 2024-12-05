<?php
// Include database connection
include 'db_connect.php';

// Initialize variables
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$username = '';
$selected_participants = [];
$error_message = '';
$success_message = '';

// Fetch user details
if ($user_id > 0) {
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header("Location: admin_dashboard.php");
        exit();
    }
    
    $user = $result->fetch_assoc();
    $username = $user['username'];
    
    // Fetch user's participants
    $stmt = $conn->prepare("SELECT participant_id FROM user_participants WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $selected_participants[] = $row['participant_id'];
    }
}

// Fetch all participants
$sql = "SELECT id, name FROM participants ORDER BY name";
$result_participants = $conn->query($sql);
$all_participants = [];
while ($row = $result_participants->fetch_assoc()) {
    $all_participants[] = $row;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST['username']);
    $new_participants = isset($_POST['participants']) ? $_POST['participants'] : [];
    
    if (empty($new_username)) {
        $error_message = "Username cannot be empty";
    } else {
        // Start transaction
        $conn->begin_transaction();
        try {
            // Update username
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->bind_param("si", $new_username, $user_id);
            $stmt->execute();
            
            // Delete existing participant associations
            $stmt = $conn->prepare("DELETE FROM user_participants WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            
            // Insert new participant associations
            if (!empty($new_participants)) {
                $stmt = $conn->prepare("INSERT INTO user_participants (user_id, participant_id) VALUES (?, ?)");
                foreach ($new_participants as $participant_id) {
                    $stmt->bind_param("ii", $user_id, $participant_id);
                    $stmt->execute();
                }
            }
            
            $conn->commit();
            $success_message = "User updated successfully";
            
            // Update local variables to reflect changes
            $username = $new_username;
            $selected_participants = $new_participants;
            
        } catch (Exception $e) {
            $conn->rollback();
            $error_message = "Error updating user: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit User</h1>
        
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" 
                       value="<?= htmlspecialchars($username) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Assigned Participants</label>
                <?php foreach ($all_participants as $participant): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               name="participants[]" 
                               value="<?= $participant['id'] ?>" 
                               id="participant<?= $participant['id'] ?>"
                               <?= in_array($participant['id'], $selected_participants) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="participant<?= $participant['id'] ?>">
                            <?= htmlspecialchars($participant['name']) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
