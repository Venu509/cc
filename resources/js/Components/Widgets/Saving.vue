<script setup>

let props = defineProps({
  message: {
    type: String,
    default: 'Saving ....',
  },
});
</script>

<template>
  <div class="flex items-center justify-center w-full h-[calc(100vh-400px)] border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700 my-4">
    <div class="px-3 py-1 text-xs font-medium leading-none text-center text-secondary-800 bg-secondary-200 rounded-full animate-pulse dark:bg-secondary-900 dark:text-secondary-200">
      {{ message }}</div>
  </div>
</template>

<style scoped>

</style>