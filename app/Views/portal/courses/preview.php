<?= $this->extend('layouts/portal') ?>
<?= $this->section('content') ?>

<style>
    .preview-container {
        background: #f7f9fa;
        min-height: calc(100vh - 200px);
    }
    
    .preview-header {
        background: #1c1d1f;
        color: #fff;
        padding: 2rem 0;
    }
    
    .preview-badge {
        display: inline-block;
        background: #ffc107;
        color: #000;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .video-player-container {
        background: #000;
        position: relative;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
        margin-bottom: 0;
    }
    
    .video-player-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .lecture-info {
        padding: 1.5rem;
        background: #fff;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .preview-sidebar {
        background: #fff;
        height: calc(100vh - 200px);
        overflow-y: auto;
        padding: 0;
    }
    
    .preview-sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e0e0e0;
        background: #fff;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .preview-lecture-item {
        padding: 0.75rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.2s;
    }
    
    .preview-lecture-item:hover {
        background: #f7f9fa;
    }
    
    .preview-lecture-item.active {
        background: #e8f4f8;
        border-left: 3px solid var(--primary-blue);
    }
    
    .enroll-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 2rem;
        text-align: center;
        margin-top: 2rem;
    }
    
    .enroll-banner h3 {
        margin-bottom: 1rem;
    }
    
    .enroll-banner p {
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }
</style>

<!-- Preview Header -->
<div class="preview-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="preview-badge">PREVIEW</span>
                <h1><?= esc($course['title']) ?></h1>
                <p class="mb-0 opacity-75">Watch free preview lectures before enrolling</p>
            </div>
            <div class="col-lg-4 text-end">
                <?php 
                $session = \Config\Services::session();
                $user = $session->get('user');
                $isLoggedIn = $user && isset($user['role']) && $user['role'] === 'student';
                ?>
                <a href="<?= base_url('courses/' . $course['id']) ?>" class="btn btn-light me-2">
                    <i class="fas fa-arrow-left me-2"></i>Back to Course
                </a>
                <?php if ($isEnrolled): ?>
                    <a href="<?= base_url('portal/learn/' . $course['id']) ?>" class="btn btn-warning">
                        <i class="fas fa-play me-2"></i>Continue Learning
                    </a>
                <?php elseif ($isLoggedIn): ?>
                    <form method="POST" action="<?= base_url('portal/enroll/' . $course['id']) ?>" style="display: inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-warning">
                            <?= $course['is_free'] ? 'Enroll for Free' : 'Enroll Now' ?>
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?= base_url('portal/login') ?>" class="btn btn-warning">
                        <i class="fas fa-sign-in-alt me-2"></i>Login to Enroll
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="preview-container">
    <div class="row g-0">
        <!-- Video Player -->
        <div class="col-lg-8">
            <div class="video-player-container my-5">
                <?php if (!empty($currentLecture) && $currentLecture['content_type'] === 'video' && !empty($currentLecture['video_url'])): ?>
                    <video id="previewVideoPlayer" controls controlsList="nodownload" preload="metadata" crossorigin="anonymous">
                        <source src="<?= esc($currentLecture['video_url']) ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php elseif (!empty($currentLecture) && $currentLecture['content_type'] === 'article' && !empty($currentLecture['article_content'])): ?>
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #fff; padding: 2rem; overflow-y: auto;">
                        <div class="article-content">
                            <?= nl2br(esc($currentLecture['article_content'])) ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #fff;">
                        <i class="fas fa-video-slash fa-3x mb-3"></i>
                        <p>No preview content available</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="lecture-info">
                <h4 id="currentLectureTitle"><?= !empty($currentLecture) ? esc($currentLecture['title']) : esc($course['title']) ?></h4>
                <p id="currentLectureDescription" class="text-muted mb-0">
                    <?= !empty($currentLecture) ? esc($currentLecture['description'] ?? '') : esc($course['short_description'] ?? '') ?>
                </p>
                <?php if (!empty($currentLecture) && !empty($currentLecture['section_title'])): ?>
                    <small class="text-muted">
                        <i class="fas fa-folder me-1"></i><?= esc($currentLecture['section_title']) ?>
                    </small>
                <?php endif; ?>
            </div>
            
            <!-- Enroll Banner -->
            <?php 
            $session = \Config\Services::session();
            $user = $session->get('user');
            $isLoggedIn = $user && isset($user['role']) && $user['role'] === 'student';
            ?>
            <?php if (!$isEnrolled && !empty($previewLectures)): ?>
            <div class="enroll-banner">
                <h3>Ready to continue learning?</h3>
                <p>Enroll now to access all <?= count($sections) ?> sections and <?php 
                    $totalLectures = 0;
                    foreach ($sections as $section) {
                        $totalLectures += count($section['lectures']);
                    }
                    echo $totalLectures;
                ?> lectures in this course.</p>
                <?php if ($isLoggedIn): ?>
                    <form method="POST" action="<?= base_url('portal/enroll/' . $course['id']) ?>" style="display: inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-light btn-lg">
                            <?= $course['is_free'] ? 'Enroll for Free' : 'Enroll Now - $' . number_format($course['discount_price'] ?? $course['price'], 2) ?>
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?= base_url('portal/login') ?>" class="btn btn-light btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Login to Enroll
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Preview Sidebar -->
        <div class="col-lg-4 my-5">
            <div class="preview-sidebar">
                <div class="preview-sidebar-header">
                    <h5 class="mb-1">Preview Lectures</h5>
                    <small class="text-muted"><?= count($previewLectures) ?> preview lecture<?= count($previewLectures) != 1 ? 's' : '' ?> available</small>
                </div>
                
                <div class="curriculum-content">
                    <?php if (empty($previewLectures)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-video-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No preview lectures available.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($previewLectures as $lecture): ?>
                            <div class="preview-lecture-item <?= !empty($currentLecture) && $currentLecture['id'] == $lecture['id'] ? 'active' : '' ?>" 
                                 data-lecture-id="<?= $lecture['id'] ?>"
                                 data-lecture-title="<?= esc($lecture['title'], 'attr') ?>"
                                 data-lecture-description="<?= esc($lecture['description'] ?? '', 'attr') ?>"
                                 data-lecture-video="<?= esc($lecture['video_url'] ?? '', 'attr') ?>"
                                 data-lecture-type="<?= esc($lecture['content_type'], 'attr') ?>"
                                 data-lecture-article="<?= esc($lecture['article_content'] ?? '', 'attr') ?>"
                                 onclick="loadPreviewLecture(<?= $lecture['id'] ?>, '<?= esc($lecture['title'], 'js') ?>', '<?= esc($lecture['description'] ?? '', 'js') ?>', '<?= esc($lecture['video_url'] ?? '', 'js') ?>', '<?= esc($lecture['content_type'], 'js') ?>', '<?= esc($lecture['article_content'] ?? '', 'js') ?>')">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-<?= $lecture['content_type'] === 'video' ? 'play-circle' : 'file-alt' ?>"></i>
                                    <span><?= esc($lecture['title']) ?></span>
                                </div>
                                <?php if (!empty($lecture['video_duration'])): ?>
                                    <span class="text-muted small"><?= esc($lecture['video_duration']) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const previewVideoPlayer = document.getElementById('previewVideoPlayer');
    let currentPreviewLectureId = <?= !empty($currentLecture) ? $currentLecture['id'] : 'null' ?>;
    
    function loadPreviewLecture(lectureId, title, description, videoUrl, contentType, articleContent) {
        // Update active state
        document.querySelectorAll('.preview-lecture-item').forEach(item => {
            item.classList.remove('active');
        });
        const clickedItem = document.querySelector(`[data-lecture-id="${lectureId}"]`);
        if (clickedItem) {
            clickedItem.classList.add('active');
        }
        
        // Update video player or article content
        if (contentType === 'video' && videoUrl && previewVideoPlayer) {
            const currentTime = previewVideoPlayer.currentTime;
            previewVideoPlayer.src = videoUrl;
            previewVideoPlayer.style.display = 'block';
            previewVideoPlayer.load();
            
            previewVideoPlayer.addEventListener('loadedmetadata', function() {
                if (currentPreviewLectureId === lectureId && currentTime > 0) {
                    previewVideoPlayer.currentTime = currentTime;
                }
            }, { once: true });
            
            // Hide article content if exists
            const articleDiv = document.querySelector('.article-content');
            if (articleDiv) {
                articleDiv.closest('div[style*="position: absolute"]').style.display = 'none';
            }
        } else if (contentType === 'article' && articleContent) {
            // Show article content
            const videoContainer = document.querySelector('.video-player-container');
            if (videoContainer) {
                let articleDiv = videoContainer.querySelector('.article-content');
                if (!articleDiv) {
                    const articleWrapper = document.createElement('div');
                    articleWrapper.style.cssText = 'position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #fff; padding: 2rem; overflow-y: auto;';
                    articleDiv = document.createElement('div');
                    articleDiv.className = 'article-content';
                    articleWrapper.appendChild(articleDiv);
                    videoContainer.appendChild(articleWrapper);
                }
                            // Escape HTML and then convert newlines to <br>
                            const tempDiv = document.createElement('div');
                            tempDiv.textContent = articleContent;
                            articleDiv.innerHTML = tempDiv.innerHTML.replace(/\n/g, '<br>');
                articleDiv.closest('div[style*="position: absolute"]').style.display = 'block';
            }
            
            // Hide video player
            if (previewVideoPlayer) {
                previewVideoPlayer.style.display = 'none';
            }
        } else if (previewVideoPlayer) {
            previewVideoPlayer.style.display = 'none';
        }
        
        // Update lecture info
        document.getElementById('currentLectureTitle').textContent = title;
        document.getElementById('currentLectureDescription').textContent = description || '';
        
        currentPreviewLectureId = lectureId;
        
        // Update URL without page reload
        const newUrl = '<?= base_url('courses/' . $course['id']) ?>/preview?lecture=' + lectureId;
        window.history.pushState({}, '', newUrl);
    }
</script>

<?= $this->endSection() ?>

