<template>
  <div class="login-container">
    <form @submit.prevent="handleLogin" class="login-form">
      <h2>Login</h2>

      <div class="form-group">
        <label>Email</label>
        <input v-model="form.email" type="email" required placeholder="email">
      </div>

      <div class="form-group">
        <label>Password</label>
        <input v-model="form.password" type="password" required placeholder="password">
      </div>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Authenticating...' : 'Login' }}
      </button>

      <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
      
      <p class="switch-auth">
        Don't have an account? <router-link to="/register">Register here</router-link>
      </p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const apiURL = import.meta.env.VITE_API

const router = useRouter();
const loading = ref(false);
const errorMessage = ref('');

const form = ref({
  email: '',
  password: ''
});

const handleLogin = async () => {
  loading.value = true;
  errorMessage.value = '';

  try {
    const response = await axios.post(apiURL + '/login', form.value);
    
    localStorage.setItem('token', response.data.access_token);
    localStorage.setItem('userRole', response.data.permission_id)
    
    if (response.data.access_token) {
        await router.push('/');
        window.location.reload()
    }
  } catch (error) {
    console.error("Auth Error:", error);
    
    if (error.response) {
      errorMessage.value = error.response.data.message || 'Invalid credentials.';
    } else {
      errorMessage.value = 'Connection to server failed.';
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.login-form {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background: white;
}
.form-group { margin-bottom: 15px; }
label { display: block; margin-bottom: 5px; font-weight: bold; }
input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
button { width: 100%; padding: 10px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
button:disabled { background: #bdc3c7; }
.error { color: #e74c3c; margin-top: 10px; text-align: center; }
.switch-auth { margin-top: 15px; text-align: center; font-size: 0.9rem; }
</style>