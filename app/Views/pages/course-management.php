<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                <div class="flex-grow-1">
                    <h3 class="mb-2 text-color-2">Course Management</h3>
                </div>
                <div class="mt-3 mt-lg-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#courseModal" onclick="openCourseModal()">
                        <i class="fa-solid fa-plus me-2"></i>Add Course
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div id="messageDiv" class="alert d-none mt-3" role="alert"></div>

    <!-- Courses Table -->
    <div class="mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle" id="coursesTable">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Instructor</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="coursesTableBody">
                            <tr>
                                <td colspan="6" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Course Modal -->
<div class="modal fade" id="courseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="courseModalTitle">Add Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="courseForm">
                <div class="modal-body">
                    <input type="hidden" id="course_id" name="course_id">
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle">
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="level" class="form-label">Level</label>
                            <select class="form-select" id="level" name="level">
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                                <option value="all">All Levels</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" value="0.00">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_free" name="is_free">
                            <label class="form-check-label" for="is_free">Free Course</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveCourseBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        Save Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentCourseId = null;

// Load courses on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCourses();
    loadCategories();
});

// Load all courses
async function loadCourses() {
    try {
        const response = await CourseCRUD.list();
        const tbody = document.getElementById('coursesTableBody');
        
        if (response.success && response.data.courses) {
            if (response.data.courses.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center">No courses found</td></tr>';
                return;
            }
            
            tbody.innerHTML = response.data.courses.map(course => `
                <tr>
                    <td>${course.title}</td>
                    <td>${course.first_name} ${course.last_name}</td>
                    <td>${course.category_name || 'N/A'}</td>
                    <td>$${parseFloat(course.price).toFixed(2)}</td>
                    <td><span class="badge bg-${course.status === 'published' ? 'success' : 'warning'}">${course.status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-info me-2" onclick="viewCourse('${course.course_id}')">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-primary me-2" onclick="editCourse('${course.course_id}')">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteCourse('${course.course_id}')">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error loading courses</td></tr>';
        }
    } catch (error) {
        showMessage('Error loading courses: ' + error.message, 'danger');
    }
}

// Load categories for dropdown
async function loadCategories() {
    try {
        const response = await CategoryCRUD.list();
        const select = document.getElementById('category_id');
        
        if (response.success && response.data.categories) {
            select.innerHTML = '<option value="">Select Category</option>' + 
                response.data.categories.map(cat => 
                    `<option value="${cat.category_id}">${cat.name}</option>`
                ).join('');
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

// Open modal for new course
function openCourseModal() {
    currentCourseId = null;
    document.getElementById('courseModalTitle').textContent = 'Add Course';
    document.getElementById('courseForm').reset();
    document.getElementById('course_id').value = '';
}

// Edit course
async function editCourse(courseId) {
    try {
        const response = await CourseCRUD.get(courseId);
        
        if (response.success && response.data.course) {
            const course = response.data.course;
            currentCourseId = courseId;
            
            document.getElementById('courseModalTitle').textContent = 'Edit Course';
            document.getElementById('course_id').value = course.course_id;
            document.getElementById('title').value = course.title || '';
            document.getElementById('subtitle').value = course.subtitle || '';
            document.getElementById('description').value = course.description || '';
            document.getElementById('category_id').value = course.category_id || '';
            document.getElementById('level').value = course.level || 'beginner';
            document.getElementById('price').value = course.price || '0.00';
            document.getElementById('status').value = course.status || 'draft';
            document.getElementById('is_free').checked = course.is_free || false;
            
            new bootstrap.Modal(document.getElementById('courseModal')).show();
        } else {
            showMessage('Course not found', 'danger');
        }
    } catch (error) {
        showMessage('Error loading course: ' + error.message, 'danger');
    }
}

// View course
function viewCourse(courseId) {
    window.location.href = `<?= base_url('course-details') ?>?id=${courseId}`;
}

// Delete course
async function deleteCourse(courseId) {
    if (!confirm('Are you sure you want to delete this course?')) {
        return;
    }
    
    try {
        const response = await CourseCRUD.delete(courseId);
        
        if (response.success) {
            showMessage('Course deleted successfully', 'success');
            loadCourses();
        } else {
            showMessage(response.message || 'Failed to delete course', 'danger');
        }
    } catch (error) {
        showMessage('Error deleting course: ' + error.message, 'danger');
    }
}

// Save course (create or update)
document.getElementById('courseForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {};
    formData.forEach((value, key) => {
        if (key === 'is_free') {
            data[key] = document.getElementById('is_free').checked;
        } else {
            data[key] = value;
        }
    });
    
    const saveBtn = document.getElementById('saveCourseBtn');
    const spinner = saveBtn.querySelector('.spinner-border');
    
    saveBtn.disabled = true;
    spinner.classList.remove('d-none');
    
    try {
        let response;
        if (currentCourseId) {
            response = await CourseCRUD.update(currentCourseId, data);
        } else {
            response = await CourseCRUD.create(data);
        }
        
        if (response.success) {
            showMessage(response.message || 'Course saved successfully', 'success');
            bootstrap.Modal.getInstance(document.getElementById('courseModal')).hide();
            loadCourses();
        } else {
            showMessage(response.message || 'Failed to save course', 'danger');
        }
    } catch (error) {
        showMessage('Error saving course: ' + error.message, 'danger');
    } finally {
        saveBtn.disabled = false;
        spinner.classList.add('d-none');
    }
});

// Show message
function showMessage(message, type) {
    const messageDiv = document.getElementById('messageDiv');
    messageDiv.className = `alert alert-${type}`;
    messageDiv.textContent = message;
    messageDiv.classList.remove('d-none');
    
    setTimeout(() => {
        messageDiv.classList.add('d-none');
    }, 5000);
}
</script>

<?= $this->endSection() ?>

