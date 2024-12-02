<?php
session_start();
include 'db_connect.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $participant_id = $_POST['participant_id'];
    $action = $_POST['action'];

    if ($action === 'login') {
        // Check if there's already an active login for the same participant by this user
        $stmt = $conn->prepare("SELECT id FROM log_entries WHERE user_id = ? AND participant_id = ? AND action = 'login' AND logout_time IS NULL");
        $stmt->bind_param("ii", $user_id, $participant_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // If there is an active login for this participant, don't allow another login
        if ($result->num_rows > 0) {
            $_SESSION['message'] = 'You must logout first before logging in again for this participant.';
            $_SESSION['message_type'] = 'danger';
            header("Location: dashboard.php");
            exit();
        }

        // Insert login time if no active login
        $stmt = $conn->prepare("INSERT INTO log_entries (user_id, participant_id, action, login_time) VALUES (?, ?, 'login', NOW())");
        $stmt->bind_param("ii", $user_id, $participant_id);
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Login successfully';
            $_SESSION['message_type'] = 'success';
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['message'] = 'Error: ' . $stmt->error;
            $_SESSION['message_type'] = 'danger';
            header("Location: dashboard.php");
            exit();
        }
    } elseif ($action === 'logout') {
        $incident = $_POST['incident'] ?? 'no';
        $instructions = $_POST['instructions'] ?? null;
        $incident_time = $incident_where = $calm_time = $description = $hurt = $current_status = null;
        $service_location = $_POST['service_location'] ?? null;
        $support_details = $_POST['support_details'] ?? null;
        $medication = isset($_POST['medication']) ? implode(", ", $_POST['medication']) : null;
        $handover = $_POST['handover'] ?? null;
        $staff_name = $_POST['staff_name'] ?? null;
        $staff_contact = $_POST['staff_contact'] ?? null;
        $staff_email = $_POST['staff_email'] ?? null;
    
        if ($incident === 'yes') {
            $incident_time = $_POST['incident_time'];
            $incident_where = $_POST['incident_where'];
            $calm_time = $_POST['calm_time'];
            $description = $_POST['description'];
            $hurt = $_POST['hurt'];
            $current_status = $_POST['current_status'];
        }
    
        // Update the log entry for logout
        $stmt = $conn->prepare("UPDATE log_entries 
        SET action = 'logout', 
            logout_time = NOW(),
            incident_time = ?, 
            incident_location = ?, 
            calm_time = ?, 
            description = ?, 
            hurt = ?, 
            current_status = ?, 
            specific_instructions = ?, 
            service_location = ?, 
            support_details = ?, 
            medication = ?, 
            handover = ?, 
            staff_name = ?, 
            staff_contact = ?, 
            staff_email = ? 
        WHERE user_id = ? 
          AND participant_id = ? 
          AND action = 'login' 
          AND logout_time IS NULL");
    
        // Corrected bind_param call with the updated type definition string

        // if $medication have , replace it with space
        $medication = str_replace(",", " ", $medication);

        $stmt->bind_param("ssssssssssssssii", 
            $incident_time, 
            $incident_where, 
            $calm_time, 
            $description, 
            $hurt, 
            $current_status, 
            $instructions, 
            $service_location, 
            $support_details, 
            $medication, 
            $handover, 
            $staff_name, 
            $staff_contact, 
            $staff_email, 
            $user_id, 
            $participant_id
        );
    
        // echo "incident_time:" .$incident_time;
        // echo "incident_where:" .$incident_where;
        // echo "calm_time:" .$calm_time;
        // echo "description:" .$description;
        // echo "hurt:" .$hurt;
        // echo "current_status:" .$current_status;
        // echo "instructions:" .$instructions;
        // echo "service_location:" .$service_location;
        // echo "support_details:" .$support_details;
        // echo "medication:" .$medication;
        // echo "handover:" .$handover;
        // echo "staff_name:" .$staff_name;
        // echo "staff_contact:" .$staff_contact;
        // echo "staff_email:" .$staff_email;
        // echo "user_id:" .$user_id;
        // echo "participant_id:" .$participant_id;
 





        if ($stmt->execute()) {
            $_SESSION['message'] = 'Logout successfully';
            $_SESSION['message_type'] = 'success';
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['message'] = 'Error: ' . $stmt->error;
            $_SESSION['message_type'] = 'danger';
            header("Location: dashboard.php");
            exit();
        }
    }
}
?>