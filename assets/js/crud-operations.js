/**
 * CRUD Operations Helper
 * Provides common CRUD operations for all entities
 */

// Course CRUD
const CourseCRUD = {
    list: async function() {
        try {
            const response = await ajax.get('/api/courses');
            return response;
        } catch (error) {
            console.error('Error fetching courses:', error);
            throw error;
        }
    },
    
    get: async function(id) {
        try {
            const response = await ajax.get(`/api/courses/${id}`);
            return response;
        } catch (error) {
            console.error('Error fetching course:', error);
            throw error;
        }
    },
    
    create: async function(data) {
        try {
            const response = await ajax.post('/api/courses', data);
            return response;
        } catch (error) {
            console.error('Error creating course:', error);
            throw error;
        }
    },
    
    update: async function(id, data) {
        try {
            const response = await ajax.put(`/api/courses/${id}`, data);
            return response;
        } catch (error) {
            console.error('Error updating course:', error);
            throw error;
        }
    },
    
    delete: async function(id) {
        try {
            const response = await ajax.delete(`/api/courses/${id}`);
            return response;
        } catch (error) {
            console.error('Error deleting course:', error);
            throw error;
        }
    }
};

// Category CRUD
const CategoryCRUD = {
    list: async function() {
        return await ajax.get('/api/categories');
    },
    get: async function(id) {
        return await ajax.get(`/api/categories/${id}`);
    },
    create: async function(data) {
        return await ajax.post('/api/categories', data);
    },
    update: async function(id, data) {
        return await ajax.put(`/api/categories/${id}`, data);
    },
    delete: async function(id) {
        return await ajax.delete(`/api/categories/${id}`);
    }
};

// Enrollment CRUD
const EnrollmentCRUD = {
    list: async function() {
        return await ajax.get('/api/enrollments');
    },
    create: async function(data) {
        return await ajax.post('/api/enrollments', data);
    },
    delete: async function(id) {
        return await ajax.delete(`/api/enrollments/${id}`);
    }
};

// Section CRUD
const SectionCRUD = {
    getByCourse: async function(courseId) {
        return await ajax.get(`/api/sections/course/${courseId}`);
    },
    get: async function(id) {
        return await ajax.get(`/api/sections/${id}`);
    },
    create: async function(data) {
        return await ajax.post('/api/sections', data);
    },
    update: async function(id, data) {
        return await ajax.put(`/api/sections/${id}`, data);
    },
    delete: async function(id) {
        return await ajax.delete(`/api/sections/${id}`);
    }
};

// Lecture CRUD
const LectureCRUD = {
    getBySection: async function(sectionId) {
        return await ajax.get(`/api/lectures/section/${sectionId}`);
    },
    get: async function(id) {
        return await ajax.get(`/api/lectures/${id}`);
    },
    create: async function(data) {
        return await ajax.post('/api/lectures', data);
    },
    update: async function(id, data) {
        return await ajax.put(`/api/lectures/${id}`, data);
    },
    delete: async function(id) {
        return await ajax.delete(`/api/lectures/${id}`);
    }
};

// Auth operations
const Auth = {
    login: async function(email, password) {
        const response = await ajax.post('/api/auth/login', { email, password });
        if (response.success && response.data.token) {
            setAuthCredentials(response.data.token, response.data.user.email);
        }
        return response;
    },
    
    register: async function(data) {
        const response = await ajax.post('/api/auth/register', data);
        if (response.success && response.data.token) {
            setAuthCredentials(response.data.token, response.data.user.email);
        }
        return response;
    },
    
    logout: async function() {
        const response = await ajax.post('/api/auth/logout');
        clearAuthCredentials();
        return response;
    },
    
    me: async function() {
        return await ajax.get('/api/auth/me');
    }
};

