<?php
include 'db_connection.php'; // Assumes this file sets up $conn

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');

    if (empty($title) || empty($author) || empty($category)) {
        $error_message = "All fields are required.";
    } else {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO books (title, author, category) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $author, $category);

        if ($stmt->execute()) {
            $success_message = "Book added successfully!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Output result
    if (!empty($error_message)) {
        echo "<div class='error'>$error_message</div>";
    } elseif (!empty($success_message)) {
        echo "<div class='success'>$success_message</div>";
    }

    exit;
}
?>
