<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Sticky Header -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center mr-3">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Task Manager</h1>
          </div>
          <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
              <span>{{ user?.userData?.name }}</span>
            </div>
            <Button variant="outline" size="sm" @click="handleLogout">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
              Logout
            </Button>
          </div>
        </div>
      </div>
    </header>

    <div class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <Card class="shadow-sm border-0">
            <CardContent class="py-4 px-6">
              <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-600">Total Projects</p>
                  <p class="text-2xl font-bold text-gray-900">{{ stats.totalProjects }}</p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card class="shadow-sm border-0">
            <CardContent class="py-4 px-6">
              <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-600">Completed</p>
                  <p class="text-2xl font-bold text-gray-900">{{ stats.completedProjects.valueOf() }}</p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card class="shadow-sm border-0">
            <CardContent class="py-4 px-6">
              <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                  <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-600">In Progress</p>
                  <p class="text-2xl font-bold text-gray-900">{{ stats.inProgressProjects.valueOf() }}</p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card class="shadow-sm border-0">
            <CardContent class="py-4 px-6">
              <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                  <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-600">Overdue</p>
                  <p class="text-2xl font-bold text-gray-900">{{ stats.overdueProjects.valueOf() }}</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Projects List -->
          <div class="lg:col-span-2">
            <Card>
              <CardHeader class="flex flex-row items-center justify-between">
                <div>
                  <CardTitle>Projects</CardTitle>
                  <CardDescription>Manage your projects and track progress</CardDescription>
                </div>
                <Button @click="handleNewProject">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                  </svg>
                  New Project
                </Button>
              </CardHeader>
              <CardContent>
                <ProjectsList
                  :projects="projects"
                  :loading="loading"
                  @project-selected="handleProjectSelected"
                  @edit-project="handleEditProject"
                  @delete-project="handleDeleteProject"
                />
              </CardContent>
            </Card>
          </div>

          <!-- Sidebar -->
          <div class="space-y-6">
            <!-- Project Progress -->
            <Card>
              <CardHeader>
                <CardTitle>Project Progress</CardTitle>
                <CardDescription>Overall completion rate</CardDescription>
              </CardHeader>
              <CardContent>
                <div class="space-y-4">
                  <div v-for="project in projects?.slice(0, 5)" :key="project.id" class="flex items-center justify-between">
                    <div class="w-full">
                      <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 truncate mr-2">{{ project.title }}</span>
                        <span class="text-sm text-gray-500 whitespace-nowrap">{{ project.completion }}%</span>
                      </div>
                      <Progress :modelValue="project.completion" class="w-full" />
                    </div>
                  </div>
                  <div v-if="projects.length === 0" class="text-center py-4 text-gray-500">
                    No projects yet
                  </div>
                </div>
              </CardContent>
            </Card>

            <!-- Upcoming Deadlines -->
            <Card>
              <CardHeader>
                <CardTitle>Upcoming Deadlines</CardTitle>
                <CardDescription>Projects due soon</CardDescription>
              </CardHeader>
              <CardContent>
                <div class="space-y-3">
                  <div 
                    v-for="project in upcomingDeadlines()" 
                    :key="project.id" 
                    class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-100 cursor-pointer hover:bg-orange-100 transition-colors"
                    @click="handleProjectSelected(project)"
                  >
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-900 truncate">{{ project.title }}</p>
                      <p class="text-xs text-gray-500">Due {{ formatDate(project.deadlineDate) }}</p>
                    </div>
                    <Badge variant="secondary" class="whitespace-nowrap">
                      {{ project.daysLeft }} days
                    </Badge>
                  </div>
                  <div v-if="upcomingDeadlines().length === 0" class="text-center py-4 text-gray-500">
                    No upcoming deadlines
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </div>

    <!-- Project Form Modal -->
    <ProjectFormModal 
      :open="showProjectForm" 
      :editing-project="editingProject"
      @close="handleCloseProjectForm"
      @saved="fetchProjects"
    />

    <!-- Project Details Sheet -->
    <ProjectDetailsSheet
      :open="showProjectDetails"
      :project="selectedProject" 
      @close="showProjectDetails = false"
      @task-created="fetchProjects"
      @task-updated="fetchProjects"
      @task-deleted="fetchProjects"
      @edit-project="handleEditProject"
      @delete-project="handleDeleteProject"
    />

    <!-- Back to Top Button -->
    <button
      v-if="showBackToTop"
      @click="scrollToTop"
      class="fixed bottom-8 right-8 bg-black text-white p-3 rounded-full shadow-lg hover:bg-black/50 transition-all duration-200 z-40"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
      </svg>
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Progress } from '@/components/ui/progress'
import { Badge } from '@/components/ui/badge'
import ProjectsList from '@/components/ProjectsList.vue'
import ProjectFormModal from '@/components/ProjectFormModal.vue'
import ProjectDetailsSheet from '@/components/ProjectDetailsSheet.vue'
import { authService } from '@/service/AuthService'
import { useUserStore } from '@/store/userUserStore'
import { projectService } from '@/service/ProjectService'

const router = useRouter()
const user = useUserStore()
// State
const showProjectForm = ref(false)
const showProjectDetails = ref(false)
const selectedProject = ref(null)
const editingProject = ref(null)
const projects = ref([])
const showBackToTop = ref(false)
const stats = ref({
  totalProjects: 0,
  completedProjects: 0,
  inProgressProjects: 0,
  overdueProjects: 0

})
const loading = ref(false)

// Computed
const loadStats = () => {
    stats.value = {
      totalProjects: projects.value.length,
      completedProjects: projects.value.filter(p => p.completion === 100).length,
      inProgressProjects: projects.value.filter(p => p.completion >= 0 && p.completion < 100).length,
      overdueProjects: projects.value.filter(p => new Date(p.deadlineDate) < new Date() && p.completion < 100).length
    }
}

const upcomingDeadlines = () => {
  const now = new Date()
  const nextWeek = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000)
  
  return projects.value
    .filter(project => {
      const deadline = new Date(project.deadlineDate)
      return deadline > now && deadline <= nextWeek && project.completion < 100
    })
    .map(project => {
      const deadline = new Date(project.deadlineDate)
      const now = new Date()
      const daysLeft = Math.ceil((deadline - now) / (1000 * 60 * 60 * 24))
      return {
        ...project,
        daysLeft
      }
    })
    .sort((a, b) => new Date(a.deadlineDate) - new Date(b.deadlineDate))
    .slice(0, 3)
}

const handleLogout = () => {
  authService.logout();
  user.clearUser();
  router.push('/login')
}

const handleNewProject = () => {
  editingProject.value = null
  showProjectForm.value = true
}

const handleCloseProjectForm = () => {
  showProjectForm.value = false
  editingProject.value = null;
}

const handleProjectSelected = (project) => {
  selectedProject.value = project
  showProjectDetails.value = true
}

const handleEditProject = (project) => {
  editingProject.value = project
  showProjectForm.value = true
  showProjectDetails.value = false
}

const handleDeleteProject = async (projectId) => {
  if (selectedProject.value?.id === projectId) {
    selectedProject.value = null
    showProjectDetails.value = false
  }
  await fetchProjects();
}

const _handleProjectSaved = (projectData) => {
  if (projectData.id && projects.value.some(p => p.id === projectData.id)) {
    // Update existing project
    const index = projects.value.findIndex(p => p.id === projectData.id)
    if (index !== -1) {
      projects.value[index] = { 
        ...projects.value[index], 
        ...projectData,
        completion: projects.value[index].completion || 0,
        taskCount: projects.value[index].tasks.value.filter(t => t.projectId === projectData.id).length
      }
    }
  } else {
    // Create new project
    projects.value = [projectData, ...projects.value]
  }
  
  handleCloseProjectForm();
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const handleScroll = () => {
  showBackToTop.value = window.scrollY > 400
}


const fetchProjects = async ()  => {
 loading.value = true;
  try {
    const {data: prjs} = await projectService.getAll();
    projects.value = prjs;
    loadStats();
  } catch (e) {
    console.log(e);
    projects.value = [];
  } finally {
 loading.value = false;
  }
}

onMounted(() => {
  fetchProjects();
  
  // Add scroll listener for back to top button
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>