<template>
  <div>
    <header class="header">
    <h1>SUMIT</h1>
    <nav class="auth-nav">
      <template v-if="!isLoggedIn">
        <router-link to="/login" class="btn">Login</router-link>
        <router-link to="/register" class="btn">Register</router-link>
      </template>

      <template v-else>
        <button @click="handleLogout" class="btn btn-logout">Logout</button>
      </template>
    </nav>
  </header>

    <main>
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const token = ref(localStorage.getItem('token'));
const isLoggedIn = computed(() => !!token.value);

const handleLogout = async () => {
  try {
    await axios.post('/api/logout', {
      headers: { Authorization: `Bearer ${token.value}` }
    });
  } catch (error) {
    console.error("Logout failed", error);
  } finally {
    localStorage.removeItem('token')
    localStorage.removeItem('userRole')
    window.location.reload()
    token.value = null
  }
};
</script>

<style>
body {
  margin: 0;
  font-family: 'Inter', Arial, sans-serif;
  background-color: #f1f5f9;
  color: #0f172a;
}
main {
  padding: 40px;
  max-width: 1200px;
  margin: auto;
}
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background-color: #0f172a;
  color: white;
}
.btn {
  margin-left: 10px;
  padding: 5px 15px;
  text-decoration: none;
  color: white;
  border: 1px solid white;
  border-radius: 4px;
}
.btn-logout {
  background-color: #e74c3c;
  border: none;
  cursor: pointer;
}
</style>


