<template>
  <div class="p-4 bg-white shadow-md rounded-md mb-4">
    <div class="flex justify-between items-center">
      <div>
        <h3 class="text-lg font-semibold">{{ job.title }}</h3>
        <p class="text-sm text-gray-600">{{ job.company }}</p>
        <p class="text-sm text-gray-600">{{ job.category }}</p>
      </div>
      <div>
        <button class="text-orange-500 font-semibold">Apply</button>
      </div>
    </div>
    <div class="flex items-center text-sm text-gray-600 mt-2">
      <span class="mr-4">{{ job.type }}</span>
      <span>{{ job.location }}</span>
    </div>
    <p class="text-sm text-gray-400 mt-2">Posted on {{ job.postedDate }}</p>
  </div>
</template>

<script setup>
defineProps({
  job: Object
});
</script>
