<?php
session_start();
include 'includes/connection.php';
if(!isset($_SESSION['xusername'])){
    header("Location: login.php");
    exit();
}
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : $_SESSION['xusername'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Alex Driving School</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        /* --- 1. Global Setup (High Contrast: White BG + Navy Cards) --- */
        :root {
            /* Backgrounds */
            --bg-body: #ffffff;
            /* PURE WHITE Background */
            --bg-sidebar: #f8f9fa;
            /* Very Light Gray Sidebar */
            --card-bg: #0f3460;
            /* NAVY BLUE Card Background (Preserved) */

            /* Accents */
            --primary-color: #e94560;
            --secondary-color: #4361ee;

            /* Text Colors */
            --text-main: #333333;
            /* Dark Text for White Background */
            --text-card: #ffffff;
            /* White Text for Navy Cards */
            --text-muted: #666666;
            /* Gray Text for placeholders */
            --text-card-muted: #e0e0e0;
            /* Light Gray for Card placeholders */

            --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 20px 25px rgba(0, 0, 0, 0.15);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-body);
            color: var(--text-main);
            height: 100vh;
            overflow: hidden;
        }

        /* --- 2. Layout Structure --- */
        .dashboard-container {
            display: grid;
            grid-template-columns: 260px 1fr;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
        }

        /* --- 3. Sidebar --- */
        .sidebar {
            background-color: var(--bg-sidebar);
            height: 100%;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e1e4e8;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            z-index: 10;
        }

        .logo {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid #e1e4e8;
        }

        .logo h2 {
            margin: 0;
            font-size: 1.6em;
            font-weight: 700;
            color: #0f3460;
            /* Navy Logo Text */
            letter-spacing: 0.5px;
        }

        .logo h2 span {
            color: var(--primary-color);
        }

        .nav-menu {
            flex: 1;
            overflow-y: auto;
            padding-top: 20px;
        }

        .nav-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-menu li {
            margin-bottom: 5px;
        }

        .nav-menu a {
            display: flex;
            align-items: center;
            padding: 14px 25px;
            color: #555;
            /* Dark Gray Links */
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95em;
            transition: all 0.3s ease;
            margin: 0 10px;
            border-radius: 10px;
        }

        .nav-menu a i {
            margin-right: 15px;
            font-size: 1.2em;
            width: 25px;
            text-align: center;
            color: #888;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--secondary-color);
            border-left: 3px solid var(--secondary-color);
        }

        .nav-menu a:hover i,
        .nav-menu a.active i {
            color: var(--secondary-color);
            transform: scale(1.1);
        }

        .logout-link {
            padding: 20px;
            border-top: 1px solid #e1e4e8;
        }

        .logout-link a {
            color: #ff6b6b !important;
            background-color: rgba(255, 107, 107, 0.1) !important;
            justify-content: center;
        }

        /* --- 4. Main Content --- */
        .main-content {
            display: flex;
            flex-direction: column;
            height: 100vh;
            padding: 25px 35px;
            overflow: hidden;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-shrink: 0;
            color: var(--text-main);
            height: 70px;
        }

        .welcome-text h1 {
            margin: 0;
            font-size: 1.8em;
            font-weight: 700;
            color: #0f3460;
            /* Navy Heading */
        }

        .welcome-text p {
            margin: 5px 0 0;
            color: var(--text-muted);
            font-size: 0.9em;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #fff;
            padding: 8px 15px;
            border-radius: 50px;
            box-shadow: var(--shadow-sm);
            border: 1px solid #e1e4e8;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--secondary-color);
        }

        .user-profile span {
            font-weight: 600;
            font-size: 0.9em;
            color: var(--text-main);
        }

        /* --- 5. Content Frame --- */
        .content-wrapper {
            flex: 1;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            overflow: hidden;
            position: relative;
        }

        iframe[name="content_frame"] {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }


        /* --- 6. Responsive --- */
        @media (max-width: 768px) {
            body {
                height: auto;
                overflow: auto;
            }

            .dashboard-container {
                display: block;
                height: auto;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: -260px;
                height: 100vh;
                transition: left 0.3s ease;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                padding: 15px;
                width: 100%;
                height: 100vh;
            }
            .content-wrapper {
                 height: calc(100vh - 100px);
            }

            .menu-btn {
                display: block !important;
            }
        }

        .menu-btn {
            display: none;
            font-size: 1.5em;
            cursor: pointer;
            color: #333;
        }
    </style>
</head>

<body>

    <div class="dashboard-container">

        <aside class="sidebar" id="sidebar">
            <div class="logo">
                <h2>Alex <span>Admin</span></h2>
            </div>

            <nav class="nav-menu">
                <ul>
                    <!-- Updated links to target content_frame -->
                    <li><a href="welcome_splash.php" target="content_frame" class="active"><i class="fas fa-th-large"></i> Dashboard</a></li>
                    <li><a href="registration.php" target="content_frame"><i class="fas fa-user-plus"></i> New Registration</a></li>
                    <li><a href="admin/student_details.php" target="content_frame"><i class="fas fa-users"></i> Student Details</a></li>
                    <?php
                    // Count pending approvals
                    $pending_count_sql = "SELECT COUNT(*) as count FROM onlineapplication";
                    $pending_result = mysqli_query($con, $pending_count_sql);
                    $pending_count = 0;
                    if($pending_result) {
                        $row = mysqli_fetch_assoc($pending_result);
                        $pending_count = $row['count'];
                    }
                    ?>
                    <li>
                        <a href="admin/pending_approvals.php" target="content_frame">
                            <i class="fas fa-tasks"></i> Approvals 
                            <?php if($pending_count > 0): ?>
                                <span style="background: #e94560; color: white; border-radius: 50%; padding: 2px 8px; font-size: 0.8em; margin-left: auto;">
                                    <?php echo $pending_count; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li><a href="admin/payments.php" target="content_frame"><i class="fas fa-file-invoice-dollar"></i> Payments</a></li>
                    <li><a href="admin/schedule.php" target="content_frame"><i class="fas fa-calendar-check"></i> Scheduling</a></li>
                    <li><a href="admin/results.php" target="content_frame"><i class="fas fa-graduation-cap"></i> Exam Results</a></li>
                    <li><a href="admin/reports.php" target="content_frame"><i class="fas fa-chart-pie"></i> Reports</a></li>
                </ul>

                <div class="logout-link">
                    <a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <i class="fas fa-bars menu-btn" onclick="toggleSidebar()"></i>
                    <div class="welcome-text">
                        <h1>Dashboard</h1>
                        <!-- Display Admin Name -->
                        <p>Welcome back, <?php echo htmlspecialchars($admin_name); ?>!</p>
                    </div>
                </div>
                <div class="user-profile">
                    <img src="assets/images/boy.jpg" alt="User">
                    <!-- Display Admin Name -->
                    <span><?php echo htmlspecialchars($admin_name); ?></span>
                </div>
            </header>

            <div class="content-wrapper">
                <!-- IFrame to load content -->
                <iframe name="content_frame" src="welcome_splash.php"></iframe>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Optional: Update active menu item
        const links = document.querySelectorAll('.nav-menu a');
        links.forEach(link => {
            link.addEventListener('click', function() {
                if (this.getAttribute('href') !== 'login.php') {
                    links.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>
