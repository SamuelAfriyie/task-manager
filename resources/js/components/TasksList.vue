<template>
  <Card>
    <CardHeader class="flex flex-row items-center justify-between">
      <div>
        <CardTitle>Tasks - {{ selectedProject?.title }}</CardTitle>
        <CardDescription>Manage tasks for this project</CardDescription>
      </div>
      <Button @click="handleNewTask" size="sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Add Task
      </Button>
    </CardHeader>
    <CardContent>
      <div class="space-y-4">
        <!-- Task Filters -->
        <div class="flex flex-wrap gap-2">
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
            class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors"
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
                  <Badge :variant="getStatusVariant(task.status)" class="shrink-0">
                    {{ task.status }}
                  </Badge>
                </div>
              </div>
            </div>
            
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="ghost" size="sm" class="h-8 w-8 p-0 shrink-0">
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
        <div v-if="filteredTasks.length === 0" class="text-center py-8">
          <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
          </svg>
          <p class="text-gray-500" v-if="currentFilter !== 'all'">
            No {{ currentFilter }} tasks found.
          </p>
          <p class="text-gray-500" v-else>
            No tasks found. Add your first task to get started.
          </p>
        </div>
      </div>
    </CardContent>
  </Card>

  <!-- Task Form Modal -->
  <TaskFormModal 
    :open="showTaskForm"
    :editing-task="editingTask"
    @close="showTaskForm = false"
    @saved="handleTaskSaved"
  />
</template>

<script>
// Add this method to the Tasks List script
const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}
</script>

<script setup>
import { ref, computed, watch } from 'vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Checkbox } from '@/components/ui/checkbox'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import TaskFormModal from './TaskFormModal.vue'

// Props
const props = defineProps({
  selectedProject: {
    type: Object,
    default: null
  },
  tasks: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['task-updated', 'task-deleted', 'task-created'])

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
    'pending': 'secondary',
    'in-progress': 'default',
    'completed': 'outline'
  }
  return variants[status] || 'secondary'
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

const handleTaskSaved = (taskData) => {
  showTaskForm.value = false
  
  if (taskData.id) {
    // Update existing task
    emit('task-updated', taskData)
  } else {
    // Create new task
    emit('task-created', {
      ...taskData,
      projectId: props.selectedProject.id
    })
  }
  
  editingTask.value = null
}

const handleNewTask = () => {
  editingTask.value = null
  showTaskForm.value = true
}

// Watch for project changes
watch(() => props.selectedProject, () => {
  currentFilter.value = 'all'
})
</script>