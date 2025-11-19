/**
 * Authentication JavaScript
 * Handles login, register, and session management
 */

document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in
    if (api.token && api.username) {
        api.verifyToken().then(response => {
            if (response && response.status === 'success') {
                // User is logged in, redirect to dashboard if on login page
                if (window.location.pathname === '/login' || window.location.pathname === '/signup') {
                    window.location.href = '/';
                }
            } else {
                api.clearAuth();
            }
        }).catch(() => {
            api.clearAuth();
        });
    }

    setupAuthForms();
});

function setupAuthForms() {
    // Login form
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await api.login(email, password);
                if (response && response.status === 'success') {
                    showNotification('Login successful!', 'success');
                    window.location.href = '/';
                }
            } catch (error) {
                handleApiError(error);
            }
        });
    }

    // Register form
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(registerForm);
            const data = {
                email: formData.get('email'),
                password: formData.get('password'),
                first_name: formData.get('first_name'),
                last_name: formData.get('last_name'),
                user_type: formData.get('user_type') || 'student',
            };

            try {
                const response = await api.register(data);
                if (response && response.status === 'success') {
                    showNotification('Registration successful! Please login.', 'success');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                }
            } catch (error) {
                handleApiError(error);
            }
        });
    }
}

async function logout() {
    try {
        await api.logout();
        showNotification('Logged out successfully', 'success');
        window.location.href = '/login';
    } catch (error) {
        handleApiError(error);
    }
}

window.logout = logout;

