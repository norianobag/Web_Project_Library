<?php
session_start();
include "db_connection.php"; 
$error_message = "";

if (isset($_POST["username"]) && isset($_POST["password"]) && $_POST["username"] !== "" && $_POST["password"] !== "") {
    if ($conn) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $sql = "SELECT * FROM users WHERE username='$username' AND passwords='$password'";
        $result = $conn->query($sql);  // Use $conn directly

        if ($result && $result->num_rows > 0) {
            echo "Login successful";
            header('Location: admin_dashboard.php');
            exit(); // Stop script after redirect
        } else {
            $error_message = "Username or password is incorrect";
        }
    } else {
        $error_message = "Database connection failed";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="proxy-image-removebg-preview.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0a1a2e, #162d4a);
            color: #e2e8f0;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
        }
        .auth-container {
            max-width: 500px;
            width: 100%;
            padding: 2.5rem;
            background: rgba(15, 23, 42, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.5), 0 0 20px rgba(59, 130, 246, 0.3);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(59, 130, 246, 0.2);
            animation: fadeIn 0.5s ease-in;
            position: relative;
            margin-top: 50px; /* Added to accommodate the back button */
        }
        h2 {
            color: #3b82f6;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            text-transform: uppercase;
            font-size: 2rem;
            text-shadow: 0 2px 10px rgba(59, 130, 246, 0.4);
        }
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        .form-label {
            font-weight: 600;
            color: #60a5fa;
            margin-bottom: 0.5rem;
            display: block;
        }
        .form-control {
            background: #1e293b;
            border: 2px solid #3b82f6;
            color: #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 10px rgba(96, 165, 250, 0.5);
            outline: none;
        }
        .form-control::placeholder {
            color: #94a3b8;
        }
        button[type="submit"] {
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            color: #ffffff;
            font-weight: 600;
            font-size: 1.1rem;
            text-transform: uppercase;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }
        button[type="submit"]:hover {
            background: linear-gradient(90deg, #60a5fa, #3b82f6);
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6);
        }
        .toggle-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .toggle-link a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .toggle-link a:hover {
            color: #3b82f6;
        }
        .error, .success {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 1rem;
        }
        .error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            text-shadow: 0 1px 3px rgba(239, 68, 68, 0.2);
        }
        .success {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            text-shadow: 0 1px 3px rgba(34, 197, 94, 0.2);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Back button styles - Updated to top left */
        .back-button {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 10;
        }
        .back-button a {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        .back-button a:hover {
            background: rgba(59, 130, 246, 0.4);
            color: #3b82f6;
            transform: translateY(-2px);
        }
        .back-button i {
            margin-right: 0.3rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
                margin-top: 70px; /* Increased for mobile */
            }
            h2 {
                font-size: 1.5rem;
            }
            .form-control {
                padding: 0.6rem 0.9rem;
                font-size: 0.9rem;
            }
            button[type="submit"] {
                padding: 0.6rem 1.5rem;
                font-size: 1rem;
            }
            .back-button {
                top: 0.5rem;
                left: 0.5rem;
            }
        }
        @media (max-width: 480px) {
            .auth-container {
                padding: 1.5rem 1rem;
                margin: 0.5rem;
                margin-top: 60px; /* Increased for smaller mobile */
            }
            h2 {
                font-size: 1.2rem;
            }
            .form-control {
                padding: 0.5rem 0.8rem;
                font-size: 0.8rem;
            }
            button[type="submit"] {
                padding: 0.5rem 1.2rem;
                font-size: 0.9rem;
            }
            .back-button a {
                padding: 0.3rem 0.8rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="back-button">
        <a href="index.html"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    
    <div class="auth-container">        
        <div class="login-header">
            <h2><i class="fas fa-user-shield"></i> Admin Login</h2>
            <p class="login-subtitle">Sign in to access the admin dashboard</p>
        </div>
        
        <?php if (!empty($error_message)): ?>
            <div class="error"><i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username" class="form-label"><i class="fas fa-user"></i> Admin Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label"><i class="fas fa-lock"></i> Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>