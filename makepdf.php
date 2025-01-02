<?php

require_once 'vendor/autoload.php';

// Include database connection
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['entry_id'])) {
    $entry_id = $_POST['entry_id'];

    // Fetch the log entry details
    $sql = "SELECT l.*, u.username, p.name AS participant_name 
            FROM log_entries l
            JOIN users u ON l.user_id = u.id
            JOIN participants p ON l.participant_id = p.id
            WHERE l.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $entry_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $log = $result->fetch_assoc();

        $mpdf = new \Mpdf\Mpdf();



        // Create HTML content
        $html = '<style>
            body {
                font-family: Arial, sans-serif;
            }
            .report-header {
                text-align: center;
                margin-bottom: 20px;
            }
            .report-title {
                font-size: 24px;
                font-weight: bold;
            }
            .report-date {
                font-size: 14px;
                color: #666;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            table, th, td {
                border: 1px solid #ddd;
            }
            th, td {
                padding: 12px;
                text-align: left;
            }
            th {
                background-color: #f4f4f4;
                color: #333;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            tr:hover {
                background-color: #f1f1f1;
            }
        </style>
        <div class="report-header">
            <div class="report-title">Participant Report</div>
            <div class="report-date">' . date('Y-m-d') . '</div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <tr>
                        <th>Participant Name</th>
                        <td>' . $log['participant_name'] . '</td>
                    </tr>

                    <tr>
                        <th>Login Time</th>
                        <td>' . $log['login_time'] . '</td>
                    </tr>

                    <tr>
                        <th>Logout Time</th>
                        <td>' . $log['logout_time'] . '</td>
                    </tr>

                    <tr>
                        <th>Incident Details</th>
                        <td>                
                            <table>
                                <tr>
                                    <tr><td>Incident Time:  ' . $log['incident_time'] . '</td></tr>
                                    <tr><td>Location:' . $log['incident_location'] . '</td></tr>
                                    <tr><td>Calm Time:' . $log['calm_time'] . '</td></tr>
                                    <tr><td>Description:' . $log['description'] . '</td></tr>
                                    <tr><td>Hurt: ' . $log['hurt'] . '</td></tr>
                                    <tr><td>Status:' . $log['current_status'] . '</td></tr>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <th>Support Worker Info</th>
                        <td>               
                            <table>
                                <tr>
                                    <tr><td>Name:' . $log['staff_name'] . '</td></tr>
                                    <tr><td>Email: ' . $log['staff_email'] . '</td></tr>
                                    <tr><td>Location: ' . $log['service_location'] . '</td></tr>
                                    <tr><td>Details: ' . $log['support_details'] . '</td></tr>
                                    <tr><td>Medication:' . $log['medication'] . '</td></tr>
                                    <tr><td>Handover: ' . $log['handover'] . '</td></tr>
                                    </tr>
                            </table>
                        </td>
                    </tr>


                    <tr>
                        <th>Specific Instructions</th>
                        <td>' . $log['specific_instructions'] . '</td>
                    </tr>
            
            <tbody>
            </table>
    
    ';

        echo $html;

        $mpdf->WriteHTML($html);
    
    
        //output to the browser
        $mpdf->Output($log['login_time'], 'D');    
    } else {
        echo "No data found for the specified entry.";
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
