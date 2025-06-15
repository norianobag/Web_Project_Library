
<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $department = $_POST['department'];

    // Prevent SQL injection
    $student_id = mysqli_real_escape_string($conn, $student_id);
    $name = mysqli_real_escape_string($conn, $name);
    $department = mysqli_real_escape_string($conn, $department);

    // Check if student already exists
    $check_query = "SELECT * FROM students WHERE student_id='$student_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "Error: A student with this ID already exists.";
        exit;
    } else {
        // Insert the new student into the Students table
        $query = "INSERT INTO students (student_id, name, department) VALUES ('$student_id', '$name', '$department')";
        
        if (mysqli_query($conn, $query)) {
            echo "student added successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
    exit;
}
?>