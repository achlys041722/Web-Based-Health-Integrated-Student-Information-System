<?php
if (!isset($activePage)) $activePage = '';
?>
<nav class="col-lg-2 col-md-3 d-md-block bg-light sidebar collapse show" id="sidebarMenu">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link<?php if($activePage==='dashboard') echo ' active'; ?>" href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/teacher/teacher_dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link<?php if($activePage==='notifications') echo ' active'; ?>" href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/teacher/notifications.php">Notifications</a></li>
            <li class="nav-item"><a class="nav-link<?php if($activePage==='my_students') echo ' active'; ?>" href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/teacher/my_students.php">My Students</a></li>
            <li class="nav-item"><a class="nav-link<?php if($activePage==='health_records') echo ' active'; ?>" href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/teacher/health_records.php">Health Records</a></li>
            <li class="nav-item"><a class="nav-link<?php if($activePage==='profile') echo ' active'; ?>" href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/teacher/profile.php">Profile</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/register/logout.php">Logout</a></li>
        </ul>
    </div>
</nav> 