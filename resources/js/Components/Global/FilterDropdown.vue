<template>
<!--  <div>-->
<!--    <div class="flex flex-col space-x-2 mx-1 mt-4 md:mt-0">-->
<!--      <Button-->
<!--          :id="`${index}-button`"-->
<!--          :data-dropdown-toggle="index"-->
<!--          type="button"-->
<!--          btn-size="xxm"-->
<!--          btn-color="yellow"-->
<!--          class="flex items-center justify-between"-->
<!--          @click="handleToggleDropdown"-->
<!--      >-->
<!--        {{ filter.label }}-->
<!--        <ArrowDownIcon class="w-2.5 h-2.5 ms-3"/>-->
<!--      </Button>-->
<!--    </div>-->

<!--    <div v-show="isOpen" class="relative">-->
<!--      <FilterItems-->
<!--          :filter="filter"-->
<!--          :index="index"-->
<!--          @update-filter="updateFilter"-->
<!--          :selectedFilters="selectedFilters"-->
<!--      />-->
<!--    </div>-->
<!--  </div>-->


  <div class="relative inline-block text-left rounded-full" ref="dropdown">
    <input type="checkbox" v-model="isDropdownOpen" :id="`${index}-toggle-dropdown`" class="peer hidden" />

    <label :for="`${index}-toggle-dropdown`" class="inline-flex justify-center w-full rounded-full border border-gray-300 shadow-sm px-4 py-2 bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 cursor-pointer">
      {{ filter.label }}

      <ArrowDownIcon class="-mr-1 ml-2 h-4 w-4"/>
    </label>

    <div v-if="isDropdownOpen" class="origin-top-right absolute -right-20 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
      <FilterItems
          :filter="filter"
          :index="index"
          @update-filter="updateFilter"
          :selectedFilters="selectedFilters"
      />
    </div>
  </div>
</template>

<script setup>
import { ArrowDownIcon } from "@heroicons/vue/solid";
import FilterItems from "@/Components/Global/FilterItems.vue";
import { ref, onMounted, onBeforeUnmount } from 'vue';

let props = defineProps({
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
  isOpen: {
    type: Boolean,
    default: false,
  },
});

let emit = defineEmits(['update-filter', 'toggle']);

const handleToggleDropdown = () => {
  emit('toggle', props.index);
};

const updateFilter = (payload) => {
  emit('update-filter', { index: props.index, payload });
};

const isDropdownOpen = ref(false);

const dropdown = ref(null);

const handleClickOutside = (event) => {
  if (dropdown.value && !dropdown.value.contains(event.target)) {
    isDropdownOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
