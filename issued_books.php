<?php
include '../db_connection.php';

// Get the first student_id from the database
$student_query = mysqli_query($conn, "SELECT student_id FROM students ORDER BY student_id ASC LIMIT 1");
if (!$student_query || mysqli_num_rows($student_query) === 0) {
    echo "❌ Error: No students found in the database.";
    return;
}

$student_row = mysqli_fetch_assoc($student_query);
$student_id = $student_row['student_id'];

// Fetch issued books for that student
$result = mysqli_query($conn, "SELECT books.title, transactions.return_date, transactions.id 
                               FROM transactions 
                               JOIN books ON transactions.book_id = books.id 
                               WHERE transactions.student_id='$student_id' AND transactions.status='Issued'");

// Optional: check for query failure
if (!$result) {
    echo "❌ Error fetching transactions: " . mysqli_error($conn);
    return;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Issued Books - Library Hub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --secondary: #60a5fa;
            --accent: #ef4444;
            --accent-hover: #dc2626;
            --dark-bg: #0f172a;
            --card-bg: #1e293b;
            --text-light: #e2e8f0;
            --text-muted: #94a3b8;
            --nav-border: rgba(148, 163, 184, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }

        body {
            background: linear-gradient(145deg, var(--dark-bg) 0%, #1e293b 100%);
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            position: relative;
            margin: 0;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
            z-index: -1;
            animation: pulse 15s infinite;
            contain: strict;
        }

        /* Enhanced Navbar */
        .navbar {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 0.8rem 2rem;
            border-bottom: 1px solid var(--nav-border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            padding: 0 1rem;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            color: var(--secondary);
        }

        .navbar-brand i {
            font-size: 1.8rem;
            color: var(--primary);
        }

        .nav-item {
            position: relative;
            margin: 0 0.5rem;
        }

        .nav-link {
            color: var(--text-light) !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.75rem 1.25rem !important;
            transition: all 0.3s ease;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .nav-link:hover {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary) !important;
        }

        .nav-link.active {
            background: rgba(59, 130, 246, 0.2);
            color: var(--primary) !important;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 70%;
            height: 3px;
            background: var(--primary);
            border-radius: 3px;
        }

        .btn-logout {
            background: var(--accent);
            color: #fff !important;
            border-radius: 8px;
            padding: 0.75rem 1.5rem !important;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: 0.5rem;
        }

        .btn-logout:hover {
            background: var(--accent-hover) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-logout i {
            font-size: 1rem;
        }

        /* Main Container */
        .dashboard-container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 1rem;
            width: 100%;
            min-height: calc(100vh - 6rem);
            overflow-y: auto;
        }

        .welcome-text {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 3rem;
            text-shadow: 0 2px 10px rgba(59, 130, 246, 0.4);
            animation: fadeInDown 1s ease-in;
        }

        /* Book Cards */
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            width: 100%;
            margin-bottom: 2rem;
        }

        .book-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            transition: all 0.4s ease;
            border-left: 5px solid var(--secondary);
        }

        .book-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
            transform: rotate(30deg);
            transition: all 0.5s ease;
            opacity: 0;
            z-index: 0;
        }

        .book-card:hover::before {
            opacity: 1;
            transform: rotate(0deg);
        }

        .book-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }

        .book-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .book-info {
            color: var(--text-muted);
            font-size: 1rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .btn-return {
            display: inline-block;
            padding: 0.75rem 2rem;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #fff;
            position: relative;
            z-index: 1;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border: none;
            cursor: pointer;
            width: 100%;
        }

        .btn-return:hover {
            background: linear-gradient(90deg, var(--secondary), var(--primary));
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.5);
            transform: scale(1.05);
        }

        .no-books {
            text-align: center;
            color: var(--text-muted);
            font-size: 1.2rem;
            padding: 3rem;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            grid-column: 1 / -1;
        }

        /* Additional Book Info Section */
        .additional-books {
            margin-top: 3rem;
            padding: 2rem;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .additional-books h3 {
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 0.5rem;
            display: inline-block;
        }

        .additional-book-info {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .additional-book-item {
            background: rgba(59, 130, 246, 0.1);
            padding: 1rem;
            border-radius: 10px;
            flex: 1;
            min-width: 200px;
        }

        .additional-book-item i {
            color: var(--primary);
            margin-right: 0.5rem;
        }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .book-grid {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            }

            .welcome-text {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0.8rem 1rem;
            }

            .nav-link {
                padding: 0.6rem 1rem !important;
                font-size: 0.9rem;
            }

            .book-card {
                padding: 1.5rem;
            }

            .welcome-text {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-container {
                margin: 2rem auto;
                padding: 0 0.5rem;
            }

            .navbar-brand {
                font-size: 1.3rem;
            }

            .navbar-brand i {
                font-size: 1.5rem;
            }

            .book-title {
                font-size: 1.25rem;
            }

            .btn-return {
                padding: 0.6rem 1.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Enhanced Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="navbar-container container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-book-open"></i>
                <span>Library Hub</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="books.php">
                            <i class="fas fa-book"></i>
                            <span>Books</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="issued_books.php">
                            <i class="fas fa-list-check"></i>
                            <span>Issued</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-logout" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="dashboard-container">
        <h1 class="welcome-text">Your Issued Books</h1>
        
        <div class="book-grid">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='book-card'>";
                    echo "<h3 class='book-title'><i class='fas fa-book'></i> {$row['title']}</h3>";
                    echo "<p class='book-info'>Return Date: {$row['return_date']}</p>";
                    echo "<button class='btn-return' onclick='returnBook({$row['transaction_id']})'>Return Book</button>";
                    echo "</div>";
                }
            } else {
                echo "<div class='no-books'>";
                echo "<i class='fas fa-book-open' style='font-size: 3rem; margin-bottom: 1rem;'></i>";
                echo "<p>You don't have any issued books at the moment.</p>";
                echo "</div>";
            }
            ?>
        </div>

        <!-- Additional Book Information Section -->
        <div class="additional-books">
            <h3><i class="fas fa-info-circle"></i> Recently Added Books</h3>
            <div class="additional-book-info">
                <div class="additional-book-item">
                    <i class="fas fa-book"></i> <strong>Title:</strong> The Darkness Outside Us
                </div>
                <div class="additional-book-item">
                    <i class="fas fa-calendar-alt"></i> <strong>Return Date:</strong> 2025-06-19
                </div>
                <div class="additional-book-item">
                    <i class="fas fa-user-tag"></i> <strong>Author:</strong> Eliot Schrefer
                </div>
                <div class="additional-book-item">
                    <i class="fas fa-barcode"></i> <strong>ISBN:</strong> 9781338343822
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function returnBook(transactionId) {
            Swal.fire({
                title: "Are you sure?",
                text: "Do you really want to return this book?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3b82f6",
                cancelButtonColor: "#ef4444",
                confirmButtonText: "Yes, Return it!",
                background: "#1e293b",
                color: "#e2e8f0",
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('return_book.php?transaction_id=' + transactionId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: "Returned!",
                                    text: "Book returned successfully. Fine: ₹" + data.fine,
                                    icon: "success",
                                    background: "#1e293b",
                                    color: "#e2e8f0",
                                    confirmButtonColor: "#3b82f6"
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: data.message,
                                    icon: "error",
                                    background: "#1e293b",
                                    color: "#e2e8f0",
                                    confirmButtonColor: "#3b82f6"
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: "Error!",
                                text: "Server connection failed!",
                                icon: "error",
                                background: "#1e293b",
                                color: "#e2e8f0",
                                confirmButtonColor: "#3b82f6"
                            });
                        });
                }
            });
        }
    </script>
</body>
</html>
