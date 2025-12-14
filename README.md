# Alex Driving School Management System

A comprehensive web-based management system for driving schools, built with PHP, MySQL, and Bootstrap.

## Features

### Public Portal
-   **Student Registration:** Online application with document upload (NIC, Address Proof) and webcam photo support.
-   **Student Login:** Secure portal for students to check their status.
-   **Dashboard:** Students can view their profile and (in future) schedule/payments.

### Admin Dashboard
-   **Approvals:** Review and approve/reject new student registrations.
-   **Payments:** Track 1st and 2nd installment payments and exam fees.
-   **Scheduling:** Manage driving lessons, theory tests, and practical tests.
-   **Results:** Record and view student exam results (Theory/Practical).
-   **Reports:** Analytics on revenue, student counts, and exam pass rates.

## Installation

1.  **Server Requirements:**
    -   Apache Server (XAMPP/WAMP/LAMP)
    -   MySQL Database
    -   PHP 7.4+

2.  **Setup:**
    -   Clone/Copy project files to `htdocs/drivingSchool`.
    -   Import the database structure.

3.  **Database Configuration:**
    -   Create a database named `dms`.
    -   Import `includes/db_updates.sql` (Phase 1) and `includes/db_fix.sql` (Phase 2) to set up all tables.
    -   Check `includes/connection.php` to ensure username/password matches your local MySQL setup (Default: root/empty).

## Directory Structure
-   `admin/` - Administrative modules (Payments, Results, Schedules, Reports).
-   `assets/` - CSS, JS, Images, and Uploaded Documents.
-   `includes/` - Database connection and helper scripts.
-   `index.html` - Main landing page.
-   `registration.php` - Student application form.
-   `student_login.php` - Student entry point.

## Usage
-   **Admin Access:** Navigate to `login.php` (Default: admin/admin123 - *You may need to insert this into the `admin` table manually for first use*).
-   **Student Access:** Register via "Apply Now", wait for admin approval, then login.
