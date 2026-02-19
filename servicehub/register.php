<?php
// START SESSION
session_start();
if (file_exists('db_connect.php')) { include 'db_connect.php'; } 
else { die("Error: db_connect.php not found."); }

$error = "";
$success = "";

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'client'; // Default to client

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "Email is already registered.";
        } else {
            // Hash password and Insert User
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashed_pass, $role);

            if ($stmt->execute()) {
                // Redirect to login page after success
                header("Location: login.php?registered=true");
                exit();
            } else {
                $error = "Database Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - ServiceHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        
        body {
            background-color: #050910; /* Deep Dark Background */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .container {
            width: 100%;
            max-width: 480px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header-title {
            align-self: flex-start;
            margin-bottom: 15px;
            color: #64748b;
            font-size: 1.1rem;
            font-weight: 500;
            width: 100%;
        }

        /* --- CARD STYLE --- */
        .card {
            background-color: #0b1121;
            border: 1px solid #1e293b;
            border-radius: 16px;
            padding: 40px 35px;
            width: 100%;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }

        .card h2 {
            text-align: center;
            font-size: 1.25rem;
            margin-bottom: 8px;
            color: white;
            font-weight: 600;
        }

        .card p.subtitle {
            text-align: center;
            color: #cbd5e1;
            font-size: 0.85rem;
            margin-bottom: 30px;
        }

        /* --- TOGGLE BUTTONS (ADMIN / CUSTOMER) --- */
        .role-label {
            font-size: 0.85rem;
            color: #cbd5e1;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .toggle-container {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        /* Base Button Style (Glassy/Transparent) */
        .toggle-btn {
            flex: 1;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.1); /* Subtle border */
            background-color: rgba(255,255,255,0.05); /* Very dark transparent bg */
            color: #94a3b8; /* Dimmed text */
            cursor: pointer;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-align: center;
        }

        /* Customer Hover/Active (Matches Login Red) */
        .toggle-btn.client:hover,
        .toggle-btn.client.active {
            background-color: #c92014; /* Red */
            color: white;
            border-color: #df190b;
            box-shadow: 0 4px 20px rgba(75, 85, 99, 0.4);
            transform: translateY(-2px);
        }

        /* Admin Hover/Active (Matches Login Orange) */
        .toggle-btn.admin:hover,
        .toggle-btn.admin.active {
            background-color: #ff9f2a; /* Orange */
            color: white;
            border-color: #ff9f2a;
            box-shadow: 0 4px 20px rgba(255, 159, 42, 0.4);
            transform: translateY(-2px);
        }

        /* --- FORM INPUTS --- */
        .form-group { margin-bottom: 15px; }
        
        label {
            display: block;
            font-size: 0.85rem;
            color: #cbd5e1;
            margin-bottom: 6px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            background-color: #4b5563; 
            border: 2px solid transparent;
            border-radius: 6px;
            color: white;
            font-size: 0.9rem;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            background-color: #586375;
            border-color: #ff4444; 
        }

        input::placeholder { color: #9ca3af; }

        /* --- SUBMIT BUTTON --- */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #ff4444; /* Red Button */
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background: rgba(255, 68, 68, 0.8);
            backdrop-filter: blur(5px);
            box-shadow: 0 0 15px rgba(255, 68, 68, 0.4);
            transform: translateY(-1px);
        }

        /* Footer Link */
        .footer-link {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 25px;
            gap: 10px;
            font-size: 0.85rem;
            color: white;
        }

        .btn-signin {
            background-color: #ff4444;
            color: white;
            padding: 4px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-signin:hover {
            background: rgba(255, 68, 68, 0.8);
            backdrop-filter: blur(5px);
            box-shadow: 0 0 10px rgba(255, 68, 68, 0.4);
        }
        
        .error-msg {
            color: #ff4444;
            background: rgba(255, 68, 68, 0.1);
            padding: 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-bottom: 15px;
            text-align: center;
        }

    </style>
</head>
<body>

    <div class="container">
        <div class="header-title">Create account/Admin</div>
        
        <div class="card">
            <h2>Create your account</h2>
            <p class="subtitle">Get started with ServiceHub workshop management</p>

            <?php if(!empty($error)) echo "<div class='error-msg'>$error</div>"; ?>

            <form method="POST">
                <input type="hidden" name="role" id="roleInput" value="client">

                <div class="form-group">
                    <div class="role-label">Account Type</div>
                    <div class="toggle-container">
                        <div class="toggle-btn client active" id="btn-client" onclick="setRole('client')">Customer</div>
                        <div class="toggle-btn admin" id="btn-admin" onclick="setRole('admin')">Admin</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" placeholder="Carlo" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="you@example.com" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-submit">Create Account</button>
            </form>

            <div class="footer-link">
                Already have an account? <a href="login.php" class="btn-signin">Sign In</a>
            </div>
        </div>
    </div>

    <script>
        function setRole(role) {
            // Update hidden input
            document.getElementById('roleInput').value = role;

            // Get buttons
            const btnClient = document.getElementById('btn-client');
            const btnAdmin = document.getElementById('btn-admin');

            if (role === 'client') {
                btnClient.classList.add('active');
                btnAdmin.classList.remove('active');
            } else {
                btnAdmin.classList.add('active');
                btnClient.classList.remove('active');
            }
        }

        // --- CHECK URL FOR PRE-SELECTED ROLE ---
        const urlParams = new URLSearchParams(window.location.search);
        const roleParam = urlParams.get('role');
        
        if (roleParam === 'admin') {
            setRole('admin');
        } else {
            setRole('client');
        }
    </script>
</body>
</html>