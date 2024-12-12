<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="text-center">
      <div class="flex items-center justify-center space-x-2">
        <h1 class="text-6xl font-bold text-primary-600">{{ status }}</h1>
        <EmojiSadIcon class="h-16 w-16 text-yellow-400"/>
      </div>
      <p class="text-2xl mt-4 text-gray-700">Something went wrong</p>
      <p class="text-lg mt-2 text-gray-500">We're sorry, but something went wrong. Please try again later.</p>
      <button @click="goBack" class="mt-4 px-4 py-2 bg-secondary-600 text-white rounded-lg">
        Go Back
      </button>
    </div>
  </div>
</template>

<script setup>
import { EmojiSadIcon } from "@heroicons/vue/solid";
const goBack = () => {
  window.history.back();
};

defineProps({
  status: Number
});
</script>
