<?php
// START SESSION
session_start();
if (file_exists('db_connect.php')) { include 'db_connect.php'; } else { die("Error: db_connect.php not found."); }
$error = "";
$success = "";

if (isset($_GET['registered'])) { $success = "Account created! Please log in."; }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['full_name'];
            $_SESSION['role'] = $row['role']; 
            
            if ($row['role'] === 'admin') { header("Location: admin-dashboard.php"); } 
            else { header("Location: client-dashboard.php"); }
            exit();
        } else { $error = "Invalid password."; }
    } else { $error = "No account found with that email."; }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ServiceHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        
        body {
            background-color: #050910;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
            overflow-x: hidden;
        }

        .page-wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }

        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 420px;
            z-index: 10;
            padding: 20px;
        }

        .logo-header { display: flex; align-items: center; gap: 15px; margin-bottom: 40px; }
        .logo-header i { font-size: 3rem; color: #ff6b2c; transform: rotate(-10deg); transition: color 0.3s ease; }
        .logo-header h1 { font-size: 3.5rem; font-weight: 600; letter-spacing: -1px; color: white; }

        .card {
            background-color: #0b1121;
            border: 1px solid #1e293b;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }

        .card h2 { text-align: center; font-size: 1.1rem; margin-bottom: 5px; color: white; font-weight: 600; }
        .card p.subtitle { text-align: center; color: #cbd5e1; font-size: 0.85rem; margin-bottom: 25px; }

        label { display: block; font-size: 0.85rem; color: white; margin-bottom: 8px; font-weight: 600; }
        input {
            width: 100%; padding: 14px 15px; background-color: #4b5563; 
            border: 2px solid transparent; border-radius: 6px; color: #e2e8f0; font-size: 0.9rem; outline: none; margin-bottom: 20px; transition: 0.3s;
        }
        input:focus { background-color: #586375; box-shadow: 0 0 0 2px #ff4444; }
        input::placeholder { color: #9ca3af; }

        .btn-submit {
            width: 100%; padding: 14px; background-color: #ff4444; color: white; border: none; border-radius: 6px; font-weight: 500; font-size: 0.95rem; cursor: pointer; margin-top: 5px; transition: background 0.3s ease, transform 0.2s;
        }
        .btn-submit:hover { filter: brightness(0.9); transform: translateY(-1px); }

        .footer-link { display: flex; justify-content: center; align-items: center; margin-top: 25px; gap: 10px; font-size: 0.85rem; color: white; font-weight: 600; }
        .btn-create { background-color: #ff4444; color: white; padding: 4px 12px; text-decoration: none; border-radius: 12px; font-size: 0.75rem; font-weight: 600; transition: background 0.3s ease; }
        
        .error-msg { color: #ff4444; background: rgba(255, 68, 68, 0.1); padding: 10px; border-radius: 6px; text-align: center; margin-bottom: 20px; font-size: 0.85rem; }
        .success-msg { color: #22c55e; text-align: center; margin-bottom: 15px; font-size: 0.9rem;}

        /* --- UPDATED ROLE TOGGLES --- */
        .role-toggles {
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 20;
        }

        @media (min-width: 1000px) {
            .role-toggles { position: absolute; bottom: 60px; left: 60px; }
        }
        @media (max-width: 999px) {
            .page-wrapper { flex-direction: column; padding: 20px 0; height: auto; }
            .role-toggles { position: relative; flex-direction: row; margin-top: 40px; margin-bottom: 20px; }
        }

        .role-btn {
            width: 180px;
            padding: 20px 0;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.1); /* Subtle border by default */
            background-color: rgba(255,255,255,0.05); /* Very dark transparent bg */
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
            color: #94a3b8; /* Dimmed text color by default */
        }

        /* --- HOVER & ACTIVE STATES (ADMIN) --- */
        /* Only show Orange when Hovered OR Active */
        .role-btn.admin:hover, 
        .role-btn.admin.active {
            background-color: #ff9f2a; /* Bright Orange */
            color: white; /* Bright White Text */
            border-color: #ff9f2a;
            box-shadow: 0 4px 20px rgba(255, 159, 42, 0.4); /* Glow effect */
            transform: translateY(-2px);
        }

        /* --- HOVER & ACTIVE STATES (CUSTOMER) --- */
        /* Only show Solid Gray when Hovered OR Active */
        .role-btn.customer:hover, 
        .role-btn.customer.active {
            background-color: #c92014; /* Solid Gray */
            color: white;
            border-color: #df190b;
            box-shadow: 0 4px 20px rgba(75, 85, 99, 0.4);
            transform: translateY(-2px);
        }

    </style>
</head>
<body>

    <div class="page-wrapper">
        <div class="main-container">
            <div class="logo-header">
                <i class="fa-solid fa-wrench" id="logo-icon"></i>
                <h1>ServiceHub</h1>
            </div>

            <div class="card">
                <h2>Welcome Back</h2>
                <p class="subtitle">Sign in to your account to continue</p>
                <?php if(!empty($error)) echo "<div class='error-msg'>$error</div>"; ?>
                <?php if(!empty($success)) echo "<div class='success-msg'>$success</div>"; ?>

                <form method="POST">
                    <input type="hidden" name="login_role" id="login_role" value="client">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="you@example.com" required>
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                    <button type="submit" class="btn-submit" id="btn-signin">Sign in</button>
                </form>

                <div class="footer-link">
                    Don't have an account? <a href="register.php?role=client" id="link-create" class="btn-create">Create one</a>
                </div>
            </div>
        </div>

        <div class="role-toggles">
            <button class="role-btn admin" id="btn-admin" onclick="setRole('admin')">Admin</button>
            <button class="role-btn customer active" id="btn-customer" onclick="setRole('client')">Customer</button>
        </div>
    </div>

    <script>
        function setRole(role) {
            const btnAdmin = document.getElementById('btn-admin');
            const btnCustomer = document.getElementById('btn-customer');
            const btnSignIn = document.getElementById('btn-signin');
            const btnCreate = document.getElementById('link-create');
            const logoIcon = document.getElementById('logo-icon');
            const loginRoleInput = document.getElementById('login_role');

            if (role === 'admin') {
                btnAdmin.classList.add('active');
                btnCustomer.classList.remove('active');
                
                const orangeColor = '#ff9f2a';
                btnSignIn.style.backgroundColor = orangeColor;
                btnCreate.style.backgroundColor = orangeColor;
                logoIcon.style.color = orangeColor;
                
                btnCreate.href = "register.php?role=admin";
                loginRoleInput.value = 'admin';
            } else {
                btnCustomer.classList.add('active');
                btnAdmin.classList.remove('active');
                
                const redColor = '#ff4444';
                btnSignIn.style.backgroundColor = redColor;
                btnCreate.style.backgroundColor = redColor;
                logoIcon.style.color = '#ff6b2c';
                
                btnCreate.href = "register.php?role=client";
                loginRoleInput.value = 'client';
            }
        }
    </script>
</body>
</html>