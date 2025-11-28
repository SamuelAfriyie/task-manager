import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useUserStore = defineStore('user', () => {
    const userData = ref(null);
    const isAuthenticated = ref(false);

    function setUser(user: any) {
        userData.value = user;
        isAuthenticated.value = !!user; // true if user object exists, false otherwise
    }

    function clearUser() {
        userData.value = null;
        isAuthenticated.value = false;
    }

    return { userData, isAuthenticated, setUser, clearUser };
}, { persist: true } as any);