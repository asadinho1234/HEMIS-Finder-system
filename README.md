# HEMIS Finder - Student Information System

A simple PHP/MySQL application to look up student HEMIS numbers and academic information.

## Features

- Search for students by Student ID
- Display student information including HEMIS number and degree program
- Show student status (Learning, Graduated, or Closed)
- Responsive design for all devices

## Installation

1. **Set up your web server environment**:
   - Install XAMPP, WAMP, MAMP, or any PHP/MySQL server
   - Start Apache and MySQL services

2. **Create the database**:
   - Open phpMyAdmin (usually at http://localhost/phpmyadmin)
   - Create a new database named "hemis_finder"
   - Import the `database.sql` file or run the queries manually

3. **Configure database connection**:
   - Edit `config/database.php` with your MySQL username and password if different from the defaults

4. **Access the application**:
   - Place the project folder in your web server's document root (e.g., htdocs for XAMPP)
   - Open your web browser and navigate to http://localhost/hemis-finder/

## Usage

1. Enter a student ID in the search box (e.g., 20190052)
2. Click the Search button
3. View the student's information, including HEMIS number and status

## Sample Student IDs for Testing

- 20190052 - Emily Johnson (Graduated)
- 20190054 - Michael Smith (Learning)
- 20190055 - Sarah Williams (Graduated)
- 20190056 - James Wilson (Closed)

## Security Considerations

This implementation includes basic security measures:
- Using PDO with prepared statements to prevent SQL injection
- Input validation and sanitization
- Output escaping with htmlspecialchars() to prevent XSS attacks

For a production environment, consider implementing:
- User authentication for admin access
- HTTPS
- CSRF protection for forms
- Rate limiting to prevent brute force attacks