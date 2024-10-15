# Art Gallery Management System

## Overview
This project is an **Art Gallery Management System** designed for gallery employees to manage artist, guest, and employee information. The system allows authenticated users to perform CRUD (Create, Read, Update, Delete) operations on the gallery’s data, using a MySQL database deployed on **WampServer**. Key features include user authentication, profile management, and different levels of access control for managing the gallery's artists, guests, and employees.

## Features
- **User Authentication**: Secure login, forgot password functionality, and email verification.
- **Artist Management**: Add, view, update, delete artist information such as name, email, number of art pieces, art style, and exhibition dates.
- **Guest Management**: Track guest attendance, whether they are paid or invited, and manage their "plus one" option.
- **Employee Management**: Accessible only to higher-ranking employees, allowing them to manage employee data, including personal details and positions.
- **Search Functionality**: Quickly search for specific artists or guests from the database.
- **Role-based Access Control**: Only authorized employees can access certain tables, such as the employee table.

## Technology Stack
- **Backend**: 
  - **PHP** for server-side logic.
  - **MySQL** database hosted on **WampServer** to manage gallery data.
- **Frontend**: 
  - HTML, CSS, and JavaScript for the user interface.
  - **Bootstrap** framework for a responsive design.
- **Database**: Structured with tables for artists, guests, and employees.
- **Email Integration**: Email functionality for password recovery and user verification using one-time passwords (OTPs).

## Setup Instructions

### Prerequisites
- **WampServer**: Ensure you have WampServer installed for local server and database hosting.
- **MySQL**: The system requires MySQL to store and manage the gallery's data.

### Steps to Install
1. Download and unzip the project folder, then move it to your WampServer’s `www` directory.
2. Start WampServer and open your web browser.
3. Navigate to the following URL to create the database:
   ```
   http://localhost/ArtGallery/DBInitialisation/createDB.php
   ```
   A success message should appear confirming the creation of the database.
4. Import the `art_gallery.sql` file into phpMyAdmin:
   - Go to WampServer Icon > phpMyAdmin > art_gallery database > Import.
   - Select the `art_gallery.sql` file and click `Import`.
5. Alternatively, you can initialize individual tables by navigating to the following URLs:
   - Artists Table: `http://localhost/ArtGallery/DBInitialisation/createArtistTable.php`
   - Guests Table: `http://localhost/ArtGallery/DBInitialisation/createGuestsTable.php`
   - Employees Table: `http://localhost/ArtGallery/DBInitialisation/createEmployeeTable.php`

### Accessing the System
1. As a first-time user, log in using the following credentials:
   - **Email**: `artgallery23@outlook.com`
   - **Password**: `artgalleryPass`
2. After logging in, you can:
   - **View/Add/Update/Delete**: Artists, Guests, and Employees.
   - **Search**: Use the search bar to filter through records.
   - **Reset Password**: Request a new password via email if needed.

## Core Functionalities

### Artist Management
- **Artist Table**: View all artist details including name, email, exhibition date, and art style.
- **Add New Artist**: Submit artist details through a form and store them in the database.
- **Update Artist**: Modify artist information directly from the table.
- **Delete Artist**: Remove artist records permanently with confirmation.

### Guest Management
- **Guest Table**: Track guest details like name, email, attendance type (paid/invited), and plus-one options.
- **Add New Guest**: Manage guest information, including a toggle for inviting a plus-one based on their attendance type.

### Employee Management
- **Employee Table**: Accessible only to authorized users. Manage employee records including sensitive information like nationality and position.

### Authentication
- **Login**: Secure login using the provided credentials.
- **Forgot Password**: Send a one-time password to the user's email to reset their password securely.
- **Role-Based Access**: Ensure that only authorized users can modify sensitive data like employee records.

## Future Enhancements
- Implement role-specific dashboards for different employee roles (e.g., admin vs. regular employee).
- Add an audit log for tracking changes made to the database.
- Expand the guest management system to include RSVPs and event invitations.
