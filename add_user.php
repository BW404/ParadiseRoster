<?php
// Include database connection
include 'db_connect.php';

// Initialize variables
$username = $password = '';
$error_message = $success_message = '';

// Fetch all participants
$sql = "SELECT id, name FROM participants ORDER BY name";
$result_participants = $conn->query($sql);
$all_participants = [];
while ($row = $result_participants->fetch_assoc()) {
    $all_participants[] = $row;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $selected_participants = isset($_POST['participants']) ? $_POST['participants'] : [];
    
    // Validate input
    if (empty($username)) {
        $error_message = "Username is required";
    } elseif (empty($password)) {
        $error_message = "Password is required";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error_message = "Username already exists";
        } else {
            // Start transaction
            $conn->begin_transaction();
            try {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $hashed_password);
                $stmt->execute();
                $user_id = $conn->insert_id;
                
                // Insert participant associations
                if (!empty($selected_participants)) {
                    $stmt = $conn->prepare("INSERT INTO user_participants (user_id, participant_id) VALUES (?, ?)");
                    foreach ($selected_participants as $participant_id) {
                        $stmt->bind_param("ii", $user_id, $participant_id);
                        $stmt->execute();
                    }
                }
                
                $conn->commit();
                $success_message = "User created successfully";
                
                // Clear form
                $username = '';
                $password = '';
                
            } catch (Exception $e) {
                $conn->rollback();
                $error_message = "Error creating user: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Add New User</h1>
        
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
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>Assign Participants</label>
                <?php foreach ($all_participants as $participant): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               name="participants[]" 
                               value="<?= $participant['id'] ?>" 
                               id="participant<?= $participant['id'] ?>">
                        <label class="form-check-label" for="participant<?= $participant['id'] ?>">
                            <?= htmlspecialchars($participant['name']) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
