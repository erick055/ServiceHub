<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Handle Booking Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle = $_POST['vehicle_model'];
    $service = $_POST['service_type'];
    $date = $_POST['appointment_date'];

    // Insert into DB with user_id
    $stmt = $conn->prepare("INSERT INTO appointments (user_id, client_name, vehicle_model, service_type, appointment_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $user_name, $vehicle, $service, $date);
    
    if ($stmt->execute()) {
        $success_msg = "Booking successful!";
    } else {
        $error_msg = "Error: " . $conn->error;
    }
}

// Fetch User's Past Bookings
$history_sql = "SELECT * FROM appointments WHERE user_id = '$user_id' ORDER BY appointment_date DESC";
$history_result = $conn->query($history_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Garage - ServiceHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #ff5722; --bg: #f8f9fa; --sidebar: #0f172a; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); display: flex; min-height: 100vh; margin: 0; }
        
        /* Sidebar (Simplified for Client) */
        .sidebar { width: 250px; background: var(--sidebar); color: white; padding: 20px; display: flex; flex-direction: column; }
        .logo { font-size: 1.5rem; font-weight: bold; margin-bottom: 40px; display: flex; align-items: center; gap: 10px; }
        .logo i { color: var(--primary); }
        .user-info { margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        
        /* Main Content */
        .main { flex: 1; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { color: #1e293b; }
        
        /* Grid Layout */
        .dashboard-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; }
        
        /* Cards */
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h2 { font-size: 1.2rem; margin-bottom: 20px; color: #334155; }
        
        /* Form Styling */
        input, select { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #e2e8f0; border-radius: 8px; box-sizing: border-box; }
        .btn-submit { background: var(--primary); color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn-submit:hover { background: #e64a19; }
        
        /* History Items */
        .history-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #f1f5f9; }
        .history-item:last-child { border-bottom: none; }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .status-Pending { background: #fef9c3; color: #854d0e; }
        .status-Confirmed { background: #dcfce7; color: #166534; }
        .status-InProgress { background: #dbeafe; color: #1e40af; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo"><i class="fa-solid fa-wrench"></i> ServiceHub</div>
        <div style="flex: 1;">
            <p style="color: #94a3b8; font-size: 0.9rem;">MENU</p>
            <p style="color: white; font-weight: 600; margin-bottom: 10px;">My Garage</p>
        </div>
        <div class="user-info">
            <p>Logged in as:</p>
            <h3><?php echo htmlspecialchars($user_name); ?></h3>
            <a href="login.php" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem;">Sign Out</a>
        </div>
    </aside>

    <main class="main">
        <div class="header">
            <h1>My Dashboard</h1>
            <p style="color: #64748b;">Manage your vehicle services</p>
        </div>

        <div class="dashboard-grid">
            <div class="card">
                <h2><i class="fa-solid fa-plus-circle"></i> Book New Service</h2>
                <?php if(isset($success_msg)) echo "<p style='color:green;'>$success_msg</p>"; ?>
                <form method="POST">
                    <label style="font-size:0.9rem; color:#64748b;">Vehicle Model</label>
                    <input type="text" name="vehicle_model" placeholder="e.g. Ford Mustang" required>
                    
                    <label style="font-size:0.9rem; color:#64748b;">Service Type</label>
                    <select name="service_type">
                        <option value="Oil Change">Oil Change</option>
                        <option value="Brake Check">Brake Check</option>
                        <option value="Tire Rotation">Tire Rotation</option>
                        <option value="Full Inspection">Full Inspection</option>
                    </select>
                    
                    <label style="font-size:0.9rem; color:#64748b;">Preferred Date</label>
                    <input type="datetime-local" name="appointment_date" required>
                    
                    <button type="submit" class="btn-submit">Confirm Booking</button>
                </form>
            </div>

            <div class="card">
                <h2><i class="fa-solid fa-clock-rotate-left"></i> Service History</h2>
                <?php
                if ($history_result->num_rows > 0) {
                    while($row = $history_result->fetch_assoc()) {
                        $statusClass = str_replace(' ', '', $row['status']); 
                        $dateFormatted = date('M d, Y - h:i A', strtotime($row['appointment_date']));
                        echo "
                        <div class='history-item'>
                            <div>
                                <div style='font-weight: 600; color: #1e293b;'>{$row['service_type']}</div>
                                <div style='font-size: 0.85rem; color: #64748b;'>{$row['vehicle_model']} • $dateFormatted</div>
                            </div>
                            <span class='status-badge status-$statusClass'>{$row['status']}</span>
                        </div>";
                    }
                } else {
                    echo "<p style='color:#64748b;'>No bookings yet.</p>";
                }
                ?>
            </div>
        </div>
    </main>

</body>
</html>