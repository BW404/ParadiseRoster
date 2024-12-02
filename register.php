<?php
// Include database connection
include 'db_connect.php';

// Initialize variables
$username = $password = $confirm_password = $participant_id = "";
$username_err = $password_err = $confirm_password_err = $participant_err = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = trim($_POST["username"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password !== $confirm_password) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate participant selection
    if (empty($_POST["participant_id"])) {
        $participant_err = "Please select a participant.";
    } else {
        $participant_id = $_POST["participant_id"];
    }

    // Check for errors before inserting into the database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($participant_err)) {
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $param_username, $param_password);
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash password

            if ($stmt->execute()) {
                // Get the newly inserted user ID
                $new_user_id = $stmt->insert_id;

                // Assign the participant to the user
                $sql_assign = "INSERT INTO user_participants (user_id, participant_id) VALUES (?, ?)";
                if ($stmt_assign = $conn->prepare($sql_assign)) {
                    $stmt_assign->bind_param("ii", $new_user_id, $participant_id);
                    $stmt_assign->execute();
                    $stmt_assign->close();
                }

                // Redirect to login page
                header("location: login.php");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
}

// Fetch participants for the dropdown
$sql_participants = "SELECT id, name FROM participants";
$result = $conn->query($sql_participants);
$participants = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Register</h2>
    <p>Please fill out this form to create an account.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- Username -->
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <!-- Password -->
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <!-- Confirm Password -->
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
        </div>
        <!-- Participant Selection -->
        <div class="form-group">
            <label>Assign Participant</label>
            <select name="participant_id" class="form-control <?php echo (!empty($participant_err)) ? 'is-invalid' : ''; ?>">
                <option value="">Select a participant</option>
                <?php foreach ($participants as $participant): ?>
                    <option value="<?php echo htmlspecialchars($participant['id']); ?>" <?php echo ($participant_id == $participant['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($participant['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="invalid-feedback"><?php echo $participant_err; ?></span>
        </div>
        <!-- Submit Button -->
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Register">
        </div>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</div>
</body>
</html>
