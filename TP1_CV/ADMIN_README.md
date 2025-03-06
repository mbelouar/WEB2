# CV Generator - Administration Workflow Guide

This guide explains the complete workflow of the administration interface for managing student CVs.

## File Workflow Diagrams

### 1. Adding a New Student
```
[Admin Interface (index.php)]
         ↓
[clear_session.php]
         ↓
[formulaire.php] → [recap.php]
         ↓
[Database Tables]
```

### 2. Modifying a Student's CV
```
[Admin Interface (index.php)]
         ↓
[edit_student.php]
         ↓
[Database] → [getStudentById()] → [getCvData()]
         ↓
[formulaire.php] → [recap.php]
         ↓
[Database Tables]
```

### 3. Deleting a Student
```
[Admin Interface (index.php)]
         ↓
[delete_student.php]
         ↓
[Database] → [Cascade Delete]
         ↓
[Admin Interface (index.php)]
```

### 4. Detailed Data Flow for Edit Process
```
[Admin Interface]
    ↓ (Click "Modifier")
[edit_student.php]
    ↓ (Get student ID)
[Database]
    ↓ (Retrieve data)
[Session Setup]
    ↓ (Store in $_SESSION)
[formulaire.php]
    ↓ (Pre-fill form)
[User Input]
    ↓ (Submit form)
[recap.php]
    ↓ (Process data)
[Database Update]
    ↓ (Save changes)
[Admin Interface]
```

### 5. Session Data Flow
```
[edit_student.php]
    ↓
$_SESSION['edit_mode'] = true
$_SESSION['cv_data'] = [...]
$_SESSION['user_id'] = ...
    ↓
[formulaire.php]
    ↓
[recap.php]
    ↓
[Database]
```

### 6. Database Operations Flow
```
[User Action]
    ↓
[Process File]
    ↓
[Database Connection]
    ↓
[Transaction Start]
    ↓
[Multiple Table Operations]
    ↓
[Transaction Commit/Rollback]
    ↓
[Response to User]
```

## Starting Point: Admin Interface

The administration process begins at:
```
http://localhost/WEB2/TP1_CV/ADMIN/index.php
```

## Detailed File Workflow

### 1. edit_student.php - Student Modification Process

This file is the central point for initiating student CV modifications. Here's how it works:

#### Location and Access
- **File Path**: `TP1_CV/ADMIN/Traitement/edit_student.php`
- **Access Point**: Clicking "Modifier" button in the admin interface
- **URL Format**: `edit_student.php?id=STUDENT_ID`

#### Process Flow
1. **Initial Setup**
   ```php
   session_start();
   require_once '../../getInfo.php';
   require_once '../BD/database.php';
   ```

2. **ID Validation**
   ```php
   if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
       $_SESSION['message'] = "ID de candidat invalide.";
       header('Location: ../index.php');
       exit();
   }
   ```

3. **Data Retrieval**
   ```php
   $student = getStudentById($studentId);
   $cv_data = getCvData($studentId);
   ```

4. **Session Setup**
   ```php
   $_SESSION['cv_data'] = $cv_data;
   $_SESSION['cv_data']['user_id'] = $studentId;
   $_SESSION['edit_mode'] = true;
   $_SESSION['preserve_edit_mode'] = true;
   ```

5. **Array Fields Initialization**
   ```php
   $arrayFields = [
       'projects', 'projectDesc', 'projectStartDate', 'projectEndDate',
       'stages', 'stageDesc', 'stageStartDate', 'stageEndDate',
       'experiences', 'experienceDesc', 'experienceStartDate', 'experienceEndDate',
       'competences', 'interests', 'modules'
   ];
   ```

6. **Counter Setup**
   ```php
   $_SESSION['cv_data']['projectCount'] = count($_SESSION['cv_data']['projects']);
   $_SESSION['cv_data']['stageCount'] = count($_SESSION['cv_data']['stages']);
   $_SESSION['cv_data']['experienceCount'] = count($_SESSION['cv_data']['experiences']);
   ```

#### Debugging Features
- Comprehensive error logging
- Session data verification
- Database operation tracking
- Redirect to debug page for verification

#### Error Handling
1. **Invalid ID**
   - Redirects to admin interface
   - Shows error message

2. **Student Not Found**
   - Redirects to admin interface
   - Shows "Candidat non trouvé" message

3. **Empty CV Data**
   - Redirects to admin interface
   - Shows "Données du candidat non trouvées" message

#### Security Measures
- Input validation
- Session management
- Database query sanitization
- Error logging

#### Integration Points
1. **With Admin Interface**
   - Receives student ID from admin list
   - Returns error messages to admin interface

2. **With Formulaire.php**
   - Sets up session data for form pre-filling
   - Establishes edit mode flags

3. **With Database**
   - Uses getStudentById() function
   - Uses getCvData() function
   - Maintains data integrity

#### Debugging Tips
1. **Check Session Data**
   ```php
   error_log('Session Data: ' . print_r($_SESSION, true));
   ```

2. **Verify Student Data**
   ```php
   error_log('Student Data: ' . print_r($student, true));
   ```

3. **Monitor Redirects**
   ```php
   error_log('Redirecting to: ' . $redirect_url);
   ```

## 1. Adding a New Student

### Process Flow:
1. Click "Ajouter un Candidat" button
2. **File: `ADMIN/Traitement/clear_session.php`**
   - Clears any existing session data
   - Redirects to the CV creation form

3. **File: `formulaire.php`**
   - Displays empty form for new CV creation
   - No edit mode flags are set
   - Form submits to `recap.php`

4. **File: `recap.php`**
   - Processes the form submission
   - Saves data to database
   - Sets up edit mode for future modifications
   - Shows confirmation message

## 2. Modifying a Student's CV

### Process Flow:
1. Click "Modifier" button on student list
2. **File: `ADMIN/Traitement/edit_student.php`**
   - Receives student ID from URL
   - Retrieves student data from database
   - Sets up edit mode in session
   - Redirects to CV form

3. **File: `formulaire.php`**
   - Detects edit mode from session
   - Pre-fills form with student's data
   - Includes hidden fields for edit_mode and user_id
   - Form submits to `recap.php`

4. **File: `recap.php`**
   - Processes the form submission
   - Updates existing record instead of creating new one
   - Shows confirmation message

## 3. Deleting a Student

### Process Flow:
1. Click "Supprimer" button on student list
2. **File: `ADMIN/Traitement/delete_student.php`**
   - Receives student ID from URL
   - Shows confirmation dialog
   - If confirmed:
     - Deletes user record (cascading deletes related data)
     - Sets success/error message
     - Redirects back to admin interface

## Database Operations

### Adding New Student:
1. Insert into `users` table
2. Insert into `academic_info` table
3. Insert into `modules` table
4. Insert into `projects` table
5. Insert into `internships` table
6. Insert into `experiences` table
7. Insert into `skills` table
8. Insert into `interests` table
9. Insert into `languages` table

### Modifying Student:
1. Update `users` table
2. Delete and re-insert related data in other tables
3. Maintain existing profile picture if not changed

### Deleting Student:
1. Delete from `users` table (cascading deletes related records)
2. Clean up uploaded files

## Session Management

### Key Session Variables:
- `$_SESSION['edit_mode']`: Boolean flag for edit mode
- `$_SESSION['cv_data']`: Array containing form data
- `$_SESSION['user_id']`: Current user ID being edited
- `$_SESSION['preserve_edit_mode']`: Prevents clearing edit mode on direct access

## Error Handling

### Common Scenarios:
1. **Invalid Student ID**
   - Redirects to admin interface
   - Shows error message

2. **Database Errors**
   - Rolls back transactions
   - Logs error details
   - Shows user-friendly error message

3. **File Upload Issues**
   - Validates file type and size
   - Shows specific error messages
   - Maintains form data

## Security Measures

1. **Input Validation**
   - Sanitizes all user inputs
   - Validates file uploads
   - Checks required fields

2. **Database Security**
   - Uses prepared statements
   - Implements transaction management
   - Handles cascading deletes safely

3. **Session Security**
   - Validates session data
   - Prevents unauthorized access
   - Cleans up sensitive data

## Debugging Tips

1. **Check Session Data**
   ```php
   error_log('Session Data: ' . print_r($_SESSION, true));
   ```

2. **Database Operations**
   ```php
   error_log('Database Operation: ' . $sql);
   error_log('Parameters: ' . print_r($params, true));
   ```

3. **File Operations**
   ```php
   error_log('File Upload: ' . print_r($_FILES, true));
   ```

## Common Issues and Solutions

1. **Form Not Pre-filling**
   - Check session data is properly set
   - Verify edit mode flags
   - Check database connection

2. **Delete Operation Fails**
   - Check foreign key constraints
   - Verify user permissions
   - Check file system permissions

3. **Session Data Lost**
   - Check session configuration
   - Verify session storage permissions
   - Clear browser cache

## Best Practices

1. **Data Management**
   - Always use transactions for multiple operations
   - Implement proper error handling
   - Clean up temporary files

2. **User Experience**
   - Show clear success/error messages
   - Maintain form data on errors
   - Provide confirmation dialogs for destructive actions

3. **Security**
   - Validate all inputs
   - Use prepared statements
   - Implement proper access controls 