<?php
include 'db_connection.php';

// Initialize search query
$search = '';
$where = '';

// Check if search parameter is present
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where = "WHERE student_id LIKE '%$search%' OR name LIKE '%$search%' OR department LIKE '%$search%'";
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_query = "DELETE FROM students WHERE student_id = '$delete_id'";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: view_members.php?success=Student deleted successfully");
        exit();
    } else {
        header("Location: view_members.php?error=Error deleting student");
        exit();
    }
}

$query = "SELECT * FROM students $where";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Students</title>
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

        /* Content Container */
        .content-container {
            background: var(--dark-light);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Table Styles */
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

        /* Back Button */
        .back-btn {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(90deg, var(--secondary), #0d9c6b);
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
        }

        .back-btn:hover {
            background: linear-gradient(90deg, #0d9c6b, var(--secondary));
            transform: scale(1.05);
        }

        /* No Students Message */
        .no-students {
            text-align: center;
            padding: 20px;
            color: var(--gray);
        }
        
        /* Search Box */
        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        
        .search-box input {
            flex: 1;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: var(--dark);
            color: var(--light);
            font-size: 16px;
        }
        
        .search-box button {
            padding: 10px 20px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .search-box button:hover {
            background-color: var(--primary-dark);
        }
        
        /* Delete Button */
        .delete-btn {
            background-color: var(--danger);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .delete-btn:hover {
            background-color: #dc2626;
        }
        
        /* Alert Messages */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        
        .alert-success {
            background-color: rgba(16, 185, 129, 0.2);
            color: var(--secondary);
            border: 1px solid var(--secondary);
        }
        
        .alert-danger {
            background-color: rgba(239, 68, 68, 0.2);
            color: var(--danger);
            border: 1px solid var(--danger);
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
            <li><a href="view_issued_books.php"><i class="fas fa-book-reader"></i> Issued Books</a></li>
            <li><a href="add_student1.php"><i class="fas fa-user-plus"></i> Add Student</a></li>
            <li><a href="view_members.php" class="active"><i class="fas fa-users"></i> View Students</a></li>
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
                    <h1>Registered Students</h1>
                </div>
                <div class="user-profile">
                    <div class="user-avatar">AD</div>
                    <span>Admin User</span>
                </div>
            </header>
            
            <div class="content-container">
                <h2 class="text-center mb-4" style="color: var(--primary-light);">📋 Registered Students</h2>
                
                <!-- Search Box -->
                <form method="GET" action="view_members.php" class="search-box">
                    <input type="text" name="search" placeholder="Search by ID, name or department..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit"><i class="fas fa-search"></i> Search</button>
                    <?php if (!empty($search)): ?>
                        <a href="view_members.php" class="btn btn-secondary">Clear</a>
                    <?php endif; ?>
                </form>
                
                <!-- Success/Error Messages -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($_GET['success']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                                    <td>
                                        <button class="delete-btn" onclick="confirmDelete('<?php echo $row['student_id']; ?>')">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-students">No students found<?php echo !empty($search) ? ' matching your search' : ''; ?>.</p>
                <?php endif; ?>
                
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
    
    // Confirm before deleting a student
    function confirmDelete(studentId) {
        if (confirm('Are you sure you want to delete this student? This action cannot be undone.')) {
            window.location.href = 'view_members.php?delete_id=' + studentId;
        }
    }
    </script>
</body>
</html>