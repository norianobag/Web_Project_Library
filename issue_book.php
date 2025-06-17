
<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $book_id = $_POST['book_id'];
    $issue_date = date('Y-m-d');

    // Check if the student exists in the Students table
    $student_check_query = "SELECT * FROM students WHERE student_id='$student_id'";
    $student_result = mysqli_query($conn, $student_check_query);

    if (mysqli_num_rows($student_result) == 0) {
        echo "Error: Student ID not found. Please add the student first.";
        exit;
    }

    // Check if the book is available
    $check_query = "SELECT status FROM books WHERE id='$book_id'";
    $result = mysqli_query($conn, $check_query);
    $book = mysqli_fetch_assoc($result);

    if ($book['status'] == 'Available') {
        $query = "INSERT INTO transactions (student_id, book_id, issue_date, status) 
                  VALUES ('$student_id', '$book_id', '$issue_date', 'Issued')";
        $update_book_status = "UPDATE books SET status='Issued' WHERE id='$book_id'";

        if (mysqli_query($conn, $query) && mysqli_query($conn, $update_book_status)) {
            echo "✅ Book issued successfully!";
        } else {
            echo "❌ Error: " . mysqli_error($conn);
        }
    } else {
        echo "⚠ Book is already issued.";
    }
    exit;
}
?>