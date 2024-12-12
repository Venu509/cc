<template>
    <div class="bg-white shadow-md shadow-gray-400 rounded-lg p-4">
        <div class="grid gap-1 px-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-10">
            <div class="col-span-3 relative">
                <TagInput
                    v-model="keywordTags"
                    placeholder="Job title or keywords"
                    class="relative w-full"
                    required
                >
                    <template #icon>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <SearchIcon class="h-4 w-4 text-gray-500" />
                        </div>
                    </template>
                </TagInput>
            </div>

            <div class="col-span-3 relative">
                <TagInput
                    v-model="locationTags"
                    placeholder="Anywhere"
                    class="relative w-full"
                    required
                >
                    <template #icon>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <LocationMarkerIcon class="h-4 w-4 text-gray-500" />
                        </div>
                    </template>
                </TagInput>
            </div>

            <div class="col-span-2 relative">
                <Input
                    id="postedDate"
                    v-model="postedDate"
                    autocomplete="postedDate"
                    name="postedDate"
                    type="date"
                    :max="maxDate()"
                    placeholder="Job posted date"
                    class="mt-0 py-3 text-sm text-gray-900 border border-gray-300 bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                />
            </div>

            <div class="col-span-2">
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

    <div class="flex flex-col md:flex-row items-center px-4 ">
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
      <div class="col-span-2 ml-5">
          <span
              class="ml-auto mt-2 md:mt-0 text-sm text-gray-700 cursor-pointer hover:text-gray-900"
          >
            Show only applied jobs
          </span>
              <Toggle @click="appliedJob" class="ml-3" :data="appliedJobsToggle"/>
      </div>
      </div>
      <span
          v-if="hasFilter"
          class="ml-auto mt-2 md:mt-0 text-sm text-purple-600  cursor-pointer hover:text-purple-900"
          @click="clearFilters"
      >
        Clear Filters
      </span>
    </div>
  </div>
</template>

<script setup>
import { LocationMarkerIcon, SearchIcon } from "@heroicons/vue/solid";
import Input from "@/Components/Input.vue";
import Button from "@/Components/Button.vue";
import FilterDropdown from "@/Components/Global/FilterDropdown.vue";
import { computed, ref, onMounted, watch } from 'vue';
import TagInput from "@/Components/TagInput.vue";
import {errors, maxDate, validateField} from "@/Components/Services/Validation";
import Label from "@/Components/Label.vue";
import InputError from "@/Components/InputError.vue";
import Toggle from "@/Components/Toggle.vue";
import SalaryDropdown from "@/Components/Jobs/Single/SalaryDropdown.vue";

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
const postedDate = ref(null);
const appliedJobs = ref(null);
let appliedJobsToggle = ref({
    isActive:  false
});
function appliedJob(){
    appliedJobs.value = appliedJobs.value === 'yes' ? null : 'yes';
    appliedJobsToggle.value.isActive = appliedJobs.value === 'yes';
    triggerSearch()
}


const buttonText = computed(() => {
  return props.isSearching ? 'Searching ....' : 'Search';
});

const hasFilter = computed(() => {
  return (
      keywordTags.value.length > 0 ||
      locationTags.value.length > 0 ||
      Object.values(selectedFilters.value).some(filterArray => filterArray.length > 0) ||
      postedDate != null ||
      appliedJobs != null
  );
});

watch([keywordTags, locationTags, selectedFilters, postedDate, appliedJobs], () => {
    hasFilter.value = (
        keywordTags.value.length > 0 ||
        locationTags.value.length > 0 ||
        Object.values(selectedFilters.value).some(filterArray => filterArray.length > 0) ||
        postedDate !== null ||
        appliedJobs !== null
    );
}, { deep: true });

const clearFilters = () => {
  keywordTags.value = [];
  locationTags.value = [];
  selectedFilters.value = {};
  postedDate.value = null;
  appliedJobs.value = null;
  appliedJobsToggle.value.isActive = false;
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

  if (postedDate.value) {
      params['posted-date'] = postedDate.value;
  }

  if (appliedJobs.value === 'yes') {
    params['applied-jobs'] = appliedJobs.value;
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
  postedDate.value = urlParams.get('posted-date') ?? null;
  appliedJobs.value = urlParams.get('applied-jobs') ?? null;

  appliedJobsToggle.value = {
    isActive: appliedJobs.value === 'yes'
  };

  Object.keys(props.filters).forEach((filterKey) => {
    const filter = props.filters[filterKey];
    const filterValues = urlParams.getAll(`${filter.index}[]`) ?? [];
    if (filterValues.length > 0) {
      selectedFilters.value[filter.index] = filterValues.map(value => ({
        label: value,
        value: value
      }));
    }
  });
});
</script>
