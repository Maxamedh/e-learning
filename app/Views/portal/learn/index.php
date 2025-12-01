<?= $this->extend('layouts/portal') ?>
<?= $this->section('content') ?>

<style>
    .learn-container {
        background: #f7f9fa;
        min-height: calc(100vh - 200px);
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
    
    .course-sidebar {
        background: #fff;
        height: calc(100vh - 80px);
        overflow-y: auto;
        padding: 0;
    }
    
    .course-sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e0e0e0;
        background: #fff;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .section-item {
        border-bottom: 1px solid #e0e0e0;
    }
    
    .section-header {
        padding: 1rem 1.5rem;
        background: #f7f9fa;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .section-header:hover {
        background: #e0e0e0;
    }
    
    .lecture-list {
        display: none;
    }
    
    .lecture-list.expanded {
        display: block;
    }
    
    .lecture-item {
        padding: 0.75rem 1.5rem 0.75rem 3rem;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.2s;
    }
    
    .lecture-item:hover {
        background: #f7f9fa;
    }
    
    .lecture-item.active {
        background: #e8f4f8;
        border-left: 3px solid var(--primary-blue);
    }
    
    .lecture-item.completed i {
        color: var(--primary-blue);
    }
    
    .lecture-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
    }
    
    .lecture-info {
        padding: 1.5rem;
        background: #fff;
        border-bottom: 1px solid #e0e0e0;
    }
</style>

<div class="learn-container">
    <div class="row g-0">
        <!-- Video Player -->
        <div class="col-lg-8">
            <div class="video-player-container">
                <?php if (!empty($currentLecture) && $currentLecture['content_type'] === 'video' && !empty($currentLecture['video_url'])): ?>
                    <video id="courseVideoPlayer" controls controlsList="nodownload" preload="metadata" crossorigin="anonymous">
                        <source src="<?= esc($currentLecture['video_url']) ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php else: ?>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #fff;">
                        <i class="fas fa-video-slash fa-3x mb-3"></i>
                        <p>No video available</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="lecture-info">
                <h4 id="currentLectureTitle"><?= !empty($currentLecture) ? esc($currentLecture['title']) : esc($course['title']) ?></h4>
                <p id="currentLectureDescription" class="text-muted mb-0">
                    <?= !empty($currentLecture) ? esc($currentLecture['description'] ?? '') : esc($course['short_description'] ?? '') ?>
                </p>
            </div>
        </div>
        
        <!-- Course Sidebar -->
        <div class="col-lg-4">
            <div class="course-sidebar">
                <div class="course-sidebar-header">
                    <h5 class="mb-1"><?= esc($course['title']) ?></h5>
                    <small class="text-muted"><?= count($sections) ?> section<?= count($sections) != 1 ? 's' : '' ?> • 
                    <?php 
                    $totalLectures = 0;
                    foreach ($sections as $section) {
                        $totalLectures += count($section['lectures']);
                    }
                    echo $totalLectures;
                    ?> lecture<?= $totalLectures != 1 ? 's' : '' ?></small>
                </div>
                
                <div class="curriculum-content">
                    <?php if (empty($sections)): ?>
                        <div class="text-center py-5">
                            <p class="text-muted">No content available yet.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($sections as $sectionIndex => $section): ?>
                            <div class="section-item">
                                <div class="section-header" onclick="toggleSection(<?= $section['id'] ?>)">
                                    <span><?= esc($section['title']) ?></span>
                                    <span class="text-muted"><?= count($section['lectures']) ?> lecture<?= count($section['lectures']) != 1 ? 's' : '' ?></span>
                                </div>
                                <div class="lecture-list <?= $sectionIndex === 0 ? 'expanded' : '' ?>" id="lectures-<?= $section['id'] ?>">
                                    <?php if (!empty($section['lectures'])): ?>
                                        <?php foreach ($section['lectures'] as $lectureIndex => $lecture): ?>
                                            <?php 
                                            $isActive = !empty($currentLecture) && $currentLecture['id'] == $lecture['id'];
                                            $isCompleted = isset($lectureProgress[$lecture['id']]) && $lectureProgress[$lecture['id']]['is_completed'];
                                            ?>
                                            <div class="lecture-item <?= $isActive ? 'active' : '' ?> <?= $isCompleted ? 'completed' : '' ?>" 
                                                 data-lecture-id="<?= $lecture['id'] ?>"
                                                 data-lecture-title="<?= esc($lecture['title'], 'attr') ?>"
                                                 data-lecture-description="<?= esc($lecture['description'] ?? '', 'attr') ?>"
                                                 data-lecture-video="<?= esc($lecture['video_url'] ?? '', 'attr') ?>"
                                                 data-lecture-type="<?= esc($lecture['content_type'], 'attr') ?>"
                                                 onclick="loadLecture(<?= $lecture['id'] ?>, '<?= esc($lecture['title'], 'js') ?>', '<?= esc($lecture['description'] ?? '', 'js') ?>', '<?= esc($lecture['video_url'] ?? '', 'js') ?>', '<?= esc($lecture['content_type'], 'js') ?>')">
                                                <div class="lecture-title">
                                                    <?php if ($isCompleted): ?>
                                                        <i class="fas fa-check-circle"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-<?= $lecture['content_type'] === 'video' ? 'play-circle' : 'file-alt' ?>"></i>
                                                    <?php endif; ?>
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
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const videoPlayer = document.getElementById('courseVideoPlayer');
    let currentLectureId = <?= !empty($currentLecture) ? $currentLecture['id'] : 'null' ?>;
    
    function toggleSection(sectionId) {
        const lectureList = document.getElementById('lectures-' + sectionId);
        if (lectureList) {
            lectureList.classList.toggle('expanded');
        }
    }
    
    function loadLecture(lectureId, title, description, videoUrl, contentType) {
        // Update active state
        document.querySelectorAll('.lecture-item').forEach(item => {
            item.classList.remove('active');
        });
        const clickedItem = document.querySelector(`[data-lecture-id="${lectureId}"]`);
        if (clickedItem) {
            clickedItem.classList.add('active');
        }
        
        // Update video player
        if (contentType === 'video' && videoUrl && videoPlayer) {
            const currentTime = videoPlayer.currentTime;
            videoPlayer.src = videoUrl;
            videoPlayer.style.display = 'block';
            videoPlayer.load();
            
            videoPlayer.addEventListener('loadedmetadata', function() {
                if (currentLectureId === lectureId && currentTime > 0) {
                    videoPlayer.currentTime = currentTime;
                }
            }, { once: true });
        } else if (videoPlayer) {
            videoPlayer.style.display = 'none';
        }
        
        // Update lecture info
        document.getElementById('currentLectureTitle').textContent = title;
        document.getElementById('currentLectureDescription').textContent = description || '';
        
        currentLectureId = lectureId;
        
        // Update URL without page reload
        const newUrl = '<?= base_url('portal/learn/' . $course['id']) ?>/lecture/' + lectureId;
        window.history.pushState({}, '', newUrl);
        
        // Track progress
        trackLectureProgress(lectureId);
    }
    
    function trackLectureProgress(lectureId) {
        // Mark lecture as started
        fetch('<?= base_url('api/progress/start') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                lecture_id: lectureId,
                course_id: <?= $course['id'] ?>
            })
        }).catch(err => console.log('Progress tracking error:', err));
    }
    
    // Auto-expand first section
    document.addEventListener('DOMContentLoaded', function() {
        const firstSection = document.querySelector('.section-item');
        if (firstSection) {
            const firstLectureList = firstSection.querySelector('.lecture-list');
            if (firstLectureList) {
                firstLectureList.classList.add('expanded');
            }
        }
    });
    
    // Track video completion
    if (videoPlayer) {
        videoPlayer.addEventListener('ended', function() {
            if (currentLectureId) {
                markLectureComplete(currentLectureId);
            }
        });
    }
    
    function markLectureComplete(lectureId) {
        fetch('<?= base_url('api/progress/complete') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                lecture_id: lectureId,
                course_id: <?= $course['id'] ?>
            })
        }).then(() => {
            const lectureItem = document.querySelector(`[data-lecture-id="${lectureId}"]`);
            if (lectureItem) {
                lectureItem.classList.add('completed');
                const icon = lectureItem.querySelector('i');
                if (icon) {
                    icon.className = 'fas fa-check-circle';
                }
            }
        }).catch(err => console.log('Completion tracking error:', err));
    }
</script>

<?= $this->endSection() ?>

