// router/index.js
import { createRouter, createWebHistory } from 'vue-router'
import Signup from "../Pages/Auth/Signup.vue"
import DashboardLayout from "../Pages/Dahboard/DashboardLayout.vue"
import Login from "../Pages/Auth/Login.vue"

const routes = [
  // ... other routes
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { requiresGuest: true }
  },
  {
    path: '/signup',
    name: 'Signup',
    component: Signup,
    meta: { requiresGuest: true }
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: DashboardLayout,
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})


// Global beforeEach guard
// Simple midleware to restrict access
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('authToken')

  // Check if route requires auth
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login')
  }
  // Redirect authenticated users away from auth pages
  else if (to.meta.requiresGuest && isAuthenticated) {
    next('/dashboard')
  } else {
    next()
  }
})

export default router