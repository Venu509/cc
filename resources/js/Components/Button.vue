<script setup>
import {computed, defineEmits, defineProps} from 'vue';

const emit = defineEmits(['click'])

const props = defineProps({
  disabled: {
    type: Boolean,
    default: false
  },
  btnColor: {
    type: String,
    default: 'blue'
  },
  btnSize: {
    type: String,
    default: 'sm'
  },
  isRounded: {
    type: Boolean,
    default: true
  },
  type: {
    type: String,
    default: 'button'
  },
  theme: {
    type: String,
    default: 'base'
  },
  additionalClass: {
    type: String,
    default: ''
  }
});

const sizeClass = computed(() => {
  const sizeMap = {
    xxm: 'px-3 py-1.5 text-xs font-medium text',
    xm: 'px-4 py-2 text-xs font-medium text',
    sm: 'px-4 py-2 text-sm font-medium',
    base: 'px-6 py-2.5 text-sm font-medium',
    lg: 'px-6 py-3 text-base font-medium',
    xl: 'px-7 py-3.5 text-base font-medium',
  };

  return sizeMap[props.btnSize] || 'px-4 py-2 text-sm font-medium';
});

const colorClass = computed(() => {
  const colorMap = {
    blue: 'bg-secondary-700 text-white hover:bg-secondary-800 focus:ring-4 focus:ring-secondary-300 dark:bg-secondary-600 dark:hover:bg-secondary-700 dark:focus:ring-secondary-900',
    red: 'bg-red-700 text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900',
    green: 'bg-green-700 text-white hover:bg-green-800 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900',
    dark: 'bg-gray-800 text-white hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700',
    light: 'text-black bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700',
    purple: 'bg-purple-700 text-white hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900',
    yellow: 'bg-yellow-400 text-white hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 dark:focus:ring-yellow-900',
  };

  return colorMap[props.btnColor] || 'bg-secondary-700 hover:bg-secondary-800 focus:ring-4 focus:ring-secondary-300 dark:bg-secondary-600 dark:hover:bg-secondary-700 dark:focus:ring-secondary-900';
});

const handleClick = () => {
  emit('click')
};

</script>

<template>
  <div class="flex-none">
    <button
        :disabled="disabled"
        :type="type"
        :class="[
        `text-center ${additionalClass} ${theme === 'block' ? 'w-full' : ''} ${sizeClass} ${colorClass} ${isRounded ? 'rounded-full' : ''} ${disabled ? 'opacity-50 cursor-not-allowed' : ''}`,
          $attrs.class
        ]"
        @click="handleClick">
      <slot/>
    </button>
  </div>
</template>

<style scoped>
</style>
