import apiClient from "@/config/axios"

export const taskService = {
    getAll: async () => {
        try {
            const { data: response } = await apiClient.get(`/task`)

            return { status: true, data: { ...response.data } }

        } catch (error) {
            throw taskService.handleError(error)
        }
    },
    create: async (task: any) => {
        try {
            const { data: response } = await apiClient.post('/task', task)

            return { status: true, data: { ...response.data } }

        } catch (error) {
            throw taskService.handleError(error)
        }
    },
    update: async (task: any) => {
        try {
            const { data: response } = await apiClient.put(`/task/${task.id}`, task)

            return { status: true, data: { ...response.data } }

        } catch (error) {
            throw taskService.handleError(error)
        }
    },
    delete: async (task: any) => {
        try {
            const { data: response } = await apiClient.delete(`/task/${task.id}`, task)

            return { status: true, data: { ...response.data } }

        } catch (error) {
            throw taskService.handleError(error)
        }
    },
    markAsCompleted: async (task: any) => {
        try {
            const { data: response } = await apiClient.patch(`/task/${task.id}`, task)

            return { status: true, data: { ...response.data } }

        } catch (error) {
            throw taskService.handleError(error)
        }
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