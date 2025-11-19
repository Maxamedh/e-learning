/**
 * E-Learning API Helper
 * Handles all AJAX requests with token authentication
 */

class ELearningAPI {
    constructor() {
        this.baseURL = window.location.origin;
        this.token = localStorage.getItem('auth_token') || '';
        this.username = localStorage.getItem('auth_username') || '';
    }

    /**
     * Set authentication credentials
     */
    setAuth(token, username) {
        this.token = token;
        this.username = username;
        localStorage.setItem('auth_token', token);
        localStorage.setItem('auth_username', username);
    }

    /**
     * Clear authentication
     */
    clearAuth() {
        this.token = '';
        this.username = '';
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_username');
    }

    /**
     * Get headers with authentication
     */
    getHeaders() {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        };

        if (this.token && this.username) {
            headers['X-Auth-Token'] = this.token;
            headers['X-Username'] = this.username;
        }

        return headers;
    }

    /**
     * Make API request
     */
    async request(endpoint, method = 'GET', data = null) {
        const url = `${this.baseURL}/api/${endpoint}`;
        const options = {
            method: method,
            headers: this.getHeaders(),
        };

        if (data && (method === 'POST' || method === 'PUT')) {
            options.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(url, options);
            const result = await response.json();

            if (!response.ok) {
                if (response.status === 401) {
                    // Token expired or invalid
                    this.clearAuth();
                    window.location.href = '/login';
                    return null;
                }
                throw new Error(result.message || 'Request failed');
            }

            return result;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    // Authentication methods
    async register(data) {
        return await this.request('auth/register', 'POST', data);
    }

    async login(email, password) {
        const result = await this.request('auth/login', 'POST', { email, password });
        if (result && result.data && result.data.token) {
            this.setAuth(result.data.token, email);
        }
        return result;
    }

    async logout() {
        const result = await this.request('auth/logout', 'POST');
        this.clearAuth();
        return result;
    }

    async verifyToken() {
        return await this.request('auth/verify', 'POST');
    }

    // User CRUD
    async getUsers(params = {}) {
        const query = new URLSearchParams(params).toString();
        return await this.request(`users${query ? '?' + query : ''}`);
    }

    async getUser(id) {
        return await this.request(`users/${id}`);
    }

    async createUser(data) {
        return await this.request('users', 'POST', data);
    }

    async updateUser(id, data) {
        return await this.request(`users/${id}`, 'PUT', data);
    }

    async deleteUser(id) {
        return await this.request(`users/${id}`, 'DELETE');
    }

    // Category CRUD
    async getCategories() {
        return await this.request('categories');
    }

    async getCategory(id) {
        return await this.request(`categories/${id}`);
    }

    async createCategory(data) {
        return await this.request('categories', 'POST', data);
    }

    async updateCategory(id, data) {
        return await this.request(`categories/${id}`, 'PUT', data);
    }

    async deleteCategory(id) {
        return await this.request(`categories/${id}`, 'DELETE');
    }

    // Course CRUD
    async getCourses(params = {}) {
        const query = new URLSearchParams(params).toString();
        return await this.request(`courses${query ? '?' + query : ''}`);
    }

    async getCourse(id) {
        return await this.request(`courses/${id}`);
    }

    async createCourse(data) {
        return await this.request('courses', 'POST', data);
    }

    async updateCourse(id, data) {
        return await this.request(`courses/${id}`, 'PUT', data);
    }

    async deleteCourse(id) {
        return await this.request(`courses/${id}`, 'DELETE');
    }

    // Section CRUD
    async getSections(courseId) {
        return await this.request(`sections?course_id=${courseId}`);
    }

    async getSection(id) {
        return await this.request(`sections/${id}`);
    }

    async createSection(data) {
        return await this.request('sections', 'POST', data);
    }

    async updateSection(id, data) {
        return await this.request(`sections/${id}`, 'PUT', data);
    }

    async deleteSection(id) {
        return await this.request(`sections/${id}`, 'DELETE');
    }

    // Lecture CRUD
    async getLectures(sectionId) {
        return await this.request(`lectures?section_id=${sectionId}`);
    }

    async getLecture(id) {
        return await this.request(`lectures/${id}`);
    }

    async createLecture(data) {
        return await this.request('lectures', 'POST', data);
    }

    async updateLecture(id, data) {
        return await this.request(`lectures/${id}`, 'PUT', data);
    }

    async deleteLecture(id) {
        return await this.request(`lectures/${id}`, 'DELETE');
    }

    // Enrollment CRUD
    async getEnrollments(params = {}) {
        const query = new URLSearchParams(params).toString();
        return await this.request(`enrollments${query ? '?' + query : ''}`);
    }

    async getEnrollment(id) {
        return await this.request(`enrollments/${id}`);
    }

    async createEnrollment(data) {
        return await this.request('enrollments', 'POST', data);
    }

    async updateEnrollment(id, data) {
        return await this.request(`enrollments/${id}`, 'PUT', data);
    }

    async deleteEnrollment(id) {
        return await this.request(`enrollments/${id}`, 'DELETE');
    }

    // Order CRUD
    async getOrders(params = {}) {
        const query = new URLSearchParams(params).toString();
        return await this.request(`orders${query ? '?' + query : ''}`);
    }

    async getOrder(id) {
        return await this.request(`orders/${id}`);
    }

    async createOrder(data) {
        return await this.request('orders', 'POST', data);
    }

    async updateOrder(id, data) {
        return await this.request(`orders/${id}`, 'PUT', data);
    }

    // Quiz CRUD
    async getQuizzes(lectureId) {
        return await this.request(`quizzes?lecture_id=${lectureId}`);
    }

    async getQuiz(id) {
        return await this.request(`quizzes/${id}`);
    }

    async createQuiz(data) {
        return await this.request('quizzes', 'POST', data);
    }

    async updateQuiz(id, data) {
        return await this.request(`quizzes/${id}`, 'PUT', data);
    }

    async deleteQuiz(id) {
        return await this.request(`quizzes/${id}`, 'DELETE');
    }

    // Assignment CRUD
    async getAssignments(courseId) {
        return await this.request(`assignments?course_id=${courseId}`);
    }

    async getAssignment(id) {
        return await this.request(`assignments/${id}`);
    }

    async createAssignment(data) {
        return await this.request('assignments', 'POST', data);
    }

    async updateAssignment(id, data) {
        return await this.request(`assignments/${id}`, 'PUT', data);
    }

    async deleteAssignment(id) {
        return await this.request(`assignments/${id}`, 'DELETE');
    }

    // Discussion CRUD
    async getDiscussions(courseId) {
        return await this.request(`discussions?course_id=${courseId}`);
    }

    async getDiscussion(id) {
        return await this.request(`discussions/${id}`);
    }

    async createDiscussion(data) {
        return await this.request('discussions', 'POST', data);
    }

    async updateDiscussion(id, data) {
        return await this.request(`discussions/${id}`, 'PUT', data);
    }

    async deleteDiscussion(id) {
        return await this.request(`discussions/${id}`, 'DELETE');
    }

    // Review CRUD
    async getReviews(courseId) {
        return await this.request(`reviews?course_id=${courseId}`);
    }

    async getReview(id) {
        return await this.request(`reviews/${id}`);
    }

    async createReview(data) {
        return await this.request('reviews', 'POST', data);
    }

    async updateReview(id, data) {
        return await this.request(`reviews/${id}`, 'PUT', data);
    }

    async deleteReview(id) {
        return await this.request(`reviews/${id}`, 'DELETE');
    }

    // Certificate CRUD
    async getCertificates(params = {}) {
        const query = new URLSearchParams(params).toString();
        return await this.request(`certificates${query ? '?' + query : ''}`);
    }

    async getCertificate(id) {
        return await this.request(`certificates/${id}`);
    }

    async createCertificate(data) {
        return await this.request('certificates', 'POST', data);
    }

    // Notification CRUD
    async getNotifications(params = {}) {
        const query = new URLSearchParams(params).toString();
        return await this.request(`notifications${query ? '?' + query : ''}`);
    }

    async getNotification(id) {
        return await this.request(`notifications/${id}`);
    }

    async updateNotification(id, data) {
        return await this.request(`notifications/${id}`, 'PUT', data);
    }
}

// Create global instance
const api = new ELearningAPI();

// Helper functions for common operations
function showNotification(message, type = 'success') {
    // You can integrate with your notification system
    alert(message);
}

function handleApiError(error) {
    console.error('API Error:', error);
    showNotification(error.message || 'An error occurred', 'error');
}

