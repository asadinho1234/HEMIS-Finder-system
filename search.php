<?php
// Include database connection
require_once 'config/database.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if request is GET and student_id is set
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['student_id'])) {
    $student_id = trim($_GET['student_id']);
    
    // Validate input
    if (empty($student_id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Please enter a Student ID']);
        exit;
    }
    
    // Create database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // Prepare query
    $query = "SELECT * FROM students WHERE student_id = :student_id LIMIT 1";
    $stmt = $db->prepare($query);
    
    // Bind parameters
    $stmt->bindParam(":student_id", $student_id);
    
    try {
        // Execute query
        $stmt->execute();
        
        // Check if student exists
        if ($stmt->rowCount() > 0) {
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode([
                'success' => true,
                'student' => [
                    'name' => $student['name'],
                    'student_id' => $student['student_id'],
                    'hemis_number' => $student['hemis_number'],
                    'degree_program' => $student['degree_program'],
                    'graduated' => $student['graduated'] // Returns 'learning', 'graduate', or 'closed'
                ]
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No student found with ID: ' . $student_id]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>