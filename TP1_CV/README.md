# CV Generator Application

A comprehensive web application for creating and managing professional CVs, built with PHP and MySQL. This application allows users to create, edit, and generate PDF versions of their CVs with a modern and professional design.

## Features

- **User-Friendly Interface**: Clean and intuitive form-based interface for CV creation
- **Dynamic Form Fields**: 
  - Personal information management
  - Academic information with module selection
  - Project portfolio management
  - Internship tracking
  - Work experience documentation
  - Skills and competencies
  - Language proficiency
  - Professional interests
  - Profile picture upload
- **Real-time Form Validation**: Client-side validation for required fields
- **PDF Generation**: Ability to generate professional PDF versions of CVs
- **Admin Interface**: Dedicated administration panel for managing CVs
- **Responsive Design**: Works seamlessly on both desktop and mobile devices
- **Session Management**: Secure handling of user data during form submission
- **File Upload Support**: Profile picture upload with validation

## Project Structure

```
TP1_CV/
├── ADMIN/                    # Administration section
│   ├── BD/                  # Database connection and functions
│   │   └── database.php     # Database configuration
│   ├── IHM/                 # Admin interface files
│   │   └── list_students.php # Student management interface
│   └── Traitement/          # Admin processing files
│       ├── clear_session.php
│       ├── delete_student.php
│       └── edit_student.php
├── assets/                  # Static assets
│   └── particles.json       # Particles.js configuration
├── uploads/                 # Profile picture storage
├── formulaire.php          # Main CV creation form
├── recap.php              # Form submission confirmation
├── getInfo.php            # Database operations and data retrieval
├── handle_upload.php      # File upload handling
├── cv_gen.php             # CV generation and display
├── view_cv.php            # CV viewing interface
├── delete_cv.php          # CV deletion handling
├── script.js              # Client-side JavaScript
├── particles.js           # Particles.js library
├── style.css              # Main stylesheet
├── valide.css             # Validation styles
└── confirm.css            # Confirmation page styles
```

## Database Structure

The application uses a MySQL database with the following tables:

1. **users**
   - Primary user information
   - Fields: id, firstname, lastname, email, phone, age, address, github, linkedin, profile_desc, picture_path

2. **academic_info**
   - Educational background
   - Fields: id, user_id, formation, niveau

3. **modules**
   - Academic modules/courses
   - Fields: id, academic_id, module_name

4. **projects**
   - Project portfolio
   - Fields: id, user_id, name, description, start_date, end_date

5. **internships**
   - Internship experiences
   - Fields: id, user_id, name, description, start_date, end_date, company, location

6. **experiences**
   - Work experiences
   - Fields: id, user_id, name, description, start_date, end_date, company, location, position

7. **skills**
   - Professional skills
   - Fields: id, user_id, skill_name

8. **interests**
   - Professional interests
   - Fields: id, user_id, interest_name

9. **languages**
   - Language proficiency
   - Fields: id, user_id, language_name, level

## Setup Instructions

1. **Prerequisites**
   - XAMPP installed and running (Apache and MySQL)
   - Access to PHPMyAdmin

2. **Database Setup**
   - Start XAMPP server (Apache and MySQL services)
   - Open PHPMyAdmin (http://localhost/phpmyadmin)
   - Create new database named "cv_generator"
   - Import the database structure from `database_setup.sql`

3. **Application Setup**
   - Place the project files in your XAMPP htdocs directory
   - Ensure proper permissions on the uploads directory
   - Configure database connection in `getInfo.php` if needed

4. **Access the Application**
   - CV Generator: http://localhost/WEB2/TP1_CV/formulaire.php
   - Admin Interface: http://localhost/WEB2/TP1_CV/ADMIN/index.php

## Usage

1. **Creating a CV**
   - Access the CV generator form
   - Fill in all required information
   - Upload a profile picture
   - Submit the form
   - Review and generate PDF

2. **Editing a CV**
   - Access through admin interface
   - Click "Modifier" on the desired CV
   - Update information as needed
   - Save changes

3. **Managing CVs (Admin)**
   - Access admin interface
   - View all CVs
   - Edit or delete CVs as needed
   - Generate PDF versions

## Technical Details

- **Frontend**: HTML5, CSS3, JavaScript, jQuery, Bootstrap 5
- **Backend**: PHP 7+
- **Database**: MySQL
- **PDF Generation**: html2canvas, jsPDF
- **File Upload**: Supports JPG, PNG, JPEG (max 5MB)
- **Session Management**: PHP sessions for form data persistence

## Security Features

- Input validation and sanitization
- Prepared statements for database queries
- File upload validation
- Session-based authentication
- XSS prevention

## Troubleshooting

1. **Database Connection Issues**
   - Verify MySQL service is running
   - Check database credentials in `getInfo.php`
   - Default XAMPP credentials: username="root", password=""

2. **File Upload Problems**
   - Check uploads directory permissions
   - Verify file size limits in PHP configuration
   - Ensure valid file types

3. **Session Issues**
   - Check PHP session configuration
   - Verify session directory permissions
   - Clear browser cache if needed

## Contributing

Feel free to submit issues and enhancement requests! 