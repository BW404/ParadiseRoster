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
- <s>Add edit and delete functionality for support workers. </s>
- <s>Add a way to inform the next support worker about privious state of the participant.</s>
- <s> Make the ui responsive</s>
- Add authentication for admin_dashboard
- Fix the bug when adding participants. 


## **Contributing**

Contributions are welcome! Please fork the repository and create a pull request with your changes.

## **License**

This project is licensed under the MIT License. See the LICENSE file for details.