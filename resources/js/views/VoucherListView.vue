<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1>Voucher Management</h1>
        
        <div class="card mb-4">
          <div class="card-header">
            <h5>Create New Vouchers</h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="createVouchers">
              <div class="row">
                <div class="col-md-4">
                  <label for="planSelect" class="form-label">Plan</label>
                  <select class="form-select" id="planSelect" v-model="selectedPlan" required>
                    <option value="">Select a plan</option>
                    <option v-for="plan in plans" :key="plan.id" :value="plan.id">
                      {{ plan.name }} ({{ plan.speed }} - {{ plan.price | currency }})
                    </option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="quantity" class="form-label">Quantity</label>
                  <input type="number" class="form-control" id="quantity" v-model="quantity" min="1" required>
                </div>
                <div class="col-md-4">
                  <label for="expiryDays" class="form-label">Expiry Days</label>
                  <input type="number" class="form-control" id="expiryDays" v-model="expiryDays" min="1" value="30">
                </div>
              </div>
              <button type="submit" class="btn btn-success mt-3">Generate Vouchers</button>
            </form>
          </div>
        </div>
        
        <div class="card">
          <div class="card-header">
            <h5>All Vouchers</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Code</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Expires At</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="voucher in vouchers" :key="voucher.id">
                    <td>{{ voucher.code }}</td>
                    <td>{{ voucher.plan ? voucher.plan.name : 'N/A' }}</td>
                    <td>
                      <span :class="{'badge': true, 'bg-success': voucher.is_used, 'bg-warning': !voucher.is_used}">
                        {{ voucher.is_used ? 'Used' : 'Available' }}
                      </span>
                    </td>
                    <td>{{ formatDate(voucher.expires_at) }}</td>
                    <td>
                      <button class="btn btn-sm btn-outline-danger" @click="deleteVoucher(voucher.id)">Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <div v-if="vouchers.length === 0" class="text-center py-4">
              <p>No vouchers found. Create some using the form above.</p>
            </div>
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
  name: 'VoucherListView',
  setup() {
    const vouchers = ref([]);
    const plans = ref([]);
    const selectedPlan = ref('');
    const quantity = ref(1);
    const expiryDays = ref(30);

    const fetchVouchers = async () => {
      try {
        const response = await axios.get('/api/vouchers', {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          }
        });
        vouchers.value = response.data;
      } catch (err) {
        console.error('Error fetching vouchers:', err);
      }
    };

    const fetchPlans = async () => {
      try {
        const response = await axios.get('/api/plans', {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          }
        });
        plans.value = response.data;
      } catch (err) {
        console.error('Error fetching plans:', err);
      }
    };

    const createVouchers = async () => {
      try {
        const response = await axios.post('/api/vouchers', {
          plan_id: selectedPlan.value,
          quantity: quantity.value,
          expiry_days: expiryDays.value
        }, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          }
        });

        // Refresh the list
        fetchVouchers();
        
        // Reset form
        quantity.value = 1;
        expiryDays.value = 30;
        
        alert('Vouchers created successfully!');
      } catch (err) {
        console.error('Error creating vouchers:', err);
        alert('Error creating vouchers: ' + (err.response?.data?.message || 'Unknown error'));
      }
    };

    const deleteVoucher = async (id) => {
      if (!confirm('Are you sure you want to delete this voucher?')) {
        return;
      }

      try {
        await axios.delete(`/api/vouchers/${id}`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          }
        });

        // Refresh the list
        fetchVouchers();
      } catch (err) {
        console.error('Error deleting voucher:', err);
        alert('Error deleting voucher: ' + (err.response?.data?.message || 'Unknown error'));
      }
    };

    const formatDate = (dateString) => {
      const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    };

    onMounted(async () => {
      await fetchVouchers();
      await fetchPlans();
    });

    return {
      vouchers,
      plans,
      selectedPlan,
      quantity,
      expiryDays,
      createVouchers,
      deleteVoucher,
      formatDate
    };
  }
};
</script>