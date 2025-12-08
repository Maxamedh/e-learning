<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="main-content">
  <div class="row">
    <div class="col-12">
      <div class="d-flex align-items-lg-center flex-column flex-md-row  flex-lg-row  mt-3">
        <div class="flex-grow-1">
          <h3 class="mb-2 text-color-2">Dashboard</h3>
        </div>
        <?php
        $session = \Config\Services::session();
        $user = $session->get('user');
        $isAdmin = isset($user['role']) && $user['role'] === 'admin';
        ?>
        <?php if ($isAdmin): ?>
          <div class="mt-3 mt-lg-0">
            <?php if ($unreadNotificationCount > 0): ?>
              <a href="<?= base_url('admin/notifications') ?>" class="btn btn-primary position-relative">
                <i class="fas fa-bell me-2"></i>Notifications
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  <?= $unreadNotificationCount ?>
                </span>
              </a>
            <?php else: ?>
              <a href="<?= base_url('admin/notifications') ?>" class="btn btn-outline-primary">
                <i class="fas fa-bell me-2"></i>Notifications
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div><!-- end card header -->
    </div>
    <!--end col-->
  </div>
  <div class="mt-4">
    <div class="row">
      <div class="col-lg-3">
        <div class="row">
          <!-- Total Students Card -->
          <div class="col-12 col-md-6 col-lg-12 mb-4">
            <div class="stats-card">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="stats-label">Total Students</div>
                  <div class="stats-value"><?= number_format($stats['total_students'] ?? 0) ?></div>
                  <div class="trend-wrapper">
                    Active Students
                  </div>
                </div>
                <div class="icon-wrapper icon-purple">
                  <i class="fas fa-users"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Total Courses Card -->
          <div class="col-12 col-md-6 col-lg-12 mb-4">
            <div class="stats-card">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="stats-label">Total Courses</div>
                  <div class="stats-value"><?= number_format($stats['total_courses'] ?? 0) ?></div>
                  <div class="trend-wrapper">
                    Published: <?= number_format($stats['published_courses'] ?? 0) ?>
                  </div>
                </div>
                <div class="icon-wrapper icon-red">
                  <i class="fas fa-play-circle"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Overall Revenue Card -->
          <div class="col-12 col-md-6 col-lg-12 mb-4">
            <div class="stats-card">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="stats-label">Total Revenue</div>
                  <div class="stats-value">$<?= number_format($stats['total_revenue'] ?? 0, 2) ?></div>
                  <div class="trend-wrapper">
                    Completed Orders: <?= number_format($stats['total_orders'] ?? 0) ?>
                  </div>
                </div>
                <div class="icon-wrapper icon-green">
                  <i class="fas fa-dollar-sign"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Total Instructors Card -->
          <div class="col-12 col-md-6 col-lg-12 mb-4">
            <div class="stats-card">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="stats-label">Total Instructors</div>
                  <div class="stats-value"><?= number_format($stats['total_instructors'] ?? 0) ?></div>
                  <div class="trend-wrapper">
                    Active Instructors
                  </div>
                </div>
                <div class="icon-wrapper" style="background: rgba(124, 93, 250, 0.1); color: #7C5DFA;">
                  <i class="fas fa-chalkboard-teacher"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Total Enrollments Card -->
          <div class="col-12 col-md-6 col-lg-12 mb-4">
            <div class="stats-card">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="stats-label">Total Enrollments</div>
                  <div class="stats-value"><?= number_format($stats['total_enrollments'] ?? 0) ?></div>
                  <div class="trend-wrapper">
                    All Time
                  </div>
                </div>
                <div class="icon-wrapper" style="background: rgba(75, 222, 151, 0.1); color: #4BDE97;">
                  <i class="fas fa-user-graduate"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="instructors-section card pb-1">
          <div class="card-header bg-white d-flex justify-content-between align-items-center py-4">
            <h5 class="mb-0 text-color-2">Recent Notifications</h5>
            <a href="<?= base_url('admin/notifications') ?>" class="text-color-3">View All</a>
          </div>
          <div class="card-body p-0">
            <?php if (empty($recentNotifications)): ?>
              <div class="p-3 text-center text-muted">
                <i class="fas fa-bell-slash fa-2x mb-2"></i>
                <p class="mb-0">No notifications</p>
              </div>
            <?php else: ?>
              <ul class="list-group list-group-flush">
                <?php foreach (array_slice($recentNotifications, 0, 5) as $notification): ?>
                  <li class="list-group-item py-3 <?= !$notification['is_read'] ? 'bg-light' : '' ?>">
                    <div class="d-flex align-items-start">
                      <div class="flex-grow-1">
                        <h6 class="mb-1 text-color-2">
                          <?= esc($notification['title']) ?>
                          <?php if (!$notification['is_read']): ?>
                            <span class="badge bg-danger ms-2">New</span>
                          <?php endif; ?>
                        </h6>
                        <p class="mb-1 text-color-3 small">
                          <?= esc(substr($notification['message'], 0, 80)) ?>    <?= strlen($notification['message']) > 80 ? '...' : '' ?>
                        </p>
                        <small
                          class="text-muted"><?= date('M d, Y H:i', strtotime($notification['sent_at'] ?? $notification['created_at'] ?? 'now')) ?></small>
                      </div>
                      <a href="<?= base_url('admin/notifications/view/' . $notification['id']) ?>"
                        class="btn btn-sm btn-link text-primary">
                        <i class="fas fa-arrow-right"></i>
                      </a>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="col-lg-8 mb-4 mb-lg-0">
                              <div class="instructors-section card pb-1">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center py-4">
                                  <h5 class="mb-0 text-color-2">Recent Courses</h5>
                                  <a href="<?= base_url('admin/courses') ?>" class="text-color-3">View All</a>
                                </div>
                                <div class="card-body p-0">
                                  <?php if (empty($recentCourses)): ?>
                                      <div class="p-3 text-center text-muted">
                                        <i class="fas fa-book fa-2x mb-2"></i>
                                        <p class="mb-0">No courses yet</p>
                                      </div>
                                  <?php else: ?>
                                      <ul class="list-group list-group-flush">
                                        <?php foreach ($recentCourses as $course): ?>
                                            <li class="list-group-item d-flex align-items-center py-3">
                                              <div class="flex-grow-1">
                                                <h6 class="mb-0 text-color-2">
                                                  <a href="<?= base_url('admin/courses/view/' . $course['id']) ?>" class="text-decoration-none">
                                                    <?= esc($course['title']) ?>
                                                  </a>
                                                </h6>
                                                <small class="text-color-3">
                                                  <?= esc($course['first_name'] . ' ' . $course['last_name']) ?> • 
                                                  <?= esc($course['category_name'] ?? 'Uncategorized') ?>
                                                </small>
                                              </div>
                                              <div class="text-end">
                                                <span class="badge bg-<?= $course['status'] === 'published' ? 'success' : 'secondary' ?>">
                                                  <?= ucfirst($course['status']) ?>
                                                </span>
                                              </div>
                                            </li>
                                        <?php endforeach; ?>
                                      </ul>
                                  <?php endif; ?>
                                </div>
                              </div>
                             </div>
                             <div class="col-lg-4">
                              <div class="instructors-section card pb-1">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center py-4">
                                  <h5 class="mb-0 text-color-2">Top Courses</h5>
                                  <a href="<?= base_url('admin/courses') ?>" class="text-color-3">View All</a>
                                </div>
                                <div class="card-body p-0">
                                  <?php if (empty($topCourses)): ?>
                                      <div class="p-3 text-center text-muted">
                                        <i class="fas fa-book fa-2x mb-2"></i>
                                        <p class="mb-0">No courses yet</p>
                                      </div>
                                  <?php else: ?>
                                      <ul class="list-group list-group-flush">
                                        <?php foreach ($topCourses as $course): ?>
                                            <li class="list-group-item d-flex align-items-center py-3">
                                              <div class="flex-grow-1">
                                                <h6 class="mb-0 text-color-2">
                                                  <a href="<?= base_url('admin/courses/view/' . $course['id']) ?>" class="text-decoration-none">
                                                    <?= esc($course['title']) ?>
                                                  </a>
                                                </h6>
                                                <small class="text-color-3">
                                                  <?= number_format($course['total_students'] ?? 0) ?> students • 
                                                  <?= number_format($course['avg_rating'] ?? 0, 1) ?> <i class="fas fa-star text-warning"></i>
                                                </small>
                                              </div>
                                            </li>
                                        <?php endforeach; ?>
                                      </ul>
                                  <?php endif; ?>
                                </div>
                              </div>
                             </div>
                      </div> 
                </div>
            </div>

  <?= $this->endSection() ?>