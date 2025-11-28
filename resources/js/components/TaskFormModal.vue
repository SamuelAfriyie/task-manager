<template>
  <Dialog :open="open" @update:open="$emit('close')">
    <DialogContent class="sm:max-w-[500px]">
      <DialogHeader>
        <DialogTitle>{{ editingTask ? 'Edit Task' : 'Create New Task' }}</DialogTitle>
        <DialogDescription>
          Add a new task to the project.
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div class="grid grid-cols-1 gap-4">
          <!-- Title -->
          <div class="space-y-2">
            <Label for="task-title">Task Title</Label>
            <Input
              id="task-title"
              v-model="form.title"
              placeholder="Enter task title"
              required
            />
          </div>

          <!-- Description -->
          <div class="space-y-2">
            <Label for="task-description">Description (Optional)</Label>
            <Textarea
              id="task-description"
              v-model="form.description"
              placeholder="Describe the task..."
              rows="3"
            />
          </div>

          <!-- Status -->
          <div class="space-y-2">
            <Label for="status">Status</Label>
            <Select v-model="form.status">
              <SelectTrigger>
                <SelectValue placeholder="Select status" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="pending">Pending</SelectItem>
                <SelectItem value="in-progress">In Progress</SelectItem>
                <SelectItem value="completed">Completed</SelectItem>
              </SelectContent>
            </Select>
          </div>

          <!-- Assigned Date -->
          <div class="space-y-2">
            <Label for="assigned-date">Assigned Date</Label>
            <Input
              id="assigned-date"
              v-model="form.assignedDate"
              type="date"
              required
            />
          </div>
        </div>

        <DialogFooter>
          <Button variant="outline" type="button" @click="$emit('close')">
            Cancel
          </Button>
          <Button type="submit">
            {{ editingTask ? 'Update Task' : 'Create Task' }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>

<script setup>
import { ref, watch, reactive } from 'vue'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'

// Props
const props = defineProps({
  open: {
    type: Boolean,
    default: false
  },
  editingTask: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'saved'])

// Form state
const form = reactive({
  title: '',
  description: '',
  status: 'pending',
  assignedDate: new Date().toISOString().split('T')[0]
})

const errors = reactive({
  title: '',
  assignedDate: ''
})

// Watch for editing task changes
watch(() => props.editingTask, (task) => {
  if (task) {
    form.title = task.title
    form.description = task.description
    form.status = task.status
    form.assignedDate = task.assignedDate
  } else {
    resetForm()
  }
})

// Watch for modal open/close
watch(() => props.open, (isOpen) => {
  if (!isOpen) {
    resetForm()
  }
})

// Methods
const resetForm = () => {
  form.title = ''
  form.description = ''
  form.status = 'pending'
  form.assignedDate = new Date().toISOString().split('T')[0]
  errors.title = ''
  errors.assignedDate = ''
}

const validateForm = () => {
  let isValid = true
  errors.title = ''
  errors.assignedDate = ''

  if (!form.title.trim()) {
    errors.title = 'Task title is required'
    isValid = false
  }

  if (!form.assignedDate) {
    errors.assignedDate = 'Assigned date is required'
    isValid = false
  }

  return isValid
}

const handleSubmit = () => {
  if (!validateForm()) {
    return
  }

  const taskData = {
    ...form,
    id: props.editingTask?.id || null
  }

  emit('saved', taskData)
  emit('close')
}
</script>