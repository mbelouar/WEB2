<?php
/**
 * Database connection file for the Admin section
 */

function connectDB() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cv_generator";
    
    // Create connection with error reporting
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
        return $conn;
    } catch (Exception $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

/**
 * Get all students/candidates from the database
 */
function getAllStudents() {
    $conn = connectDB();
    $students = [];
    
    try {
        $stmt = $conn->prepare("SELECT id, firstname, lastname, email FROM users ORDER BY lastname, firstname");
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        
        $stmt->close();
    } catch (Exception $e) {
        die("Error retrieving students: " . $e->getMessage());
    } finally {
        $conn->close();
    }
    
    return $students;
}

/**
 * Delete a student/candidate from the database
 */
function deleteStudent($id) {
    $conn = connectDB();
    $success = false;
    
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // Delete user (cascading will delete related data)
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            $success = true;
        }
        
        $stmt->close();
        
        // Commit transaction
        $conn->commit();
    } catch (Exception $e) {
        // Roll back in case of error
        if ($conn->connect_errno === 0) {
            $conn->rollback();
        }
        die("Error deleting student: " . $e->getMessage());
    } finally {
        $conn->close();
    }
    
    return $success;
}

/**
 * Get a specific student by ID
 */
function getStudentById($id) {
    error_log('[getStudentById] Looking for student with ID: ' . $id);
    $conn = connectDB();
    $student = null;
    
    try {
        $stmt = $conn->prepare("SELECT id, firstname, lastname, email FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            error_log('[getStudentById] Student found: ' . $student['firstname'] . ' ' . $student['lastname']);
        } else {
            error_log('[getStudentById] No student found with ID: ' . $id);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        error_log('[getStudentById] Error: ' . $e->getMessage());
        die("Error retrieving student: " . $e->getMessage());
    } finally {
        $conn->close();
    }
    
    return $student;
}
?> 