<?php
// Include database connection
include 'db_connect.php';

// Fetch all users
$sql_users = "SELECT u.id, u.username, GROUP_CONCAT(p.name SEPARATOR ', ') AS participants 
              FROM users u 
              LEFT JOIN user_participants up ON u.id = up.user_id 
              LEFT JOIN participants p ON up.participant_id = p.id 
              GROUP BY u.id, u.username";
$result_users = $conn->query($sql_users);

// Fetch all log entries
$sql_logs = "SELECT l.id, u.username, p.name AS participant_name, l.action, l.login_time, l.logout_time, 
                    l.incident_time, l.incident_location, l.calm_time, l.description, l.hurt, l.current_status, 
                    l.specific_instructions
             FROM log_entries l
             JOIN users u ON l.user_id = u.id
             JOIN participants p ON l.participant_id = p.id
             ORDER BY l.id DESC";
$result_logs = $conn->query($sql_logs);

// Fetch all participants
$sql_participants = "SELECT id, name FROM participants";
$result_participants = $conn->query($sql_participants);

// Handle participant addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_participant'])) {
    $participant_name = $_POST['participant_name'];
    if (!empty($participant_name)) {
        $stmt = $conn->prepare("INSERT INTO participants (name) VALUES (?)");
        $stmt->bind_param("s", $participant_name);
        if ($stmt->execute()) {
            echo "<script>alert('Participant added successfully!'); window.location.reload();</script>";
        } else {
            echo "<script>alert('Error adding participant.');</script>";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1 class="text-center mb-4">Admin Dashboard</h1>
    
    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link active" href="#users" data-toggle="tab">Manage Users</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#reports" data-toggle="tab">View Reports</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#participants" data-toggle="tab">Manage Participants</a>
        </li>
    </ul>
    
    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Manage Users Tab -->
        <div class="tab-pane fade show active" id="users">
            <h3>Users</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Participants</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($result_users->num_rows > 0): ?>
                    <?php while ($row = $result_users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['participants'] ?: 'None' ?></td>
                            <td>
                                <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No users found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- View Reports Tab -->
        <div class="tab-pane fade" id="reports">
            <h3>Reports</h3>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Participant</th>
                    <th>Action</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>Incident Details</th>
                    <th>Specific Instructions</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($result_logs->num_rows > 0): ?>
                    <?php while ($log = $result_logs->fetch_assoc()): ?>
                        <tr>
                            <td><?= $log['id'] ?></td>
                            <td><?= $log['username'] ?></td>
                            <td><?= $log['participant_name'] ?></td>
                            <td><?= ucfirst($log['action']) ?></td>
                            <td><?= $log['login_time'] ?: '-' ?></td>
                            <td><?= $log['logout_time'] ?: '-' ?></td>
                            <td>
                                <?php if ($log['incident_time']): ?>
                                    <strong>Incident Time:</strong> <?= $log['incident_time'] ?><br>
                                    <strong>Location:</strong> <?= $log['incident_location'] ?><br>
                                    <strong>Calm Time:</strong> <?= $log['calm_time'] ?><br>
                                    <strong>Description:</strong> <?= $log['description'] ?><br>
                                    <strong>Hurt:</strong> <?= $log['hurt'] ?><br>
                                    <strong>Status:</strong> <?= $log['current_status'] ?><br>
                                <?php else: ?>
                                    No Incident
                                <?php endif; ?>
                            </td>
                            <td><?= $log['specific_instructions'] ?: 'None' ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No logs found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Manage Participants Tab -->
        <div class="tab-pane fade" id="participants">
            <h3>Participants</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($result_participants->num_rows > 0): ?>
                    <?php while ($participant = $result_participants->fetch_assoc()): ?>
                        <tr>
                            <td><?= $participant['id'] ?></td>
                            <td><?= $participant['name'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center">No participants found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

            <!-- Add New Participant -->
            <h4>Add New Participant</h4>
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <div class="form-group">
                    <label for="participant_name">Participant Name</label>
                    <input type="text" name="participant_name" id="participant_name" class="form-control" required>
                </div>
                <button type="submit" name="add_participant" class="btn btn-primary">Add Participant</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
