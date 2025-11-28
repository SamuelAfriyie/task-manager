import apiClient from "@/config/axios"

type ApiResponse = {
    status: boolean;
    data: any;
    message?: string
}

export const authService = {
    login: async (credentials: any): Promise<ApiResponse> => {
        try {
            const { data: response } = await apiClient.post('/auth/login', credentials)
            // Store token if available
            if (response.data.token) {
                localStorage.setItem('authToken', response.data.token)
                apiClient.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
            }

            return { status: true, data: response.data?.user }

        } catch (error) {
            throw authService.handleError(error)
        }
    },

    signup: async (userData: any) => {
        try {
            const { data: response } = await apiClient.post('/auth/signup', userData)
            return response.data
        } catch (error) {
            throw authService.handleError(error)
        }
    },

    logout: () => {
        localStorage.removeItem('authToken')
        delete apiClient.defaults.headers.common['Authorization']
    },

    // Handle API errors
    handleError: (error: any) => {
        if (error.response?.data?.message) {
            return new Error(error.response.data.message)
        } else if (error.message) {
            return new Error(error.message)
        } else {
            return new Error('An unexpected error occurred')
        }
    }
}