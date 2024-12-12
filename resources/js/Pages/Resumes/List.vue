<template>
  <AppLayout title="Search Resumes">
    <Search :filters="filters" @onSearch="handleSearch" :is-searching="isSearching" />
    <p class="text-lg font-semibold my-3">
      {{ resumes.total > 0 ? resumes.total + ' total Resumes' : 'Records Not Found' }} </p>
    <Resume v-for="(resume, index) in resumes.data" :key="index" :resume="resume" />
    <Pagination :items="resumes" :is-inertia="false" @page-change="handlePageChange" />
  </AppLayout>
</template>

<script setup>
import Resume from "@/Components/Resumes/Resume.vue";
import Search from "@/Components/Resumes/Search.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed, onMounted, ref } from "vue";
import Pagination from "@/Components/Pagination.vue";

let props = defineProps({
  noticePeriods: {
    type: Object,
    required: true,
  },
  noOfExperiences: {
    type: Object,
    required: true,
  },
  qualifications: {
    type: Object,
    required: true,
  },
  keySkills: {
    type: Object,
    required: true,
  },
  jobTypes: {
    type: Object,
    required: true,
  },
});

// Define filters object
const filters = computed(() => ({
  noticePeriods: {
    index: "notice-periods",
    label: "Notice Periods",
    value: props.noticePeriods,
  },
  noOfExperiences: {
    index: "number-of-experiences",
    label: "No Of Years Of Experiences",
    value: props.noOfExperiences,
  },
  qualifications: {
    index: "qualifications",
    label: "Qualifications",
    value: props.qualifications,
  },
  jobTypes: {
    index: "job-types",
    label: "Job Types",
    value: props.jobTypes,
  },
}));

let selectedFilters = ref({});

let resumes = ref({});
let isSearching = ref(false);

let initialSearchTriggered = false;

const handleSearch = async (params, isInitial = false) => {
  if (isInitial && initialSearchTriggered) {
    return;
  }

  const query = new URLSearchParams();
  isSearching.value = true;

  Object.keys(params).forEach((key) => {
    const values = params[key];
    if (Array.isArray(values)) {
      values.forEach(value => {
        query.append(`${key}[]`, value);
      });
    } else {
      query.append(key, values);
    }
  });

  const newUrl = `${window.location.pathname}?${query.toString()}`;
  window.history.replaceState(null, "", newUrl);

  await fetchResumes(query);
  isSearching.value = false;

  if (isInitial) {
    initialSearchTriggered = true;
  }
};

onMounted(async () => {
  window.dispatchEvent(new Event("start"));
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);

  const params = {};

  urlParams.forEach((value, key) => {
    if (key.endsWith('[]')) {
      const cleanKey = key.slice(0, -2);
      if (!params[cleanKey]) {
        params[cleanKey] = [];
      }
      params[cleanKey].push(value);
    } else {
      params[key] = value;
    }
  });

  try {
    if (Object.keys(params).length > 0) {
      await handleSearch(params, true);
    } else {
      await fetchResumes();
    }
  } finally {
    window.dispatchEvent(new Event("finish"));
  }
});

const fetchResumes = async (query = null, page = 1) => {
  try {
    window.dispatchEvent(new Event("start"));
    const queryParams = new URLSearchParams(query || window.location.search);
    queryParams.set('page', page); // Set or override the page parameter

    const response = await axios.get(route("admin.resumes.fetch"), {
      params: queryParams,
    });
    resumes.value = response.data.resumes;
  } catch (error) {
    console.error("Error fetching data:", error);
  } finally {
    window.dispatchEvent(new Event("finish"));
  }
};


function handlePageChange(page) {
  const queryParams = new URLSearchParams(window.location.search);

  queryParams.set('page', page);

  const newUrl = `${window.location.pathname}?${queryParams.toString()}`;
  window.history.replaceState(null, "", newUrl);

  fetchResumes(queryParams, page);
}
</script>
