<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['participant_id'])) {
    $participant_id = $_GET['participant_id'];

    $stmt = $conn->prepare("SELECT staff_name, staff_contact, staff_email, support_details, medication, specific_instructions 
                            FROM log_entries 
                            WHERE user_id = ? AND participant_id = ? AND action = 'logout' 
                            ORDER BY logout_time DESC LIMIT 1");
    $stmt->bind_param("ii", $user_id, $participant_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    echo json_encode($result);
    exit();
}

// Fetch participants allocated to the logged-in user along with the last login time
$stmt = $conn->prepare("SELECT p.id, p.name, 
                               (SELECT login_time FROM log_entries WHERE user_id = ? AND participant_id = p.id AND action = 'login' ORDER BY login_time DESC LIMIT 1) AS last_login
                        FROM participants p
                        INNER JOIN user_participants up ON p.id = up.participant_id
                        WHERE up.user_id = ?");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$participants = $stmt->get_result();

// Fetch last support worker info
$stmt = $conn->prepare("SELECT staff_name, staff_contact, staff_email, support_details, medication, specific_instructions 
                        FROM log_entries 
                        WHERE user_id = ? AND action = 'logout' 
                        ORDER BY logout_time DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$participants_last_info = $stmt->get_result()->fetch_assoc();

$last_staff_name = $participants_last_info['staff_name'];
$last_staff_contact = $participants_last_info['staff_contact'];
$last_staff_email = $participants_last_info['staff_email'];
$last_support_details = $participants_last_info['support_details'];
$last_medication = $participants_last_info['medication'];
$last_instructions = $participants_last_info['specific_instructions'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        h2 {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: bold;
        }

        .hidden {
            display: none;
        }

        .container {
            padding: 15px;
        }

        .table-responsive {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Welcome</h2>

        <!-- Display message if any -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?>" role="alert">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message']); ?>
            <?php unset($_SESSION['message_type']); ?>
        <?php endif; ?>

        <h3>Last Support Worker Info</h3>
        <div class="table-responsive">
            <table id="supportWorkerInfo" class="table table-striped hidden">
                <thead>
                    <tr>
                        <th>Staff Name</th>
                        <th>Staff Contact</th>
                        <th>Staff Email</th>
                        <th>Support Details</th>
                        <th>Medication Provided</th>
                        <th>Instructions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="last_staff_name"><?php echo $last_staff_name; ?></td>
                        <td id="last_staff_contact"><?php echo $last_staff_contact; ?></td>
                        <td id="last_staff_email"><?php echo $last_staff_email; ?></td>
                        <td id="last_support_details"><?php echo $last_support_details; ?></td>
                        <td id="last_medication"><?php echo $last_medication; ?></td>
                        <td id="last_instructions"><?php echo $last_instructions; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <form id="actionForm" action="process_action.php" method="POST">
            <div class="mb-3">
                <label for="participant" class="form-label">Participant Name</label>
                <select name="participant_id" id="participant" class="form-select" required>
                    <option value="">-- Select Participant --</option>
                    <?php while ($row = $participants->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo $row['name']; ?>
                            <?php if ($row['last_login']): ?>
                                (Last Login: <?php echo date('Y-m-d H:i', strtotime($row['last_login'])); ?>)
                            <?php else: ?>
                                (No login yet)
                            <?php endif; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Action</label><br>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="action" value="login" id="login" required>
                    <label class="form-check-label" for="login">Login</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="action" value="logout" id="logout">
                    <label class="form-check-label" for="logout">Logout</label>
                </div>
            </div>

            <div id="logoutFields" style="display: none;">
                <div class="mb-3">
                    <label class="form-label">Is there any incident?</label>
                    <select name="incident" id="incident" class="form-select">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>
                <div id="incidentDetails" style="display: none;">
                    <!-- Incident details fields here -->
                    <div class="mb-3">
                        <label for="incident_time" class="form-label">When (Time)</label>
                        <input type="time" name="incident_time" id="incident_time" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="incident_where" class="form-label">Where?</label>
                        <input type="text" name="incident_where" id="incident_where" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="calm_time" class="form-label">When did they calm down? (Time)</label>
                        <input type="time" name="calm_time" id="calm_time" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="hurt" class="form-label">Did anyone get hurt?</label>
                        <select name="hurt" id="hurt" class="form-select">
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="current_status" class="form-label">Current Status</label>
                        <textarea name="current_status" id="current_status" class="form-control"></textarea>
                    </div>
                </div>

                <!-- New Fields for Logout -->
                <div class="mb-3">
                    <label for="staff_name" class="form-label">Staff Name</label>
                    <input type="text" name="staff_name" id="staff_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="staff_contact" class="form-label">Staff Contact</label>
                    <input type="text" name="staff_contact" id="staff_contact" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="staff_email" class="form-label">Staff Email</label>
                    <input type="email" name="staff_email" id="staff_email" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="service_location" class="form-label">Service Location</label>
                    <input type="text" name="service_location" id="service_location" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="support_details" class="form-label">Details of Today's Support</label>
                    <textarea name="support_details" id="support_details" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Medication</label><br>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="medication[]" value="Morning"
                            id="medication_morning">
                        <label class="form-check-label" for="medication_morning">Morning</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="medication[]" value="Lunch"
                            id="medication_lunch">
                        <label class="form-check-label" for="medication_lunch">Lunch</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="medication[]" value="Evening"
                            id="medication_evening">
                        <label class="form-check-label" for="medication_evening">Evening</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="medication[]" value="Bedtime"
                            id="medication_bedtime">
                        <label class="form-check-label" for="medication_bedtime">Bedtime</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="handover" class="form-label">Handover to Support Worker</label>
                    <input type="text" name="handover" id="handover" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="instructions" class="form-label">Instructions for Next Staff</label>
                    <textarea name="instructions" id="instructions" class="form-control"></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('input[name="action"]').change(function () {
                if ($(this).val() === 'logout') {
                    $('#logoutFields').show();
                    $('#instructions, #staff_name, #staff_contact, #staff_email, #service_location, #support_details, #handover').prop('required', true);
                } else {
                    $('#logoutFields').hide();
                    $('#incidentDetails').hide();
                    $('#instructions, #staff_name , #staff_contact, #staff_email, #service_location, #support_details, #handover').prop('required', false);
                }
            });

            $('#incident').change(function () {
                if ($(this).val() === 'yes') {
                    $('#incidentDetails').show();
                } else {
                    $('#incidentDetails').hide();
                }
            });

            $('#participant').change(function () {
                var participant_id = $(this).val();
                if (participant_id) {
                    $.ajax({
                        url: 'dashboard.php',
                        type: 'GET',
                        data: { participant_id: participant_id },
                        success: function (data) {
                            var info = JSON.parse(data);
                            $('#last_staff_name').text(info.staff_name);
                            $('#last_staff_contact').text(info.staff_contact);
                            $('#last_staff_email').text(info.staff_email);
                            $('#last_support_details').text(info.support_details);
                            $('#last_medication').text(info.medication);
                            $('#last_instructions').text(info.specific_instructions);
                            $('#supportWorkerInfo').removeClass('hidden');
                        }
                    });
                } else {
                    $('#supportWorkerInfo').addClass('hidden');
                }
            });
        });
    </script>
</body>

</html>