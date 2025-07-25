<?php
if (!isset($activePage)) $activePage = '';
?>
<nav class="col-lg-2 col-md-3 d-md-block bg-light sidebar collapse show" id="sidebarMenu">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link<?php if($activePage==='dashboard') echo ' active'; ?>" href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/nurse/nurse_dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link<?php if($activePage==='notifications') echo ' active'; ?>" href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/nurse/notifications.php">Notifications</a></li>
            <li class="nav-item"><a class="nav-link<?php if($activePage==='requests') echo ' active'; ?>" href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/nurse/requests.php">Principal Requests</a></li>
            <li class="nav-item"><a class="nav-link<?php if($activePage==='schools') echo ' active'; ?>" href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/nurse/schools.php">Schools</a></li>
            <li class="nav-item"><a class="nav-link<?php if($activePage==='students') echo ' active'; ?>" href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/nurse/students.php">Students</a></li>
            <li class="nav-item"><a class="nav-link<?php if($activePage==='health_records') echo ' active'; ?>" href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/nurse/health_records.php">Health Records</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="/Web-Based%20Health-Integrated%20Student%20Information%20System/templates/register/logout.php">Logout</a></li>
        </ul>
    </div>
</nav> 