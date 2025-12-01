  <?= $this->extend('layouts/base') ?>
  <?= $this->section('content') ?>
  
  <div class="main-content">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-column flex-md-row  flex-lg-row  mt-3">
                            <div class="flex-grow-1">
                                <h3 class="mb-2 text-color-2">Dashboard</h3>
                            </div>
                            <div class="mt-3 mt-lg-0">
                                
                            </div>
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
                             <div class="col-lg-5 mb-4 mb-lg-0">
                              <div class="instructors-section card pb-0">
                                <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center py-3">
                                  <h5 class="mb-0 text-color-2">Traffic Sources</h5>
                                  <div>
                                    <select class="form-select form-select-sm w-auto border-0 text-color-3" aria-label="Select time period">
                                        <option value="30 days" selected>30 days</option>
                                        <option value="15 days">15 days</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="card-body p-0 mt-40">
                                  <div class="mb-2">
                                    <div class="chart-container">
                                      <canvas id="trafficChart"></canvas>
                                   </div>
                                    <div class="mx-5 mt-5 traffic-legend">
                                      <table class="table table-borderless">
                                          <tbody>
                                              <tr>
                                                  <td><span class="organic text-color-1">Organic Search</span></td>
                                                  <td><span class="text-color-2">4,305</span></td>
                                              </tr>
                                              <tr>
                                                  <td><span class="referrals text-color-1">Referrals</span></td>
                                                  <td><span class="text-color-2">482</span></td>
                                              </tr>
                                              <tr>
                                                  <td><span class="social-media text-color-1">Social Media</span></td>
                                                  <td><span class="text-color-2">859</span></td>
                                              </tr>
                                          </tbody>
                                      </table>
                                  </div>                                
                                </div>
                                </div>
                              </div>
                             </div>
                             <div class="col-lg-4 mb-4 mb-lg-0">
                              <div class="instructors-section card pb-1">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center py-4">
                                  <h5 class="mb-0 text-color-2">Top Instructors</h5>
                                  <a href="#" class="text-color-3">View All</a>
                                </div>
                                <div class="card-body p-0">
                                  <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center py-3">
                                      <div class="avatar rounded-circle bg-primary text-white me-3">AB</div>
                                      <div class="flex-grow-1">
                                        <h6 class="mb-0 text-color-2">Sofnio</h6>
                                        <small class="text-color-3">info@softnio.com</small>
                                      </div>
                                      <div class="text-end">
                                        <div class="rating-stars text-size-13">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <small class="d-block text-color-3">25 Reviews</small>
                                      </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center py-3">
                                      <div class="avatar rounded-circle bg-info text-white me-3">AL</div>
                                      <div class="flex-grow-1">
                                        <h6 class="mb-0 text-color-2">Ashley Lawson</h6>
                                        <small class="text-color-3">ashley@softnio.com</small>
                                      </div>
                                      <div class="text-end">
                                        <div class="rating-stars text-size-13">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <small class="d-block text-color-3">22 Reviews</small>
                                      </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center py-3">
                                      <div class="avatar rounded-circle bg-success text-white me-3">JM</div>
                                      <div class="flex-grow-1">
                                        <h6 class="mb-0 text-color-2">Jane Montgomery</h6>
                                        <small class="text-color-3">jane84@example.com</small>
                                      </div>
                                      <div class="text-end">
                                        <div class="rating-stars text-size-13">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                        </div>
                                        <small class="d-block text-color-3">19 Reviews</small>
                                      </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center py-3">
                                      <div class="avatar rounded-circle bg-secondary text-white me-3">LH</div>
                                      <div class="flex-grow-1">
                                        <h6 class="mb-0 text-color-2">Larry Henry</h6>
                                        <small class="text-color-3">larry108@example.com</small>
                                      </div>
                                      <div class="text-end">
                                        <div class="rating-stars text-size-13">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                        </div>
                                        <small class="d-block text-color-3">24 Reviews</small>
                                      </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center py-3">
                                      <div class="avatar rounded-circle bg-secondary text-white me-3">LH</div>
                                      <div class="flex-grow-1">
                                        <h6 class="mb-0 text-color-2">Larry Henry</h6>
                                        <small class="text-color-3">larry108@example.com</small>
                                      </div>
                                      <div class="text-end">
                                        <div class="rating-stars text-size-13">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                        </div>
                                        <small class="d-block text-color-3">24 Reviews</small>
                                      </div>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                             </div>
                             <div class="col-lg-8 mb-4 mb-lg-0">
                              <div class="instructors-section card">
                                <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center py-3">
                                  <h5 class="mb-0 text-color-2">Conversions</h5>
                                  <div>
                                    <select class="form-select form-select-sm w-auto border-0 text-color-3" aria-label="Select time period">
                                        <option value="30 days" selected>30 days</option>
                                        <option value="15 days">15 days</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="card-body">
                                  <canvas id="barChart" class="mt-5" height="96"></canvas>
                                </div>
                              </div>
                             </div>
                             <div class="col-lg-4">
                              <div class="instructors-section card pb-1">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center py-4">
                                  <h5 class="mb-0 text-color-2">Top Categories</h5>
                                  <a href="#" class="text-color-3">View All</a>
                                </div>
                                <div class="card-body p-0">
                                  <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center py-3">
                                      <div class="avatar rounded-circle bg-primary text-white me-3">AB</div>
                                      <div class="flex-grow-1">
                                        <h6 class="mb-0 text-color-2">Digital Marketing</h6>
                                        <small class="text-color-3">16+ Courses</small>
                                      </div>
                                      <div class="text-end">
                                        <i class="fa-solid fa-chevron-right arrow-icon"></i>
                                      </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center py-3">
                                      <div class="avatar rounded-circle bg-info text-white me-3">AL</div>
                                      <div class="flex-grow-1">
                                        <h6 class="mb-0 text-color-2">Web Development</h6>
                                        <small class="text-color-3">16+ Courses</small>
                                      </div>
                                      <div class="text-end">
                                        <i class="fa-solid fa-chevron-right arrow-icon"></i>
                                      </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center py-3">
                                      <div class="avatar rounded-circle bg-success text-white me-3">JM</div>
                                      <div class="flex-grow-1">
                                        <h6 class="mb-0 text-color-2">UI/UX Design</h6>
                                        <small class="text-color-3">16+ Courses</small>
                                      </div>
                                      <div class="text-end">
                                        <i class="fa-solid fa-chevron-right arrow-icon"></i>
                                      </div>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center py-3">
                                      <div class="avatar rounded-circle bg-secondary text-white me-3">LH</div>
                                      <div class="flex-grow-1">
                                        <h6 class="mb-0 text-color-2">Graphic Design</h6>
                                        <small class="text-color-3">16+ Courses</small>
                                      </div>
                                      <div class="text-end">
                                        <i class="fa-solid fa-chevron-right arrow-icon"></i>
                                      </div>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                             </div>
                      </div> 
                </div>
            </div>

  <?= $this->endSection() ?>