# **Paradise Roster**

Paradise Roster is a web application designed to manage user logins and logouts for participants, track incidents, and provide administrative functionalities. The application is built using PHP and MySQL.

## **Table of Contents**

- Installation
- Usage
- File Structure
- Database Schema
- Contributing
- License

## **Installation**

1. Clone the repository to your local machine:
    
    git clone https://github.com/yourusername/paradise-roster.git
    
2. Navigate to the project directory:
    
    cd paradise-roster
    
3. Set up your web server (e.g., XAMPP, WAMP) and place the project directory in the server's root directory (e.g., `htdocs` for XAMPP).
4. Create a MySQL database named `ParadiseRoster` and import the log_entries.sql file to set up the database schema:
    
    mysql -u root -p ParadiseRoster *<* log_entries.sql
    
5. Update the database connection settings in db_connect.php:
    
    `<?php`
    
    `$host = "localhost";`
    
    `$username = "root";`
    
    `$password = "";`
    
    `$database = "ParadiseRoster";`
    
6. Start your web server and navigate to `http://localhost/paradise-roster` in your web browser.

## **Usage**

1. **Register a new user:**
    - Navigate to the registration page (`register.php`) and fill out the form to create a new user account.
2. **Login:**
    - Navigate to the login page (`login.php`) and enter your credentials to log in.
3. **Dashboard:**
    - After logging in, you will be redirected to the dashboard (`dashboard.php`), where you can select a participant and perform login or logout actions.
4. **Admin Dashboard:**
    - Navigate to the admin dashboard (`admin_dashboard.php`) to manage users, view reports, and manage participants.

## **File Structure**

- admin_dashboard.php
- dashboard.php
- db_connect.php
- log_entries.sql
- login.php
- process_action.php
- register.php

### File Descriptions

- admin_dashboard.php: Admin dashboard for managing users, viewing reports, and managing participants.
- auto_push.py: Script for automated tasks (ignored by Git).
- dashboard.php: User dashboard for logging in and out participants.
- db_connect.php: Database connection settings.
- log_entries.sql: SQL file for setting up the database schema.
- login.php: User login page.
- process_action.php: Script for processing login and logout actions.
- register.php: User registration page.

## **Database Schema**

CREATE TABLE `log_entries` (
`id` int(11) NOT NULL,
`user_id` int(11) NOT NULL,
`participant_id` int(11) NOT NULL,
`action` enum('login','logout') NOT NULL,
`login_time` datetime DEFAULT NULL,
`logout_time` datetime DEFAULT NULL,
`incident_details` text DEFAULT NULL,
`specific_instructions` text DEFAULT NULL,
`incident_time` time DEFAULT NULL,
`incident_location` varchar(255) DEFAULT NULL,
`calm_time` time DEFAULT NULL,
`description` text DEFAULT NULL,
`hurt` enum('yes','no') DEFAULT NULL,
`current_status` text DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `user_id` (`user_id`),
KEY `participant_id` (`participant_id`),
CONSTRAINT `log_entries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
CONSTRAINT `log_entries_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


## **TODO**
- <s> Add following fields before incident details (This part will be visible to the next support worker ).
    - Staff Name:
    - Staff Contact: 
    - Staff Email:
    - Service Location:
    - Details of the today's Support:
    - Medication: (Can select multiple options)
        - Morning
        - Lunch
        - Evening
        - Bedtime
    - Handover: to which support worker( name)
    - Instructions for next Staff: </s>
- Add authentication for admin_dashboard
- Add edit and delete functionality for support workers
- Fix the bug when adding participants. 
- Add a way to inform the next support worker about privious state of the participant.
- Make the ui responsive


## **Contributing**

Contributions are welcome! Please fork the repository and create a pull request with your changes.

## **License**

This project is licensed under the MIT License. See the LICENSE file for details.