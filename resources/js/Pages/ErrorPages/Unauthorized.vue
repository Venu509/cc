<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="text-center">
      <div class="flex items-center justify-center space-x-2">
        <h1 class="text-6xl font-bold text-primary-600">401</h1>
        <EmojiSadIcon class="h-16 w-16 text-yellow-400"/>
      </div>
      <p class="text-2xl mt-4 text-gray-700">Unauthorized</p>
      <p class="text-lg mt-2 text-gray-500">Sorry, you are not authorized to access this page.</p>
      <p class="text-lg mt-4 text-gray-500 animate-pulse">Redirecting to login page...</p>
    </div>
  </div>
</template>

<script setup>
import { EmojiSadIcon } from "@heroicons/vue/solid";

setTimeout(() => {
  window.location.href = '/login';
}, 5000);
</script>
