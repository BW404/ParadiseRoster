<?php
// require('tcpdf/tcpdf.php'); // Ensure TCPDF is included

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

        // // Create new PDF document
        // $pdf = new TCPDF();
        // $pdf->SetCreator(PDF_CREATOR);
        // $pdf->SetAuthor('Admin');
        // $pdf->SetTitle('Participant Report');
        // $pdf->SetHeaderData('', '', 'Participant Report', date('Y-m-d'));
        // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        // $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // $pdf->SetMargins(10, 10, 10);
        // $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // $pdf->AddPage();

        // Create HTML content
        $html = '
        <style>
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
        <table>
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
                    <strong>Incident Time:</strong> ' . $log['incident_time'] . '<br>
                    <strong>Location:</strong> ' . $log['incident_location'] . '<br>
                    <strong>Calm Time:</strong> ' . $log['calm_time'] . '<br>
                    <strong>Description:</strong> ' . $log['description'] . '<br>
                    <strong>Hurt:</strong> ' . $log['hurt'] . '<br>
                    <strong>Status:</strong> ' . $log['current_status'] . '<br>
                </td>
            </tr>
            <tr>
                <th>Support Worker Info</th>
                <td>
                    <strong>Name:</strong> ' . $log['staff_name'] . '<br>
                    <strong>Email:</strong> ' . $log['staff_email'] . '<br>
                    <strong>Location:</strong> ' . $log['service_location'] . '<br>
                    <strong>Details:</strong> ' . $log['support_details'] . '<br>
                    <strong>Medication:</strong> ' . $log['medication'] . '<br>
                    <strong>Handover:</strong> ' . $log['handover'] . '<br>
                </td>
            </tr>
            <tr>
                <th>Specific Instructions</th>
                <td>' . $log['specific_instructions'] . '</td>
            </tr>
        </table>';

        echo $html;

        // Output the HTML content
        // $pdf->writeHTML($html, true, false, true, false, '');

        // Close and output PDF document
        // $pdf->Output('participant_report.pdf', 'D');
    } else {
        echo "No data found for the specified entry.";
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
