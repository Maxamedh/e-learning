/**
 * User Management JavaScript
 * Handles CRUD operations for users using AJAX
 */

document.addEventListener('DOMContentLoaded', function() {
    initUserManagement();
});

function initUserManagement() {
    loadUsers();
    setupUserForm();
}

async function loadUsers() {
    try {
        const response = await api.getUsers({ page: 1, limit: 10 });
        if (response && response.status === 'success') {
            displayUsers(response.data);
            displayPagination(response.pagination);
        }
    } catch (error) {
        handleApiError(error);
    }
}

function displayUsers(users) {
    const tbody = document.querySelector('#usersTable tbody');
    if (!tbody) return;
    tbody.innerHTML = '';

    users.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="checkbox" class="row-checkbox" value="${user.user_id}"></td>
            <td>
                <div class="d-flex justify-content-start align-items-center">
                    <img src="${user.profile_picture_url || baseURL + '/assets/images/profile.png'}" class="tbl-img" alt="">
                    <span class="ms-2">${user.first_name} ${user.last_name}</span>
                </div>
            </td>
            <td>${user.email}</td>
            <td>${user.phone_number || 'N/A'}</td>
            <td><span class="badge bg-${getUserTypeColor(user.user_type)}">${user.user_type}</span></td>
            <td><span class="badge bg-${user.is_active ? 'success' : 'danger'}">${user.is_active ? 'Active' : 'Inactive'}</span></td>
            <td class="text-center">
                <button onclick="editUser('${user.user_id}')" class="btn btn-sm btn-primary me-2">
                    <i class="fa-regular fa-pen-to-square"></i>
                </button>
                <button onclick="deleteUser('${user.user_id}')" class="btn btn-sm btn-danger">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function getUserTypeColor(type) {
    const colors = { 'admin': 'danger', 'instructor': 'primary', 'student': 'success' };
    return colors[type] || 'secondary';
}

async function createUser() {
    const form = document.getElementById('userForm');
    if (!form) return;

    const formData = new FormData(form);
    const data = {
        email: formData.get('email'),
        password: formData.get('password'),
        first_name: formData.get('first_name'),
        last_name: formData.get('last_name'),
        user_type: formData.get('user_type'),
        phone_number: formData.get('phone_number'),
        is_active: formData.get('is_active') === 'on',
    };

    try {
        const response = await api.createUser(data);
        if (response && response.status === 'success') {
            showNotification('User created successfully');
            bootstrap.Modal.getInstance(document.getElementById('userCreateModal')).hide();
            form.reset();
            loadUsers();
        }
    } catch (error) {
        handleApiError(error);
    }
}

async function editUser(userId) {
    try {
        const response = await api.getUser(userId);
        if (response && response.status === 'success') {
            populateUserForm(response.data);
            const modal = new bootstrap.Modal(document.getElementById('userEditModal'));
            modal.show();
        }
    } catch (error) {
        handleApiError(error);
    }
}

async function updateUser(userId) {
    const form = document.getElementById('userEditForm');
    if (!form) return;

    const formData = new FormData(form);
    const data = {
        first_name: formData.get('first_name'),
        last_name: formData.get('last_name'),
        user_type: formData.get('user_type'),
        phone_number: formData.get('phone_number'),
        is_active: formData.get('is_active') === 'on',
    };

    if (formData.get('password')) {
        data.password = formData.get('password');
    }

    try {
        const response = await api.updateUser(userId, data);
        if (response && response.status === 'success') {
            showNotification('User updated successfully');
            bootstrap.Modal.getInstance(document.getElementById('userEditModal')).hide();
            loadUsers();
        }
    } catch (error) {
        handleApiError(error);
    }
}

async function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user?')) return;

    try {
        const response = await api.deleteUser(userId);
        if (response && response.status === 'success') {
            showNotification('User deleted successfully');
            loadUsers();
        }
    } catch (error) {
        handleApiError(error);
    }
}

function populateUserForm(user) {
    const form = document.getElementById('userEditForm');
    if (!form) return;
    form.dataset.userId = user.user_id;
    form.querySelector('[name="first_name"]').value = user.first_name || '';
    form.querySelector('[name="last_name"]').value = user.last_name || '';
    form.querySelector('[name="email"]').value = user.email || '';
    form.querySelector('[name="user_type"]').value = user.user_type || '';
    form.querySelector('[name="phone_number"]').value = user.phone_number || '';
    form.querySelector('[name="is_active"]').checked = user.is_active || false;
}

function setupUserForm() {
    const createForm = document.getElementById('userForm');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            e.preventDefault();
            createUser();
        });
    }

    const editForm = document.getElementById('userEditForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const userId = editForm.dataset.userId;
            if (userId) updateUser(userId);
        });
    }
}

window.editUser = editUser;
window.deleteUser = deleteUser;
window.createUser = createUser;
window.updateUser = updateUser;

