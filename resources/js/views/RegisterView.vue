<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">Register</div>
          <div class="card-body">
            <form @submit.prevent="register">
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" v-model="name" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" v-model="email" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" v-model="password" required>
              </div>
              <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" v-model="passwordConfirmation" required>
              </div>
              <button type="submit" class="btn btn-primary">Register</button>
            </form>
            <div v-if="error" class="alert alert-danger mt-3">
              {{ error }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

export default {
  name: 'RegisterView',
  setup() {
    const name = ref('');
    const email = ref('');
    const password = ref('');
    const passwordConfirmation = ref('');
    const error = ref('');
    const router = useRouter();

    const register = async () => {
      try {
        if (password.value !== passwordConfirmation.value) {
          error.value = 'Passwords do not match';
          return;
        }

        const response = await axios.post('/api/register', {
          name: name.value,
          email: email.value,
          password: password.value,
          password_confirmation: passwordConfirmation.value
        });

        // Store the token if auto-login is enabled
        if (response.data.access_token) {
          localStorage.setItem('access_token', response.data.access_token);
          router.push('/dashboard');
        } else {
          // Show success message and redirect to login
          alert('Registration successful. Please log in.');
          router.push('/login');
        }
      } catch (err) {
        error.value = err.response?.data?.message || 'An error occurred during registration';
      }
    };

    return {
      name,
      email,
      password,
      passwordConfirmation,
      error,
      register
    };
  }
};
</script>