<?php
include 'db_connect.php';

require('vendor/autoload.php');
// $con=mysqli_connect('localhost','root','','youtube');
// $res=mysqli_query($con,"select * from user");
// if(mysqli_num_rows($res)>0){
// 	$html='<style></style><table class="table">';
// 		$html.='<tr><td>ID</td><td>Name</td><td>Email</td></tr>';
// 		while($row=mysqli_fetch_assoc($res)){
// 			$html.='<tr><td>'.$row['id'].'</td><td>'.$row['name'].'</td><td>'.$row['email'].'</td></tr>';
// 		}
// 	$html.='</table>';
// }else{
// 	$html="Data not found";





// Creaet a table for the following fields:

// Participant Name
// Login Time
// Logout Time	
// Incident Details
// Support Workwe Info
// Specific Instructions











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
// }
$mpdf=new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$file='media/'.time().'.pdf';
$mpdf->output($file,'D');
//D
//I
//F
//S
?>