<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= ($title ?? 'Dashboard') ?> - E-LOOX Academy Admin</title>
    <!-- Stylesheets -->
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico')?>" type="image/x-icon">
    <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/fontawesome.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/brands.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/solid.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/css/style.css')?>" rel="stylesheet">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <base href="<?= base_url() ?>">
</head>
<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <!-- Main Wrapper -->
    <div id="main-wrapper" class="d-flex">
        
          <!-- Sidebar -->
            <?= $this->include('layouts/sidebar') ?>
            <!-- End Sidebar -->    
       <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Header -->
            <div class="header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="collapse-sidebar me-3 d-none d-lg-block text-color-1"><span><i class="fa-solid fa-bars font-size-24"></i></span></div>
                    <div class="menu-toggle me-3 d-block d-lg-none text-color-1"><span><i class="fa-solid fa-bars font-size-24"></i></span></div>
                    <div class="d-none d-md-block d-lg-block">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text bg-white " id="addon-wrapping"><i class="fa-solid search-icon fa-magnifying-glass text-color-1"></i></span>
                            <input type="text" class="form-control search-input border-l-none ps-0" placeholder="Search anything" aria-label="Username" aria-describedby="addon-wrapping">
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <ul class="nav d-flex align-items-center">
                        <!-- Messages Dropdown -->
                        <li class="nav-item me-2-5">
                            <a href="#" class="text-color-1 position-relative"  role="button" 
                            data-bs-toggle="dropdown" 
                            data-bs-offset="0,0" 
                            aria-expanded="false">
                            <i class="fa-regular fa-message font-size-24"></i>
                        </a>
                            <div class="dropdown-menu dropdown-menu-end mt-4">
                                <div id="chatmessage" class="h-380 scroll-y p-3 custom-scrollbar">
                                    <!-- Chat Timeline -->
                                    <ul class="timeline">
                                        <!-- Item 1 -->
                                        <li>
                                            <div class="timeline-panel">
                                                <div class="media me-2">
                                                    <img alt="image" width="50" src="./assets/images/avatar-1.jpg">
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-1">We talked about a project...</h6>
                                                    <small class="d-block"><i class="fa-solid fa-clock"></i> 30 min ago</small>
                                                </div>
                                            </div>
                                        </li>
                                        <!-- Item 2 -->
                                        <li>
                                            <div class="timeline-panel">
                                                <div class="media me-2">
                                                    <img alt="image" width="50" src="./assets/images/avatar-2.jpg">
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-1">You sent an email to the client...</h6>
                                                    <small class="d-block"><i class="fa-solid fa-clock"></i> 1 hour ago</small>
                                                </div>
                                            </div>
                                        </li>
                                        <!-- Item 3 -->
                                        <li>
                                            <div class="timeline-panel">
                                                <div class="media me-2">
                                                    <img alt="image" width="50" src="./assets/images/avatar-3.jpg">
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-1">Meeting with the design team...</h6>
                                                    <small class="d-block"><i class="fa-solid fa-clock"></i> 2 hours ago</small>
                                                </div>
                                            </div>
                                        </li>
                                        <!-- Item 4 -->
                                        <li>
                                            <div class="timeline-panel">
                                                <div class="media me-2">
                                                    <img alt="image" width="50" src="./assets/images/avatar-4.jpg">
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-1">Reviewed the project documents...</h6>
                                                    <small class="d-block"><i class="fa-solid fa-clock"></i> Yesterday</small>
                                                </div>
                                            </div>
                                        </li>
                                        <!-- Item 5 -->
                                        <li>
                                            <div class="timeline-panel">
                                                <div class="media me-2">
                                                    <img alt="image" width="50" src="./assets/images/avatar-5.jpg">
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-1">Finalized the project timeline...</h6>
                                                    <small class="d-block"><i class="fa-solid fa-clock"></i> 2 days ago</small>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <a class="all-notification" href="#">See all message <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </li>
                        <!-- Notifications Dropdown -->
                        <li class="nav-item me-2-5">
                            <a href="#" class="text-color-1 notification" 
                                role="button" 
                                data-bs-toggle="dropdown" 
                                data-bs-offset="0,0" 
                                aria-expanded="false">
                                <i class="fa-regular fa-bell font-size-24"></i>
                                <div class="marker"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end mt-4">
                                <div id="Notification" class="h-380 scroll-y p-3 custom-scrollbar">
                                    <!-- Notifications Timeline -->
                                    <ul class="timeline">
                                        <li>
                                            <div class="timeline-panel">
                                                <div class="media me-2">
                                                    <img alt="image" width="50" src="./assets/images/profile.png">
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-1">Dr Smith uploaded a new report</h6>
                                                    <small class="d-block">10 December 2023 - 08:15 AM</small>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="timeline-panel">
                                                <div class="media me-2 media-info">
                                                    AP
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-1">New Appointment Scheduled</h6>
                                                    <small class="d-block">10 December 2023 - 09:45 AM</small>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="timeline-panel">
                                                <div class="media me-2 media-success">
                                                    <i class="fa fa-check-circle"></i>
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-1">Patient checked in at reception</h6>
                                                    <small class="d-block">10 December 2023 - 10:20 AM</small>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="timeline-panel">
                                                <div class="media me-2">
                                                    <img alt="image" width="50" src="./assets/images/profile.png">
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-1">Dr Alice shared a prescription</h6>
                                                    <small class="d-block">10 December 2023 - 11:00 AM</small>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="timeline-panel">
                                                <div class="media me-2 media-danger">
                                                    EM
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-1">Emergency Alert: Critical Patient</h6>
                                                    <small class="d-block">10 December 2023 - 11:30 AM</small>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="timeline-panel">
                                                <div class="media me-2 media-primary">
                                                    <i class="fa fa-calendar-alt"></i>
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-1">Next Appointment Reminder</h6>
                                                    <small class="d-block">10 December 2023 - 12:00 PM</small>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    
                                </div>
                                <a class="all-notification" href="#">See all notifications <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </li>
                         <!-- User Profile -->
                        <li class="nav-item dropdown user-profile">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php 
                                $user = session()->get('user');
                                $initials = '';
                                $profilePicture = null;
                                if ($user) {
                                    $initials = strtoupper(substr($user['first_name'] ?? 'A', 0, 1) . substr($user['last_name'] ?? '', 0, 1));
                                    $profilePicture = $user['profile_picture'] ?? null;
                                } else {
                                    $initials = 'A';
                                }
                                ?>
                                <?php if ($profilePicture): ?>
                                    <img src="<?= esc($profilePicture) ?>" alt="Profile" 
                                         class="user-avatar me-0 me-lg-3 rounded-circle" 
                                         style="width: 40px; height: 40px; object-fit: cover;"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                    <span class="user-avatar me-0 me-lg-3" style="display: none;"><?= $initials ?></span>
                                <?php else: ?>
                                    <span class="user-avatar me-0 me-lg-3"><?= $initials ?></span>
                                <?php endif; ?>
                                <div class="d-none d-lg-block">
                                    <span class="d-block auth-role"><?= ucfirst($user['role'] ?? 'Admin') ?></span>
                                    <span class="auth-name"><?= ($user['first_name'] ?? 'Admin') . ' ' . ($user['last_name'] ?? 'User') ?></span>
                                    <span class="ms-2 text-color-1 text-size-sm"><i class="fa-solid fa-angle-down"></i></span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end mt-3">
                                <li><a class="dropdown-item" href="<?= base_url('admin/profile') ?>"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('admin/settings') ?>"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= base_url('admin/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Main Content -->
             <main class="p-4">
                <?= $this->renderSection('content') ?>
            </main>
            <!-- End Main Content -->   

            <!-- Footer -->
            <?= $this->include('layouts/footer') ?>
            <!-- End Footer --> 



          
            