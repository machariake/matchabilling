<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Welcome to Matcha Hotspot</div>
          <div class="card-body">
            <p>This is a comprehensive hotspot billing system with Mikrotik integration.</p>
            <p v-if="!isLoggedIn">
              <router-link to="/login" class="btn btn-primary me-2">Login</router-link>
              <router-link to="/register" class="btn btn-secondary">Register</router-link>
            </p>
            <p v-else>
              <router-link to="/dashboard" class="btn btn-primary">Go to Dashboard</router-link>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';

export default {
  name: 'HomeView',
  setup() {
    const isLoggedIn = ref(false);

    const checkAuthStatus = () => {
      const token = localStorage.getItem('access_token');
      isLoggedIn.value = !!token;
    };

    onMounted(() => {
      checkAuthStatus();
    });

    return {
      isLoggedIn
    };
  }
};
</script>