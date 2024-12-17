<div class="sidebar">
    <div class="py-4 px-3">
        <div class="logo-container">
            <img src="img/dbkulogowithname.png" alt="Logo" class="img-fluid mb-4">
        </div>
        <br>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">
                    Dashboard
                </a>
            </li>
            <li class="nav-item position-relative">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'attendance.php' || basename($_SERVER['PHP_SELF']) == 'qr_generator.php' ? 'active' : ''; ?>" href="attendance.php">
                    Attendance Management
                </a>
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'attendance.php' ? 'active' : ''; ?>" href="attendance.php">
                            Attendance Form
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'form.php' ? 'active' : ''; ?>" href="form.php">
                            QR Generator
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item position-relative">
                <a class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['license.php', 'hosting.php', 'sla.php']) ? 'active' : ''; ?>" href="license.php">
                    Application Management (trial)
                </a>
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'license.php' ? 'active' : ''; ?>" href="license.php">
                            License
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'hosting.php' ? 'active' : ''; ?>" href="hosting.php">
                            Hosting
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'sla.php' ? 'active' : ''; ?>" href="sla.php">
                            SLA
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'account.php' ? 'active' : ''; ?>" href="account.php">
                    User Account Management (trial)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" href="profile.php">
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    Logout
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
    @font-face {
        font-family: 'AareaKilometer';
        src: url('css/AareaKilometer-Regular.woff2') format('woff2'),
             url('css/AareaKilometer-Regular.woff') format('woff');
        font-weight: normal;
        font-style: normal;
    }
    .sidebar {
        font-family: 'AareaKilometer', sans-serif;
        text-transform: uppercase;
        background-color: #f0f5f4;
        border-right: 1px solid #b5d1c7;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        padding-top: 0;
        overflow-y: auto;
        z-index: 100;
        transition: width 0.3s, transform 0.3s;
    }
    .collapsed .sidebar {
        transform: translateX(-250px);
    }
    .sidebar .nav-link {
        color: #808080;
        transition: background-color 0.3s, color 0.3s;
        padding: 10px 20px;
        display: block;
    }
    .sidebar .nav-link:hover {
        background: linear-gradient(135deg, #e0f7fa, #b3e5fc);
        color: #3b82f6;
    }
    .sidebar .nav-link.active {
        background-color: #3b82f6;
        color: #ffffff;
    }
    .logo-container {
        text-align: center;
        margin-bottom: 20px;
    }
    .logo-text {
        font-size: 0.8rem;
    }
    .nav-item.position-relative {
        position: relative;
    }
    .sub-menu {
        display: none;
        position: relative;
        background-color: #f0f5f4;
        border-top: 1px solid #b5d1c7;
        width: 100%;
    }
    .nav-item:hover > .sub-menu {
        display: block;
    }
    .sub-menu .nav-link {
        padding: 10px 20px;
        color: #00000;
    }
    .sub-menu .nav-link:hover {
        background-color: #c1e1d6;
        color: #00000;
    }
    .sub-menu .nav-link.active {
        background-color: #3b82f6 ;
        color: #ffffff;
    }
</style>
