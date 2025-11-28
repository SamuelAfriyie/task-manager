<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <Card class="w-full max-w-md">
      <CardHeader class="space-y-1">
        <div class="flex items-center justify-center mb-4">
          <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
          </div>
        </div>
        <CardTitle class="text-2xl font-bold text-center text-gray-900">
          Create Account
        </CardTitle>
        <CardDescription class="text-center text-gray-600">
          Enter your information to get started
        </CardDescription>
      </CardHeader>

      <CardContent>
        <form @submit.prevent="handleSignup" class="space-y-4">
          <!-- Username Field -->
          <div class="space-y-2">
            <Label for="username" class="text-sm font-medium text-gray-700">
              Username
            </Label>
            <Input
              id="username"
              v-model="form.username"
              type="text"
              placeholder="Choose a username"
              class="w-full"
              :class="errors.username ? 'border-red-500 focus-visible:ring-red-500' : ''"
              required
            />
            <p v-if="errors.username" class="text-sm text-red-600 flex items-center gap-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              {{ errors.username }}
            </p>
          </div>

          <!-- Email Field -->
          <div class="space-y-2">
            <Label for="email" class="text-sm font-medium text-gray-700">
              Email
            </Label>
            <Input
              id="email"
              v-model="form.email"
              type="email"
              placeholder="Enter your email"
              class="w-full"
              :class="errors.email ? 'border-red-500 focus-visible:ring-red-500' : ''"
              required
            />
            <p v-if="errors.email" class="text-sm text-red-600 flex items-center gap-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              {{ errors.email }}
            </p>
          </div>

          <!-- Password Field -->
          <div class="space-y-2">
            <Label for="password" class="text-sm font-medium text-gray-700">
              Password
            </Label>
            <div class="relative">
              <Input
                id="password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Create a password"
                class="w-full pr-10"
                :class="errors.password ? 'border-red-500 focus-visible:ring-red-500' : ''"
                required
              />
              <Button
                type="button"
                variant="ghost"
                size="sm"
                class="absolute right-0 top-0 h-full px-3 py-2 hover:bg-transparent"
                @click="showPassword = !showPassword"
              >
                <svg
                  v-if="showPassword"
                  class="w-4 h-4 text-gray-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg
                  v-else
                  class="w-4 h-4 text-gray-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
              </Button>
            </div>
            <p v-if="errors.password" class="text-sm text-red-600 flex items-center gap-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              {{ errors.password }}
            </p>
            <p class="text-xs text-gray-500">
              Must be at least 8 characters with uppercase, lowercase, and number
            </p>
          </div>

          <!-- Confirm Password Field -->
          <div class="space-y-2">
            <Label for="confirmPassword" class="text-sm font-medium text-gray-700">
              Confirm Password
            </Label>
            <Input
              id="confirmPassword"
              v-model="form.confirmPassword"
              type="password"
              placeholder="Confirm your password"
              class="w-full"
              :class="errors.confirmPassword ? 'border-red-500 focus-visible:ring-red-500' : ''"
              required
            />
            <p v-if="errors.confirmPassword" class="text-sm text-red-600 flex items-center gap-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              {{ errors.confirmPassword }}
            </p>
          </div>

          <!-- Submit Button -->
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
            {{ loading ? 'Creating account...' : 'Create account' }}
          </Button>
        </form>
      </CardContent>

      <CardFooter>
        <p class="text-center text-sm text-gray-600 w-full">
          Already have an account?
          <Button variant="link" class="p-0 h-auto text-blue-600 hover:text-blue-800 ml-1" as-child>
            <router-link to="/login">
              Sign in
            </router-link>
          </Button>
        </p>
      </CardFooter>
    </Card>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { authService } from '@/service/AuthService'
import { email } from 'zod/v4'

const router = useRouter()

// Reactive data
const form = ref({
  username: '',
  email: '',
  password: '',
  confirmPassword: '',
})

const showPassword = ref(false)
const loading = ref(false)
const errors = ref({
  username: '',
  email: '',
  password: '',
  confirmPassword: '',
})

// Validation methods
const validateEmail = (email) => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

const validatePassword = (password) => {
  // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/
  return passwordRegex.test(password)
}

const validateForm = () => {
  errors.value = { 
    username: '', 
    email: '', 
    password: '', 
    confirmPassword: '',  
  }
  
  let isValid = true

  // Username validation
  if (!form.value.username.trim()) {
    errors.value.username = 'Username is required'
    isValid = false
  } else if (form.value.username.length < 3) {
    errors.value.username = 'Username must be at least 3 characters'
    isValid = false
  }

  // Email validation
  if (!form.value.email.trim()) {
    errors.value.email = 'Email is required'
    isValid = false
  } else if (!validateEmail(form.value.email)) {
    errors.value.email = 'Please enter a valid email address'
    isValid = false
  }

  // Password validation
  if (!form.value.password) {
    errors.value.password = 'Password is required'
    isValid = false
  } else if (!validatePassword(form.value.password)) {
    errors.value.password = 'Password must be at least 8 characters with uppercase, lowercase, and number'
    isValid = false
  }

  // Confirm password validation
  if (!form.value.confirmPassword) {
    errors.value.confirmPassword = 'Please confirm your password'
    isValid = false
  } else if (form.value.password !== form.value.confirmPassword) {
    errors.value.confirmPassword = 'Passwords do not match'
    isValid = false
  }

  return isValid
}

const handleSignup = async () => {
  if (!validateForm()) {
    return
  }

  loading.value = true

  try {
    
    const userData = {
      name: form.value.username,
      email: form.value.email,
      password: form.value.password
    }
    await authService.signup(userData);

    router.push('/login?message=account_created')
    
  } catch (error) {
    // Handle API errors
    if (error.response?.data?.message) {
      errors.value.email = error.response.data.message
    } else {
      errors.value.email = 'Signup failed. Please try again.'
    }
  } finally {
    loading.value = false
  }
}
</script>