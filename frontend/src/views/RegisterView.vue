<template>
    <RouterLink to="/" class="nazad">Nazad</RouterLink>
  <div class="register-container">
    <form @submit.prevent="handleRegister" class="register-form">
      <h2>Create Account</h2>

      <div class="form-group">
        <label>First Name</label>
        <input v-model="form.first_name" type="text" required>
      </div>

      <div class="form-group">
        <label>Last Name</label>
        <input v-model="form.last_name" type="text" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input v-model="form.email" type="email" required>
      </div>

      <div class="form-group">
        <label>Password</label>
        <input v-model="form.password" type="password" required>
      </div>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Registering...' : 'Register' }}
      </button>

      <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
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
  first_name: '',
  last_name: '',
  email: '',
  password: '',
  permission_id: '3',
  occupation_id: '2'
});

const handleRegister = async () => {
  loading.value = true;
  errorMessage.value = '';

  try {
    const response = await axios.post(apiURL + '/register', form.value);
    
    localStorage.setItem('token', response.data.access_token);
    localStorage.setItem('userRole', form.value.permission_id);
    await router.push('/');
    window.location.reload()
  } catch (error) {
    if (error.response && error.response.data.errors) {
      errorMessage.value = Object.values(error.response.data.errors)[0][0];
    } else {
      errorMessage.value = 'Registration failed. Please try again.';
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.register-form {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
}
.form-group { margin-bottom: 15px; }
label { display: block; margin-bottom: 5px; }
input { width: 100%; padding: 8px; box-sizing: border-box; }
button { width: 100%; padding: 10px; background: #3498db; color: white; border: none; cursor: pointer; }
button:disabled { background: #95a5a6; }
.error { color: red; margin-top: 10px; font-size: 0.9rem; }
.nazad {
    display: inline-block;
    background: #2563eb;
    color: white;
    padding: 14px 26px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 500;
}
</style>