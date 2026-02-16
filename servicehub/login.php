<?php
session_start();

// FIX: Use "../" to look for the file in the main folder
if (file_exists('db_connect.php')) {
    include 'db_connect.php';
} else {
    die("Error: Could not find db_connect.php. Make sure it is in the main folder.");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Prepare the SQL statement to find the user
    $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // 2. Check if user exists
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // 3. Verify the password
        if (password_verify($password, $row['password'])) {
            // Login Success! Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['full_name'];
            $_SESSION['role'] = $row['role']; 

            // Redirect based on role
            if ($row['role'] === 'admin') {
                header("Location: admin-dashboard.php");
            } else {
                header("Location: client-dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - ServiceHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .auth-card { background: white; padding: 40px; border-radius: 12px; width: 350px; text-align: center; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #ff5722; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .error-msg { color: red; font-size: 0.9rem; margin-bottom: 10px; }
        h2 { color: #0f172a; }
        a { color: #ff5722; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="auth-card">
        <h2>Welcome Back</h2>
        <?php if(!empty($error)) echo "<p class='error-msg'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p style="margin-top:15px; font-size:0.9rem;">New here? <a href="register.php">Create account</a></p>
    </div>
</body>
</html>