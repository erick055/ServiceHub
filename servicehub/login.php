<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['full_name'];
            header("Location: client-dashboard.php");
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - ServiceHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card { background: white; padding: 40px; border-radius: 12px; width: 350px; text-align: center; }
        input { width: 90%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; }
        button { width: 100%; padding: 12px; background: #ff5722; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; }
        h2 { color: #0f172a; }
        a { color: #ff5722; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="auth-card">
        <h2>Welcome Back</h2>
        <?php if(isset($error)) echo "<p style='color:red; font-size:0.9rem;'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p style="margin-top:15px; font-size:0.9rem;">New here? <a href="register.php">Create account</a></p>
    </div>
</body>
</html>