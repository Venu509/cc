<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  modelValue: String,
  items: {
    type: Array,
    required: true,
  },
  index: {
    type: String,
    default: 'radio-unique',
  },
});

const emit = defineEmits(['update:modelValue']);

const updateValue = (value) => {
  emit('update:modelValue', value);
};
</script>

<template>
  <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-slate-300 rounded-xl sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    <li v-for="(item, key) in items" :key="item.value"
        :class="[
          'w-full border-b border-slate-300 sm:border-b-0 dark:border-gray-600',
          key !== items.length - 1 ? 'sm:border-r' : ''
        ]">
      <div class="flex items-center ps-3">
        <input
            :id="`horizontal-list-radio-${item.value}-${index}-${item.name}`"
            type="radio"
            :value="item.value"
            :name="item.name"
            class="w-4 h-4 text-secondary-600 bg-gray-100 border-gray-300 focus:ring-secondary-500 dark:focus:ring-secondary-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
            :checked="modelValue === item.value || item.checked"
            @change="updateValue(item.value)"
        >
        <label :for="`horizontal-list-radio-${item.value}-${index}-${item.name}`" class="w-full py-2.5 ms-2 text-sm sm:text-xs font-medium text-gray-900 dark:text-gray-300">{{ item.label }}</label>
      </div>
    </li>
  </ul>
</template>

<style scoped>
/* Your custom styles here */
</style>
