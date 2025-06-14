<?php
include 'db_connection.php';

$query = "SELECT transactions.transaction_id, books.title, students.name AS student_name, 
                 transactions.issue_date, transactions.fine_amount 
          FROM transactions 
          JOIN books ON transactions.book_id = books.id 
          JOIN students ON transactions.student_id = students.student_id 
          WHERE transactions.status = 'Issued'";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issued Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-light: #60a5fa;
            --primary-dark: #2563eb;
            --secondary: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #0f172a;
            --dark-light: #1e293b;
            --light: #f8fafc;
            --gray: #94a3b8;
            --gray-light: #e2e8f0;
        }
        
        body {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #e2e8f0;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        .dashboard-container {
            max-width: 1600px;
            width: 100%;
            margin: 0 auto;
            padding: 1.5rem;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: var(--dark-light);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            padding: 1.5rem;
            z-index: 1000;
            transition: all 0.3s ease;
            transform: translateX(0);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--light);
            margin: 0;
        }
        
        .sidebar-header i {
            color: var(--primary);
            font-size: 1.5rem;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            color: var(--gray-light);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: rgba(59, 130, 246, 0.2);
            color: var(--primary-light);
        }
        
        .sidebar-menu a i {
            width: 24px;
            text-align: center;
        }
        
        .sidebar-footer {
            position: absolute;
            bottom: 1.5rem;
            left: 1.5rem;
            right: 1.5rem;
        }
        
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            background-color: rgba(239, 68, 68, 0.2);
            color: var(--danger);
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .logout-btn:hover {
            background-color: rgba(239, 68, 68, 0.3);
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            transition: all 0.3s ease;
        }
        
        .admin-header {
            background: var(--dark-light);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .admin-header h1 {
            color: var(--light);
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--light);
            cursor: pointer;
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-toggle {
                display: block;
            }
        }
        
        /* Centered Page Title */
        .page-title {
            text-align: center;
            font-size: 24px;
            margin-top: 20px;
            color: #333;
            color: var(--light);
        }

        /* Back to Dashboard Link */
        .back-link {
            display: block;
            width: fit-content;
            margin: 10px auto;
            padding: 8px 15px;
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }

        .back-link:hover {
            background-color: var(--primary-dark);
        }

        /* Stylish Table */
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
            background: var(--dark-light);
            color: var(--light);
            border-radius: 12px;
            overflow: hidden;
        }

        .styled-table th, .styled-table td {
            padding: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .styled-table th {
            background-color: var(--primary);
            color: white;
        }

        .styled-table tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Stylish Return Button */
        .return-btn {
            background-color: var(--secondary);
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        .return-btn:hover {
            background-color: #0d9c6b;
        }
        
        .pdf-btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #FF5733;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        .pdf-btn:hover {
            background-color: #C70039;
        }
        
        .content-container {
            background: var(--dark-light);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-book-open"></i>
            <h2>Library Admin</h2>
        </div>
        
        <ul class="sidebar-menu">
            <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="add_book1.php"><i class="fas fa-plus"></i> Add Book</a></li>
            <li><a href="issue_book1.php"><i class="fas fa-book"></i> Issue Book</a></li>
            <li><a href="view_books.php"><i class="fas fa-book-open"></i> View Books</a></li>
            <li><a href="view_issued_books.php" class="active"><i class="fas fa-book-reader"></i> Issued Books</a></li>
            <li><a href="add_student1.php"><i class="fas fa-user-plus"></i> Add Student</a></li>
            <li><a href="view_members.php"><i class="fas fa-users"></i> View Students</a></li>
            <li><a href="book_requests.php"><i class="fas fa-clock"></i> Book Requests</a></li>
        </ul>
        
        <div class="sidebar-footer">
            <button class="logout-btn" onclick="window.location.href='logout.php'">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-container">
            <header class="admin-header">
                <div>
                    <button class="mobile-menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>Issued Books</h1>
                </div>
                <div class="user-profile">
                    <div class="user-avatar">AD</div>
                    <span>Admin User</span>
                </div>
            </header>
            
            <div class="content-container">
                <h2 class="page-title">📚 Currently Issued Books</h2>

                <table class="styled-table">
                <tr>
                    <th>Transaction ID</th>
                    <th>Book Title</th>
                    <th>Issued To</th>
                    <th>Issue Date</th>
                    <th>Fine (₹)</th>
                    <th>Action</th>
                </tr>

                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr id='row_" . $row['transaction_id'] . "'>";
                    echo "<td>" . $row['transaction_id'] . "</td>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['student_name'] . "</td>";
                    echo "<td>" . $row['issue_date'] . "</td>";
                    echo "<td>" . ($row['fine_amount'] > 0 ? "₹" . $row['fine_amount'] : "No Fine") . "</td>";
                    echo "<td>
                            <button class='return-btn' onclick='returnBook(" . $row['transaction_id'] . ")'>Return</button>
                          </td>";
                    echo "</tr>";
                }
                ?>
                </table>
                <center><a href="generate_pdf.php" class="pdf-btn">📄 Download PDF Report</a></center>
            </div>
        </div>
    </div>

    <!-- SweetAlert & jQuery (For AJAX) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function returnBook(transactionId) {
        $.ajax({
            url: 'return_book.php',
            type: 'POST',
            data: { transaction_id: transactionId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message + (response.fine > 0 ? " Fine: ₹" + response.fine : ""),
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(() => {
                        $("#row_" + transactionId).remove();
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: response.message,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: "Book Return Successfully!",
                    text: "Successfully returned the book.",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            }
        });
    }
    
    // Mobile menu toggle
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });
    
    // Close sidebar when clicking outside
    document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    });
    </script>
</body>
</html>