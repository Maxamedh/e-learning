/**
 * AJAX Helper with Token and Username Headers
 */

// Store auth token and username
let authToken = localStorage.getItem('auth_token') || '';
let username = localStorage.getItem('username') || '';

// Update token and username
function setAuthCredentials(token, userEmail) {
    authToken = token;
    username = userEmail;
    localStorage.setItem('auth_token', token);
    localStorage.setItem('username', userEmail);
}

// Clear auth credentials
function clearAuthCredentials() {
    authToken = '';
    username = '';
    localStorage.removeItem('auth_token');
    localStorage.removeItem('username');
}

// Get CSRF token from meta tag
function getCSRFToken() {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    return metaTag ? metaTag.getAttribute('content') : '';
}

// Get base URL
function getBaseURL() {
    const baseTag = document.querySelector('base');
    return baseTag ? baseTag.getAttribute('href') : '';
}

/**
 * Make AJAX request with token and username headers
 * 
 * @param {string} url - API endpoint
 * @param {string} method - HTTP method (GET, POST, PUT, DELETE)
 * @param {object} data - Request data
 * @param {object} options - Additional options
 * @returns {Promise}
 */
function ajaxRequest(url, method = 'GET', data = null, options = {}) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        const baseURL = getBaseURL();
        const fullURL = url.startsWith('http') ? url : baseURL + url;
        
        xhr.open(method, fullURL, true);
        
        // Set headers
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-Auth-Token', authToken);
        xhr.setRequestHeader('X-Username', username);
        xhr.setRequestHeader('X-CSRF-TOKEN', getCSRFToken());
        
        // Set additional headers if provided
        if (options.headers) {
            Object.keys(options.headers).forEach(key => {
                xhr.setRequestHeader(key, options.headers[key]);
            });
        }
        
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    
                    // Update CSRF token if provided
                    if (response.csrf_token) {
                        const metaTag = document.querySelector('meta[name="csrf-token"]');
                        if (metaTag) {
                            metaTag.setAttribute('content', response.csrf_token);
                        }
                    }
                    
                    // Handle authentication errors
                    if (response.success === false && (xhr.status === 401 || response.redirect)) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                        clearAuthCredentials();
                    }
                    
                    resolve(response);
                } catch (e) {
                    resolve({ success: false, message: 'Invalid JSON response', data: xhr.responseText });
                }
            } else {
                try {
                    const error = JSON.parse(xhr.responseText);
                    reject(error);
                } catch (e) {
                    reject({ success: false, message: 'Request failed', status: xhr.status });
                }
            }
        };
        
        xhr.onerror = function() {
            reject({ success: false, message: 'Network error' });
        };
        
        // Send request
        if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
            // For form data
            if (data instanceof FormData) {
                // Remove Content-Type header for FormData (browser sets it automatically)
                xhr.setRequestHeader('X-Auth-Token', authToken);
                xhr.setRequestHeader('X-Username', username);
                xhr.setRequestHeader('X-CSRF-TOKEN', getCSRFToken());
                // Add CSRF token to form data
                data.append('csrf_token', getCSRFToken());
                xhr.send(data);
            } else if (typeof data === 'object') {
                // For JSON data, add CSRF token
                data.csrf_token = getCSRFToken();
                xhr.send(JSON.stringify(data));
            } else {
                xhr.send(data);
            }
        } else {
            xhr.send();
        }
    });
}

// Convenience methods
const ajax = {
    get: (url, options = {}) => ajaxRequest(url, 'GET', null, options),
    post: (url, data, options = {}) => ajaxRequest(url, 'POST', data, options),
    put: (url, data, options = {}) => ajaxRequest(url, 'PUT', data, options),
    delete: (url, options = {}) => ajaxRequest(url, 'DELETE', null, options),
    patch: (url, data, options = {}) => ajaxRequest(url, 'PATCH', data, options),
};

// Form submission helper
function submitForm(formId, url, method = 'POST', onSuccess = null, onError = null) {
    const form = document.getElementById(formId);
    if (!form) {
        console.error('Form not found:', formId);
        return;
    }
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        
        try {
            const response = await ajaxRequest(url, method, data);
            if (response.success) {
                if (onSuccess) {
                    onSuccess(response);
                } else {
                    showNotification(response.message || 'Operation successful', 'success');
                    if (response.redirect) {
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 1000);
                    }
                }
            } else {
                if (onError) {
                    onError(response);
                } else {
                    showNotification(response.message || 'Operation failed', 'error');
                }
            }
        } catch (error) {
            if (onError) {
                onError(error);
            } else {
                showNotification(error.message || 'An error occurred', 'error');
            }
        }
    });
}

// Notification helper
function showNotification(message, type = 'info') {
    // You can integrate with your notification system
    alert(message); // Simple alert for now
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { ajax, ajaxRequest, setAuthCredentials, clearAuthCredentials, submitForm };
}

