// router/index.js
import { createRouter, createWebHistory } from 'vue-router'
import Signup from "../Pages/Signup.vue"
import Login from "../Pages/Login.vue"

const routes = [
    // ... other routes
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/signup',
        name: 'Signup',
        component: Signup
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})


// Global beforeEach guard
// router.beforeEach((to, from, next) => {
//   const isAuthenticated = !!localStorage.getItem('authToken')
  
//   // Check if route requires auth
//   if (to.meta.requiresAuth && !isAuthenticated) {
//     next('/login')
//   } 
//   // Redirect authenticated users away from auth pages
//   else if (to.meta.requiresGuest && isAuthenticated) {
//     next('/dashboard')
//   } else {
//     next()
//   }
// })

export default router