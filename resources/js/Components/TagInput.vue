<template>
  <div class="relative w-full">
    <!-- Container for icons and input field -->
    <div class="relative">
      <slot name="icon" class="absolute inset-y-0 left-0 flex items-center pl-3"></slot>

      <input
          type="text"
          v-model="newTag"
          @keydown.enter.prevent="addTag"
          @keydown.comma.prevent="addTag"
          @blur="addTag"
          :placeholder="placeholder"
          class="w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 bg-secondary-100 rounded-xl focus:ring-blue-500 focus:border-blue-500"
      />
    </div>

    <!-- Tags display area -->
    <div class="mt-2 flex flex-wrap space-x-2">
      <span
          v-for="(tag, index) in tags"
          :key="index"
          class="flex items-center bg-secondary-200 text-gray-800 text-sm font-medium px-2.5 py-0.5 rounded mb-1"
      >
        {{ tag }}
        <button @click="removeTag(index)" class="ml-2 text-gray-600 hover:text-gray-900">
          &times;
        </button>
      </span>
    </div>

    <!-- Optional error message -->
    <p v-if="errorMessage" class="text-red-400 text-xm mt-2">{{ errorMessage }}</p>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  modelValue: {
    type: Array,
    required: true,
  },
  placeholder: {
    type: String,
    default: ''
  },
});

const emit = defineEmits(['update:modelValue']);

const newTag = ref('');
const tags = ref([...props.modelValue]);
const errorMessage = ref('');

const addTag = () => {
  const trimmedTag = newTag.value.trim();

  if (trimmedTag === '') return;

  if (tags.value.includes(trimmedTag)) {
    errorMessage.value = 'This tag already exists.';
    return;
  }

  tags.value.push(trimmedTag);
  newTag.value = '';
  errorMessage.value = '';
  emit('update:modelValue', tags.value);
};

const removeTag = (index) => {
  tags.value.splice(index, 1);
  emit('update:modelValue', tags.value);
  errorMessage.value = '';
};

// Watch for external updates to modelValue
watch(() => props.modelValue, (newValue) => {
  tags.value = [...newValue];
}, { immediate: true });
</script>

<style scoped>
input {
  padding-left: 2.5rem; /* Adjust padding to make space for the icons */
}
</style>
