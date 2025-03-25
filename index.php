<?php
// Start session
session_start();

// Include database connection
require_once 'config/database.php';

// Initialize variables
$student = null;
$error = "";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    $student_id = trim($_POST['student_id']);
    
    // Validate input
    if (empty($student_id)) {
        $error = "Please enter a Student ID";
    } else {
        // Create database connection
        $database = new Database();
        $db = $database->getConnection();
        
        // Prepare query
        $query = "SELECT * FROM students WHERE student_id = :student_id LIMIT 1";
        $stmt = $db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(":student_id", $student_id);
        
        // Execute query
        $stmt->execute();
        
        // Check if student exists
        if ($stmt->rowCount() > 0) {
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = "No student found with ID: " . htmlspecialchars($student_id);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEMIS Finder - Student Information System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <p class="system-title">HIGH EDUCATION MANAGEMENT INFORMATION SYSTEM</p>
            <h1>HEMIS FINDER</h1>
        </header>
        
        <main class="card">
            <div class="search-section">
                <h2>Find Your HEMIS Number</h2>
                <p class="subtitle">Enter your Student ID to retrieve your HEMIS number and academic information</p>
                
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="search-form">
                    <input 
                        type="text" 
                        name="student_id" 
                        placeholder="Enter Student ID" 
                        value="<?php echo isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : ''; ?>"
                    >
                    <button type="submit" class="search-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        Search
                    </button>
                </form>
                
                <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if ($student): ?>
            <div class="result-section">
                <div class="student-header">
                    <h3 class="student-name"><?php echo htmlspecialchars($student['name']); ?></h3>
                    
                    <?php 
                    // Display badge based on graduated status
                    $statusClass = '';
                    $statusText = '';
                    
                    switch($student['graduated']) {
                        case 'learning':
                            $statusClass = 'badge-learning';
                            $statusText = 'Learning';
                            break;
                        case 'graduate':
                            $statusClass = 'badge-graduate';
                            $statusText = 'Graduated';
                            break;
                        case 'closed':
                            $statusClass = 'badge-closed';
                            $statusText = 'Closed';
                            break;
                    }
                    ?>
                    
                    <span class="badge <?php echo $statusClass; ?>">
                        <?php echo $statusText; ?>
                    </span>
                </div>
                
                <div class="student-details">
                    <div class="detail-item">
                        <p class="detail-label">Student ID</p>
                        <p class="detail-value"><?php echo htmlspecialchars($student['student_id']); ?></p>
                    </div>
                    
                    <div class="detail-item">
                        <p class="detail-label">HEMIS Number</p>
                        <p class="detail-value hemis"><?php echo htmlspecialchars($student['hemis_number']); ?></p>
                    </div>
                    
                    <div class="detail-item">
                        <p class="detail-label">Degree Program</p>
                        <p class="detail-value"><?php echo htmlspecialchars($student['degree_program']); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>
    
    <!-- Optional: Add JavaScript for enhanced functionality -->
    <script src="js/script.js"></script>
</body>
</html>