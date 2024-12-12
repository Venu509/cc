<script setup>
import FilterItem from "@/Components/Global/FilterItem.vue";

const props = defineProps({
  filter: {
    type: Object,
    required: true,
  },
  index: {
    type: String,
    required: true,
  },
  selectedFilters: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['update-filter']);

const handleToggleFilter = (payload) => {
  emit('update-filter', payload);
};
</script>

<template>
  <div class="py-1">
    <FilterItem
        v-for="(item, key) in filter.value"
        :key="key"
        :item="item"
        :isChecked="selectedFilters[index]?.some(filterItem => filterItem.value === item.value) || false"
        @toggle-filter="handleToggleFilter"
    />
  </div>

<!--  <div-->
<!--      :id="index"-->
<!--      class="z-10 w-auto bg-white rounded-lg shadow dark:bg-gray-700"-->
<!--  >-->
<!--    <ul-->
<!--        class="space-y-1 text-sm text-gray-700 dark:text-gray-200"-->
<!--        :aria-labelledby="`${index}-button`"-->
<!--    >-->
<!--      <FilterItem-->
<!--          v-for="(item, key) in filter.value"-->
<!--          :key="key"-->
<!--          :item="item"-->
<!--          :isChecked="selectedFilters[index]?.some(filterItem => filterItem.value === item.value) || false"-->
<!--          @toggle-filter="handleToggleFilter"-->
<!--      />-->
<!--    </ul>-->
<!--  </div>-->
</template>
