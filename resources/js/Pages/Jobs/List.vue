<template>
  <AppLayout title="Jobs">
    <div class="p-4">
      <Search :filters="filters" @onSearch="handleSearch" :is-searching="isSearching" />
      <p class="text-lg font-semibold my-3">
        {{ jobs.total > 0 ? jobs.total + ' total Jobs' : 'Records Not Found' }} </p>
      <Job v-for="(job, index) in jobs.data"
           :key="index"
           :job="job"
           @click-save="save"
           @add-to-save="addToSave"

      />
      <Pagination :items="jobs" :is-inertia="false" @page-change="handlePageChange" />
    </div>
      <JetDialogModal :show="isModelOpen" @close="closeForm" >
          <template #title> {{ modalTitle }}</template>

          <template #content>
              <QuestionsForm :job="job" @submitAnswer="saveAppliedJob"/>
          </template>

      </JetDialogModal>
  </AppLayout>
</template>

<script setup>
import Job from "@/Components/Jobs/Job.vue";
import Search from "@/Components/Jobs/Search.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed, onMounted, ref } from "vue";
import {Inertia} from "@inertiajs/inertia";
import Pagination from "@/Components/Pagination.vue";
import JetDialogModal from "@/Components/DialogModal.vue";
import QuestionsForm from "@/Components/Jobs/QuestionsForm.vue";
import {usePage} from "@inertiajs/inertia-vue3";

let props = defineProps({
  jobTypes: {
    type: Object,
    required: true,
  },
});

let isModelOpen = ref(false);
let modalTitle = ref('Apply Job');
let job = ref({});
let page = usePage().props.value;
let authUser = page.authUser;

function closeForm(){
    isModelOpen.value = false
}

const filters = computed(() => ({
  jobTypes: {
    index: "job-types",
    label: "Job Types",
    value: props.jobTypes,
  },
}));

let selectedFilters = ref({});

let jobs = ref({});
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

  await fetchJobs(query);
  isSearching.value = false;

  if (isInitial) {
    initialSearchTriggered = true;
  }
};

onMounted(() => {
    fetchDataWithUrlParams()
});

function fetchDataWithUrlParams(){
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

    if (Object.keys(params).length > 0) {
        handleSearch(params, true);
    } else {
        fetchJobs();
    }
}

function save(jobData) {
    if(jobData.questions.length !== 0){
        job.value = jobData
        isModelOpen.value = true
    }else{
        saveAppliedJob(jobData)
    }
}

function saveAppliedJob(jobData) {
    isModelOpen.value = false;
    window.dispatchEvent(new Event("start"));

    const data = {
        vacancyId: jobData.id,
        role: 'candidate',
        candidate: authUser.id,
        ...(jobData.answers ? { answers: jobData.answers } : {})
    };

    Inertia.post(route("admin.jobs.store"), data, {
        onError: () => {
            fetchJobs();
        },
        onSuccess: () => {
            fetchJobs();
        },
    });
}


function addToSave(jobData) {
    window.dispatchEvent(new Event("start"));
    Inertia.post(route("admin.saved-jobs.store"),{
      vacancyId: jobData.id,
      role: 'candidate',
      candidate: authUser.id,
    }, {

        onError: () => {
            fetchJobs();
        },
        onSuccess: () => {
            fetchJobs();
        },
    });
}

const fetchJobs = async (query = null, page = 1) => {
  try {
    window.dispatchEvent(new Event("start"));
    const queryParams = new URLSearchParams(query || window.location.search);
    queryParams.set('page', page);

    const response = await axios.get(route("admin.jobs.fetch"), {
      params: queryParams,
    });
    jobs.value = response.data.jobs;
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

  fetchJobs(queryParams, page);
}
</script>
