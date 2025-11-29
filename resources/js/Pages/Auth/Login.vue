<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <Card class="w-full max-w-md">
      <CardHeader class="space-y-1">
        <div class="flex items-center justify-center mb-4">
          <div class="w-12 h-12 bg-black rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
          </div>
        </div>
        <CardTitle class="text-2xl font-bold text-center text-gray-900">
          Task Manager
        </CardTitle>
        <CardDescription class="text-center text-gray-600">
          Sign in to your account
        </CardDescription>
      </CardHeader>

      <CardContent>
        <form @submit.prevent="handleLogin" class="space-y-4">
          <div class="space-y-2">
            <Label for="email" class="text-sm font-medium text-gray-700">
              Email
            </Label>
            <Input
              id="email"
              v-model="email"
              type="text"
              placeholder="Enter your email"
              class="w-full"
              :class="errors.email ? 'border-red-500 focus-visible:ring-red-500' : ''"
              required
            />
            <p v-if="errors.email" class="text-sm text-red-600">
              {{ errors.email }}
            </p>
          </div>

          <div class="space-y-2">
            <Label for="password" class="text-sm font-medium text-gray-700">
              Password
            </Label>
            <Input
              id="password"
              v-model="password"
              type="password"
              placeholder="Enter your password"
              class="w-full"
              :class="errors.password ? 'border-red-500 focus-visible:ring-red-500' : ''"
              required
            />
            <p v-if="errors.password" class="text-sm text-red-600">
              {{ errors.password }}
            </p>
          </div>

          <div class="flex items-center justify-end">
            <Button
              variant="link"
              class="h-auto p-0 text-sm text-blue-600 hover:text-blue-800"
              as-child
            >
              <a href="#">
                Forgot password?
              </a>
            </Button>
          </div>

          <Button
            type="submit"
            class="w-full"
            :disabled="loading"
          >
            <svg
              v-if="loading"
              class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
              ></circle>
              <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
              ></path>
            </svg>
            {{ loading ? 'Signing in...' : 'Sign in' }}
          </Button>
        </form>
      </CardContent>

      <CardFooter>
        <p class="text-center text-sm text-gray-600 w-full">
          Don't have an account?
          <Button variant="link" class="p-0 h-auto text-blue-600 hover:text-blue-800 ml-1" as-child>
            <a href="/signup">
              Sign up
            </a>
          </Button>
        </p>
      </CardFooter>
    </Card>
  </div>
</template>


<script setup>
import { ref } from 'vue'
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useRouter } from 'vue-router'
import { authService } from '@/service/AuthService'
import { useUserStore } from '@/store/userUserStore'

const router = useRouter();
const user = useUserStore();

// Reactive data
const email = ref('')
const password = ref('')
const showPassword = ref(false)
const loading = ref(false)
const errors = ref({
  email: '',
  password: ''
})

// Methods
const validateForm = () => {
  errors.value = { email: '', password: '' }
  let isValid = true

  if (!email.value.trim()) {
    errors.value.email = 'Email is required'
    isValid = false
  }

  if (!password.value) {
    errors.value.password = 'Password is required'
    isValid = false
  } else if (password.value.length < 6) {
    errors.value.password = 'Password must be at least 6 characters'
    isValid = false
  }

  return isValid
}

const handleLogin = async () => {
  if (!validateForm()) {
    return
  }

  loading.value = true

  try {
    const credentials = {email: email.value, password: password.value};
    const {data: userData} = await authService.login(credentials);
    user.setUser(userData) 
    router.push('/dashboard')
  } catch (error) {
    errors.value.password = 'Invalid email or password'
  } finally {
    loading.value = false
  }
}
</script>