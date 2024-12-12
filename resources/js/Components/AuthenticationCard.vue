<template>
  <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 mx-5">
    <div>
      <slot name="logo"/>
    </div>

    <div class="mt-2">
      <slot name="pageTitle"/>
    </div>

    <div :class="[
        `w-full sm:${maxWidth} mt-6 px-6 py-4 overflow-hidden ${overrideClasses}`
    ]">
      <slot/>
    </div>

    <div class="mt-10 mb-10 md:mb-2">
      <slot name="additional"/>
    </div>
  </div>

  <Notification/>
</template>
<script setup>
import Notification from "@/Components/Notification.vue";

const props = defineProps({
  maxWidth: {
    type: String,
    default: 'max-w-md',
  },
  overrideClasses: {
    type: String,
    default: 'bg-white shadow-md sm:rounded-lg',
  },
});
</script>