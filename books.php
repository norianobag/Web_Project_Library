<?php
include '../db_connection.php';

// ✅ Get the first student_id from the database
$student_query = mysqli_query($conn, "SELECT student_id FROM students ORDER BY student_id ASC LIMIT 1");
if (!$student_query || mysqli_num_rows($student_query) === 0) {
    echo "❌ Error: No students found in the database.";
    return;
}

$student_row = mysqli_fetch_assoc($student_query);
$student_id = $student_row['student_id'];

// ✅ Fetch available books
$result = mysqli_query($conn, "SELECT * FROM books WHERE status='Available'");

// ✅ Optional: check for query failure
if (!$result) {
    echo "❌ Error fetching books: " . mysqli_error($conn);
    return;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Books - Library Hub</title>
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

        /* Search Bar */
        .search-container {
            max-width: 400px;
            margin: 0 auto;
            position: relative;
        }

        .search-input {
            background: var(--card-bg);
            border: 2px solid var(--primary);
            color: var(--text-light);
            border-radius: 30px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 15px rgba(96, 165, 250, 0.5);
        }

        .search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        /* Main Container */
        .books-container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 1rem;
            width: 100%;
        }

        .page-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 2rem;
            text-shadow: 0 2px 10px rgba(59, 130, 246, 0.4);
            animation: fadeInDown 1s ease-in;
        }

        /* Book Grid */
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            width: 100%;
            margin-bottom: 2rem;
        }

        .book-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            transition: all 0.4s ease;
            border: 1px solid rgba(59, 130, 246, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
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
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
            border-color: rgba(59, 130, 246, 0.4);
        }

        .book-icon {
            font-size: 3.5rem;
            color: var(--secondary);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .book-card:hover .book-icon {
            transform: scale(1.1);
            color: var(--primary);
        }

        .book-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .book-author {
            color: var(--secondary);
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .book-meta {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .book-id {
            display: inline-block;
            background: rgba(59, 130, 246, 0.2);
            padding: 0.2rem 0.5rem;
            border-radius: 5px;
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }

        .btn-request {
            display: inline-block;
            padding: 0.75rem 1.5rem;
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
            width: 100%;
            cursor: pointer;
        }

        .btn-request:hover {
            background: linear-gradient(90deg, var(--secondary), var(--primary));
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.5);
            transform: scale(1.05);
        }

        /* Status Badge */
        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
        }

        .status-available {
            background: linear-gradient(90deg, #10b981, #34d399);
            color: white;
        }

        /* Popup Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: linear-gradient(135deg, var(--card-bg), var(--dark-bg));
            padding: 2rem;
            border-radius: 15px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            transform: translateY(-20px);
            transition: all 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }

        .modal-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-light);
        }

        .modal-message {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: var(--text-muted);
        }

        .modal-btn {
            padding: 0.75rem 2rem;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            color: white;
        }

        .modal-btn:hover {
            background: linear-gradient(90deg, var(--secondary), var(--primary));
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.5);
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
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }

            .page-title {
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

            .page-title {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 576px) {
            .books-container {
                margin: 2rem auto;
                padding: 0 0.5rem;
            }

            .book-grid {
                grid-template-columns: 1fr;
            }

            .navbar-brand {
                font-size: 1.3rem;
            }

            .navbar-brand i {
                font-size: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
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
                        <a class="nav-link active" href="books.php">
                            <i class="fas fa-book"></i>
                            <span>Books</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="issued_books.php">
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
    <div class="books-container">
        <h1 class="page-title">Available Books</h1>
        
        <!-- Search Bar -->
        <div class="search-container mb-4">
            <input type="text" id="search" class="form-control search-input" placeholder="Search books...">
            <i class="fas fa-search search-icon"></i>
        </div>
        
        <!-- Book Grid -->
        <div class="book-grid" id="book-list">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="book-card book-item">
                    <span class="status-badge status-available">Available</span>
                    <i class="fas fa-book-open book-icon"></i>
                    <h3 class="book-title"><?php echo $row['title']; ?></h3>
                    <p class="book-author">by <?php echo $row['author']; ?></p>
                    <span class="book-id">ID: <?php echo $row['id']; ?></span>
                    <form class="book-request-form" action="request_book.php" method="POST">
                        <input type="hidden" name="book_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn-request">
                            <i class="fas fa-bookmark"></i> Request Book
                        </button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Modal Popup -->
    <div class="modal-overlay" id="responseModal">
        <div class="modal-content">
            <i class="fas fa-check-circle modal-icon" id="modalIcon"></i>
            <h3 class="modal-title" id="modalTitle">Success!</h3>
            <p class="modal-message" id="modalMessage">Your book request has been submitted successfully.</p>
            <button class="modal-btn" id="modalButton">OK</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        document.getElementById('search').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const books = document.querySelectorAll('.book-item');
            
            books.forEach(book => {
                const title = book.querySelector('.book-title').textContent.toLowerCase();
                const author = book.querySelector('.book-author').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || author.includes(searchTerm)) {
                    book.style.display = 'block';
                } else {
                    book.style.display = 'none';
                }
            });
        });

        // Book request form handling
        document.querySelectorAll('.book-request-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('request_book.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    const modal = document.getElementById('responseModal');
                    const icon = document.getElementById('modalIcon');
                    const title = document.getElementById('modalTitle');
                    const message = document.getElementById('modalMessage');
                    
                    if (data === 'Book request submitted!') {
                        icon.className = 'fas fa-check-circle modal-icon';
                        icon.style.color = '#10b981';
                        title.textContent = 'Success!';
                        title.style.color = '#10b981';
                        message.textContent = data;
                    } else {
                        icon.className = 'fas fa-exclamation-circle modal-icon';
                        icon.style.color = '#ef4444';
                        title.textContent = 'Error!';
                        title.style.color = '#ef4444';
                        message.textContent = data;
                    }
                    
                    modal.classList.add('active');
                })
                .catch(error => {
                    console.error('Success:', error);
                    
                    const modal = document.getElementById('responseModal');
                    const icon = document.getElementById('modalIcon');
                    const title = document.getElementById('modalTitle');
                    const message = document.getElementById('modalMessage');
                    
                    icon.className = 'fas fa-exclamation-circle modal-icon';
                    icon.style.color = '#ef4444';
                    title.textContent = 'Error!';
                    title.style.color = '#ef4444';
                    message.textContent = 'An error occurred while processing your request.';
                    
                    modal.classList.add('active');
                });
            });
        });

        // Close modal
        document.getElementById('modalButton').addEventListener('click', function() {
            document.getElementById('responseModal').classList.remove('active');
        });
    </script>
</body>
</html>