<script setup>
import {computed} from "vue";

let props = defineProps({
  title: {
    type: String,
    required: true,
  },
  color: {
    type: String,
    required: true,
  }
});

const colorClass = computed(() => {
  const colorMap = {
    green: 'bg-green-100 text-green-800 border-green-400',
    yellow: 'bg-yellow-100 text-yellow-800 border-yellow-400',
    indigo: 'bg-indigo-100 text-indigo-800 border-indigo-400',
    purple: 'bg-purple-100 text-purple-800 border-purple-400',
    dark: 'bg-gray-800 text-gray-50 border-gray-900',
  };

  return colorMap[props.color] || 'bg-gray-100 text-gray-800 border-gray-400';
});

</script>

<template>
<span
    :class="[
        `text-xs font-medium px-2.5 py-0.5 rounded-full border`,
          $attrs.class,
          colorClass
        ]"
>
          {{ title }}
</span>

  <br/>
  <div class="my-2"></div>
</template>

<style scoped>

</style>