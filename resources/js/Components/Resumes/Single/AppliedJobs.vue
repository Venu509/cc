<script setup>
import { computed } from 'vue';

const props = defineProps({
  appliedJobs: {
    type: Array, // Assuming it's an array of job objects
    required: true
  }
});

function formatDate(date) {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(date).toLocaleDateString(undefined, options);
}

const hasAppliedJobs = computed(() => props.appliedJobs && props.appliedJobs.length > 0);
</script>

<template>
  <div class="container mx-auto">
    <div v-if="hasAppliedJobs" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="job in appliedJobs" :key="job.data.id" class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-2">
          {{ job.data.title }}


        </h2>
        <p class="text-gray-600 mb-2">{{ job.data.company.name }}</p>
        <p class="text-gray-500 text-sm mb-4">{{ formatDate(job.data.createdAt) }}</p>

        <div class="mb-4">
          <p><strong>Location:</strong> {{ job.data.location }}</p>
          <p><strong>Salary:</strong> {{ job.data.salary }}</p>
          <p><strong>Category:</strong> {{ job.data.category.title }}</p>
        </div>

        <div class="mb-4">
          <p v-if="job.data.workModes.length">
            <strong>Work Modes:</strong>
            <ul class="list-disc list-inside">
              <li v-for="(workMode, index) in job.data.workModes" :key="index">{{ workMode }}</li>
            </ul>
          </p>
        </div>
        <p class="text-sm text-gray-500">
          <strong>Expiration Date:</strong> {{ formatDate(job.data.expireDate) }}
        </p>
      </div>
    </div>

    <div v-else>
      <p class="text-gray-600">No jobs have been applied for yet.</p>
    </div>
  </div>
</template>

<style>
/* Additional styles, if needed */
</style>
