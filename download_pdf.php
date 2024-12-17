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
                        <td>Abid</td>
                    </tr>

                    <tr>
                        <th>Login Time</th>
                        <td>2024-11-23 08:06:55</td>
                    </tr>

                    <tr>
                        <th>Logout Time</th>
                        <td>2024-11-23 14:02:28</td>
                    </tr>

                    <tr>
                        <th>Incident Details</th>
                        <td>                
                            <table>
                                <tr>
                                    <tr><td>Incident Time: 15:03:00</td></tr>
                                    <tr></tr><td>Location: Bed Room</td></tr>
                                    <tr><td>Calm Time: 00:00:06</td></tr>
                                    <tr><td>Description: Desc</td></tr>
                                    <tr><td>Hurt: yes</td></tr>
                                    <tr><td>Status: sat</td></tr>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <th>Support Worker Info</th>
                        <td>               
                            <table>
                                <tr>
                                    <tr><td>Name: staff 1</td></tr>
                                    <tr><td>Email: staff1@gmail.com</td></tr>
                                    <tr><td>Location: sservice location</td></tr>
                                    <tr><td>Details: Do something</td></tr>
                                    <tr><td>Medication: Morning Lunch Evening Bedtime</td></tr>
                                    <tr><td>Handover: Support worker 2</td></tr>
                                    </tr>
                            </table>
                        </td>
                    </tr>


                    <tr>
                        <th>Specific Instructions</th>
                        <td>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book</td>
                    </tr>
            
            <tbody>';

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