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
            <Label for="desc">Description</Label>
            <Textarea
              id="desc"
              v-model="form.desc"
              placeholder="Describe the project..."
              rows="3"
            />
          </div>

          <!-- Deadline -->
          <div class="space-y-2">
            <Label for="deadlineDate">Deadline</Label>
            <Input
              id="deadlineDate"
              v-model="form.deadlineDate"
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
import { projectService } from '@/service/ProjectService'

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
  desc: '',
  deadlineDate: ''
})

const errors = reactive({
  title: '',
  deadlineDate: ''
})

// Watch for editing project changes
watch(() => props.editingProject, (project) => {
  if (project) {
    form.title = project.title
    form.desc = project.desc
    form.deadlineDate = project.deadlineDate
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
  form.desc = ''
  form.deadlineDate = ''
  errors.title = ''
  errors.deadlineDate = ''
}

const validateForm = () => {
  let isValid = true
  errors.title = ''
  errors.deadlineDate = ''

  if (!form.title.trim()) {
    errors.title = 'Project title is required'
    isValid = false
  }

  if (!form.deadlineDate) {
    errors.deadlineDate = 'Deadline is required'
    isValid = false
  } else if (new Date(form.deadlineDate) < new Date().setHours(0, 0, 0, 0)) {
    errors.deadlineDate = 'Deadline cannot be in the past'
    isValid = false
  }

  return isValid
}

const handleSubmit = async () => {
  if (!validateForm()) {
    return
  }

  const projectData = {
    ...form
  }
if(props.editingProject?.id == undefined) {
  await projectService.create(projectData);
  projectData.id = Date.now();
} else {
  projectData.id = props.editingProject?.id ;
  await projectService.update(projectData);
}
  emit('saved', projectData)
  emit('close')
}
</script>