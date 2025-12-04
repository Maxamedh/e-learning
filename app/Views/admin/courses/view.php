<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<style>
/* Override main padding for full-width layout */
main.p-4 {
    padding: 0 !important;
}

.course-view-container {
    background: #f7f9fa;
    min-height: calc(100vh - 120px);
    padding: 0;
    margin: 0;
    margin-top: 70px !important; /* Push content below header */
}

.video-player-container {
    position: sticky;
    top: 70px; /* Stay below header when scrolling */
    background: #000;
    padding: 0;
    height: calc(100vh - 190px); /* Account for header (70px) + spacing */
    overflow-y: auto;
    z-index: 1;
}

.video-wrapper {
    position: relative;
    width: 100%;
    padding-top: 56.25%;
    /* 16:9 Aspect Ratio */
    background: #000;
}

.video-wrapper video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.video-info {
    padding: 20px;
    background: #fff;
    border-bottom: 1px solid #e0e0e0;
}

.video-info h4 {
    margin: 0 0 10px 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #1c1d1f;
}

.video-info p {
    margin: 0;
    color: #6a6f73;
    font-size: 0.9rem;
}

.curriculum-sidebar {
    background: #fff;
    height: calc(100vh - 190px); /* Account for header */
    overflow-y: auto;
    padding: 0;
    position: sticky;
    top: 70px; /* Stay below header when scrolling */
    z-index: 1;
}

.curriculum-header {
    padding: 20px;
    background: #fff;
    border-bottom: 1px solid #e0e0e0;
    position: sticky;
    top: 0;
    z-index: 10;
}

.curriculum-header h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1c1d1f;
}

.curriculum-header .course-meta {
    margin-top: 10px;
    font-size: 0.875rem;
    color: #6a6f73;
}

.section-item {
    border-bottom: 1px solid #e0e0e0;
}

.section-header {
    padding: 16px 20px;
    background: #f7f9fa;
    cursor: pointer;
    user-select: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.2s;
}

.section-header:hover {
    background: #f0f0f0;
}

.section-header.active {
    background: #e0e0e0;
}

.section-title {
    font-weight: 600;
    color: #1c1d1f;
    font-size: 0.95rem;
}

.section-info {
    font-size: 0.875rem;
    color: #6a6f73;
    margin-left: 10px;
}

.section-toggle {
    color: #6a6f73;
    transition: transform 0.2s;
}

.section-toggle.expanded {
    transform: rotate(90deg);
}

.lecture-list {
    display: none;
    background: #fff;
}

.lecture-list.expanded {
    display: block;
}

.lecture-item {
    padding: 12px 20px 12px 40px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.lecture-item:hover {
    background: #f7f9fa;
}

.lecture-item.active {
    background: #e8f4f8;
    border-left: 3px solid #0d6efd;
}

.lecture-title {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.lecture-icon {
    color: #6a6f73;
    font-size: 0.875rem;
}

.lecture-name {
    color: #1c1d1f;
    font-size: 0.9rem;
}

.lecture-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.8rem;
    color: #6a6f73;
}

.lecture-badge {
    font-size: 0.75rem;
    padding: 2px 6px;
    border-radius: 3px;
}

.badge-preview {
    background: #0d6efd;
    color: #fff;
}

.badge-draft {
    background: #ffc107;
    color: #000;
}

.no-video-message {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: #fff;
}

.course-info-panel {
    padding: 20px;
    background: #fff;
    border-top: 1px solid #e0e0e0;
}

.course-description {
    color: #1c1d1f;
    line-height: 1.6;
    margin-bottom: 20px;
}

.course-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-top: 20px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.detail-item i {
    color: #6a6f73;
    width: 20px;
}

.detail-item span {
    color: #1c1d1f;
    font-size: 0.9rem;
}
</style>

<div class="course-view-container my-2">
    <div class="row g-0">
        <!-- Left Side: Video Player -->
        
        <div class="col-lg-8 video-player-container my-5">
            <div class="video-wrapper">
                <?php if (!empty($defaultVideo)): ?>
                <!-- Debug: Video URL (only in development) -->
                <?php if (ENVIRONMENT === 'development'): ?>
                <div
                    style="position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.7); color: #fff; padding: 5px 10px; font-size: 0.8rem; z-index: 1000; border-radius: 3px;">
                    <!-- Video URL: <?= esc($defaultVideo) ?> -->
                </div>
                <?php endif; ?>
                <video id="courseVideoPlayer" controls controlsList="nodownload" preload="metadata"
                    crossorigin="anonymous" style="width: 100%; height: 100%;">
                    <source src="<?= esc($defaultVideo) ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <?php else: ?>
                <div class="no-video-message">
                    <i class="fa-solid fa-video-slash fa-3x mb-3"></i>
                    <p>No video available for this course</p>
                </div>
                <?php endif; ?>
            </div>

            <div class="video-info">
                <h4 id="currentLectureTitle">
                    <?= !empty($defaultLecture) ? esc($defaultLecture['title']) : esc($course['title']) ?></h4>
                <p id="currentLectureDescription">
                    <?= !empty($defaultLecture) ? esc($defaultLecture['description'] ?? '') : esc($course['short_description'] ?? '') ?>
                </p>
            </div>

            <div class="course-info-panel">
                <h5 class="mb-3">About this course</h5>
                <div class="course-description">
                    <?= nl2br(esc($course['description'])) ?>
                </div>

                <div class="course-details">
                    <div class="detail-item">
                        <i class="fa-solid fa-user"></i>
                        <span><strong>Instructor:</strong>
                            <?= esc(($instructor['first_name'] ?? '') . ' ' . ($instructor['last_name'] ?? 'N/A')) ?></span>
                    </div>
                    <div class="detail-item">
                        <i class="fa-solid fa-tag"></i>
                        <span><strong>Category:</strong> <?= esc($category['name'] ?? 'N/A') ?></span>
                    </div>
                    <div class="detail-item">
                        <i class="fa-solid fa-signal"></i>
                        <span><strong>Level:</strong> <?= ucfirst(esc($course['level'])) ?></span>
                    </div>
                    <div class="detail-item">
                        <i class="fa-solid fa-language"></i>
                        <span><strong>Language:</strong> <?= esc($course['language']) ?></span>
                    </div>
                    <div class="detail-item">
                        <i class="fa-solid fa-clock"></i>
                        <span><strong>Duration:</strong>
                            <?= !empty($course['duration_hours']) ? esc($course['duration_hours']) . ' hours' : 'N/A' ?></span>
                    </div>
                    <div class="detail-item">
                        <i class="fa-solid fa-video"></i>
                        <span><strong>Lectures:</strong> <?= count($allLectures) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Curriculum -->
        <div class="col-lg-4 curriculum-sidebar my-5">
            <div class="curriculum-header">
                <h3>Course Content</h3>
                <div class="course-meta">
                    <?= count($sections) ?> section<?= count($sections) != 1 ? 's' : '' ?> •
                    <?= count($allLectures) ?> lecture<?= count($allLectures) != 1 ? 's' : '' ?>
                </div>
            </div>

            <div class="curriculum-content">
                <?php if (empty($sections)): ?>
                <div class="text-center py-5">
                    <i class="fa-solid fa-book-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No sections added to this course yet.</p>
                </div>
                <?php else: ?>
                <?php foreach ($sections as $sectionIndex => $section): ?>
                <div class="section-item">
                    <div class="section-header <?= $sectionIndex === 0 ? 'active' : '' ?>"
                        onclick="toggleSection(<?= $section['id'] ?>)">
                        <div>
                            <span class="section-title"><?= esc($section['title']) ?></span>
                            <span class="section-info">
                                (<?= count($section['lectures']) ?>
                                lecture<?= count($section['lectures']) != 1 ? 's' : '' ?>)
                            </span>
                        </div>
                        <i class="fa-solid fa-chevron-right section-toggle <?= $sectionIndex === 0 ? 'expanded' : '' ?>"
                            id="toggle-<?= $section['id'] ?>"></i>
                    </div>
                    <div class="lecture-list <?= $sectionIndex === 0 ? 'expanded' : '' ?>"
                        id="lectures-<?= $section['id'] ?>">
                        <?php if (empty($section['lectures'])): ?>
                        <div class="lecture-item">
                            <span class="text-muted" style="padding-left: 0;">No lectures in this section</span>
                        </div>
                        <?php else: ?>
                        <?php foreach ($section['lectures'] as $lectureIndex => $lecture): ?>
                        <div class="lecture-item <?= ($sectionIndex === 0 && $lectureIndex === 0 && !empty($defaultLecture) && $defaultLecture['id'] == $lecture['id']) ? 'active' : '' ?>"
                            data-lecture-id="<?= $lecture['id'] ?>"
                            data-lecture-title="<?= esc($lecture['title'], 'attr') ?>"
                            data-lecture-description="<?= esc($lecture['description'] ?? '', 'attr') ?>"
                            data-lecture-video="<?= esc($lecture['video_url'] ?? '', 'attr') ?>"
                            data-lecture-type="<?= esc($lecture['content_type'], 'attr') ?>"
                            data-lecture-article="<?= esc($lecture['article_content'] ?? '', 'attr') ?>">
                            <div class="lecture-title">
                                <i
                                    class="fa-solid fa-<?= $lecture['content_type'] === 'video' ? 'play-circle' : ($lecture['content_type'] === 'article' ? 'file-alt' : 'question-circle') ?> lecture-icon"></i>
                                <span class="lecture-name"><?= esc($lecture['title']) ?></span>
                            </div>
                            <div class="lecture-meta">
                                <?php if ($lecture['is_preview']): ?>
                                <span class="badge badge-preview">Preview</span>
                                <?php endif; ?>
                                <?php if (!$lecture['is_published']): ?>
                                <span class="badge badge-draft">Draft</span>
                                <?php endif; ?>
                                <?php if (!empty($lecture['video_duration'])): ?>
                                <span><?= esc($lecture['video_duration']) ?></span>
                                <?php endif; ?>
                            </div>
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

<script>
const videoPlayer = document.getElementById('courseVideoPlayer');
let currentLectureId = <?= !empty($defaultLecture) ? $defaultLecture['id'] : 'null' ?>;

// Video error handling
if (videoPlayer) {
    videoPlayer.addEventListener('error', function(e) {
        console.error('Video error:', e);
        console.error('Video source:', videoPlayer.src);
        console.error('Video error code:', videoPlayer.error ? videoPlayer.error.code : 'unknown');
        console.error('Video error message:', videoPlayer.error ? videoPlayer.error.message : 'unknown');

        // Get more detailed error info
        let errorMessage = 'Error loading video. ';
        if (videoPlayer.error) {
            switch (videoPlayer.error.code) {
                case 1: // MEDIA_ERR_ABORTED
                    errorMessage += 'Video loading was aborted.';
                    break;
                case 2: // MEDIA_ERR_NETWORK
                    errorMessage += 'Network error. Please check your connection.';
                    break;
                case 3: // MEDIA_ERR_DECODE
                    errorMessage += 'Video decoding error. The file may be corrupted.';
                    break;
                case 4: // MEDIA_ERR_SRC_NOT_SUPPORTED
                    errorMessage += 'Video format not supported or file not found.';
                    break;
                default:
                    errorMessage += 'Unknown error. Please check the video URL: ' + videoPlayer.src;
            }
        } else {
            errorMessage += 'Please check the video URL: ' + videoPlayer.src;
        }

        const errorMsg = document.querySelector('.no-video-message') || document.createElement('div');
        errorMsg.className = 'no-video-message';
        errorMsg.innerHTML = '<i class="fa-solid fa-exclamation-triangle fa-3x mb-3"></i><p>' + errorMessage +
            '</p><small style="color: #ccc; font-size: 0.8rem;">Check browser console for details</small>';
        if (!document.querySelector('.no-video-message')) {
            videoPlayer.parentElement.appendChild(errorMsg);
        }
        videoPlayer.style.display = 'none';
    });

    videoPlayer.addEventListener('loadeddata', function() {
        console.log('Video loaded successfully:', videoPlayer.src);
        // Remove any error messages
        const errorMsg = document.querySelector('.no-video-message');
        if (errorMsg) {
            errorMsg.remove();
        }
    });

    // Log initial video source
    console.log('Initial video source:', videoPlayer.src);
}

function toggleSection(sectionId) {
    const lectureList = document.getElementById('lectures-' + sectionId);
    const toggle = document.getElementById('toggle-' + sectionId);
    const header = toggle.closest('.section-header');

    if (lectureList.classList.contains('expanded')) {
        lectureList.classList.remove('expanded');
        toggle.classList.remove('expanded');
        header.classList.remove('active');
    } else {
        // Close all other sections
        document.querySelectorAll('.lecture-list.expanded').forEach(list => {
            if (list.id !== 'lectures-' + sectionId) {
                list.classList.remove('expanded');
                const otherToggle = document.getElementById('toggle-' + list.id.replace('lectures-', ''));
                if (otherToggle) {
                    otherToggle.classList.remove('expanded');
                    otherToggle.closest('.section-header').classList.remove('active');
                }
            }
        });

        lectureList.classList.add('expanded');
        toggle.classList.add('expanded');
        header.classList.add('active');
    }
}

function loadLecture(lectureId, title, description, videoUrl, contentType, articleContent) {
    // Update active state
    document.querySelectorAll('.lecture-item').forEach(item => {
        item.classList.remove('active');
    });
    // Find and activate the clicked lecture item
    const clickedItem = document.querySelector(`[data-lecture-id="${lectureId}"]`);
    if (clickedItem) {
        clickedItem.classList.add('active');
    }

    // Update video player
    if (contentType === 'video' && videoUrl) {
        if (videoPlayer) {
            const currentTime = videoPlayer.currentTime;
            const wasPlaying = !videoPlayer.paused;

            // Ensure video URL is absolute
            let absoluteVideoUrl = videoUrl;
            if (videoUrl) {
                // If it's already a full URL, use it
                if (videoUrl.startsWith('http://') || videoUrl.startsWith('https://')) {
                    absoluteVideoUrl = videoUrl;
                }
                // If it starts with /, it's already absolute
                else if (videoUrl.startsWith('/')) {
                    absoluteVideoUrl = videoUrl;
                }
                // Otherwise, make it absolute
                else {
                    // Check if it contains base_url
                    const baseUrl = '<?= base_url() ?>';
                    if (videoUrl.includes(baseUrl)) {
                        absoluteVideoUrl = videoUrl;
                    } else {
                        // Make it absolute path
                        absoluteVideoUrl = videoUrl.startsWith('uploads/') ? '/' + videoUrl : '/' + videoUrl;
                    }
                }
            }

            console.log('Loading video:', absoluteVideoUrl);

            // Update video source
            videoPlayer.src = absoluteVideoUrl;
            videoPlayer.style.display = 'block';

            // Remove any existing error messages
            const existingError = videoPlayer.parentElement.querySelector('.no-video-message');
            if (existingError) {
                existingError.remove();
            }

            // Load the new video
            videoPlayer.load();

            // Try to restore playback position if it was the same video
            const loadHandler = function() {
                if (currentLectureId === lectureId && currentTime > 0) {
                    videoPlayer.currentTime = currentTime;
                }
                videoPlayer.removeEventListener('loadedmetadata', loadHandler);
            };
            videoPlayer.addEventListener('loadedmetadata', loadHandler);
        }
    } else if (contentType === 'article' && articleContent) {
        // For articles, show a message (you can enhance this to show article content)
        if (videoPlayer) {
            videoPlayer.style.display = 'none';
        }
        // You could create a div to show article content here
        console.log('Article content:', articleContent);
    } else {
        if (videoPlayer) {
            videoPlayer.style.display = 'none';
        }
    }

    // Update lecture info
    document.getElementById('currentLectureTitle').textContent = title;
    document.getElementById('currentLectureDescription').textContent = description || '';

    currentLectureId = lectureId;

    // Scroll video player to top
    document.querySelector('.video-player-container').scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Add event listeners to lecture items
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.lecture-item').forEach(item => {
        item.addEventListener('click', function() {
            const lectureId = this.getAttribute('data-lecture-id');
            const title = this.getAttribute('data-lecture-title');
            const description = this.getAttribute('data-lecture-description');
            const videoUrl = this.getAttribute('data-lecture-video');
            const contentType = this.getAttribute('data-lecture-type');
            const articleContent = this.getAttribute('data-lecture-article');

            loadLecture(lectureId, title, description, videoUrl, contentType, articleContent);
        });
    });

    // Initialize: Auto-load first lecture if no default lecture is set
    <?php if (empty($defaultLecture) && !empty($sections) && !empty($sections[0]['lectures']) && !empty($sections[0]['lectures'][0])): ?>
    const firstLec = <?= json_encode($sections[0]['lectures'][0]) ?>;
    if (firstLec.content_type === 'video' && firstLec.video_url) {
        loadLecture(
            firstLec.id,
            firstLec.title,
            firstLec.description || '',
            firstLec.video_url,
            firstLec.content_type,
            firstLec.article_content || ''
        );
    }
    <?php endif; ?>
});
</script>

<?= $this->endSection() ?>