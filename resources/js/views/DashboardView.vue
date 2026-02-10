<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1>Dashboard</h1>
        <p>Welcome back, {{ user.name }}!</p>
      </div>
    </div>
    
    <div class="row mt-4">
      <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
          <div class="card-body">
            <h5 class="card-title">Total Plans</h5>
            <h3>{{ stats.totalPlans }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
          <div class="card-body">
            <h5 class="card-title">Total Vouchers</h5>
            <h3>{{ stats.totalVouchers }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
          <div class="card-body">
            <h5 class="card-title">Active Users</h5>
            <h3>{{ stats.activeUsers }}</h3>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h5>Quick Actions</h5>
          </div>
          <div class="card-body">
            <router-link to="/vouchers" class="btn btn-primary me-2">Manage Vouchers</router-link>
            <router-link to="/plans" class="btn btn-secondary me-2">Manage Plans</router-link>
            <button class="btn btn-success" @click="generateVouchers">Generate New Vouchers</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'DashboardView',
  setup() {
    const user = ref({});
    const stats = ref({
      totalPlans: 0,
      totalVouchers: 0,
      activeUsers: 0
    });

    const fetchUserData = async () => {
      try {
        const response = await axios.get('/api/user', {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          }
        });
        user.value = response.data;
      } catch (err) {
        console.error('Error fetching user data:', err);
      }
    };

    const fetchStats = async () => {
      try {
        // In a real application, you would fetch these from an API
        // For now, we'll use mock data
        stats.value = {
          totalPlans: 5,
          totalVouchers: 42,
          activeUsers: 12
        };
      } catch (err) {
        console.error('Error fetching stats:', err);
      }
    };

    const generateVouchers = () => {
      // This would normally open a modal to generate vouchers
      alert('Voucher generation functionality would go here');
    };

    onMounted(async () => {
      await fetchUserData();
      await fetchStats();
    });

    return {
      user,
      stats,
      generateVouchers
    };
  }
};
</script>