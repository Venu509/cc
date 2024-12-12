<script setup>

import {Inertia} from "@inertiajs/inertia";

let props = defineProps({
  item: {
    type: Object,
    required: true,
  },
  displayName: {
    type: String,
    default: 'name',
  },
  route: {
    type: String,
    required: true,
  },
  color: {
    type: String,
    default: 'green'
  },
});

</script>

<template>
  <span
      :class="`bg-${color}-100 text-${color}-800 text-xs cursor-pointer font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-${color}-900 dark:text-${color}-300`"
      @click="Inertia.get(route)"
  >
    {{ item[displayName]}}
  </span>
</template>