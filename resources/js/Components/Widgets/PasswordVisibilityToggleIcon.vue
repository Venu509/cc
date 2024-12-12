<script setup>
import {EyeIcon, EyeOffIcon} from "@heroicons/vue/solid";
import {defineProps, defineEmits} from 'vue';

const props = defineProps({
  showPassword: Boolean,
  label: String
});

const emit = defineEmits(['toggle-visibility']);

const togglePasswordVisibility = () => {
  emit('toggle-visibility', props.label);
};
</script>

<template>
  <div class="absolute inset-y-1.5 right-0 pr-3 flex items-center text-sm leading-5">
    <EyeIcon
        v-if="showPassword"
        class="w-5 h-5 text-gray-700 cursor-pointer"
        @click="togglePasswordVisibility"
    />
    <EyeOffIcon
        v-if="!showPassword"
        class="w-5 h-5 text-gray-700 cursor-pointer"
        @click="togglePasswordVisibility"
    />
  </div>
</template>
