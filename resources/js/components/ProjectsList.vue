<template>
  <div class="space-y-4">
    <!-- Search and Filter -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
      <div class="flex-1">
        <Input
          v-model="searchQuery"
          placeholder="Search projects..."
          class="w-full"
        >
          <template #left>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </template>
        </Input>
      </div>
      <Select v-model="statusFilter">
        <SelectTrigger class="w-[180px]">
          <SelectValue placeholder="Filter by status" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem value="all">All Projects</SelectItem>
          <SelectItem value="active">Active</SelectItem>
          <SelectItem value="completed">Completed</SelectItem>
          <SelectItem value="overdue">Overdue</SelectItem>
        </SelectContent>
      </Select>
    </div>

    <!-- Rest of the template remains the same but update the progress and date display -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <Card 
        v-for="project in filteredProjects" 
        :key="project.id"
        class="hover:shadow-md transition-shadow cursor-pointer border-2 border-transparent hover:border-blue-200"
        @click="selectProject(project)"
      >
        <CardContent class="p-6">
          <div class="flex justify-between items-start mb-4">
            <div class="flex-1 min-w-0">
              <div class="flex items-center space-x-2 mb-2">
                <h3 class="font-semibold text-lg text-gray-900 truncate">{{ project.title }}</h3>
                <Badge :variant="getStatusBadgeVariant(project.status)" class="shrink-0">
                  {{ project.status }}
                </Badge>
              </div>
              <p class="text-sm text-gray-600 line-clamp-2">{{ project.description }}</p>
            </div>
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="ghost" size="sm" class="h-8 w-8 p-0 shrink-0" @click.stop>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                  </svg>
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                <DropdownMenuItem @click="editProject(project)">
                  Edit Project
                </DropdownMenuItem>
                <DropdownMenuItem @click="deleteProject(project)" class="text-red-600">
                  Delete Project
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </div>

          <!-- Progress -->
          <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm text-gray-600">Progress</span>
              <span class="text-sm font-medium text-gray-900">{{ project.completion }}%</span>
            </div>
            <Progress :value="project.completion" class="w-full" />
          </div>

          <!-- Project Meta -->
          <div class="flex justify-between items-center text-sm text-gray-500">
            <div class="flex items-center">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              {{ formatDate(project.deadlineDate) }}
            </div>
            <div class="flex items-center">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
              {{ project.tasks.length }} tasks
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Empty State -->
    <div v-if="filteredProjects.length === 0" class="text-center py-12">
      <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
      </svg>
      <h3 class="text-lg font-medium text-gray-900 mb-2">No projects found</h3>
      <p class="text-gray-500 mb-4" v-if="searchQuery || statusFilter !== 'all'">
        Try adjusting your search or filter criteria.
      </p>
      <p class="text-gray-500 mb-4" v-else>
        Get started by creating your first project.
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Card, CardContent } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Progress } from '@/components/ui/progress'
import { Badge } from '@/components/ui/badge'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'

// Props
const props = defineProps({
  projects: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['project-selected', 'edit-project', 'delete-project'])

// Local state
const searchQuery = ref('')
const statusFilter = ref('all')

// Computed
const filteredProjects = computed(() => {
  let filtered = props.projects

  // Filter by search query
  if (searchQuery.value) {
    filtered = filtered.filter(project =>
      project.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      project.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }

  // Filter by status
  if (statusFilter.value !== 'all') {
    filtered = filtered.filter(project => project.status === statusFilter.value)
  }

  return filtered
})

// Methods
const selectProject = (project) => {
  emit('project-selected', project)
}

const editProject = (project) => {
  emit('edit-project', project)
}

const deleteProject = (project) => {
  if (confirm(`Are you sure you want to delete "${project.title}"? This will also delete all tasks in this project.`)) {
    emit('delete-project', project.id)
  }
}

const getStatusBadgeVariant = (status) => {
  const variants = {
    'active': 'default',
    'completed': 'outline',
    'overdue': 'destructive'
  }
  return variants[status] || 'secondary'
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}
</script>