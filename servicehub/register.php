<?php
// FIX: Use "../" to look for the file in the main folder
if (file_exists('db_connect.php')) {
    include 'db_connect.php';
} else {
    die("Error: Could not find db_connect.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    // Hash the password securely
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Default role is 'client'
    $sql = "INSERT INTO users (full_name, email, password, role) VALUES ('$name', '$email', '$pass', 'client')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: login.php"); // Redirect to login after success
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Join ServiceHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .auth-card { background: white; padding: 40px; border-radius: 12px; width: 350px; text-align: center; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #ff5722; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        h2 { color: #0f172a; }
        a { color: #ff5722; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="auth-card">
        <h2>Create Account</h2>
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p style="margin-top:15px; font-size:0.9rem;">Already a member? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>