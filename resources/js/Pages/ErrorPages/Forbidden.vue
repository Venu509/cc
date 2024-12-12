<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="text-center">
      <div class="flex items-center justify-center space-x-2">
        <h1 class="text-6xl font-bold text-primary-600">403</h1>
        <EmojiSadIcon class="h-16 w-16 text-yellow-400"/>
      </div>
      <p class="text-2xl mt-4 text-gray-700">Forbidden</p>
      <p class="text-lg mt-2 text-gray-500">Sorry, you don't have permission to access this page.</p>
      <div class="mt-6">
        <button @click="goBack" class="text-secondary-600 hover:text-secondary-500 text-lg font-semibold">
          Go Back to Previous Page
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { EmojiSadIcon } from "@heroicons/vue/solid";
const goBack = () => {
  window.history.back();
};
</script>
