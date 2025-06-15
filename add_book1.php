<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
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
        
        /* Form Styling */
        .form-container {
            background: var(--dark-light);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .form-title {
            color: var(--primary-light);
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            background: var(--dark);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--light);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
            outline: none;
        }
        
        .form-control::placeholder {
            color: var(--gray);
        }
        
        .submit-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .submit-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .back-link {
            display: inline-block;
            margin-top: 1rem;
            color: var(--gray-light);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .back-link:hover {
            color: var(--primary-light);
        }
        
        /* Popup Styling */
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--dark-light);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            z-index: 1050;
            color: var(--light);
            display: none;
            border: 1px solid var(--primary);
            max-width: 400px;
            width: 90%;
        }
        
        .popup-content {
            text-align: center;
        }
        
        .popup-message {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            color: var(--primary-light);
        }
        
        .popup-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .popup-btn:hover {
            background: var(--primary-dark);
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
            <li><a href="add_book1.php" class="active"><i class="fas fa-plus"></i> Add Book</a></li>
            <li><a href="issue_book1.php"><i class="fas fa-book"></i> Issue Book</a></li>
            <li><a href="view_books.php"><i class="fas fa-book-open"></i> View Books</a></li>
            <li><a href="view_issued_books.php"><i class="fas fa-book-reader"></i> Issued Books</a></li>
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
                    <h1>Add New Book</h1>
                </div>
                <div class="user-profile">
                    <div class="user-avatar">AD</div>
                    <span>Admin User</span>
                </div>
            </header>
            
            <div class="form-container">
                <h2 class="form-title"><i class="fas fa-plus"></i> Add New Book</h2>
                <form id="addBookForm" action="add_book.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="title" class="form-control" placeholder="Book Title" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="author" class="form-control" placeholder="Author" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="category" class="form-control" placeholder="Category">
                    </div>
                    <button type="submit" class="submit-btn">Add Book</button>
                </form>
                <a href="admin_dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Popup -->
    <div id="successPopup" class="popup">
        <div class="popup-content">
            <p class="popup-message" id="popupMessage"></p>
            <button class="popup-btn" onclick="closePopup()">OK</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form submission with AJAX
        document.getElementById('addBookForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('add_book.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('popupMessage').innerText = data;
                document.getElementById('successPopup').style.display = 'block';
                
                // Clear the form on success
                if (data.includes('successfully')) {
                    document.getElementById('addBookForm').reset();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('popupMessage').innerText = 'An error occurred. Please try again.';
                document.getElementById('successPopup').style.display = 'block';
            });
        });

        function closePopup() {
            document.getElementById('successPopup').style.display = 'none';
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