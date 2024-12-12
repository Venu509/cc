<script setup>
const props = defineProps({
  item: {
    type: Object,
    required: true,
  },
  isChecked: {
    type: Boolean,
    default: false,
  }
});

const emit = defineEmits(['toggle-filter']);

const toggleCheckbox = () => {
  emit('toggle-filter', { item: props.item, checked: !props.isChecked });
};
</script>

<template>
  <label :for="`checkbox-item-${item.value}`" class="block px-4 py-2 text-xs text-gray-700"
         @click="toggleCheckbox"
  >
    <input type="checkbox"
           :id="`checkbox-item-${item.value}`"
           :value="item.value"
           :checked="isChecked"
           class="mr-2"
    > {{ item.label }}
  </label>

<!--  <li-->
<!--      @click="toggleCheckbox"-->
<!--      :class="{ 'bg-gray-100 dark:bg-gray-600': isChecked }"-->
<!--      class="cursor-pointer"-->
<!--  >-->
<!--    <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">-->
<!--      <input-->
<!--          :id="`checkbox-item-${item.value}`"-->
<!--          type="checkbox"-->
<!--          :value="item.value"-->
<!--          :checked="isChecked"-->
<!--          class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"-->
<!--      />-->
<!--      <label-->
<!--          :for="`checkbox-item-${item.value}`"-->
<!--          class="w-full ms-2 font-medium text-xs text-gray-900 rounded dark:text-gray-300"-->
<!--      >{{ item.label }}</label>-->
<!--    </div>-->
<!--  </li>-->
</template>
