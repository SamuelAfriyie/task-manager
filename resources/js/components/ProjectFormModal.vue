<template>
  <Dialog :open="open" @update:open="$emit('close')">
    <DialogContent class="sm:max-w-[600px]">
      <DialogHeader>
        <DialogTitle>{{ editingProject ? 'Edit Project' : 'Create New Project' }}</DialogTitle>
        <DialogDescription>
          Add a new project to organize your tasks and track progress.
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div class="grid grid-cols-1 gap-4">
          <!-- Title -->
          <div class="space-y-2">
            <Label for="title">Project Title</Label>
            <Input
              id="title"
              v-model="form.title"
              placeholder="Enter project title"
              required
            />
          </div>

          <!-- Description -->
          <div class="space-y-2">
            <Label for="description">Description</Label>
            <Textarea
              id="description"
              v-model="form.description"
              placeholder="Describe the project..."
              rows="3"
            />
          </div>

          <!-- Deadline -->
          <div class="space-y-2">
            <Label for="deadline">Deadline</Label>
            <Input
              id="deadline"
              v-model="form.deadline"
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
            {{ editingProject ? 'Update Project' : 'Create Project' }}
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

// Props
const props = defineProps({
  open: {
    type: Boolean,
    default: false
  },
  editingProject: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'saved'])

// Form state
const form = reactive({
  title: '',
  description: '',
  deadline: ''
})

const errors = reactive({
  title: '',
  deadline: ''
})

// Watch for editing project changes
watch(() => props.editingProject, (project) => {
  if (project) {
    form.title = project.title
    form.description = project.description
    form.deadline = project.deadline
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
  form.deadline = ''
  errors.title = ''
  errors.deadline = ''
}

const validateForm = () => {
  let isValid = true
  errors.title = ''
  errors.deadline = ''

  if (!form.title.trim()) {
    errors.title = 'Project title is required'
    isValid = false
  }

  if (!form.deadline) {
    errors.deadline = 'Deadline is required'
    isValid = false
  } else if (new Date(form.deadline) < new Date().setHours(0, 0, 0, 0)) {
    errors.deadline = 'Deadline cannot be in the past'
    isValid = false
  }

  return isValid
}

const handleSubmit = () => {
  if (!validateForm()) {
    return
  }

  const projectData = {
    ...form,
    id: props.editingProject?.id || Date.now()
  }

  emit('saved', projectData)
  emit('close')
}
</script>