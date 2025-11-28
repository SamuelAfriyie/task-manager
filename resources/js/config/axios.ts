import axios from 'axios'

// Create axios instance with base configuration
const apiClient = axios.create({
    baseURL:'http://localhost:8000/api',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
})

// Request interceptor to add auth token
apiClient.interceptors.request.use(
    (config) => {
        // Get token from localStorage or Vuex store
        const token = localStorage.getItem('authToken')
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    (error) => {
        return Promise.reject(error)
    }
)

// Response interceptor to handle common errors
apiClient.interceptors.response.use(
    (response) => {
        return response
    },
    (error) => {
        // Handle common errors
        if (error.response?.status === 401) {
            // Unauthorized - clear token and redirect to login
            localStorage.removeItem('authToken')
            window.location.href = '/login'
        } else if (error.response?.status === 500) {
            // Server error
            console.error('Server error:', error)
        }

        return Promise.reject(error)
    }
)

export default apiClient