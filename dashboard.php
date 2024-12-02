
<?php

session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();

// Database connection
$conn = new mysqli("localhost", "username", "password", "database");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch participants and their last login/logout information
$user_id = 1; // Replace with actual user_id
$stmt = $conn->prepare(
    "SELECT p.id, p.name, 
            (SELECT MAX(login_time) FROM log_entries WHERE user_id = ? AND participant_id = p.id AND action = 'login') AS last_login,
            (SELECT staff_name FROM log_entries WHERE user_id = ? AND participant_id = p.id AND action = 'logout' ORDER BY logout_time DESC LIMIT 1) AS last_staff_name,
            (SELECT staff_contact FROM log_entries WHERE user_id = ? AND participant_id = p.id AND action = 'logout' ORDER BY logout_time DESC LIMIT 1) AS last_staff_contact,
            (SELECT staff_email FROM log_entries WHERE user_id = ? AND participant_id = p.id AND action = 'logout' ORDER BY logout_time DESC LIMIT 1) AS last_staff_email,
            (SELECT support_details FROM log_entries WHERE user_id = ? AND participant_id = p.id AND action = 'logout' ORDER BY logout_time DESC LIMIT 1) AS last_support_details,
            (SELECT medication FROM log_entries WHERE user_id = ? AND participant_id = p.id AND action = 'logout' ORDER BY logout_time DESC LIMIT 1) AS last_medication,
            (SELECT instructions FROM log_entries WHERE user_id = ? AND participant_id = p.id AND action = 'logout' ORDER BY logout_time DESC LIMIT 1) AS last_instructions
     FROM participants p
     INNER JOIN user_participants up ON p.id = up.participant_id
     WHERE up.user_id = ?"
);

$stmt->bind_param("iiiiiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
$stmt->execute();
$participants = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant Log</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<h1>Participant Log</h1>

<form action="submit_log.php" method="POST">
    <label for="participant">Select Participant:</label>
    <select name="participant" id="participant" required>
        <?php while ($row = $participants->fetch_assoc()): ?>
            <option value="<?php echo htmlspecialchars($row['id']); ?>">
                <?php echo htmlspecialchars($row['name']); ?>
                <?php if ($row['last_login']): ?>
                    (Last Login: <?php echo date('Y-m-d H:i', strtotime($row['last_login'])); ?>)
                <?php else: ?>
                    (No login yet)
                <?php endif; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <br><br>

    <label>Action:</label><br>
    <input type="radio" name="action" value="login" required> Login<br>
    <input type="radio" name="action" value="logout" required> Logout<br>

    <div id="logoutFields" style="display: none;">
        <h3>Logout Details</h3>
        <label for="staff_name">Staff Name:</label>
        <input type="text" name="staff_name" id="staff_name"><br>

        <label for="staff_contact">Staff Contact:</label>
        <input type="text" name="staff_contact" id="staff_contact"><br>

        <label for="staff_email">Staff Email:</label>
        <input type="email" name="staff_email" id="staff_email"><br>

        <label for="support_details">Support Details:</label>
        <textarea name="support_details" id="support_details"></textarea><br>

        <label for="medication">Medication:</label>
        <textarea name="medication" id="medication"></textarea><br>

        <label for="instructions">Instructions:</label>
        <textarea name="instructions" id="instructions"></textarea><br>
    </div>

    <br>
    <button type="submit">Submit</button>
</form>

<script>
$(document).ready(function () {
    // Default hide fields
    $('#logoutFields').hide();

    $('input[name="action"]').change(function () {
        if ($(this).val() === 'logout') {
            $('#logoutFields').show();
            $('#staff_name, #staff_contact, #staff_email, #support_details, #medication, #instructions').prop('required', true);
        } else {
            $('#logoutFields').hide();
            $('#staff_name, #staff_contact, #staff_email, #support_details, #medication, #instructions').prop('required', false);
        }
    });
});
</script>

</body>
</html>