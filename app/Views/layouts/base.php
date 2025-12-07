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
                        <?php
                        $session = \Config\Services::session();
                        $currentUser = $session->get('user');
                        $isAdmin = isset($currentUser['role']) && $currentUser['role'] === 'admin';
                        $isInstructor = isset($currentUser['role']) && $currentUser['role'] === 'instructor';
                        ?>
                        <?php if ($isAdmin): ?>
                        <!-- Messages Dropdown (Admin Only) -->
                        <?php
                        // Load recent discussion replies as messages
                        $db = \Config\Database::connect();
                        $tables = $db->listTables();
                        $recentMessages = [];
                        
                        try {
                            if (in_array('discussion_replies', $tables)) {
                                $discussionReplyModel = new \App\Models\DiscussionReplyModel();
                                $builder = $db->table('discussion_replies');
                                $builder->select('discussion_replies.*, discussions.title as discussion_title, users.first_name, users.last_name, users.profile_picture');
                                $builder->join('discussions', 'discussions.id = discussion_replies.discussion_id', 'left');
                                $builder->join('users', 'users.id = discussion_replies.user_id', 'left');
                                $builder->orderBy('discussion_replies.created_at', 'DESC');
                                $builder->limit(5);
                                $recentMessages = $builder->get()->getResultArray();
                            } else {
                                // Use discussions table with parent_id
                                $builder = $db->table('discussions');
                                $builder->select('discussions.*, parent.title as discussion_title, users.first_name, users.last_name, users.profile_picture');
                                $builder->join('discussions as parent', 'parent.id = discussions.parent_id', 'left');
                                $builder->join('users', 'users.id = discussions.user_id', 'left');
                                $builder->where('discussions.parent_id IS NOT NULL');
                                $builder->orderBy('discussions.created_at', 'DESC');
                                $builder->limit(5);
                                $recentMessages = $builder->get()->getResultArray();
                            }
                        } catch (\Exception $e) {
                            // If there's an error, just show empty messages
                            $recentMessages = [];
                        }
                        ?>
                        <li class="nav-item me-2-5">
                            <a href="#" class="text-color-1 position-relative"  role="button" 
                            data-bs-toggle="dropdown" 
                            data-bs-offset="0,0" 
                            aria-expanded="false">
                            <i class="fa-regular fa-message font-size-24"></i>
                            <?php if (count($recentMessages) > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                    <?= count($recentMessages) ?>
                                </span>
                            <?php endif; ?>
                        </a>
                            <div class="dropdown-menu dropdown-menu-end mt-4">
                                <div id="chatmessage" class="h-380 scroll-y p-3 custom-scrollbar">
                                    <!-- Chat Timeline -->
                                    <?php if (empty($recentMessages)): ?>
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p class="mb-0">No messages</p>
                                        </div>
                                    <?php else: ?>
                                        <ul class="timeline">
                                            <?php foreach ($recentMessages as $message): ?>
                                                <li>
                                                    <div class="timeline-panel">
                                                        <div class="media me-2">
                                                            <?php if (!empty($message['profile_picture'])): ?>
                                                                <img alt="image" width="50" src="<?= esc($message['profile_picture']) ?>" class="rounded-circle">
                                                            <?php else: ?>
                                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                                    <?= strtoupper(substr($message['first_name'] ?? 'U', 0, 1)) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="media-body">
                                                            <h6 class="mb-1">
                                                                <strong><?= esc(($message['first_name'] ?? '') . ' ' . ($message['last_name'] ?? '')) ?></strong>
                                                                <?php if (!empty($message['discussion_title'])): ?>
                                                                    replied to: <?= esc(substr($message['discussion_title'], 0, 30)) ?><?= strlen($message['discussion_title']) > 30 ? '...' : '' ?>
                                                                <?php else: ?>
                                                                    sent a message
                                                                <?php endif; ?>
                                                            </h6>
                                                            <p class="mb-1 small text-muted"><?= esc(substr($message['content'] ?? '', 0, 50)) ?><?= strlen($message['content'] ?? '') > 50 ? '...' : '' ?></p>
                                                            <small class="d-block"><i class="fa-solid fa-clock"></i> <?php
                                                                $time = strtotime($message['created_at'] ?? 'now');
                                                                $diff = time() - $time;
                                                                if ($diff < 60) echo 'Just now';
                                                                elseif ($diff < 3600) echo floor($diff/60) . ' min ago';
                                                                elseif ($diff < 86400) echo floor($diff/3600) . ' hour' . (floor($diff/3600) > 1 ? 's' : '') . ' ago';
                                                                elseif ($diff < 604800) echo floor($diff/86400) . ' day' . (floor($diff/86400) > 1 ? 's' : '') . ' ago';
                                                                else echo date('M d, Y', $time);
                                                            ?></small>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                                <a class="all-notification" href="<?= base_url('admin/discussions') ?>">See all messages <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php if ($isAdmin): ?>
                        <!-- Notifications Dropdown (Admin Only) -->
                        <?php
                        // Load notifications for admin users
                        try {
                            $db = \Config\Database::connect();
                            $userModel = new \App\Models\UserModel();
                            
                            // Get all admin users
                            $adminUsers = $userModel->where('role', 'admin')->findAll();
                            $adminIds = array_column($adminUsers, 'id');
                            
                            $recentNotifications = [];
                            $unreadCount = 0;
                            
                            if (!empty($adminIds)) {
                                $builder = $db->table('notifications');
                                $builder->select('notifications.*, users.first_name, users.last_name, users.profile_picture');
                                $builder->join('users', 'users.id = notifications.user_id', 'left');
                                $builder->whereIn('notifications.user_id', $adminIds);
                                $builder->orderBy('notifications.sent_at', 'DESC');
                                $builder->limit(6);
                                $recentNotifications = $builder->get()->getResultArray();
                                
                                // Get unread count
                                $unreadBuilder = $db->table('notifications');
                                $unreadCount = $unreadBuilder->whereIn('user_id', $adminIds)
                                    ->where('is_read', false)
                                    ->countAllResults();
                            }
                        } catch (\Exception $e) {
                            $recentNotifications = [];
                            $unreadCount = 0;
                        }
                        ?>
                        <li class="nav-item me-2-5">
                            <a href="#" class="text-color-1 notification position-relative" 
                                role="button" 
                                data-bs-toggle="dropdown" 
                                data-bs-offset="0,0" 
                                aria-expanded="false">
                                <i class="fa-regular fa-bell font-size-24"></i>
                                <?php if ($unreadCount > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                        <?= $unreadCount ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end mt-4">
                                <div id="Notification" class="h-380 scroll-y p-3 custom-scrollbar">
                                    <!-- Notifications Timeline -->
                                    <?php if (empty($recentNotifications)): ?>
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                            <p class="mb-0">No notifications</p>
                                        </div>
                                    <?php else: ?>
                                        <ul class="timeline">
                                            <?php foreach ($recentNotifications as $notification): ?>
                                                <li>
                                                    <div class="timeline-panel">
                                                        <div class="media me-2">
                                                            <?php
                                                            $iconClass = 'media-primary';
                                                            $icon = 'fa-info-circle';
                                                            if ($notification['type'] === 'payment') {
                                                                $iconClass = 'media-warning';
                                                                $icon = 'fa-credit-card';
                                                            } elseif ($notification['type'] === 'course') {
                                                                $iconClass = 'media-success';
                                                                $icon = 'fa-book';
                                                            } elseif ($notification['type'] === 'announcement') {
                                                                $iconClass = 'media-info';
                                                                $icon = 'fa-bullhorn';
                                                            }
                                                            ?>
                                                            <div class="media me-2 <?= $iconClass ?>">
                                                                <i class="fa <?= $icon ?>"></i>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <h6 class="mb-1">
                                                                <?= esc($notification['title']) ?>
                                                                <?php if (!$notification['is_read']): ?>
                                                                    <span class="badge bg-danger ms-1" style="font-size: 0.6rem;">New</span>
                                                                <?php endif; ?>
                                                            </h6>
                                                            <p class="mb-1 small text-muted"><?= esc(substr($notification['message'], 0, 60)) ?><?= strlen($notification['message']) > 60 ? '...' : '' ?></p>
                                                            <small class="d-block">
                                                                <i class="fa-solid fa-clock"></i> 
                                                                <?php
                                                                $time = strtotime($notification['sent_at'] ?? $notification['created_at'] ?? 'now');
                                                                $diff = time() - $time;
                                                                if ($diff < 60) echo 'Just now';
                                                                elseif ($diff < 3600) echo floor($diff/60) . ' min ago';
                                                                elseif ($diff < 86400) echo floor($diff/3600) . ' hour' . (floor($diff/3600) > 1 ? 's' : '') . ' ago';
                                                                elseif ($diff < 604800) echo floor($diff/86400) . ' day' . (floor($diff/86400) > 1 ? 's' : '') . ' ago';
                                                                else echo date('M d, Y', $time);
                                                            ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                                <a class="all-notification" href="<?= base_url('admin/notifications') ?>">See all notifications <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </li>
                        <?php endif; ?>
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



          
            