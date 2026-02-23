<?php 
// 1. Determine if this file is being viewed alone or included
$is_included = count(get_included_files()) > 1;
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('theme.php'); 
$theme_class = (isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark') ? 'dark-mode' : '';
?>

<?php if (!$is_included): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sidebar Preview</title>
    <link rel="stylesheet" href="CSS/dashboard.css"> <?php endif; ?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="CSS/sidebar.css">

<div class="sidebar <?php echo $theme_class; ?>">
    <div class="logo">
        <div class="logo-box">H</div> 
        <span>Supervisor</span>
    </div>
    
    <ul>
        <li class="<?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>" onclick="location.href='dashboard.php'">
            <i class="fas fa-chart-pie"></i> Dashboard
        </li>
        <li class="<?php echo ($current_page == 'attendance') ? 'active' : ''; ?>" onclick="location.href='attendance.php'">
            <i class="fas fa-clock"></i> Attendance
        </li>
        <li class="<?php echo ($current_page == 'schedule') ? 'active' : ''; ?>" onclick="location.href='scheduling.php'">
            <i class="fas fa-calendar-alt"></i> Schedule
        </li>
        <li class="<?php echo ($current_page == 'timesheets') ? 'active' : ''; ?>" onclick="location.href='timesheet.php'">
            <i class="fas fa-file-invoice"></i> Timesheets
        </li>
        <li class="<?php echo ($current_page == 'leave') ? 'active' : ''; ?>" onclick="location.href='leave.php'">
            <i class="fas fa-plane-departure"></i> Leave
        </li>
        <li class="<?php echo ($current_page == 'claims') ? 'active' : ''; ?>" onclick="location.href='claims.php'">
            <i class="fas fa-hand-holding-usd"></i> Claims
        </li>
        <li class="<?php echo ($current_page == 'reports') ? 'active' : ''; ?>" onclick="location.href='reports.php'">
            <i class="fas fa-file-medical-alt"></i> Reports
        </li>
        <li class="<?php echo ($current_page == 'notifications') ? 'active' : ''; ?>" onclick="location.href='notifications.php'">
            <i class="fas fa-bell"></i> Notifications
        </li>
    </ul>
</div>

<?php if (!$is_included): ?>
</body>
</html>
<?php endif; ?>