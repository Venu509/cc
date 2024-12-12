<template>
  <div class="bg-white shadow-md shadow-gray-400 rounded-lg p-4">
    <div class="grid gap-3 px-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-5">
      <div class="col-span-2 sm:col-span-1 lg:col-span-2 relative">
        <TagInput
            v-model="keywordTags"
            placeholder="Job title, company or keywords"
            class="relative w-full"
            required
        >
          <template #icon>
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <SearchIcon class="h-6 w-6 text-secondary-500" />
            </div>
          </template>
        </TagInput>
      </div>

      <div class="col-span-2 sm:col-span-1 lg:col-span-2 relative">
        <TagInput
            v-model="locationTags"
            placeholder="Search for locations"
            class="relative w-full"
            required
        >
          <template #icon>
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <LocationMarkerIcon class="h-6 w-6 text-secondary-500" />
            </div>
          </template>
        </TagInput>
      </div>

      <div class="col-span-1 sm:col-span-2 lg:col-span-1">
        <Button
            class="mr-3 w-full rounded-xl"
            theme="block"
            @click="triggerSearch"
            additional-class="py-3"
        >
          {{ buttonText }}
        </Button>
      </div>
    </div>

    <div class="my-2 border-t border-gray-300"></div>

    <div class="flex flex-col md:flex-row items-center px-4">
      <div class="flex flex-wrap items-center gap-2 grow">
        <FilterDropdown
            v-for="(filter, index) in filters"
            :key="index"
            :filter="filter"
            :index="filter.index"
            :selectedFilters="selectedFilters"
            :isOpen="openDropdown === filter.index"
            @toggle="toggleDropdown"
            @update-filter="updateSelectedFilters"
        />
      </div>

      <!-- Clear Filters -->
      <span
          v-if="hasFilter"
          class="mt-2 ml-auto text-sm text-gray-700 cursor-pointer md:mt-0 hover:text-gray-900"
          @click="clearFilters"
      >
        Clear
      </span>
    </div>
  </div>
</template>

<script setup>
import { LocationMarkerIcon, SearchIcon } from "@heroicons/vue/outline";
import Button from "@/Components/Button.vue";
import FilterDropdown from "@/Components/Global/FilterDropdown.vue";
import { computed, ref, onMounted } from 'vue';
import TagInput from "@/Components/TagInput.vue";

const props = defineProps({
  filters: {
    type: [Array, Object],
    required: true,
  },
  isSearching: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['onSearch']);

const keywordTags = ref([]);
const locationTags = ref([]);
const selectedFilters = ref({});
const openDropdown = ref(null);

const buttonText = computed(() => {
  return props.isSearching ? 'Searching ....' : 'Search';
});

const hasFilter = computed(() => {
  return (
      keywordTags.value.length > 0 ||
      locationTags.value.length > 0 ||
      Object.values(selectedFilters.value).some(filterArray => filterArray.length > 0)
  );
});

const clearFilters = () => {
  keywordTags.value = [];
  locationTags.value = [];
  selectedFilters.value = {};
  triggerSearch();
};

const updateSelectedFilters = ({ index, payload }) => {
  if (!selectedFilters.value[index]) {
    selectedFilters.value[index] = [];
  }

  const filterIndex = selectedFilters.value[index].findIndex(
      (item) => item.value === payload.item.value
  );

  if (payload.checked) {
    if (filterIndex === -1) {
      selectedFilters.value[index].push(payload.item);
    }
  } else {
    if (filterIndex !== -1) {
      selectedFilters.value[index].splice(filterIndex, 1);
    }
  }
};

const toggleDropdown = (index) => {
  openDropdown.value = openDropdown.value === index ? null : index;
};

const triggerSearch = () => {
  const params = {};

  if (keywordTags.value.length) {
    params.keywords = keywordTags.value;
  }

  if (locationTags.value.length) {
    params.locations = locationTags.value;
  }

  Object.keys(selectedFilters.value).forEach((filterKey) => {
    params[filterKey] = selectedFilters.value[filterKey].map(filterItem => filterItem.value);
  });

  emit('onSearch', params);
};

onMounted(() => {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);

  keywordTags.value = urlParams.getAll('keywords[]') ?? [];
  locationTags.value = urlParams.getAll('locations[]') ?? [];

  Object.keys(props.filters).forEach((filterKey) => {
    const filter = props.filters[filterKey];
    const filterValues = urlParams.getAll(`${filter.index}[]`) ?? [];
    if (filterValues.length > 0) {
      selectedFilters.value[filter.index] = filterValues.map(value => ({
        label: value,
        value: value,
      }));
    }
  });
});
</script>
