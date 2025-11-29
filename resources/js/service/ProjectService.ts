import apiClient from "@/config/axios"

export const projectService = {
    getAll: async () => {
        try {
            const { data: response } = await apiClient.get(`/project`)

            return { status: true, data: response.data }

        } catch (error) {
            throw projectService.handleError(error)
        }
    },
    create: async (project: any) => {
        try {
            const { data: response } = await apiClient.post('/project', project)

            return { status: true, data: { ...response.data } }

        } catch (error) {
            throw projectService.handleError(error)
        }
    },
    update: async (project: any) => {
        try {
            const { data: response } = await apiClient.put(`/project/${project.id}`, project)

            return { status: true, data: { ...response.data } }

        } catch (error) {
            throw projectService.handleError(error)
        }
    },
    delete: async (project: any) => {
        try {
            const { data: response } = await apiClient.delete(`/project/${project.id}`, project)

            return { status: true, data: { ...response.data } }

        } catch (error) {
            throw projectService.handleError(error)
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