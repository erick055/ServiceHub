<?php
session_start();

// Check if user is logged in AND is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: user-pages/login.php"); // Send them back to login
    exit();
}
include 'db_connect.php';

// --- 1. Fetch KPI Data ---
// Total Appointments
$total_appt_query = "SELECT COUNT(*) as count FROM appointments";
$total_appt_result = $conn->query($total_appt_query);
$total_appt = $total_appt_result->fetch_assoc()['count'];

// Active Jobs (Status is 'In Progress')
$active_jobs_query = "SELECT COUNT(*) as count FROM appointments WHERE status = 'In Progress'";
$active_jobs_result = $conn->query($active_jobs_query);
$active_jobs = $active_jobs_result->fetch_assoc()['count'];

// Total Clients (Unique names)
$total_clients_query = "SELECT COUNT(DISTINCT client_name) as count FROM appointments";
$total_clients_result = $conn->query($total_clients_query);
$total_clients = $total_clients_result->fetch_assoc()['count'];

// --- 2. Fetch Recent Appointments (Limit 3) ---
$recent_query = "SELECT * FROM appointments ORDER BY created_at DESC LIMIT 3";
$recent_result = $conn->query($recent_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ServiceHub Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* PASTE YOUR CSS FROM THE PREVIOUS DASHBOARD.HTML HERE */
        /* I am keeping this short for brevity, but copy the <style> block from Part 1 fully here */
        :root { --primary-orange: #ff5722; --sidebar-bg: #0f172a; --bg-color: #f8f9fa; --text-dark: #1e293b; --text-light: #64748b; --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); --border-radius: 12px; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { display: flex; background-color: var(--bg-color); height: 100vh; overflow: hidden; }
        .sidebar { width: 260px; background-color: var(--sidebar-bg); color: white; display: flex; flex-direction: column; padding: 20px; flex-shrink: 0; }
        .brand { display: flex; align-items: center; gap: 10px; margin-bottom: 5px; } .brand i { color: var(--primary-orange); font-size: 1.5rem; } .brand h1 { font-size: 1.25rem; font-weight: 700; }
        .nav-menu { list-style: none; flex: 1; margin-top: 30px; } .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; color: #cbd5e1; border-radius: 8px; font-size: 0.9rem; transition: all 0.2s; margin-bottom: 8px;} .nav-link:hover { background-color: rgba(255, 255, 255, 0.1); } .nav-link.active { background-color: var(--primary-orange); color: white; }
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 20px; border-radius: var(--border-radius); box-shadow: var(--card-shadow); }
        .kpi-card { border-left: 4px solid var(--primary-orange); display: flex; justify-content: space-between; align-items: center; }
        .kpi-info h3 { font-size: 0.85rem; color: var(--text-light); } .kpi-info .value { font-size: 1.75rem; font-weight: 700; color: var(--text-dark); }
        .icon-box { width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; }
        .bg-blue { background-color: #3b82f6; } .bg-orange { background-color: #f97316; } .bg-green { background-color: #22c55e; } .bg-purple { background-color: #a855f7; }
        .bottom-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px;}
        .alert-item { color: #ef4444; margin-top: 15px;}
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="brand"><i class="fa-solid fa-wrench"></i><h1>ServiceHub</h1></div>
        <ul class="nav-menu">
            <li><a href="dashboard.php" class="nav-link active"><i class="fa-solid fa-chart-simple"></i> Dashboard</a></li>
            <li><a href="appointments.php" class="nav-link"><i class="fa-regular fa-calendar-check"></i> Appointments</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header style="margin-bottom: 30px;">
            <h2 style="font-size: 1.75rem; color: #1e293b;">Dashboard</h2>
            <p style="color: #64748b;">Welcome back! Here's your workshop overview.</p>
        </header>

        <div class="kpi-grid">
            <div class="card kpi-card">
                <div class="kpi-info">
                    <h3>Total Appointments</h3>
                    <div class="value"><?php echo $total_appt; ?></div>
                </div>
                <div class="icon-box bg-blue"><i class="fa-regular fa-calendar"></i></div>
            </div>
            <div class="card kpi-card">
                <div class="kpi-info">
                    <h3>Active Jobs</h3>
                    <div class="value"><?php echo $active_jobs; ?></div>
                </div>
                <div class="icon-box bg-orange"><i class="fa-solid fa-wrench"></i></div>
            </div>
            <div class="card kpi-card">
                <div class="kpi-info">
                    <h3>Total Clients</h3>
                    <div class="value"><?php echo $total_clients; ?></div>
                </div>
                <div class="icon-box bg-green"><i class="fa-solid fa-user-group"></i></div>
            </div>
            <div class="card kpi-card">
                <div class="kpi-info">
                    <h3>Monthly Revenue</h3>
                    <div class="value">$21.4K</div> </div>
                <div class="icon-box bg-purple"><i class="fa-solid fa-dollar-sign"></i></div>
            </div>
        </div>

        <div class="bottom-grid">
            <div class="card">
                <h3 style="margin-bottom: 15px;">Recent Appointments</h3>
                <?php
                if ($recent_result->num_rows > 0) {
                    while($row = $recent_result->fetch_assoc()) {
                        echo "<div style='margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #eee;'>";
                        echo "<strong>" . $row["client_name"] . "</strong>";
                        echo "<span style='float:right; font-size:0.8rem; background:#eee; padding:4px 8px; border-radius:4px;'>" . $row["status"] . "</span>";
                        echo "<div style='font-size: 0.85rem; color: #64748b;'>" . $row["vehicle_model"] . " - " . $row["service_type"] . "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "No recent appointments.";
                }
                ?>
            </div>
            
            <div class="card">
                <h3>System Alerts</h3>
                <div class="alert-item"><i class="fa-solid fa-circle-exclamation"></i> Low inventory alert</div>
            </div>
        </div>
    </main>
</body>
</html>