<?php
include 'db_connect.php';

// Fetch all appointments ordered by date
$sql = "SELECT * FROM appointments ORDER BY appointment_date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ServiceHub - Appointments</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Reuse the same CSS from appointments.html and dashboard here */
        :root { --primary-orange: #ff5722; --sidebar-bg: #0f172a; --bg-color: #f8f9fa; --text-dark: #1e293b; --text-light: #64748b; --border-color: #e2e8f0; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { display: flex; background-color: var(--bg-color); height: 100vh; overflow: hidden; }
        .sidebar { width: 260px; background-color: var(--sidebar-bg); color: white; display: flex; flex-direction: column; padding: 20px; flex-shrink: 0; }
        .nav-menu { list-style: none; flex: 1; margin-top: 30px; } .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; color: #cbd5e1; border-radius: 8px; font-size: 0.9rem; margin-bottom: 8px; } .nav-link:hover { background-color: rgba(255, 255, 255, 0.1); } .nav-link.active { background-color: var(--primary-orange); color: white; }
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        
        /* Table Styles */
        .section-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th { text-align: left; padding: 12px 16px; font-size: 0.8rem; background-color: #f8fafc; border-bottom: 1px solid var(--border-color); }
        td { padding: 16px; font-size: 0.9rem; border-bottom: 1px solid #f1f5f9; }
        
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block; }
        .status-Confirmed { background-color: #dcfce7; color: #166534; }
        .status-Pending { background-color: #fef9c3; color: #854d0e; }
        .status-InProgress { background-color: #dbeafe; color: #1e40af; } /* In Progress class */
    </style>
</head>
<body>

    <aside class="sidebar">
        <div style="font-size: 1.25rem; font-weight: 700; display: flex; gap: 10px; align-items: center; color: white;">
            <i class="fa-solid fa-wrench" style="color: var(--primary-orange);"></i> ServiceHub
        </div>
        <ul class="nav-menu">
            <li><a href="dashboard.php" class="nav-link"><i class="fa-solid fa-chart-simple"></i> Dashboard</a></li>
            <li><a href="appointments.php" class="nav-link active"><i class="fa-regular fa-calendar-check"></i> Appointments</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
            <h2 style="color: #1e293b;">Appointments</h2>
            <a href="booking.php" target="_blank" style="background: #ff5722; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;">+ Client View</a>
        </div>

        <section class="section-card">
            <h3>Scheduled Appointments</h3>
            <div style="overflow-x: auto; margin-top: 15px;">
                <table>
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Vehicle</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                // Clean up the status for the CSS class (remove space)
                                $statusClass = str_replace(' ', '', $row['status']); 
                                echo "<tr>";
                                echo "<td>" . $row['client_name'] . "</td>";
                                echo "<td style='color: #64748b;'>" . $row['vehicle_model'] . "</td>";
                                echo "<td>" . $row['service_type'] . "</td>";
                                echo "<td>" . date('Y-m-d h:i A', strtotime($row['appointment_date'])) . "</td>";
                                echo "<td><span class='status-badge status-" . $statusClass . "'>" . $row['status'] . "</span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No appointments found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>