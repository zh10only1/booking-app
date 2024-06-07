CSV File Processor with PHP and MySQL
=====================================

This project is a simple PHP application that handles CSV file uploads, processes the data, and stores it in a MySQL database. The application is designed to work with XAMPP, which provides an easy-to-use local development environment including Apache, PHP, and MySQL.

Prerequisites
-------------

-   XAMPP installed on your machine.
-   Basic understanding of PHP and MySQL.
-   Postman for testing API endpoints (optional but recommended).

Setup Instructions
------------------

### Step 1: Install XAMPP

1.  *Download XAMPP*:
    -   Go to the official XAMPP download page and download the version for your operating system.

2.  *Install XAMPP*:
    -   Run the installer and follow the on-screen instructions. Install it in the default directory (e.g., C:\xampp on Windows).

3.  *Start Apache and MySQL*:
    -   Open the XAMPP Control Panel.
    -   Start the Apache and MySQL modules by clicking the "Start" button next to each.

### Step 2: Set Up MySQL Database
1.  *Open phpMyAdmin*:
    -   Open your web browser and go to http://localhost/phpmyadmin.

2.  **Create Database and Table Using booking.sql**:
    -   In phpMyAdmin, click on the "Import" tab.
    -   Click on "Choose file" and select the booking.sql file from your project directory.
    -   Click "Go" to import the file. This will create the bookingDB database and the bookings table with the necessary schema.

### Step 3: Configure the PHP Application
1.  **Create a New Directory in htdocs**:
    -   Open your terminal or command prompt or file explorer and navigate to the XAMPP C:\xampp\htdocs directory.
    -   Create a new directory named booking.

2.  *Clone the Repository*:
    -   Clone the repository into the booking directory:
        `git clone https://github.com/zh10only1/booking-app`

3.  **Ensure db_connection.php Exists**:
    -   The db_connection.php file should already be present in the repository. Verify that it includes the correct database connection settings. *Replace the database parameters with your own credentials*.

### Step 4: Testing the Application
1.  *Start Apache and MySQL*:
    -   Make sure the Apache and MySQL modules are running in the XAMPP Control Panel.

2.  *Test the API with Postman*:
    -   Open Postman.
    -   Create a new POST request with the URL http://localhost/booking/save-csv.php.
    -   In the Body tab, select form-data.
    -   Add a key named csv_file, set its type to File, and upload your CSV file.
    -   Send the request and check the response.

Troubleshooting
---------------
-   *Ensure XAMPP is running*: Make sure Apache and MySQL are started in the XAMPP Control Panel.
-   *Check File Permissions*: Ensure the htdocs directory and files have appropriate permissions.
-   *Database Connection*: Verify the database credentials in db_connection.php and ensure MySQL is running.