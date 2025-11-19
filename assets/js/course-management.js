/**
 * Course Management JavaScript
 * Handles CRUD operations for courses using AJAX
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize course management
    initCourseManagement();
});

function initCourseManagement() {
    // Load courses on page load
    loadCourses();

    // Setup form handlers
    setupCourseForm();
}

/**
 * Load all courses
 */
async function loadCourses() {
    try {
        const response = await api.getCourses({
            page: 1,
            limit: 10
        });

        if (response && response.status === 'success') {
            displayCourses(response.data);
            displayPagination(response.pagination);
        }
    } catch (error) {
        handleApiError(error);
    }
}

/**
 * Display courses in table
 */
function displayCourses(courses) {
    const tbody = document.querySelector('#coursesTable tbody');
    if (!tbody) return;

    tbody.innerHTML = '';

    courses.forEach(course => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="checkbox" class="row-checkbox" value="${course.course_id}"></td>
            <td>${course.title}</td>
            <td>${course.category_name || 'N/A'}</td>
            <td>${course.first_name} ${course.last_name}</td>
            <td>$${course.price}</td>
            <td><span class="badge bg-${getStatusColor(course.status)}">${course.status}</span></td>
            <td>
                <a href="${baseURL}/course-details?id=${course.course_id}" class="btn btn-sm btn-info me-2">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <button onclick="editCourse('${course.course_id}')" class="btn btn-sm btn-primary me-2">
                    <i class="fa-regular fa-pen-to-square"></i>
                </button>
                <button onclick="deleteCourse('${course.course_id}')" class="btn btn-sm btn-danger">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function getStatusColor(status) {
    const colors = {
        'published': 'success',
        'draft': 'secondary',
        'pending': 'warning',
        'rejected': 'danger'
    };
    return colors[status] || 'secondary';
}

/**
 * Create new course
 */
async function createCourse() {
    const form = document.getElementById('courseForm');
    if (!form) return;

    const formData = new FormData(form);
    const data = {
        title: formData.get('title'),
        subtitle: formData.get('subtitle'),
        description: formData.get('description'),
        instructor_id: formData.get('instructor_id') || api.username, // Use current user if instructor
        category_id: formData.get('category_id'),
        price: parseFloat(formData.get('price')) || 0,
        level: formData.get('level'),
        status: formData.get('status'),
        is_free: formData.get('is_free') === 'on',
    };

    try {
        const response = await api.createCourse(data);
        if (response && response.status === 'success') {
            showNotification('Course created successfully');
            bootstrap.Modal.getInstance(document.getElementById('courseCreateModal')).hide();
            form.reset();
            loadCourses();
        }
    } catch (error) {
        handleApiError(error);
    }
}

/**
 * Edit course
 */
async function editCourse(courseId) {
    try {
        const response = await api.getCourse(courseId);
        if (response && response.status === 'success') {
            const course = response.data;
            populateCourseForm(course);
            const modal = new bootstrap.Modal(document.getElementById('courseEditModal'));
            modal.show();
        }
    } catch (error) {
        handleApiError(error);
    }
}

/**
 * Update course
 */
async function updateCourse(courseId) {
    const form = document.getElementById('courseEditForm');
    if (!form) return;

    const formData = new FormData(form);
    const data = {
        title: formData.get('title'),
        subtitle: formData.get('subtitle'),
        description: formData.get('description'),
        category_id: formData.get('category_id'),
        price: parseFloat(formData.get('price')) || 0,
        level: formData.get('level'),
        status: formData.get('status'),
        is_free: formData.get('is_free') === 'on',
    };

    try {
        const response = await api.updateCourse(courseId, data);
        if (response && response.status === 'success') {
            showNotification('Course updated successfully');
            bootstrap.Modal.getInstance(document.getElementById('courseEditModal')).hide();
            loadCourses();
        }
    } catch (error) {
        handleApiError(error);
    }
}

/**
 * Delete course
 */
async function deleteCourse(courseId) {
    if (!confirm('Are you sure you want to delete this course?')) {
        return;
    }

    try {
        const response = await api.deleteCourse(courseId);
        if (response && response.status === 'success') {
            showNotification('Course deleted successfully');
            loadCourses();
        }
    } catch (error) {
        handleApiError(error);
    }
}

/**
 * Populate course form for editing
 */
function populateCourseForm(course) {
    const form = document.getElementById('courseEditForm');
    if (!form) return;

    form.querySelector('[name="title"]').value = course.title || '';
    form.querySelector('[name="subtitle"]').value = course.subtitle || '';
    form.querySelector('[name="description"]').value = course.description || '';
    form.querySelector('[name="category_id"]').value = course.category_id || '';
    form.querySelector('[name="price"]').value = course.price || '';
    form.querySelector('[name="level"]').value = course.level || 'beginner';
    form.querySelector('[name="status"]').value = course.status || 'draft';
    form.querySelector('[name="is_free"]').checked = course.is_free || false;
}

/**
 * Setup course form handlers
 */
function setupCourseForm() {
    const createForm = document.getElementById('courseForm');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            e.preventDefault();
            createCourse();
        });
    }

    const editForm = document.getElementById('courseEditForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const courseId = editForm.dataset.courseId;
            if (courseId) {
                updateCourse(courseId);
            }
        });
    }
}

// Make functions globally available
window.editCourse = editCourse;
window.deleteCourse = deleteCourse;
window.createCourse = createCourse;
window.updateCourse = updateCourse;

