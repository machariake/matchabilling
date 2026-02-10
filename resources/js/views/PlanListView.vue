<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1>Plan Management</h1>
        
        <div class="card mb-4">
          <div class="card-header">
            <h5>Create New Plan</h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="createPlan">
              <div class="row">
                <div class="col-md-6">
                  <label for="planName" class="form-label">Plan Name</label>
                  <input type="text" class="form-control" id="planName" v-model="newPlan.name" required>
                </div>
                <div class="col-md-6">
                  <label for="planSpeed" class="form-label">Speed</label>
                  <input type="text" class="form-control" id="planSpeed" v-model="newPlan.speed" required>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-md-6">
                  <label for="validityPeriod" class="form-label">Validity Period (days)</label>
                  <input type="number" class="form-control" id="validityPeriod" v-model="newPlan.validity_period" min="1" required>
                </div>
                <div class="col-md-6">
                  <label for="planPrice" class="form-label">Price</label>
                  <input type="number" class="form-control" id="planPrice" v-model="newPlan.price" min="0" step="0.01" required>
                </div>
              </div>
              <div class="mt-3">
                <label for="planDescription" class="form-label">Description</label>
                <textarea class="form-control" id="planDescription" v-model="newPlan.description" rows="3"></textarea>
              </div>
              <button type="submit" class="btn btn-success mt-3">Create Plan</button>
            </form>
          </div>
        </div>
        
        <div class="card">
          <div class="card-header">
            <h5>All Plans</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Speed</th>
                    <th>Validity</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="plan in plans" :key="plan.id">
                    <td>{{ plan.name }}</td>
                    <td>{{ plan.speed }}</td>
                    <td>{{ plan.validity_period }} days</td>
                    <td>{{ plan.price | currency }}</td>
                    <td>{{ plan.description }}</td>
                    <td>
                      <button class="btn btn-sm btn-outline-primary me-2" @click="editPlan(plan)">Edit</button>
                      <button class="btn btn-sm btn-outline-danger" @click="deletePlan(plan.id)">Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <div v-if="plans.length === 0" class="text-center py-4">
              <p>No plans found. Create one using the form above.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Edit Plan Modal -->
    <div class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editPlanModalLabel">Edit Plan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label for="editPlanName" class="form-label">Plan Name</label>
                <input type="text" class="form-control" id="editPlanName" v-model="editingPlan.name" required>
              </div>
              <div class="mb-3">
                <label for="editPlanSpeed" class="form-label">Speed</label>
                <input type="text" class="form-control" id="editPlanSpeed" v-model="editingPlan.speed" required>
              </div>
              <div class="mb-3">
                <label for="editValidityPeriod" class="form-label">Validity Period (days)</label>
                <input type="number" class="form-control" id="editValidityPeriod" v-model="editingPlan.validity_period" min="1" required>
              </div>
              <div class="mb-3">
                <label for="editPlanPrice" class="form-label">Price</label>
                <input type="number" class="form-control" id="editPlanPrice" v-model="editingPlan.price" min="0" step="0.01" required>
              </div>
              <div class="mb-3">
                <label for="editPlanDescription" class="form-label">Description</label>
                <textarea class="form-control" id="editPlanDescription" v-model="editingPlan.description" rows="3"></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="updatePlan">Save Changes</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Modal } from 'bootstrap';

export default {
  name: 'PlanListView',
  setup() {
    const plans = ref([]);
    const newPlan = ref({
      name: '',
      speed: '',
      validity_period: 30,
      price: 0,
      description: ''
    });
    const editingPlan = ref({});
    let editModal = null;

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

    const createPlan = async () => {
      try {
        await axios.post('/api/plans', newPlan.value, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          }
        });

        // Reset form
        newPlan.value = {
          name: '',
          speed: '',
          validity_period: 30,
          price: 0,
          description: ''
        };

        // Refresh the list
        fetchPlans();
      } catch (err) {
        console.error('Error creating plan:', err);
        alert('Error creating plan: ' + (err.response?.data?.message || 'Unknown error'));
      }
    };

    const editPlan = (plan) => {
      editingPlan.value = { ...plan };
      editModal.show();
    };

    const updatePlan = async () => {
      try {
        await axios.put(`/api/plans/${editingPlan.value.id}`, editingPlan.value, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          }
        });

        // Hide modal
        editModal.hide();

        // Refresh the list
        fetchPlans();
      } catch (err) {
        console.error('Error updating plan:', err);
        alert('Error updating plan: ' + (err.response?.data?.message || 'Unknown error'));
      }
    };

    const deletePlan = async (id) => {
      if (!confirm('Are you sure you want to delete this plan?')) {
        return;
      }

      try {
        await axios.delete(`/api/plans/${id}`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          }
        });

        // Refresh the list
        fetchPlans();
      } catch (err) {
        console.error('Error deleting plan:', err);
        alert('Error deleting plan: ' + (err.response?.data?.message || 'Unknown error'));
      }
    };

    onMounted(async () => {
      await fetchPlans();
      
      // Initialize bootstrap modal
      editModal = new Modal(document.getElementById('editPlanModal'));
    });

    return {
      plans,
      newPlan,
      editingPlan,
      createPlan,
      editPlan,
      updatePlan,
      deletePlan
    };
  }
};
</script>