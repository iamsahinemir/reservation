Laboratory - Appointment System

This project is a device reservation system designed for the Laboratory. The system has two types of users: regular users and administrators.
User Types
Admin (Administrator)

    Can add or delete users.
    Can assign admin or user privileges to other users.
    Can manage device status (active/inactive).
    Can approve or reject reservations.
    Can access the calendar and view reservations.
    Can make reservations specifically for administrators.

User

    Can log in to the system.
    Can view device details.
    Can create reservations, access the calendar.
    Can manage their reservations (view, delete).
    Can update their profile information.

Important Files and Functions

    add_user.php: Adds a user.
    delete_user.php: Deletes a user.
    admin_dashboard.php: Admin homepage.
    admin_reservations.php: Reservation management.
    admin_rez.php: Allows admins to create reservations.
    admin_takvim.php: Calendar access and viewing reservation dates.
    admin_users.php: Manage users and assign privileges.
    calendar.php: Create reservations.
    devices.php: View device details.
    make_reservation.php: Submits reservation requests for both users and admins.
    profile.php: Manage user profile information.
    reservation.php: Access user reservations.
    takvim.php: Calendar access.
    test_db.php: Database connection test.
    user.php: User homepage.
    index.php: Login page, user registration, and password reset.
    login.php: User login.
    register.php: User registration.
    verification.php: Creates a token for user account verification.
    logout.php: Logs the user out of the system.
    config.php: Database connection.
    forgot_password.php: Password reset functionality.
    PHPMailer: Library used for email operations.

Email Operations

The system sends emails for operations such as password resets, account verification, reservation approval, and rejection. The PHPMailer library is used for these tasks.

Directory Structure

.
├── add_user.php
├── admin_dashboard.php
├── admin_reservations.php
├── admin_rez.php
├── admin_takvim.php
├── admin_users.php
├── calendar.php
├── ccip photos
│   ├── fat_et.jpg
│   ├── table_1.jpg
│   └── ...
├── config.php
├── devices.php
├── en
│   ├── add_user.php
│   └── ...
├── forgot_password.php
├── index.php
├── login.php
├── logout.php
├── make_reservation.php
├── PHPMailer
│   ├── LICENSE
│   └── ...
├── profile.php
├── README.md
├── register.php
├── reservations.php
├── scripts.js
├── styles.css
├── takvim.php
├── test_db.php
├── user.php
└── verification.php

This project has been made by Emir Esad Şahin
