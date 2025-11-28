<template>
  <Sheet :open="open" @update:open="$emit('close')">
    <SheetContent class="sm:max-w-2xl overflow-y-auto">
      <SheetHeader class="text-left">
        <SheetTitle class="flex items-center justify-between">
          <span>{{ project?.title }}</span>
          <div class="flex items-center space-x-2">
            <Button variant="outline" size="sm" @click="handleEditProject">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
              Edit
            </Button>
            <Button variant="outline" size="sm" @click="handleDeleteProject" class="text-red-600 hover:text-red-700">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
              Delete
            </Button>
          </div>
        </SheetTitle>
        <SheetDescription>
          {{ project?.description }}
        </SheetDescription>
      </SheetHeader>

      <div class="mt-6 space-y-6">
        <!-- Project Overview -->
        <Card>
          <CardContent class="p-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
              <div>
                <p class="text-gray-500">Deadline</p>
                <p class="font-medium">{{ formatDate(project?.deadline) }}</p>
              </div>
              <div>
                <p class="text-gray-500">Status</p>
                <Badge :variant="getStatusVariant(project?.status)" class="mt-1">
                  {{ project?.status }}
                </Badge>
              </div>
              <div>
                <p class="text-gray-500">Total Tasks</p>
                <p class="font-medium">{{ project?.taskCount }}</p>
              </div>
              <div>
                <p class="text-gray-500">Completion</p>
                <p class="font-medium">{{ project?.completion }}%</p>
              </div>
            </div>
            <div class="mt-4">
              <Progress :value="project?.completion" class="w-full" />
            </div>
          </CardContent>
        </Card>

        <!-- Tasks Section -->
        <div>
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Tasks</h3>
            <Button @click="showTaskForm = true" size="sm">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              Add Task
            </Button>
          </div>

          <!-- Task Filters -->
          <div class="flex flex-wrap gap-2 mb-4">
            <Badge 
              v-for="status in taskStatuses" 
              :key="status.value"
              :variant="currentFilter === status.value ? 'default' : 'outline'"
              class="cursor-pointer"
              @click="currentFilter = status.value"
            >
              {{ status.label }}
            </Badge>
          </div>

          <!-- Tasks List -->
          <div class="space-y-3">
            <div 
              v-for="task in filteredTasks" 
              :key="task.id"
              class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors group"
            >
              <div class="flex items-center space-x-3 flex-1">
                <Checkbox 
                  :checked="task.status === 'completed'" 
                  @click="toggleTaskStatus(task)"
                  class="shrink-0"
                />
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-gray-900" :class="{ 'line-through text-gray-500': task.status === 'completed' }">
                    {{ task.title }}
                  </p>
                  <p v-if="task.description" class="text-sm text-gray-500 mt-1">{{ task.description }}</p>
                  <div class="flex items-center space-x-4 mt-2">
                    <span class="text-xs text-gray-400">
                      Assigned: {{ formatDate(task.assignedDate) }}
                    </span>
                    <Badge :variant="getTaskStatusVariant(task.status)" class="shrink-0">
                      {{ task.status }}
                    </Badge>
                  </div>
                </div>
              </div>
              
              <DropdownMenu>
                <DropdownMenuTrigger as-child>
                  <Button variant="ghost" size="sm" class="h-8 w-8 p-0 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                    </svg>
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                  <DropdownMenuItem @click="editTask(task)">
                    Edit Task
                  </DropdownMenuItem>
                  <DropdownMenuItem @click="deleteTask(task)" class="text-red-600">
                    Delete Task
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="filteredTasks.length === 0" class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p class="text-gray-500 mb-4" v-if="currentFilter !== 'all'">
              No {{ currentFilter }} tasks found.
            </p>
            <p class="text-gray-500 mb-4" v-else>
              No tasks yet. Add your first task to get started.
            </p>
            <Button @click="showTaskForm = true">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              Add First Task
            </Button>
          </div>
        </div>
      </div>
    </SheetContent>
  </Sheet>

  <!-- Task Form Modal -->
  <TaskFormModal 
    :open="showTaskForm"
    :editing-task="editingTask"
    :project-id="project?.id"
    @close="handleCloseTaskForm"
    @saved="handleTaskSaved"
  />
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle } from '@/components/ui/sheet'
import { Card, CardContent } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Progress } from '@/components/ui/progress'
import { Checkbox } from '@/components/ui/checkbox'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import TaskFormModal from './TaskFormModal.vue'

// Props
const props = defineProps({
  open: {
    type: Boolean,
    default: false
  },
  project: {
    type: Object,
    default: null
  },
  tasks: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['close', 'task-created', 'task-updated', 'task-deleted', 'edit-project', 'delete-project'])

// Local state
const showTaskForm = ref(false)
const currentFilter = ref('all')
const editingTask = ref(null)

// Computed
const filteredTasks = computed(() => {
  if (currentFilter.value === 'all') {
    return props.tasks
  }
  return props.tasks.filter(task => task.status === currentFilter.value)
})

const taskStatuses = computed(() => [
  { value: 'all', label: 'All Tasks' },
  { value: 'pending', label: 'Pending' },
  { value: 'in-progress', label: 'In Progress' },
  { value: 'completed', label: 'Completed' }
])

// Methods
const getStatusVariant = (status) => {
  const variants = {
    'active': 'default',
    'completed': 'outline',
    'overdue': 'destructive'
  }
  return variants[status] || 'secondary'
}

const getTaskStatusVariant = (status) => {
  const variants = {
    'pending': 'secondary',
    'in-progress': 'default',
    'completed': 'outline'
  }
  return variants[status] || 'secondary'
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}

const toggleTaskStatus = (task) => {
  const newStatus = task.status === 'completed' ? 'pending' : 'completed'
  const updatedTask = { ...task, status: newStatus }
  emit('task-updated', updatedTask)
}

const editTask = (task) => {
  editingTask.value = task
  showTaskForm.value = true
}

const deleteTask = (task) => {
  if (confirm(`Are you sure you want to delete "${task.title}"?`)) {
    emit('task-deleted', task.id)
  }
}

const handleEditProject = () => {
  emit('edit-project', props.project)
}

const handleDeleteProject = () => {
  emit('delete-project', props.project.id)
}

const handleTaskSaved = (taskData) => {
  showTaskForm.value = false
  
  if (taskData.id) {
    // Update existing task
    emit('task-updated', taskData)
  } else {
    // Create new task
    emit('task-created', {
      ...taskData,
      projectId: props.project.id
    })
  }
  
  editingTask.value = null
}

const handleCloseTaskForm = () => {
  showTaskForm.value = false
  editingTask.value = null
}

// Watch for project changes
watch(() => props.project, () => {
  currentFilter.value = 'all'
  editingTask.value = null
  showTaskForm.value = false
})
</script>